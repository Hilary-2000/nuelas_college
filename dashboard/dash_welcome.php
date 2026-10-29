<?php
// Shared welcome banner for every dashboard. The including page sets:
//   $dash_dp_id   id of the profile picture (admissions.js swaps the src after a dp change)
//   $dash_tagline the line under the name
$dash_hour = (int) date("G");
if ($dash_hour < 12) { $dash_greet = "Good morning"; }
elseif ($dash_hour < 17) { $dash_greet = "Good afternoon"; }
else { $dash_greet = "Good evening"; }

$dash_name = "there";
if (isset($_SESSION['fullnames'])) {
    $dash_salute = '';
    $dash_gen = isset($_SESSION['gen']) ? $_SESSION['gen'] : '';
    if ($dash_gen == 'M') { $dash_salute = 'Mr. '; }
    elseif ($dash_gen == 'F') { $dash_salute = 'Mrs. '; }
    $dash_named = explode(" ", trim($_SESSION['fullnames']));
    $dash_name = $dash_salute . $dash_named[0];
}

$dash_role = 'User';
if (isset($_SESSION['auth'])) {
    $dash_roles = [
        0 => 'System Administrator', 1 => 'Principal',
        2 => 'Deputy Principal Academics', 3 => 'Deputy Principal Administration',
        4 => 'Dean of Students', 5 => 'Finance Office',
        6 => 'Human Resource Officer', 7 => 'Head of Department',
        8 => 'Trainer/Lecturer', 9 => 'Admissions',
    ];
    $dash_auth = $_SESSION['auth'];
    $dash_role = isset($dash_roles[$dash_auth]) ? $dash_roles[$dash_auth] : ucwords(strtolower($dash_auth));
}
?>
    <div class="dash_hero">
        <img class="dash_avatar" src="images/dp.png" id="<?php echo $dash_dp_id; ?>" alt="Profile picture">
        <div class="dash_hero_text">
            <small><?php echo $dash_greet; ?>,</small>
            <h2><?php echo htmlspecialchars($dash_name); ?></h2>
            <p><?php echo htmlspecialchars($dash_tagline); ?></p>
        </div>
        <div class="dash_hero_meta">
            <span class="dash_chip"><i class="fas fa-user-shield"></i> <?php echo htmlspecialchars($dash_role); ?></span>
            <span class="dash_chip"><i class="far fa-calendar-alt"></i> <?php echo date("l, d M Y"); ?></span>
        </div>
    </div>
