<?php
// Idle screen lock: the homepage locks itself after a period of inactivity
// and asks for the user's password before they can continue. The user stays
// logged in the whole time; the lock flag lives in the session so reloading
// the page does not get around it.
session_start();
date_default_timezone_set('Africa/Nairobi');
header('Content-Type: application/json');

define('IDLE_LOCK_DEFAULT_MINUTES', 5);
define('IDLE_LOCK_MAX_MINUTES', 1440);

if (empty($_SESSION['userids']) || empty($_SESSION['databasename']) || $_SESSION['databasename'] === '0') {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Session expired. Please log in again.']);
    exit();
}

function idleLockSchoolConn() {
    include("../../connections/conn2.php");
    if (!isset($conn2) || $conn2->connect_errno) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
        exit();
    }
    return $conn2;
}

function idleLockTimeout($conn2) {
    $minutes = IDLE_LOCK_DEFAULT_MINUTES;
    $stmt = $conn2->prepare("SELECT `valued` FROM `settings` WHERE `sett` = 'IDLE_LOCK_TIMEOUT' LIMIT 1");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $row = $result->fetch_assoc()) {
        $value = intval($row['valued']);
        if ($value >= 1 && $value <= IDLE_LOCK_MAX_MINUTES) {
            $minutes = $value;
        }
    }
    $stmt->close();
    return $minutes;
}

// Mirrors allowed("settings") in homepage.php, which decides whether the
// Settings button shows: the System Administrator (0) and Principal (1) always
// see it, the other built in roles never do, and a custom role sees it when
// its entry in the school's user_roles has "settings" switched on.
function idleLockCanEditSettings($conn2) {
    $auth = (string) ($_SESSION['auth'] ?? '');
    if ($auth === '0' || $auth === '1') {
        return true;
    }
    if ($auth === '' || in_array($auth, ['2', '3', '4', '5', '6', '7', '8', '9'], true)) {
        return false;
    }
    $stmt = $conn2->prepare("SELECT `valued` FROM `settings` WHERE `sett` = 'user_roles' LIMIT 1");
    $stmt->execute();
    $result = $stmt->get_result();
    $allowed = false;
    if ($result && $row = $result->fetch_assoc()) {
        $roles = json_decode($row['valued'] ?? '[]', true);
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ((string) ($role['name'] ?? '') !== $auth) continue;
                foreach (($role['roles'] ?? []) as $perm) {
                    if (($perm['name'] ?? '') === 'settings') {
                        $allowed = ($perm['Status'] ?? '') === 'yes';
                    }
                }
            }
        }
    }
    $stmt->close();
    return $allowed;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'get_settings') {
    $conn2 = idleLockSchoolConn();
    echo json_encode([
        'status' => 'success',
        'minutes' => idleLockTimeout($conn2),
        'can_edit' => idleLockCanEditSettings($conn2),
        'locked' => !empty($_SESSION['idle_locked']),
    ]);
    $conn2->close();
} elseif ($action === 'lock' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['idle_locked'] = true;
    echo json_encode(['status' => 'success']);
} elseif ($action === 'unlock' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    if ($password === '') {
        echo json_encode(['status' => 'error', 'message' => 'Enter your password to continue.']);
        exit();
    }
    include_once("../../assets/encrypt/encrypt.php");
    include("../../connections/conn1.php");
    $encrypted = encryptCode($password);
    $stmt = $conn->prepare("SELECT `user_id` FROM `user_tbl` WHERE `user_id` = ? AND `password` = ? LIMIT 1");
    $stmt->bind_param("ss", $_SESSION['userids'], $encrypted);
    $stmt->execute();
    $stmt->store_result();
    $matched = $stmt->num_rows > 0;
    $stmt->close();
    $conn->close();
    if ($matched) {
        unset($_SESSION['idle_locked']);
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Incorrect password. Try again.']);
    }
} elseif ($action === 'save_timeout' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn2 = idleLockSchoolConn();
    if (!idleLockCanEditSettings($conn2)) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'You do not have permission to change this setting.']);
        exit();
    }
    $minutes = intval($_POST['minutes'] ?? 0);
    if ($minutes < 1 || $minutes > IDLE_LOCK_MAX_MINUTES) {
        echo json_encode(['status' => 'error', 'message' => 'Enter a timeout between 1 and ' . IDLE_LOCK_MAX_MINUTES . ' minutes.']);
        exit();
    }
    $value = (string) $minutes;
    $stmt = $conn2->prepare("SELECT `sett` FROM `settings` WHERE `sett` = 'IDLE_LOCK_TIMEOUT' LIMIT 1");
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    if ($exists) {
        $stmt = $conn2->prepare("UPDATE `settings` SET `valued` = ? WHERE `sett` = 'IDLE_LOCK_TIMEOUT'");
    } else {
        $stmt = $conn2->prepare("INSERT INTO `settings` (`sett`, `valued`) VALUES ('IDLE_LOCK_TIMEOUT', ?)");
    }
    $stmt->bind_param("s", $value);
    $saved = $stmt->execute();
    $stmt->close();
    $conn2->close();
    if ($saved) {
        echo json_encode(['status' => 'success', 'minutes' => $minutes, 'message' => 'Idle lock timeout saved.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Could not save the timeout. Try again.']);
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Unknown action.']);
}
