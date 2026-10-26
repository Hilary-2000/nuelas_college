<?php
// Staff signatures and the school stamp: upload, remove, and the Settings choices
// (which documents carry the stamp, whose principal/deputy signature prints).
// See signature_lib.php for storage and permissions.
session_start();
date_default_timezone_set('Africa/Nairobi');
header('Content-Type: application/json');
require_once("signature_lib.php");

function sig_respond($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data);
    exit();
}

if (empty($_SESSION['userids']) || empty($_SESSION['databasename']) || $_SESSION['databasename'] === '0') {
    sig_respond(['status' => 'error', 'message' => 'Session expired. Please log in again.'], 401);
}

include("../../connections/conn1.php");
include("../../connections/conn2.php");
if (!isset($conn, $conn2) || $conn->connect_errno || $conn2->connect_errno) {
    sig_respond(['status' => 'error', 'message' => 'Database connection failed.'], 500);
}
sig_ensure_column($conn);

$school_code = (string) ($_SESSION['schoolcode'] ?? '');
$my_id = (string) $_SESSION['userids'];
$action = $_GET['action'] ?? ($_POST['action'] ?? '');

function sig_role_label($auth) {
    $labels = ['0' => 'System Administrator', '1' => 'Principal', '2' => 'Deputy Principal Academics', '3' => 'Deputy Principal Administration', '4' => 'Dean of Students', '5' => 'Finance Office', '6' => 'Human Resource Officer', '7' => 'Head of Department', '8' => 'Trainer/Lecturer', '9' => 'Admissions'];
    return $labels[(string) $auth] ?? (string) $auth;
}

// A staff member of the logged-in school, or null.
function sig_staff_row($conn, $user_id, $school_code) {
    $stmt = $conn->prepare("SELECT `user_id`, `fullname`, `auth`, `signature_loc` FROM `user_tbl` WHERE `user_id` = ? AND `school_code` = ? LIMIT 1");
    $stmt->bind_param("ss", $user_id, $school_code);
    $stmt->execute();
    $result = $stmt->get_result();
    return ($result && $row = $result->fetch_assoc()) ? $row : null;
}

// Newest-first action log, the same file log_administration() in
// ajax/administration/admissions.php writes to.
function sig_log($conn2, $message) {
    $dbname = preg_replace('/[^A-Za-z0-9_]/', '', (string) ($_SESSION['dbname'] ?? $_SESSION['databasename'] ?? ''));
    if ($dbname === '') return;
    $file_location = dirname(__DIR__) . "/logs/" . $dbname . "/logs.txt";
    if (!is_dir(dirname($file_location))) @mkdir(dirname($file_location), 0777, true);
    $full_text = date("dS M Y H:i:sA") . " : " . $message . " - {" . ($_SESSION['username'] ?? '') . "}\n";
    $content = is_file($file_location) ? file_get_contents($file_location) : "";
    @file_put_contents($file_location, $full_text . $content);
}

// Which staff signature a request may touch: your own if your role can sign,
// anyone eligible in your school if you can manage staff.
function sig_resolve_target($conn, $conn2, $school_code, $my_id, $requested_id) {
    $target_id = ($requested_id === null || $requested_id === '') ? $my_id : (string) $requested_id;
    $staff = sig_staff_row($conn, $target_id, $school_code);
    if (!$staff) sig_respond(['status' => 'error', 'message' => 'Staff member not found.'], 404);
    if ($target_id !== $my_id && !sig_can_manage_staff_signatures($conn2)) {
        sig_respond(['status' => 'error', 'message' => 'You are not allowed to change other staff signatures.'], 403);
    }
    if (!in_array((string) $staff['auth'], SIG_ELIGIBLE_ROLES, true)) {
        sig_respond(['status' => 'error', 'message' => 'Only the principal and deputy principals can have a signature.'], 400);
    }
    return $staff;
}

if ($action === 'get_signature') {
    // ?user_id= for the edit staff page; omitted for your own profile
    $target_id = (string) ($_GET['user_id'] ?? $my_id);
    $staff = sig_staff_row($conn, $target_id, $school_code);
    if (!$staff) sig_respond(['status' => 'error', 'message' => 'Staff member not found.'], 404);
    $is_self = $target_id === $my_id;
    $can_change = $is_self || sig_can_manage_staff_signatures($conn2);
    sig_respond([
        'status' => 'success',
        'eligible' => in_array((string) $staff['auth'], SIG_ELIGIBLE_ROLES, true),
        'can_change' => $can_change,
        'role' => sig_role_label($staff['auth']),
        'signature' => $can_change ? sig_data_url($staff['signature_loc'] ?? '') : null,
    ]);
}

if ($action === 'save_signature' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff = sig_resolve_target($conn, $conn2, $school_code, $my_id, $_POST['user_id'] ?? '');
    list($png, $error) = sig_clean_png_from_data_url($_POST['image'] ?? '');
    if ($error) sig_respond(['status' => 'error', 'message' => $error], 400);
    $relative = sig_store_png($school_code, 'signature_' . $staff['user_id'], $png);
    if (!$relative) sig_respond(['status' => 'error', 'message' => 'The signature could not be saved on the server. The web server cannot write to the signatures_store folder; ask the system administrator to give it write access.'], 500);
    $stmt = $conn->prepare("UPDATE `user_tbl` SET `signature_loc` = ? WHERE `user_id` = ?");
    $stmt->bind_param("ss", $relative, $staff['user_id']);
    if (!$stmt->execute()) {
        sig_delete_file($relative);
        sig_respond(['status' => 'error', 'message' => 'The signature could not be saved: ' . $stmt->error], 500);
    }
    sig_delete_file($staff['signature_loc'] ?? '');
    sig_log($conn2, "Uploaded the signature of " . ucwords(strtolower($staff['fullname'])) . ".");
    sig_respond(['status' => 'success', 'message' => 'Signature saved.', 'signature' => sig_data_url($relative)]);
}

if ($action === 'delete_signature' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff = sig_resolve_target($conn, $conn2, $school_code, $my_id, $_POST['user_id'] ?? '');
    $stmt = $conn->prepare("UPDATE `user_tbl` SET `signature_loc` = NULL WHERE `user_id` = ?");
    $stmt->bind_param("s", $staff['user_id']);
    $stmt->execute();
    sig_delete_file($staff['signature_loc'] ?? '');
    sig_log($conn2, "Removed the signature of " . ucwords(strtolower($staff['fullname'])) . ".");
    sig_respond(['status' => 'success', 'message' => 'Signature removed.']);
}

// ---------------------------------------------------------------------------
// Settings: stamp, stamped documents, signing principal / deputy principal
// ---------------------------------------------------------------------------

function sig_staff_by_role($conn, $school_code, $roles) {
    $stmt = $conn->prepare("SELECT `user_id`, `fullname`, `auth`, `signature_loc` FROM `user_tbl` WHERE `school_code` = ? AND (`deleted` = 0 OR `deleted` IS NULL) ORDER BY `fullname`");
    $stmt->bind_param("s", $school_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $list = [];
    while ($result && $row = $result->fetch_assoc()) {
        if (!in_array((string) $row['auth'], $roles, true)) continue;
        $list[] = [
            'user_id' => (string) $row['user_id'],
            'fullname' => ucwords(strtolower($row['fullname'])),
            'has_signature' => sig_absolute_path($row['signature_loc'] ?? '') !== null,
        ];
    }
    return $list;
}

if ($action === 'get_settings') {
    $config = sig_get_config($conn2, true);
    $documents = [];
    foreach (SIG_STAMP_DOCUMENTS as $key => $doc) {
        $documents[] = ['key' => $key, 'label' => $doc[0], 'description' => $doc[1], 'enabled' => in_array($key, $config['stamp_documents'], true)];
    }
    sig_respond([
        'status' => 'success',
        'can_edit' => sig_can_edit_settings($conn2),
        'stamp' => sig_data_url($config['stamp_file']),
        'documents' => $documents,
        'print_signatures' => $config['print_signatures'] === '1',
        'headteachers' => sig_staff_by_role($conn, $school_code, ['1']),
        'deputies' => sig_staff_by_role($conn, $school_code, SIG_DEPUTY_ROLES),
        'signing_headteacher' => (string) $config['signing_headteacher'],
        'signing_deputy' => (string) $config['signing_deputy'],
        'receipt_style' => $config['receipt_style'] === 'classic' ? 'classic' : 'new',
    ]);
}

if (in_array($action, ['save_stamp', 'delete_stamp', 'save_settings'], true)) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') sig_respond(['status' => 'error', 'message' => 'Invalid request.'], 405);
    if (!sig_can_edit_settings($conn2)) {
        sig_respond(['status' => 'error', 'message' => 'You do not have access to Settings, so you cannot change the college stamp and signature settings.'], 403);
    }
}

if ($action === 'save_stamp') {
    list($png, $error) = sig_clean_png_from_data_url($_POST['image'] ?? '');
    if ($error) sig_respond(['status' => 'error', 'message' => $error], 400);
    $relative = sig_store_png($school_code, 'stamp', $png);
    if (!$relative) sig_respond(['status' => 'error', 'message' => 'The stamp could not be saved on the server. The web server cannot write to the signatures_store folder; ask the system administrator to give it write access.'], 500);
    $config = sig_get_config($conn2, true);
    $old = $config['stamp_file'];
    $config['stamp_file'] = $relative;
    if (!sig_save_config($conn2, $config)) {
        sig_delete_file($relative);
        sig_respond(['status' => 'error', 'message' => 'The stamp could not be saved: ' . $conn2->error], 500);
    }
    sig_delete_file($old);
    sig_log($conn2, "Updated the college stamp.");
    sig_respond(['status' => 'success', 'message' => 'College stamp saved.', 'stamp' => sig_data_url($relative)]);
}

if ($action === 'delete_stamp') {
    $config = sig_get_config($conn2, true);
    $old = $config['stamp_file'];
    $config['stamp_file'] = '';
    sig_save_config($conn2, $config);
    sig_delete_file($old);
    sig_log($conn2, "Removed the college stamp.");
    sig_respond(['status' => 'success', 'message' => 'College stamp removed.']);
}

if ($action === 'save_settings') {
    $config = sig_get_config($conn2, true);

    $documents = json_decode($_POST['stamp_documents'] ?? '[]', true);
    $documents = is_array($documents) ? array_values(array_intersect(array_keys(SIG_STAMP_DOCUMENTS), $documents)) : [];

    // only one principal and one deputy principal sign at a time; choosing a new one replaces the old
    $headteacher = (string) ($_POST['signing_headteacher'] ?? '');
    $deputy = (string) ($_POST['signing_deputy'] ?? '');
    if ($headteacher !== '') {
        $staff = sig_staff_row($conn, $headteacher, $school_code);
        if (!$staff || (string) $staff['auth'] !== '1') sig_respond(['status' => 'error', 'message' => 'The selected principal was not found.'], 400);
    }
    if ($deputy !== '') {
        $staff = sig_staff_row($conn, $deputy, $school_code);
        if (!$staff || !in_array((string) $staff['auth'], SIG_DEPUTY_ROLES, true)) sig_respond(['status' => 'error', 'message' => 'The selected deputy principal was not found.'], 400);
    }

    $receipt_style = ($_POST['receipt_style'] ?? 'new') === 'classic' ? 'classic' : 'new';

    $changes = [];
    if ($headteacher !== (string) $config['signing_headteacher']) $changes[] = "signing principal";
    if ($deputy !== (string) $config['signing_deputy']) $changes[] = "signing deputy";
    if ($receipt_style !== $config['receipt_style']) $changes[] = "receipt style";

    $config['stamp_documents'] = $documents;
    $config['print_signatures'] = ($_POST['print_signatures'] ?? '0') === '1' ? '1' : '0';
    $config['signing_headteacher'] = $headteacher;
    $config['signing_deputy'] = $deputy;
    $config['receipt_style'] = $receipt_style;
    if (!sig_save_config($conn2, $config)) {
        sig_respond(['status' => 'error', 'message' => 'The settings could not be saved: ' . $conn2->error], 500);
    }
    sig_log($conn2, "Updated the signature and stamp settings" . (count($changes) > 0 ? " (changed the " . implode(" and ", $changes) . ")" : "") . ".");
    sig_respond(['status' => 'success', 'message' => 'Signature and stamp settings saved.']);
}

sig_respond(['status' => 'error', 'message' => 'Unknown action.'], 400);
