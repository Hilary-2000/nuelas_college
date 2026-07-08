<?php

if(session_status()==PHP_SESSION_NONE){
    //session is not started
    session_start();
}
date_default_timezone_set('Africa/Nairobi');

// Sends the "module_progression_message" template (if one has been saved under
// Template Messages) to whichever parent contacts are on file for the student,
// right as the cron steps them into their next module.
function notifyModuleProgression($student_row, $conn, $conn2) {
    $progression_message = getMessage("module_progression_message", $conn2);
    if ($progression_message === null) {
        // nothing configured under Template Messages yet -- stay silent
        return;
    }

    $contacts = explode(",", getPhoneNumber($conn2, $student_row['adm_no']));
    $parent_1 = isset($contacts[1]) ? $contacts[1] : "";
    $parent_2 = isset($contacts[2]) ? $contacts[2] : "";

    $api_key = getApiKey($conn2);
    $school = 1;
    if ($api_key == 0) {
        $school = 0;
        $api_key = getApiKey($conn);
    }
    if ($api_key === 0) {
        // no SMS API configured for this school (or the platform fallback) -- stay silent
        return;
    }
    $partnerID = $school == 0 ? getPatnerId($conn) : getPatnerId($conn2);
    $shortcodes = $school == 0 ? getShortCode($conn) : getShortCode($conn2);
    $send_sms_url = $school == 0 ? getUrl($conn) : getUrl($conn2);

    $recipients = [
        ["number" => $parent_1, "which" => "primary"],
        ["number" => $parent_2, "which" => "secondary"],
    ];
    foreach ($recipients as $recipient) {
        if (strlen($recipient["number"]) < 10) {
            continue;
        }
        $message = process_sms([$student_row], $progression_message, $student_row['adm_no'], $conn2, $recipient["which"]);
        sendSmsToClient($recipient["number"], $message, $api_key, $partnerID, $shortcodes, $send_sms_url);

        $insert = "INSERT INTO `sms_table` (`message_count`,`date_sent`,`message_sent_succesfully`,`message_undelivered`,`message_type`,`message_description`,`sender_no`,`message`,`number_collection`) VALUES (?,?,?,?,?,?,?,?,?)";
        $stmt_sms = $conn2->prepare($insert);
        $message_count = 1;
        $message_undelivered = 0;
        $message_type_sms = "Multicast";
        $message_desc = strlen($message) > 43 ? substr($message, 0, 45)."..." : $message;
        $date_sms = date("Y-m-d");
        $stmt_sms->bind_param("sssssssss", $message_count, $date_sms, $message_count, $message_undelivered, $message_type_sms, $message_desc, $recipient["number"], $message, $recipient["number"]);
        $stmt_sms->execute();
    }
}

$_SERVER['REQUEST_METHOD'] = "";
$databases = ['nuelas_college'];
foreach ($databases as $database) {
    include_once("/opt/lampp/htdocs/nuelas_college/ajax/finance/financial.php");
    include_once("/opt/lampp/htdocs/nuelas_college/ajax/sms/sms.php");
    include_once("/opt/lampp/htdocs/nuelas_college/sms_apis/sms.php");
    include_once("/opt/lampp/htdocs/nuelas_college/connections/conn1.php");
    include("/opt/lampp/htdocs/nuelas_college/connections/module_stepper_conn.php");
    if ($conn2 != null) {
        // GET ALL COURSES
        $select = "SELECT * FROM `settings` WHERE `sett` = 'courses';";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        $course_list = [];
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $course_list = isJson($row['valued']) ? json_decode($row['valued']) : [];
            }
        }

        $select = "SELECT * FROM student_data WHERE course_progress_status = '1'";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $course_done = $row['course_done'];
                $course_duration = "0 Days";
                foreach ($course_list as $key => $value) {
                    if ($value->id == $course_done) {
                        $course_duration = $value->term_duration." ".$value->duration_intervals;
                    }
                }

                // student_course
                $student_course = isJson($row['my_course_list']) ? json_decode($row['my_course_list']) : [];

                // check if its to be updated or not.
                $stepped = false;
                foreach ($student_course as $key => $course) {
                    if ($course->course_status == 1) {
                        $modules = $course->module_terms;
                        foreach ($modules as $key_mod => $value) {
                            if($value->status == 1 && date("Ymd") >= date("Ymd", strtotime($value->end_date))){
                                echo $row['first_name']." - ".$row['second_name']."<span style='color:green'>(Extended)</span> <br>\n";
                                if(!$stepped){
                                    if ($key_mod < count($modules)-1) {
                                        // notify the parents before the module_terms below get mutated,
                                        // so process_sms() still sees the currently-active module as
                                        // "current" and correctly resolves {next_module_fees} to the
                                        // module the student is being moved into.
                                        notifyModuleProgression($row, $conn, $conn2);
                                    }

                                    $student_course[$key]->module_terms[$key_mod]->status = 2;
                                    if ($key_mod < count($modules)-1) {
                                        // update the next module
                                        $student_course[$key]->module_terms[$key_mod+1]->status = 1;
                                        $student_course[$key]->module_terms[$key_mod+1]->start_date = date("YmdHis");
                                        $student_course[$key]->module_terms[$key_mod+1]->end_date = date("YmdHis", strtotime($course_duration));
                                    }

                                    // add the balance to the student
                                    $study_mode_val = strtolower($row['study_mode'] ?? 'fulltime');
                                    $term = $study_mode_val === 'weekend' ? 'TERM_3' : ($study_mode_val === 'evening' ? 'TERM_2' : ($study_mode_val === 'online' ? 'TERM_4' : 'TERM_1'));
                                    $update = "UPDATE student_data SET balance_carry_forward = ?, my_course_list = ? WHERE adm_no = ?";
                                    $stmt = $conn2->prepare($update);
                                    $student_balance = getBalanceReports($row['adm_no'], $term, $conn2);
                                    $student_course = json_encode($student_course);
                                    $stmt->bind_param("sss", $student_balance, $student_course, $row['adm_no']);
                                    $stmt->execute();
                                    $stepped = true;
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
        $conn2->close();
    }
}
?>
