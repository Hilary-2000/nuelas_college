<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    date_default_timezone_set('Africa/Nairobi');

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        include("../../connections/conn1.php");
        include("../../connections/conn2.php");

        if (isset($_GET['list_groups_light'])) {
            $select = "SELECT `group_id`, `group_name` FROM `student_groups` WHERE `deleted` = 0 ORDER BY `group_name` ASC";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $res = $stmt->get_result();
            $groups = [];
            while ($res && ($r = $res->fetch_assoc())) {
                $groups[] = $r;
            }
            echo json_encode($groups);

        } elseif (isset($_GET['lookup_student'])) {
            $adm_no = $_GET['lookup_student'];
            $select = "SELECT `adm_no`, `first_name`, `second_name`, `surname` FROM `student_data` WHERE `adm_no` = ? AND `deleted` = 0";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s", $adm_no);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && ($row = $res->fetch_assoc())) {
                $name = ucwords(strtolower($row['first_name'] . " " . $row['second_name'] . " " . $row['surname']));
                echo json_encode(["status" => "success", "adm_no" => $row['adm_no'], "name" => $name]);
            } else {
                echo json_encode(["status" => "error", "message" => "No student found with that admission number."]);
            }

        } elseif (isset($_GET['get_charged_account'])) {
            $adm_no = $_GET['get_charged_account'];
            $select = "SELECT `my_course_list` FROM `student_data` WHERE `adm_no` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s", $adm_no);
            $stmt->execute();
            $res = $stmt->get_result();
            $items = [];
            if ($res && ($row = $res->fetch_assoc())) {
                $active_term = find_active_module_term($row['my_course_list']);
                $items = get_charged_account_items_arr($active_term);
            }
            $total = 0;
            foreach ($items as $item) {
                $total += isset($item['amount']) ? (int)$item['amount'] : 0;
            }
            echo json_encode(["items" => $items, "total" => $total]);

        } elseif (isset($_GET['list_charge_batches'])) {
            $select = "SELECT cb.*, g.`group_name` FROM `charge_batches` cb LEFT JOIN `student_groups` g ON g.`group_id` = cb.`group_id` ORDER BY cb.`date_created` DESC";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();

            $data = "<table class='table table-bordered table-sm' id='charge_batches_table'>
                <thead><tr><th>Description</th><th>Period</th><th>Amount</th><th>Applied To</th><th>Date</th><th>Created By</th><th>Actions</th></tr></thead><tbody>";
            $xs = 0;
            while ($result && ($row = $result->fetch_assoc())) {
                $xs++;
                $bid = (int)$row['batch_id'];
                $applied_to = $row['target_type'] == 'group'
                    ? "Group: " . e($row['group_name'] ?? '(deleted group)') . " <small class='text-muted'>(" . $row['student_count'] . ")</small>"
                    : $row['student_count'] . " student(s)";
                $created_by_name = get_user_fullname($conn, $row['created_by']);
                $data .= "<tr>
                    <td>" . e($row['description']) . "</td>
                    <td>" . e($row['period']) . "</td>
                    <td>Kes " . number_format($row['amount']) . "</td>
                    <td>{$applied_to}</td>
                    <td>" . date("d M Y", strtotime($row['date_created'])) . "</td>
                    <td>" . e($created_by_name) . "</td>
                    <td><span class='link view_batch_students_btn' data-batch-id='{$bid}'><i class='fas fa-users'></i> View Students</span></td>
                </tr>";
            }
            $data .= "</tbody></table>";
            if ($xs == 0) {
                $data = "<div class='p-2 my-2 text-secondary border rounded'>No charges have been created yet.</div>";
            }
            echo $data;

        } elseif (isset($_GET['get_batch_students'])) {
            $batch_id = (int)$_GET['get_batch_students'];
            $select = "SELECT bs.`adm_no`, s.`first_name`, s.`second_name`, s.`surname`
                       FROM `charge_batch_students` bs
                       LEFT JOIN `student_data` s ON s.`adm_no` = bs.`adm_no`
                       WHERE bs.`batch_id` = ? ORDER BY s.`first_name` ASC";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("i", $batch_id);
            $stmt->execute();
            $result = $stmt->get_result();

            $data = "<ul class='list-group'>";
            $xs = 0;
            while ($result && ($row = $result->fetch_assoc())) {
                $xs++;
                $name = $row['first_name'] !== null ? ucwords(strtolower($row['first_name'] . " " . $row['second_name'] . " " . $row['surname'])) : "(student no longer on record)";
                $search_key = strtolower($name . " " . $row['adm_no']);
                $data .= "<li class='list-group-item batch_student_row' data-search='" . e($search_key) . "'>{$xs}. {$name} <small style='color:red;'>({$row['adm_no']})</small></li>";
            }
            $data .= "</ul>";
            if ($xs == 0) {
                $data = "<div class='p-2 text-secondary'>No students recorded for this batch.</div>";
            }
            echo $data;
        }

    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
        include("../../connections/conn1.php");
        include("../../connections/conn2.php");

        if (isset($_POST['create_charge'])) {
            $target_type = $_POST['target_type'] == 'group' ? 'group' : 'students';
            $description = trim($_POST['description']);
            $period = trim($_POST['period']);
            $amount = (int)$_POST['amount'];
            $group_id = null;
            $created_by = isset($_SESSION['userids']) ? $_SESSION['userids'] : null;

            if (strlen($description) == 0 || strlen($period) == 0 || $amount <= 0) {
                echo json_encode(["status" => "error", "message" => "Description, period, and a positive amount are required."]);
                exit();
            }

            $adm_nos = [];
            if ($target_type == 'group') {
                $group_id = (int)$_POST['group_id'];
                $select = "SELECT `adm_no` FROM `student_group_members` WHERE `group_id` = ? AND `deleted` = 0";
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("i", $group_id);
                $stmt->execute();
                $res = $stmt->get_result();
                while ($res && ($r = $res->fetch_assoc())) {
                    $adm_nos[] = $r['adm_no'];
                }
                if (count($adm_nos) == 0) {
                    echo json_encode(["status" => "error", "message" => "That group has no members to charge."]);
                    exit();
                }
            } else {
                $posted = isset($_POST['student_list']) ? json_decode($_POST['student_list'], true) : [];
                if (!is_array($posted) || count($posted) == 0) {
                    echo json_encode(["status" => "error", "message" => "Select at least one student."]);
                    exit();
                }
                // Only charge admission numbers that actually exist.
                $check = $conn2->prepare("SELECT `adm_no` FROM `student_data` WHERE `adm_no` = ? AND `deleted` = 0");
                foreach ($posted as $adm_no) {
                    $check->bind_param("s", $adm_no);
                    $check->execute();
                    if ($check->get_result()->fetch_assoc()) {
                        $adm_nos[] = $adm_no;
                    }
                }
                if (count($adm_nos) == 0) {
                    echo json_encode(["status" => "error", "message" => "None of the admission numbers given were found."]);
                    exit();
                }
            }

            $insert_batch = "INSERT INTO `charge_batches` (`description`, `period`, `amount`, `target_type`, `group_id`, `student_count`, `created_by`) VALUES (?,?,?,?,?,?,?)";
            $stmt = $conn2->prepare($insert_batch);
            $student_count = count($adm_nos);
            $stmt->bind_param("ssisiii", $description, $period, $amount, $target_type, $group_id, $student_count, $created_by);
            $stmt->execute();
            $batch_id = $conn2->insert_id;

            $insert_batch_student = $conn2->prepare("INSERT INTO `charge_batch_students` (`batch_id`, `adm_no`) VALUES (?,?)");
            $get_list = $conn2->prepare("SELECT `my_course_list` FROM `student_data` WHERE `adm_no` = ?");
            $update_list = $conn2->prepare("UPDATE `student_data` SET `my_course_list` = ? WHERE `adm_no` = ?");
            $charged = 0;

            foreach ($adm_nos as $adm_no) {
                $insert_batch_student->bind_param("is", $batch_id, $adm_no);
                $insert_batch_student->execute();

                $get_list->bind_param("s", $adm_no);
                $get_list->execute();
                $row = $get_list->get_result()->fetch_assoc();
                if (!$row) continue;

                $course_list = isJson_charge($row['my_course_list']) ? json_decode($row['my_course_list'], true) : [];
                $updated = false;
                foreach ($course_list as $ci => $course) {
                    if (!is_array($course) || !isset($course['course_status']) || $course['course_status'] != 1) continue;
                    if (!isset($course['module_terms']) || !is_array($course['module_terms'])) continue;
                    foreach ($course['module_terms'] as $ti => $term) {
                        if (!is_array($term) || !isset($term['status']) || ($term['status'] != 1 && $term['status'] !== "1")) continue;
                        if (!isset($course_list[$ci]['module_terms'][$ti]['charged_account'])) {
                            $course_list[$ci]['module_terms'][$ti]['charged_account'] = ['items' => []];
                        }
                        $course_list[$ci]['module_terms'][$ti]['charged_account']['items'][] = [
                            'charge_id' => uniqid('c_', true),
                            'description' => $description,
                            'period' => $period,
                            'amount' => $amount,
                            'batch_id' => $batch_id,
                            'date_created' => date('Y-m-d H:i:s'),
                            'created_by' => $_SESSION['username'] ?? ''
                        ];
                        $updated = true;
                        break;
                    }
                    if ($updated) break;
                }

                if ($updated) {
                    $new_json = json_encode($course_list);
                    $update_list->bind_param("ss", $new_json, $adm_no);
                    $update_list->execute();
                    $charged++;
                }
            }

            log_charges("Charged Account \"" . $description . "\" (" . $period . ", Kes " . number_format($amount) . ") created for " . $charged . " student(s).");

            echo json_encode(["status" => "success", "message" => "Charge created for " . $charged . " student(s).", "batch_id" => $batch_id]);

        } elseif (isset($_POST['update_charge_item'])) {
            $adm_no = $_POST['adm_no'];
            $charge_id = $_POST['charge_id'];
            $description = trim($_POST['description']);
            $amount = (int)$_POST['amount'];

            if (strlen($description) == 0 || $amount < 0) {
                echo json_encode(["status" => "error", "message" => "Description and a valid amount are required."]);
                exit();
            }

            $stmt = $conn2->prepare("SELECT `my_course_list` FROM `student_data` WHERE `adm_no` = ?");
            $stmt->bind_param("s", $adm_no);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            if (!$row) {
                echo json_encode(["status" => "error", "message" => "Student not found."]);
                exit();
            }

            $course_list = isJson_charge($row['my_course_list']) ? json_decode($row['my_course_list'], true) : [];
            $found = false;
            foreach ($course_list as $ci => $course) {
                if (!is_array($course) || !isset($course['course_status']) || $course['course_status'] != 1) continue;
                if (!isset($course['module_terms']) || !is_array($course['module_terms'])) continue;
                foreach ($course['module_terms'] as $ti => $term) {
                    if (!is_array($term) || !isset($term['status']) || ($term['status'] != 1 && $term['status'] !== "1")) continue;
                    if (!isset($term['charged_account']['items']) || !is_array($term['charged_account']['items'])) continue;
                    foreach ($term['charged_account']['items'] as $ii => $item) {
                        if (isset($item['charge_id']) && $item['charge_id'] == $charge_id) {
                            $course_list[$ci]['module_terms'][$ti]['charged_account']['items'][$ii]['description'] = $description;
                            $course_list[$ci]['module_terms'][$ti]['charged_account']['items'][$ii]['amount'] = $amount;
                            $found = true;
                            break;
                        }
                    }
                }
            }

            if (!$found) {
                echo json_encode(["status" => "error", "message" => "That charge item was not found on the student's active module."]);
                exit();
            }

            $new_json = json_encode($course_list);
            $update = $conn2->prepare("UPDATE `student_data` SET `my_course_list` = ? WHERE `adm_no` = ?");
            $update->bind_param("ss", $new_json, $adm_no);
            $update->execute();

            log_charges("Charged item \"" . $description . "\" (Kes " . number_format($amount) . ") updated for student " . $adm_no . ".");

            echo json_encode(["status" => "success", "message" => "Charge item updated."]);

        } elseif (isset($_POST['delete_charge_item'])) {
            $adm_no = $_POST['adm_no'];
            $charge_id = $_POST['charge_id'];

            $stmt = $conn2->prepare("SELECT `my_course_list` FROM `student_data` WHERE `adm_no` = ?");
            $stmt->bind_param("s", $adm_no);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            if (!$row) {
                echo json_encode(["status" => "error", "message" => "Student not found."]);
                exit();
            }

            $course_list = isJson_charge($row['my_course_list']) ? json_decode($row['my_course_list'], true) : [];
            $found = false;
            $removed_description = "";
            foreach ($course_list as $ci => $course) {
                if (!is_array($course) || !isset($course['course_status']) || $course['course_status'] != 1) continue;
                if (!isset($course['module_terms']) || !is_array($course['module_terms'])) continue;
                foreach ($course['module_terms'] as $ti => $term) {
                    if (!is_array($term) || !isset($term['status']) || ($term['status'] != 1 && $term['status'] !== "1")) continue;
                    if (!isset($term['charged_account']['items']) || !is_array($term['charged_account']['items'])) continue;
                    foreach ($term['charged_account']['items'] as $ii => $item) {
                        if (isset($item['charge_id']) && $item['charge_id'] == $charge_id) {
                            $removed_description = $item['description'] ?? '';
                            unset($course_list[$ci]['module_terms'][$ti]['charged_account']['items'][$ii]);
                            $course_list[$ci]['module_terms'][$ti]['charged_account']['items'] = array_values($course_list[$ci]['module_terms'][$ti]['charged_account']['items']);
                            $found = true;
                            break;
                        }
                    }
                }
            }

            if (!$found) {
                echo json_encode(["status" => "error", "message" => "That charge item was not found on the student's active module."]);
                exit();
            }

            $new_json = json_encode($course_list);
            $update = $conn2->prepare("UPDATE `student_data` SET `my_course_list` = ? WHERE `adm_no` = ?");
            $update->bind_param("ss", $new_json, $adm_no);
            $update->execute();

            log_charges("Charged item \"" . $removed_description . "\" deleted for student " . $adm_no . ".");

            echo json_encode(["status" => "success", "message" => "Charge item deleted."]);
        }
    }

    function isJson_charge($string) {
        return ((is_string($string) &&
                (is_object(json_decode($string)) ||
                is_array(json_decode($string))))) ? true : false;
    }

    function e($value) {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }

    // Returns the active module term (assoc array) from a raw my_course_list JSON string, or null.
    function find_active_module_term($my_course_list_json) {
        $course_list = isJson_charge($my_course_list_json) ? json_decode($my_course_list_json, true) : [];
        if (!is_array($course_list)) return null;
        foreach ($course_list as $course) {
            if (!is_array($course) || !isset($course['course_status']) || $course['course_status'] != 1) continue;
            if (!isset($course['module_terms']) || !is_array($course['module_terms'])) continue;
            foreach ($course['module_terms'] as $term) {
                if (is_array($term) && isset($term['status']) && ($term['status'] == 1 || $term['status'] === "1")) {
                    return $term;
                }
            }
        }
        return null;
    }

    function get_charged_account_items_arr($active_module_term) {
        if (!$active_module_term || !isset($active_module_term['charged_account']['items'])) {
            return [];
        }
        return $active_module_term['charged_account']['items'];
    }

    function get_user_fullname($conn, $user_id) {
        if (!$user_id) return "";
        $stmt = $conn->prepare("SELECT `fullname` FROM `user_tbl` WHERE `user_id` = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ? $row['fullname'] : "";
    }

    // Same file-logging convention as log_finance() in financial.php.
    // Logging is best-effort: a permission/disk issue here must never break the
    // actual API response, so every filesystem call is failure-suppressed (@).
    function log_charges($text) {
        $full_text = date("dS M Y H:i:sA") . " : " . $text . " - {" . ($_SESSION['username'] ?? '') . "}\n";
        $file_location = "../../ajax/logs/" . $_SESSION['dbname'] . "/logs.txt";
        if (file_exists($file_location)) {
            $content = @file_get_contents($file_location);
            $file = @fopen($file_location, 'w');
            if ($file) {
                fwrite($file, $full_text . $content);
                fclose($file);
            }
        } else {
            $directory = dirname($file_location);
            if (!is_dir($directory)) {
                @mkdir($directory, 0777, true);
            }
            @file_put_contents($file_location, $full_text);
        }
    }
?>
