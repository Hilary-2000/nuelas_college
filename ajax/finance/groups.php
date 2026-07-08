<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    date_default_timezone_set('Africa/Nairobi');

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        include("../../connections/conn1.php");
        include("../../connections/conn2.php");

        if (isset($_GET['get_group_filter_options'])) {
            $options = [];

            // Branches from settings
            $sel = "SELECT `valued` FROM `settings` WHERE `sett` = 'branches'";
            $stmt = $conn2->prepare($sel);
            $stmt->execute();
            $res = $stmt->get_result();
            $options['branches'] = [];
            if ($res && ($r = $res->fetch_assoc()) && strlen(trim($r['valued'])) > 0) {
                $options['branches'] = isJson_report($r['valued']) ? json_decode($r['valued'], true) : [];
            }

            // Course levels (settings.sett = 'class') -- e.g. Certificate/Diploma/Bachelors
            $sel = "SELECT `valued` FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($sel);
            $stmt->execute();
            $res = $stmt->get_result();
            $options['course_levels'] = [];
            if ($res && ($r = $res->fetch_assoc()) && strlen(trim($r['valued'])) > 0) {
                $options['course_levels'] = isJson_report($r['valued']) ? json_decode($r['valued'], true) : [];
            }

            // Courses (settings.sett = 'courses') -- `student_data`.`course_done` stores
            // the course's `id` here, not its name, so the course_level lets the UI
            // narrow the course list down before the student picks one.
            $sel = "SELECT `valued` FROM `settings` WHERE `sett` = 'courses'";
            $stmt = $conn2->prepare($sel);
            $stmt->execute();
            $res = $stmt->get_result();
            $options['courses'] = [];
            if ($res && ($r = $res->fetch_assoc()) && strlen(trim($r['valued'])) > 0) {
                $decoded = isJson_report($r['valued']) ? json_decode($r['valued'], true) : [];
                foreach ($decoded as $c) {
                    $options['courses'][] = ["id" => $c['id'], "course_name" => $c['course_name'], "course_level" => $c['course_level']];
                }
            }

            // Distinct intake years / months
            $sel = "SELECT DISTINCT `intake_year` FROM `student_data` WHERE `intake_year` IS NOT NULL AND `intake_year` != '' ORDER BY `intake_year` DESC";
            $stmt = $conn2->prepare($sel);
            $stmt->execute();
            $res = $stmt->get_result();
            $options['intake_years'] = [];
            while ($res && ($r = $res->fetch_assoc())) {
                $options['intake_years'][] = $r['intake_year'];
            }

            $sel = "SELECT DISTINCT `intake_month` FROM `student_data` WHERE `intake_month` IS NOT NULL AND `intake_month` != ''";
            $stmt = $conn2->prepare($sel);
            $stmt->execute();
            $res = $stmt->get_result();
            $options['intake_months'] = [];
            while ($res && ($r = $res->fetch_assoc())) {
                $options['intake_months'][] = $r['intake_month'];
            }
            // Chronological (Jan-Dec), not alphabetical -- intake_month is free text
            // (e.g. "JAN", "Jan"), so match case-insensitively and keep anything
            // unrecognised at the end rather than dropping it.
            $month_order = ['jan'=>1,'feb'=>2,'mar'=>3,'apr'=>4,'may'=>5,'jun'=>6,'jul'=>7,'aug'=>8,'sep'=>9,'oct'=>10,'nov'=>11,'dec'=>12];
            usort($options['intake_months'], function($a, $b) use ($month_order) {
                $a_rank = $month_order[strtolower(substr($a, 0, 3))] ?? 99;
                $b_rank = $month_order[strtolower(substr($b, 0, 3))] ?? 99;
                return $a_rank <=> $b_rank;
            });

            // Dormitories
            $sel = "SELECT `dorm_id`, `dorm_name` FROM `dorm_list` WHERE `deleted` = 0 AND `activated` = 1 ORDER BY `dorm_name` ASC";
            $stmt = $conn2->prepare($sel);
            $stmt->execute();
            $res = $stmt->get_result();
            $options['dormitories'] = [];
            while ($res && ($r = $res->fetch_assoc())) {
                $options['dormitories'][] = $r;
            }

            echo json_encode($options);

        } elseif (isset($_GET['get_filtered_students'])) {
            // Build a parameterised WHERE clause from whichever filters were sent.
            // Every filter here is optional and AND-ed together. To add a new
            // filter type: add one more `if` block below plus a matching form
            // field in financepages/groups.php / assets/JS/groups.js.
            $where = "`deleted` = 0";
            $types = "";
            $params = [];

            if (!empty($_GET['gender']) && $_GET['gender'] != 'all') {
                $where .= " AND `gender` = ?";
                $types .= "s";
                $params[] = $_GET['gender'];
            }
            if (!empty($_GET['course']) && $_GET['course'] != 'all') {
                $where .= " AND `course_done` = ?";
                $types .= "s";
                $params[] = $_GET['course'];
            }
            if (!empty($_GET['study_mode']) && $_GET['study_mode'] != 'all') {
                $where .= " AND `study_mode` = ?";
                $types .= "s";
                $params[] = $_GET['study_mode'];
            }
            if (!empty($_GET['branch']) && $_GET['branch'] != 'all' && is_numeric($_GET['branch'])) {
                $where .= " AND `branch_name` = ?";
                $types .= "i";
                $params[] = (int)$_GET['branch'];
            }
            if (!empty($_GET['boarding_status']) && $_GET['boarding_status'] != 'all') {
                $where .= " AND `boarding` = ?";
                $types .= "s";
                $params[] = $_GET['boarding_status'];
            }
            if (!empty($_GET['dormitory']) && $_GET['dormitory'] != 'all') {
                $where .= " AND `dormitory` = ?";
                $types .= "s";
                $params[] = $_GET['dormitory'];
            }
            if (!empty($_GET['intake_year']) && $_GET['intake_year'] != 'all') {
                $where .= " AND `intake_year` = ?";
                $types .= "s";
                $params[] = $_GET['intake_year'];
            }
            if (!empty($_GET['intake_month']) && $_GET['intake_month'] != 'all') {
                $where .= " AND `intake_month` = ?";
                $types .= "s";
                $params[] = $_GET['intake_month'];
            }
            if (!empty($_GET['doa_from']) && !empty($_GET['doa_to'])) {
                $where .= " AND `D_O_A` BETWEEN ? AND ?";
                $types .= "ss";
                $params[] = $_GET['doa_from'];
                $params[] = $_GET['doa_to'];
            }
            if (!empty($_GET['student_status']) && $_GET['student_status'] != 'all') {
                $where .= " AND `activated` = ?";
                $types .= "i";
                $params[] = $_GET['student_status'] == 'active' ? 1 : 0;
            }
            if (!empty($_GET['exclude_group_id']) && is_numeric($_GET['exclude_group_id'])) {
                $where .= " AND `adm_no` NOT IN (SELECT `adm_no` FROM `student_group_members` WHERE `group_id` = ? AND `deleted` = 0)";
                $types .= "i";
                $params[] = (int)$_GET['exclude_group_id'];
            }

            $select = "SELECT `adm_no`, `first_name`, `second_name`, `surname`, `gender`, `course_done`, `stud_class`, `boarding`, `dormitory` FROM `student_data` WHERE " . $where . " ORDER BY `first_name` ASC";
            $stmt = $conn2->prepare($select);
            if (!empty($types)) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $result = $stmt->get_result();

            // `prefix` keeps ids/classes unique between the create-group panel
            // and the "add students" search inside the manage-members modal,
            // since both can be mounted in the DOM at the same time.
            $prefix = isset($_GET['prefix']) && preg_match('/^[a-z0-9_]+$/', $_GET['prefix']) ? $_GET['prefix'] : 'group';
            $course_lookup = get_courses_lookup($conn2);

            $data_to_display = "<div class='staff_list'>
                <div class='staff_dets'>
                    <label for='{$prefix}_select_all_visible' style='color:cadetblue;'>Select all visible</label>
                    <input type='checkbox' id='{$prefix}_select_all_visible'>
                </div>";

            $xs = 0;
            while ($result && ($row = $result->fetch_assoc())) {
                $xs++;
                $adm = $row['adm_no'];
                $name = ucwords(strtolower($row['first_name'] . " " . $row['second_name'] . " " . $row['surname']));
                $course_name = isset($course_lookup[(string)$row['course_done']]) ? $course_lookup[(string)$row['course_done']] : $row['course_done'];
                $boarding_label = $row['boarding'] == 'enrolled' ? "<small class='badge bg-success text-white'>Boarding</small>" : "";
                $search_key = strtolower($name . " " . $adm);
                $data_to_display .= "
                    <div class='staff_dets {$prefix}_student_row' id='{$prefix}_row_{$adm}' data-search='" . e($search_key) . "'>
                        <label style='font-size:12px;'>{$xs}.</label>
                        <label class='text-left' style='font-size:14px;' for='{$prefix}adm{$adm}'>
                            {$name} <small style='color:red;'>({$adm})</small>
                            <small class='text-muted'>&nbsp;|&nbsp;" . e($course_name) . "</small>
                            {$boarding_label}
                        </label>
                        <input type='checkbox' class='{$prefix}-student-chk' name='{$prefix}adm{$adm}' id='{$prefix}adm{$adm}' value='{$adm}'>
                    </div>";
            }

            $data_to_display .= "</div><p class='hide' id='{$prefix}_filtered_total'>{$xs}</p>";

            // When searching to add students to an existing group, students already
            // in that group are excluded from these results (they're on the Current
            // Members list above) -- make that explicit so an empty/short result list
            // isn't mistaken for a bug.
            $excluding_note = !empty($_GET['exclude_group_id']) ? " Students already in this group are not shown here. See the Current Members list above." : "";

            if ($xs > 0) {
                if ($excluding_note != "") {
                    $data_to_display = "<p class='text-muted' style='font-size:12px;'><i class='fas fa-info-circle'></i>" . $excluding_note . "</p>" . $data_to_display;
                }
                echo $data_to_display;
            } else {
                echo "<div class='p-2 my-2 text-danger border border-danger rounded'>No students found matching your filters." . $excluding_note . " Try adjusting or clearing some filters.</div>";
            }

        } elseif (isset($_GET['list_groups'])) {
            $select = "SELECT g.`group_id`, g.`group_name`, g.`description`, g.`date_created`,
                        (SELECT COUNT(*) FROM `student_group_members` m WHERE m.`group_id` = g.`group_id` AND m.`deleted` = 0) AS `member_count`
                       FROM `student_groups` g WHERE g.`deleted` = 0 ORDER BY g.`date_created` DESC";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();

            $data = "<table class='table table-bordered table-sm' id='student_groups_table'>
                <thead><tr><th>Group Name</th><th>Description</th><th>Members</th><th>Date Created</th><th>Actions</th></tr></thead><tbody>";
            $xs = 0;
            while ($result && ($row = $result->fetch_assoc())) {
                $xs++;
                $gid = (int)$row['group_id'];
                $data .= "<tr id='group_list_row_{$gid}'>
                    <td>" . e($row['group_name']) . "</td>
                    <td>" . e($row['description']) . "</td>
                    <td>{$row['member_count']}</td>
                    <td>" . date("d M Y", strtotime($row['date_created'])) . "</td>
                    <td>
                        <span class='link manage_members_btn' data-group-id='{$gid}' data-group-name='" . e($row['group_name']) . "'><i class='fas fa-users'></i> Manage Students</span><br>
                        <span class='link edit_group_btn' data-group-id='{$gid}' data-group-name='" . e($row['group_name']) . "' data-group-description='" . e($row['description']) . "'><i class='fas fa-pen'></i> Edit</span><br>
                        <span class='link text-success charge_group_btn' data-group-id='{$gid}' data-group-name='" . e($row['group_name']) . "'><i class='fas fa-file-invoice-dollar'></i> Charge this Group</span><br>
                        <span class='link text-danger delete_group_btn' data-group-id='{$gid}' data-group-name='" . e($row['group_name']) . "'><i class='fas fa-trash'></i> Delete</span>
                    </td>
                </tr>";
            }
            $data .= "</tbody></table>";
            if ($xs == 0) {
                $data = "<div class='p-2 my-2 text-secondary border rounded'>No groups have been created yet.</div>";
            }
            echo $data;

        } elseif (isset($_GET['get_group_members'])) {
            $group_id = (int)$_GET['get_group_members'];
            $select = "SELECT s.`adm_no`, s.`first_name`, s.`second_name`, s.`surname`, s.`course_done`
                       FROM `student_group_members` m
                       JOIN `student_data` s ON s.`adm_no` = m.`adm_no`
                       WHERE m.`group_id` = ? AND m.`deleted` = 0
                       ORDER BY s.`first_name` ASC";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("i", $group_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $course_lookup = get_courses_lookup($conn2);

            $data = "<ul class='list-group' id='manage_members_list'>";
            $xs = 0;
            while ($result && ($row = $result->fetch_assoc())) {
                $xs++;
                $name = ucwords(strtolower($row['first_name'] . " " . $row['second_name'] . " " . $row['surname']));
                $course_name = isset($course_lookup[(string)$row['course_done']]) ? $course_lookup[(string)$row['course_done']] : $row['course_done'];
                $search_key = strtolower($name . " " . $row['adm_no']);
                $data .= "<li class='list-group-item d-flex justify-content-between align-items-center mm_current_member_row' data-adm-no='" . e($row['adm_no']) . "' data-search='" . e($search_key) . "'>
                    <span>{$xs}. {$name} <small style='color:red;'>({$row['adm_no']})</small> <small class='text-muted'>| " . e($course_name) . "</small></span>
                    <span class='link text-danger remove_member_btn' data-adm-no='" . e($row['adm_no']) . "' data-group-id='{$group_id}'><i class='fas fa-times'></i></span>
                </li>";
            }
            $data .= "</ul><p class='hide' id='manage_members_total_count'>{$xs}</p>";
            if ($xs == 0) {
                $data = "<div class='p-2 text-secondary'>This group has no members.</div><p class='hide' id='manage_members_total_count'>0</p>";
            }
            echo $data;
        }

    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
        include("../../connections/conn1.php");
        include("../../connections/conn2.php");

        if (isset($_POST['save_group'])) {
            $group_name = trim($_POST['group_name']);
            $description = isset($_POST['description']) ? trim($_POST['description']) : null;
            $filters_json = isset($_POST['filters_json']) ? $_POST['filters_json'] : '{}';
            $adm_nos = isset($_POST['adm_nos']) ? json_decode($_POST['adm_nos'], true) : [];
            $created_by = isset($_SESSION['userids']) ? $_SESSION['userids'] : null;

            if (strlen($group_name) == 0) {
                echo json_encode(["status" => "error", "message" => "Please give the group a name."]);
                exit();
            }
            if (!is_array($adm_nos) || count($adm_nos) == 0) {
                echo json_encode(["status" => "error", "message" => "Select at least one student before saving the group."]);
                exit();
            }

            $insert = "INSERT INTO `student_groups` (`group_name`, `description`, `filters_json`, `created_by`) VALUES (?,?,?,?)";
            $stmt = $conn2->prepare($insert);
            $stmt->bind_param("sssi", $group_name, $description, $filters_json, $created_by);
            $stmt->execute();
            $group_id = $conn2->insert_id;

            $insert_member = "INSERT INTO `student_group_members` (`group_id`, `adm_no`) VALUES (?,?)";
            $stmt2 = $conn2->prepare($insert_member);
            foreach ($adm_nos as $adm_no) {
                $stmt2->bind_param("is", $group_id, $adm_no);
                $stmt2->execute();
            }

            echo json_encode(["status" => "success", "message" => "Group \"" . $group_name . "\" saved with " . count($adm_nos) . " student(s).", "group_id" => $group_id]);

        } elseif (isset($_POST['update_group_details'])) {
            $group_id = (int)$_POST['group_id'];
            $group_name = trim($_POST['group_name']);
            $description = isset($_POST['description']) ? trim($_POST['description']) : null;
            if (strlen($group_name) == 0) {
                echo json_encode(["status" => "error", "message" => "Group name cannot be empty."]);
                exit();
            }
            $update = "UPDATE `student_groups` SET `group_name` = ?, `description` = ? WHERE `group_id` = ?";
            $stmt = $conn2->prepare($update);
            $stmt->bind_param("ssi", $group_name, $description, $group_id);
            $stmt->execute();
            echo json_encode(["status" => "success", "message" => "Group updated successfully."]);

        } elseif (isset($_POST['delete_group'])) {
            $group_id = (int)$_POST['group_id'];
            $update = "UPDATE `student_groups` SET `deleted` = 1 WHERE `group_id` = ?";
            $stmt = $conn2->prepare($update);
            $stmt->bind_param("i", $group_id);
            $stmt->execute();
            echo json_encode(["status" => "success", "message" => "Group deleted successfully."]);

        } elseif (isset($_POST['add_group_members'])) {
            $group_id = (int)$_POST['group_id'];
            $adm_nos = isset($_POST['adm_nos']) ? json_decode($_POST['adm_nos'], true) : [];
            if (!is_array($adm_nos) || count($adm_nos) == 0) {
                echo json_encode(["status" => "error", "message" => "Select at least one student to add."]);
                exit();
            }

            $check = "SELECT `id`, `deleted` FROM `student_group_members` WHERE `group_id` = ? AND `adm_no` = ?";
            $check_stmt = $conn2->prepare($check);
            $insert_stmt = $conn2->prepare("INSERT INTO `student_group_members` (`group_id`, `adm_no`) VALUES (?,?)");
            $reactivate_stmt = $conn2->prepare("UPDATE `student_group_members` SET `deleted` = 0 WHERE `id` = ?");

            $added = 0;
            foreach ($adm_nos as $adm_no) {
                $check_stmt->bind_param("is", $group_id, $adm_no);
                $check_stmt->execute();
                $existing = $check_stmt->get_result()->fetch_assoc();

                if (!$existing) {
                    $insert_stmt->bind_param("is", $group_id, $adm_no);
                    $insert_stmt->execute();
                    $added++;
                } elseif ($existing['deleted'] == 1) {
                    $reactivate_stmt->bind_param("i", $existing['id']);
                    $reactivate_stmt->execute();
                    $added++;
                }
            }

            echo json_encode(["status" => "success", "message" => $added . " student(s) added to the group."]);

        } elseif (isset($_POST['remove_group_member'])) {
            $group_id = (int)$_POST['group_id'];
            $adm_no = $_POST['adm_no'];
            $update = "UPDATE `student_group_members` SET `deleted` = 1 WHERE `group_id` = ? AND `adm_no` = ?";
            $stmt = $conn2->prepare($update);
            $stmt->bind_param("is", $group_id, $adm_no);
            $stmt->execute();
            echo json_encode(["status" => "success", "message" => "Student removed from the group."]);
        }
    }

    function isJson_report($string) {
        return ((is_string($string) &&
                (is_object(json_decode($string)) ||
                is_array(json_decode($string))))) ? true : false;
    }

    // `student_data`.`course_done` stores a course's numeric id (settings.sett='courses'),
    // not its name -- build an id => course_name map once per request instead of
    // decoding the settings JSON on every row.
    function get_courses_lookup($conn2) {
        $lookup = [];
        $sel = "SELECT `valued` FROM `settings` WHERE `sett` = 'courses'";
        $stmt = $conn2->prepare($sel);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && ($r = $res->fetch_assoc()) && strlen(trim($r['valued'])) > 0) {
            $decoded = isJson_report($r['valued']) ? json_decode($r['valued'], true) : [];
            foreach ($decoded as $c) {
                $lookup[(string)$c['id']] = $c['course_name'];
            }
        }
        return $lookup;
    }

    function e($value) {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
?>
