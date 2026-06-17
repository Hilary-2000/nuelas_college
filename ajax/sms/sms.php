<?php
    session_start();
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require '../administration/phpmailer/src/Exception.php';
    require '../administration/phpmailer/src/PHPMailer.php';
    require '../administration/phpmailer/src/SMTP.php';
    date_default_timezone_set('Africa/Nairobi');
    if ($_SERVER['REQUEST_METHOD'] =='GET') {
        include("../../connections/conn1.php");
        include("../../connections/conn2.php");
        if (isset($_GET['getMyStaff'])) {
            $select = "SELECT `fullname`,`phone_number` FROM `user_tbl` WHERE `school_code` = ? AND `deleted` = 0";
            $stmt = $conn->prepare($select);
            $schoolcode = $_SESSION['schoolcode'];
            $stmt->bind_param("s",$schoolcode);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $data_to_display = "<select id='select_staff_sms'><option value='' hidden>Select staff..</option>";
                $xs = 0;
                while ($row = $result->fetch_assoc()) {
                    $xs++;
                    $data_to_display.="<option value='".$row['phone_number']."'>".ucwords(strtolower($row['fullname']))."</option>";
                }
                $data_to_display.="</select>";
                if ($xs > 0) {
                    echo $data_to_display;
                }else {
                    echo "<p class='red_notice'>No staff present.</p>";
                }
            }
        }elseif (isset($_GET["save_welcome_message"])) {
            $welcome_message = $_GET['welcome_message'];
            $select = "SELECT * FROM `template_messages` WHERE `message_type` = 'welcome_message';";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $stmt->store_result();
            $num_rows = $stmt->num_rows;
            
            if ($num_rows > 0) {
                // update the welcome message
                $stmt->execute();
                $result = $stmt->get_result();
                if($row = $result->fetch_assoc()){
                    $message_id = $row['message_id'];
                    $update = "UPDATE `template_messages` SET `message_content` = ? WHERE `message_id` = ?";
                    $stmt = $conn2->prepare($update);
                    $stmt->bind_param("ss",$welcome_message, $message_id);
                    $stmt->execute();
                    echo "<p class='text-success'>Message template saved successfully!.</p>";
                }else{
                    echo "<p class='text-danger'>Error has occured! Try again later.</p>";
                }
            }else{
                $insert = "INSERT INTO `template_messages` (`message_type`,`message_content`,`date_created`,`date_updated`) VALUES (?,?,?,?)";
                $stmt = $conn2->prepare($insert);
                $message_type = "welcome_message";
                $date_today = date("YmdHis");
                $stmt->bind_param("ssss",$message_type,$welcome_message,$date_today,$date_today);
                $stmt->execute();
                echo "<p class='text-success'>Message template saved successfully!.</p>";
            }
        }elseif (isset($_GET["student_save_welcome_message"])) {
            $welcome_message = $_GET['welcome_message'];
            $select = "SELECT * FROM `template_messages` WHERE `message_type` = 'student_welcome_message';";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $stmt->store_result();
            $num_rows = $stmt->num_rows;
            
            if ($num_rows > 0) {
                // update the welcome message
                $stmt->execute();
                $result = $stmt->get_result();
                if($row = $result->fetch_assoc()){
                    $message_id = $row['message_id'];
                    $update = "UPDATE `template_messages` SET `message_content` = ? WHERE `message_id` = ?";
                    $stmt = $conn2->prepare($update);
                    $stmt->bind_param("ss",$welcome_message, $message_id);
                    $stmt->execute();
                    echo "<p class='text-success'>Message template saved successfully!.</p>";
                }else{
                    echo "<p class='text-danger'>Error has occured! Try again later.</p>";
                }
            }else{
                $insert = "INSERT INTO `template_messages` (`message_type`,`message_content`,`date_created`,`date_updated`) VALUES (?,?,?,?)";
                $stmt = $conn2->prepare($insert);
                $message_type = "student_welcome_message";
                $date_today = date("YmdHis");
                $stmt->bind_param("ssss",$message_type,$welcome_message,$date_today,$date_today);
                $stmt->execute();
                echo "<p class='text-success'>Message template saved successfully!.</p>";
            }
        }elseif (isset($_GET['get_messages_samples'])) {
            $select = "SELECT * FROM `template_messages`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = [];
            if ($result) {
                while($row = $result->fetch_assoc()) {
                    array_push($data_to_display,$row);
                }
            }
            echo json_encode($data_to_display);
        }elseif (isset($_GET["save_confirmation_message"])) {
            $confirmation_message = $_GET['confirmation_message'];
            $select = "SELECT * FROM `template_messages` WHERE `message_type` = 'confirmation_message';";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $stmt->store_result();
            $num_rows = $stmt->num_rows;
            
            if ($num_rows > 0) {
                // update the welcome message
                $stmt->execute();
                $result = $stmt->get_result();
                if($row = $result->fetch_assoc()){
                    $message_id = $row['message_id'];
                    $update = "UPDATE `template_messages` SET `message_content` = ? WHERE `message_id` = ?";
                    $stmt = $conn2->prepare($update);
                    $stmt->bind_param("ss",$confirmation_message, $message_id);
                    $stmt->execute();
                    echo "<p class='text-success'>Message template saved successfully!.</p>";
                }else{
                    echo "<p class='text-danger'>Error has occured! Try again later.</p>";
                }
            }else{
                $insert = "INSERT INTO `template_messages` (`message_type`,`message_content`,`date_created`,`date_updated`) VALUES (?,?,?,?)";
                $stmt = $conn2->prepare($insert);
                $message_type = "confirmation_message";
                $date_today = date("YmdHis");
                $stmt->bind_param("ssss",$message_type,$confirmation_message,$date_today,$date_today);
                $stmt->execute();
                echo "<p class='text-success'>Message template saved successfully!.</p>";
            }
        }elseif (isset($_GET["save_parent_confirmation"])) {
            $parent_account_msg = $_GET['parent_account_msg'];
            $select = "SELECT * FROM `template_messages` WHERE `message_type` = 'parent_account_confirmation_message';";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $stmt->store_result();
            $num_rows = $stmt->num_rows;
            
            if ($num_rows > 0) {
                // update the welcome message
                $stmt->execute();
                $result = $stmt->get_result();
                if($row = $result->fetch_assoc()){
                    $message_id = $row['message_id'];
                    $update = "UPDATE `template_messages` SET `message_content` = ? WHERE `message_id` = ?";
                    $stmt = $conn2->prepare($update);
                    $stmt->bind_param("ss",$parent_account_msg, $message_id);
                    $stmt->execute();
                    echo "<p class='text-success'>Message template saved successfully!.</p>";
                }else{
                    echo "<p class='text-danger'>Error has occured! Try again later.</p>";
                }
            }else{
                $insert = "INSERT INTO `template_messages` (`message_type`,`message_content`,`date_created`,`date_updated`) VALUES (?,?,?,?)";
                $stmt = $conn2->prepare($insert);
                $message_type = "parent_account_confirmation_message";
                $date_today = date("YmdHis");
                $stmt->bind_param("ssss",$message_type,$parent_account_msg,$date_today,$date_today);
                $stmt->execute();
                echo "<p class='text-success'>Message template saved successfully!.</p>";
            }
        }elseif (isset($_GET['getStaffMailData'])) {
            $select = "SELECT `fullname`,`email` FROM `user_tbl` WHERE `school_code` = ? AND `deleted` = 0";
            $stmt = $conn->prepare($select);
            $schoolcode = $_SESSION['schoolcode'];
            $stmt->bind_param("s",$schoolcode);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $data_to_display = "<select id='select_staff_emails'><option value='' hidden>Select staff..</option>";
                $xs = 0;
                while ($row = $result->fetch_assoc()) {
                    $xs++;
                    $data_to_display.="<option value='".$row['email']."'>".ucwords(strtolower($row['fullname']))."</option>";
                }
                $data_to_display.="</select>";
                if ($xs > 0) {
                    echo $data_to_display;
                }else {
                    echo "<p class='red_notice'>No staff present.</p>";
                }
            }
        }elseif (isset($_GET['send_sms'])) {
            // check FUP
            include_once("../../sms_apis/sms.php");
            include_once("../../connections/conn1.php");
            $fup_limit = 1000;
            // if($fup_limit < 0){
            //     echo "<p class='red_notice'>Your SMS limit for this term is reached, kindly contact your administrator, standard FUP policy applies!</p>";
            //     exit();
            // }
            $phone_number = $_GET['phone_no'];
            $message = $_GET['message'];
            
            if(strlen($phone_number) < 10){
                echo "<p class='red_notice'>Invalid phone number!</p>";
                exit();
            }

            // SEND MESSAGE
            $message_count = "1";
            $message_undelivered = "0";
            $message_type = "Broadcast";
            $message_desc = $message;
            if (strlen($message) > 45) {
                $message_desc = substr($message,0,45)."...";
            }
            $date = date("Y-m-d");

            $insert = "INSERT INTO `sms_table` (`message_count`,`date_sent`,`message_sent_succesfully`,`message_undelivered`,`message_type`,`message_description`,`sender_no`,`message`,`number_collection`, `message_status`) VALUES (?,?,?,?,?,?,?,?,?,?)";
            $stmt = $conn2->prepare($insert);
            $message_status = $fup_limit <= 0 ? "sent" : "pending";
            $stmt->bind_param("ssssssssss",$message_count,$date,$message_count,$message_undelivered,$message_type,$message_desc,$phone_number,$message,$phone_number, $message_status);
            $stmt->execute();

            // TRIGGER SMS AGENT
            trigger_sms_agent();
            $log_message = "1 Message sent to \"".$phone_number."\"!";
            log_SMS($log_message);
            echo "<p class='green_notice'>Message has been sent successfully!</p>";
            exit();
        }elseif (isset($_GET['check_delivery'])) {
            include("../../sms_apis/sms.php");
            $message_id = $_GET['message_id'];
            $api_key = getApiKeySms($conn);
            if ($api_key !== 0) {
                $partnerID = getPatnerIdSms($conn);
                echo checkDelivery($api_key,$partnerID,$message_id);
            }else {
                echo "<p class='red_notice'>Activate your sms account!</p>";
            }
        }elseif (isset($_GET['sms_val'])) {
            //$select = "INSERT INTO `sms_table` (`message_count`,`message_sent_succesfully`,`message_undelivered`,`message_type`,`message_description`,`sender_no`,`message`) VALUES (?,?,?,?,?,?,?)";
            $select = "INSERT INTO `sms_table` (`message_count`,`date_sent`,`message_undelivered`,`message_sent_succesfully`,`message_type`,`sender_no`,`message_description`,`message`,`number_collection`) VALUES (?,?,?,?,?,?,?,?,?)";
            $message_type = $_GET['message_type'];
            $message_count = $_GET['message_count'];
            $recipient_no = $_GET['recipient_no'];
            $text_message = $_GET['text_message'];
            $message_desc = substr($_GET['text_message'],0,45)."...";
            $stmt = $conn2->prepare($select);
            $date = date("YmdHis");
            $number_collection = json_encode([$recipient_no]);
            $stmt->bind_param("sssssssss",$message_count,$date,$message_count,$message_count,$message_type,$recipient_no,$message_desc,$text_message,$number_collection);
            $stmt->execute();
        }elseif (isset($_GET['mystaff_list'])) {
            $select = "SELECT `fullname`,`phone_number`,`user_id` FROM `user_tbl` WHERE `school_code` = ?";
            $stmt = $conn->prepare($select);
            $stmt->bind_param("s",$_SESSION['schoolcode']);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $data_to_display = "<div class='staff_list'>";
                $xs = 0;
                while ($row = $result->fetch_assoc()) {
                    $xs++;
                    $data_to_display.="<div class='staff_dets'>
                                        <label style='font-size:12px;'>".$xs.".</label>
                                        <label for='p".$row['user_id']."' style='font-size:12px;'>".ucwords($row['fullname'])."</label>
                                        <input type='checkbox' class='snamesd112e' name='p".$row['user_id']."' id='p".$row['user_id']."'>
                                    </div>";
                }
                $data_to_display.="</div>";
                if ($xs > 0) {
                    echo $data_to_display;
                }else {
                    echo "<p class='red_notice'>No teachers present!</p>";
                }
            }
        }elseif (isset($_GET['parents_lists'])) {
            $select = "SELECT `valued` FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $valued = $row['valued'];
                    $data_to_display = "<select id='my-class'><option value='' hidden>Select class..</option>";
                    if (strlen($valued)>0) {
                        $valued = isJson_report($valued) ? json_decode($valued) : [];
                        $class_list = [];
                        for ($index=0; $index < count($valued); $index++) { 
                            array_push($class_list,$valued[$index]->classes);
                        }
                        if (count($class_list) > 0) {
                            for ($indez=0; $indez < count($class_list); $indez++) {
                                $data_to_display.="<option value='".$class_list[$indez]."'>".majinaDarasa($class_list[$indez])."</option>";
                            }
                            $data_to_display.="<option value='-3'>Student Inquiries</option>";
                            $data_to_display.="<option value='-1'>Alumnis</option>";
                            $data_to_display.="<option value='all'>— All Levels —</option>";
                            $data_to_display.="</select>";
                            echo $data_to_display;
                        }else {
                            echo "<p class = 'red_notice'>No class avalable!</p>";
                        }
                    }else {
                        echo "<p class = 'red_notice'>No class avalable!</p>";
                    }
                }
            }
        }elseif (isset($_GET['get_parents_list'])) {
            include("../../ajax/finance/financial.php");

            $get_parents_list = $_GET['get_parents_list'];

            // PHP-side filter params
            $filter_status       = isset($_GET['filter_status']) ? $_GET['filter_status'] : 'all';
            $filter_module_end   = (isset($_GET['filter_module_end_date']) && strlen(trim($_GET['filter_module_end_date'])) > 0) ? trim($_GET['filter_module_end_date']) : null;
            $filter_bal_min      = (isset($_GET['filter_balance_min']) && strlen(trim($_GET['filter_balance_min'])) > 0) ? (float)$_GET['filter_balance_min'] : null;
            $filter_bal_max      = (isset($_GET['filter_balance_max']) && strlen(trim($_GET['filter_balance_max'])) > 0) ? (float)$_GET['filter_balance_max'] : null;

            // SQL-side filter conditions
            $sql_extra = "";
            if (isset($_GET['filter_gender']) && $_GET['filter_gender'] != 'all' && strlen($_GET['filter_gender']) > 0) {
                $sql_extra .= " AND `gender` = '" . mysqli_real_escape_string($conn2, $_GET['filter_gender']) . "'";
            }
            if (isset($_GET['filter_branch']) && $_GET['filter_branch'] != 'all' && strlen($_GET['filter_branch']) > 0 && is_numeric($_GET['filter_branch'])) {
                $sql_extra .= " AND `branch_name` = " . (int)$_GET['filter_branch'];
            }
            if (isset($_GET['filter_intake_year']) && $_GET['filter_intake_year'] != 'all' && strlen($_GET['filter_intake_year']) > 0) {
                $sql_extra .= " AND `intake_year` = '" . mysqli_real_escape_string($conn2, $_GET['filter_intake_year']) . "'";
            }
            if (isset($_GET['filter_intake_month']) && $_GET['filter_intake_month'] != 'all' && strlen($_GET['filter_intake_month']) > 0) {
                $sql_extra .= " AND `intake_month` = '" . mysqli_real_escape_string($conn2, $_GET['filter_intake_month']) . "'";
            }

            // Course/programme condition
            $course_cond = "";
            if (isset($_GET['course_selected']) && strlen(trim($_GET['course_selected'])) > 0
                && $_GET['course_selected'] != 'all'
                && $get_parents_list != "-3" && $get_parents_list != "-1") {
                $course_cond = " AND `course_done` = '" . mysqli_real_escape_string($conn2, $_GET['course_selected']) . "'";
            }

            // Build the base query
            if ($get_parents_list == 'all') {
                $select = "SELECT * FROM `student_data` WHERE `stud_class` != '-1' AND `stud_class` != '-2'" . $course_cond . $sql_extra;
            } elseif ($get_parents_list == "others") {
                $sel2 = "SELECT `valued` FROM `settings` WHERE `sett` = 'class'";
                $stmt2 = $conn2->prepare($sel2);
                $stmt2->execute();
                $res2 = $stmt2->get_result();
                $where_clause = "`stud_class` != '-1' AND `stud_class` != '-2'";
                if ($res2 && ($rw2 = $res2->fetch_assoc())) {
                    $valued = isJson_report($rw2['valued']) ? json_decode($rw2['valued']) : [];
                    for ($index = 0; $index < count($valued); $index++) {
                        $where_clause .= " AND `stud_class` != '" . mysqli_real_escape_string($conn2, $valued[$index]->classes) . "'";
                    }
                }
                $select = "SELECT * FROM `student_data` WHERE " . $where_clause . $sql_extra;
            } else {
                $select = "SELECT * FROM `student_data` WHERE `stud_class` = '" . mysqli_real_escape_string($conn2, $get_parents_list) . "'" . $course_cond . $sql_extra;
            }

            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result) {
                $data_to_display = "<div class='staff_list'>
                    <div class='staff_dets'>
                        <label for='staff123s' style='color:cadetblue;'>Select all visible</label>
                        <input type='checkbox' name='staff123s' id='staff123s'>
                    </div>";

                $xs = 0;
                $term = getTerm($conn2);

                while ($row = $result->fetch_assoc()) {
                    $my_course_list = isJson_report($row['my_course_list']) ? json_decode($row['my_course_list']) : [];
                    $active = false;
                    $active_module_name = '';
                    $active_module_end = null;

                    foreach ($my_course_list as $course_item) {
                        if ($course_item->course_status == 1) {
                            foreach ($course_item->module_terms as $term_item) {
                                if ($term_item->status == 1) {
                                    $active = true;
                                    $active_module_end = !empty($term_item->end_date) ? date("Y-m-d", strtotime($term_item->end_date)) : null;
                                    if (isset($term_item->term_name)) {
                                        $active_module_name = $term_item->term_name;
                                    }
                                }
                            }
                        }
                    }

                    // PHP-side filters
                    if ($filter_status == 'active'   && !$active) continue;
                    if ($filter_status == 'inactive' &&  $active) continue;

                    if ($filter_module_end !== null) {
                        if ($active_module_end === null || $active_module_end !== $filter_module_end) continue;
                    }

                    // Compute balance for display and optional range filter
                    $balance = (int)getBalance($row['adm_no'], $term, $conn2);

                    if ($filter_bal_min !== null && $balance < $filter_bal_min) continue;
                    if ($filter_bal_max !== null && $balance > $filter_bal_max) continue;

                    $xs++;
                    $balance_display = number_format($balance);
                    $adm = $row['adm_no'];
                    $name = ucwords(strtolower($row['first_name'] . " " . $row['second_name']));
                    $status_badge = $active
                        ? "<small class='banner active_banner' id='active_banner_{$adm}'>Active</small>"
                        : "<small class='badge bg-secondary text-white' style='font-size:10px;'>Inactive</small>";
                    $module_info = $active_module_name ? $active_module_name : 'No active module';

                    $data_to_display .= "
                        <div class='staff_dets hide_students' id='hide_students{$adm}' data-balance='{$balance}'>
                            <label style='font-size:12px;'>{$xs}.</label>
                            <label class='text-left students_sms_names' style='font-size:14px;' id='imr{$adm}' for='adm{$adm}'>
                                {$status_badge}
                                {$name} <small style='color:red;'>({$adm})</small>
                                <small class='text-muted'>&nbsp;|&nbsp;{$module_info}&nbsp;|&nbsp;KES {$balance_display}</small>
                            </label>
                            <input type='checkbox' class='student-class-par " . ($active ? "activated_banner" : "") . "' name='adm{$adm}' id='adm{$adm}'>
                        </div>";
                }

                $data_to_display .= "</div><p class='hide' id='sms_filtered_total'>{$xs}</p>";

                if ($xs > 0) {
                    echo $data_to_display;
                } else {
                    echo "<div class='p-2 my-2 text-danger border border-danger rounded'>No students found matching your filters. Try adjusting or clearing some filters.</div>";
                }
            }
        }elseif (isset($_GET['all_parents'])) {
            $select = "SELECT COUNT(*) AS 'Total' FROM `student_data` WHERE `stud_class` != '-1' AND `stud_class` != '-2'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    echo $row['Total'];
                }else {
                    echo 0;
                }
            }else {
                echo 0;
            }
        }elseif (isset($_GET['get_broadcast_filter_options'])) {
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

            // Distinct intake years
            $sel = "SELECT DISTINCT `intake_year` FROM `student_data` WHERE `intake_year` IS NOT NULL AND `intake_year` != '' ORDER BY `intake_year` DESC";
            $stmt = $conn2->prepare($sel);
            $stmt->execute();
            $res = $stmt->get_result();
            $options['intake_years'] = [];
            while ($res && ($r = $res->fetch_assoc())) {
                $options['intake_years'][] = $r['intake_year'];
            }

            // Distinct intake months
            $sel = "SELECT DISTINCT `intake_month` FROM `student_data` WHERE `intake_month` IS NOT NULL AND `intake_month` != '' ORDER BY `intake_month` ASC";
            $stmt = $conn2->prepare($sel);
            $stmt->execute();
            $res = $stmt->get_result();
            $options['intake_months'] = [];
            while ($res && ($r = $res->fetch_assoc())) {
                $options['intake_months'][] = $r['intake_month'];
            }

            echo json_encode($options);
        }elseif (isset($_GET['teacher_sms_id_group'])) {
            // USER_IDS
            $user_ids = strlen($_GET['teacher_sms_id_group']) > 0 ? explode(",",$_GET['teacher_sms_id_group']) : [];

            // get the staff data
            $staff_data = [];

            // select statement
            $select = "SELECT * FROM `user_tbl` WHERE `user_id` = ? AND `school_code` = '".$_SESSION['schcode']."'";
            
            // loop through the user id
            for($index = 0; $index < count($user_ids); $index++){
                $stmt = $conn->prepare($select);
                $stmt->bind_param("s",$user_ids[$index]);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result){
                    if($row = $result->fetch_assoc()){
                        array_push($staff_data,$row);
                    }
                }
            }

            // settings
            $select = "SELECT * FROM `settings` WHERE `sett` = 'email_setup';";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $stmt->store_result();
            $rnums = $stmt->num_rows;
            $email_username = "";
            if ($rnums > 0) {
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    if ($row = $result->fetch_assoc()) {
                        $email_settings = $row['valued'];
                        if (strlen($email_settings) > 0) {
                            // retrieve the email settings
                            $email_sets = json_decode($email_settings);
                            $sender_name = $email_sets->sender_name;
                            $email_host_addr = $email_sets->email_host_addr;
                            $email_username = $email_sets->email_username;
                            $email_password = $email_sets->email_password;
                            $tester_mail = $email_sets->tester_mail;
                        }else{
                            echo "<p class='text-danger border border-danger p-2 rounded'>The Email address has not been set up properly, Delete the current setting and redo the process again!</p>";
                            return;
                        }
                    }
                }
            }else{
                echo "<p class='text-danger border border-danger p-2 rounded'>The Email address has not been set up properly, Delete the current setting and redo the process again!</p>";
            }
            
            // try sending an email
            for ($index=0; $index < count($staff_data); $index++) {
                $staff_email = $staff_data[$index]['email'];
                if (strlen(trim($staff_email)) > 0) {
                    // bcc and cc settings
                    $cc = isset($_GET['email_cc']) ? $_GET['email_cc'] : "";
                    $bcc = isset($_GET['email_bcc']) ? $_GET['email_bcc'] : "";
                    $email_counts++;
                    
                    // save the email details
                    $insert = "INSERT INTO `email_address` (`sender_from`, `recipient_to`, `bcc`, `date_time`, `message_subject`, `message`, `cc`) VALUES (?,?,?,?,?,?,?)";
                    $stmt = $conn2->prepare($insert);
                    $dates = date("YmdHis");
                    $stmt->bind_param("sssssss", $email_username, $staff_email, $bcc, $dates, $_GET['email_subject'], $_GET['messages'], $cc);
                    $stmt->execute();
                }else {
                    $email_errors++;
                }
            }

            // already excempted those that are not to get the message
            // send the message to those who are to be sent the message
            trigger_email_agent();
            echo "<p class='text-success'>Emails sent successfully <br>".$email_counts." Success! <br><span class='text-danger'>".$email_errors." Not Sent!</span></p>";
        }elseif (isset($_GET['tr_ids_excempt'])) {
            // include SMS
            include("../../sms_apis/sms.php");

            // explode the teacher`s id
            $tr_ids_excempt = $_GET['tr_ids_excempt'];
            $teacher_no = explode(",",$tr_ids_excempt);

            // get the teachers id
            $select = "SELECT * FROM `user_tbl` WHERE `user_id` = ? AND `school_code` = '".$_SESSION['schcode']."'";
            $teachers_no = [];
            for($index = 0; $index < count($teacher_no); $index++){
                $stmt = $conn->prepare($select);
                $stmt->bind_param("s",$teacher_no[$index]);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result){
                    if($row = $result->fetch_assoc()){
                        array_push($teachers_no,$row['phone_number']);
                    }
                }
            }
            
            //set the timeout to 300 seconds so that it can accomodate more requests
            set_time_limit(300);
            $message = $_GET['messages'];

            //the value below is used to determine of we can use the school api or the company`s api
            $school = 1;
            $api_key = getApiKeySms($conn2);

            //check if the school has its own api keys
            if ($api_key == 0) {
                $school = 0;
                $api_key = getApiKeySms($conn);
            }
            if ($api_key !== 0) {
                if ($school == 0) {
                    $partnerID = getPatnerIdSms($conn);
                    $shortcodes = getShortCodeSms($conn);
                    $send_sms_url = getUrlSms($conn);
                }else {
                    $partnerID = getPatnerIdSms($conn2);
                    $shortcodes = getShortCodeSms($conn2);
                    $send_sms_url = getUrlSms($conn2);
                }
                
                // counter
                $count = 0;
                $balance = 0;
                for ($index=0; $index < count($teachers_no); $index++) { 
                    //send message to the numbers
                    $output_name = sendSmsToClient($teachers_no[$index],$message,$api_key,$partnerID,$shortcodes,$send_sms_url);
                    //echo $output_name."<br>";
                    $json = json_decode($output_name);
                    //echo $json->{'response-description'}."<br>";
                    if (!isset($json->{'response-description'})) {
                        if (isset($json->{'responses'}[0]->{'response-description'})) {
                            if( $json->{'responses'}[0]->{'response-description'} !== null ||  $json->{'responses'}[0]->{'response-description'} === "Sucess"){
                                $count++;
                                //echo $json->{'responses'}[0]->{'response-description'}."<br>";
                            }
                        }
                    }elseif ($json->{'response-description'} == "Low bulk credits, Balance is 0.00") {
                        $balance++;
                    }
                    usleep(25000);
                    /***
                    if ($index == 0) {
                        //test one
                        break;
                    } */
                }
                    
                //echo $count." ".$balance." ".count($teachers_no);
                if ($balance == 0) {
                    echo "<p class='green_notice'>Messages sent successfully is ".$count." out of ".count($teachers_no)." "."!</p>";
                    //send the information to the database
                    $insert = "INSERT INTO `sms_table` (`message_count`,`date_sent`,`message_sent_succesfully`,`message_undelivered`,`message_type`,`message_description`,`sender_no`,`message`,`number_collection`) VALUES (?,?,?,?,?,?,?,?,?)";
                    $stmt = $conn2->prepare($insert);
                    $message_count = count($teachers_no);
                    $message_undelivered = $message_count - $count;
                    $message_type = "Broadcast";
                    $message_desc = $message."...";
                    if (strlen($message) > 43) {
                        $message_desc = substr($message,0,45)."...";
                    }
                    $date = date("YmdHis");
                    $number_collection = json_encode($teachers_no);
                    $stmt->bind_param("sssssssss",$message_count,$date,$count,$message_undelivered,$message_type,$message_desc,$message_count,$message,$number_collection);
                    if($stmt->execute()){
                        echo "<p class='green_notice'>Done!</p>";
                    }else {
                        echo "<p class='red_notice'>Error!</p>";
                    }
                }else {
                    $out_of = count($teachers_no) - $balance;
                    if ($out_of == 0) {
                        $out_of = $balance;
                    }
                    echo "<p class='green_notice'>Messages sent successfully is ".$count." out of ".$out_of."!<br>".$balance." not sent due to low credit</p>";
                    //send the information to the database
                    if ($count>0) {
                        $insert = "INSERT INTO `sms_table` (`message_count`,`date_sent`,`message_sent_succesfully`,`message_undelivered`,`message_type`,`message_description`,`sender_no`,`message`,`number_collection`) VALUES (?,?,?,?,?,?,?,?,?)";
                        $stmt = $conn2->prepare($insert);
                        $message_count = $out_of;
                        $message_undelivered = $message_count - $count;
                        $message_type = "Broadcast";
                        $message_desc = $message."...";
                        $number_collection = json_encode($teachers_no);
                        if (strlen($message) > 43) {
                            $message_desc = substr($message,0,45)."...";
                        }
                        $date = date("Y-m-d");
                        $stmt->bind_param("sssssssss",$message_count,$date,$count,$message_undelivered,$message_type,$message_desc,$message_count,$message,$number_collection);
                        if($stmt->execute()){
                            echo "<p class='green_notice'>Done!</p>";
                        }else {
                            echo "<p class='red_notice'>Error!</p>";
                        }
                    }
                }
            }
        }elseif (isset($_GET['parents_ids_excempt_email'])) {
            // echo "We are here";
            include("../finance/financial.php");
            // explode the students admission number to excempt them from being sent a message
            $accepted = isset($_GET['parents_ids_excempt_email']) ? explode(",",$_GET['parents_ids_excempt_email']) : [];
            // var_dump($accepted);

            // get the accepted students data
            $students_data = [];
            $select = "SELECT * FROM `student_data` WHERE `adm_no` = ?";
            $stmt = $conn2->prepare($select);
            for($index = 0; $index < count($accepted); $index++){
                $stmt->bind_param("s",$accepted[$index]);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result){
                    if($row = $result->fetch_assoc()){
                        array_push($students_data,$row);
                    }
                }
            }

            // var_dump($students_data);
            $select = "SELECT * FROM `settings` WHERE `sett` = 'email_setup';";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $stmt->store_result();
            $rnums = $stmt->num_rows;
            $email_counts = 0;
            $email_errors = 0;
            if ($rnums > 0) {
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    if ($row = $result->fetch_assoc()) {
                        $email_settings = $row['valued'];
                        if (strlen($email_settings) > 0) {
                            // retrieve the email settings
                            $email_sets = json_decode($email_settings);
                            $sender_name = $email_sets->sender_name;
                            $email_host_addr = $email_sets->email_host_addr;
                            $email_username = $email_sets->email_username;
                            $email_password = $email_sets->email_password;
                            $tester_mail = $email_sets->tester_mail;

                        }else{
                            echo "<p class='text-danger border border-danger p-2 rounded'>The Email address has not been set up properly.</p>";
                            return;
                        }
                    }
                }
            }else{
                echo "<p class='text-danger border border-danger p-2 rounded'>The Email address has not been set up properly.</p>";
                return;
            }

            // send email
            // get the students parent email address data
            for ($index=0; $index < count($students_data); $index++) {
                // email address
                $email_primary = isset($students_data[$index]['parent_email']) ? $students_data[$index]['parent_email'] : null;
                $secondary_mail = isset($students_data[$index]['parent_email2']) ? $students_data[$index]['parent_email2'] : null;
                $student_email = isset($students_data[$index]['student_email']) ? $students_data[$index]['student_email'] : null;

                // send the email
                $messages = $_GET['messages'];
                $to_whom = $_GET['to_whom'];
                $cc = $_GET['cc'];
                $bcc = $_GET['bcc'];
                $subject = $_GET['subject'];

                // set email recepient
                if ($to_whom == "primary" && $to_whom == "all_three" && $to_whom == "both" && $email_primary != null) {
                    // SET SUBJECT
                    $subject = process_sms($students_data,$_GET['subject'],$students_data[$index]['adm_no'],$conn2,"primary");
                    // SET BODY
                    $messages = process_sms($students_data,$_GET['messages'],$students_data[$index]['adm_no'],$conn2,"primary");
                    $email_counts++;
                    
                    // save the email address sent
                    $insert = "INSERT INTO `email_address` (`sender_from`,`recipient_to`,`bcc`,`date_time`,`message_subject`,`message`,`cc`) VALUES (?,?,?,?,?,?,?)";
                    $stmt = $conn2->prepare($insert);
                    $dates = date("YmdHis");
                    $stmt->bind_param("sssssss",$email_username,$email_primary,$bcc,$dates,$subject,$messages,$cc);
                    $stmt->execute();
                }

                if ($to_whom == "secondary" && $to_whom == "all_three" && $to_whom == "both" && $secondary_mail != null) {
                    // SET SUBJECT
                    $subject = process_sms($students_data,$_GET['subject'],$students_data[$index]['adm_no'],$conn2,"secondary");
                    // SET BODY
                    $messages = process_sms($students_data,$_GET['messages'],$students_data[$index]['adm_no'],$conn2,"secondary");
                    $email_counts++;
                    
                    // save the email address sent
                    $insert = "INSERT INTO `email_address` (`sender_from`,`recipient_to`,`bcc`,`date_time`,`message_subject`,`message`,`cc`) VALUES (?,?,?,?,?,?,?)";
                    $stmt = $conn2->prepare($insert);
                    $dates = date("YmdHis",strtotime("3 hours"));
                    $stmt->bind_param("sssssss",$email_username,$secondary_mail,$bcc,$dates,$subject,$messages,$cc);
                    $stmt->execute();
                }
                
                if ($to_whom == "student_contact" && $to_whom == "primary" && $student_email != null) {
                    // SET SUBJECT
                    $subject = process_sms($students_data,$_GET['subject'],$students_data[$index]['adm_no'],$conn2,"primary");
                    // SET BODY
                    $messages = process_sms($students_data,$_GET['messages'],$students_data[$index]['adm_no'],$conn2,"primary");
                    $email_counts++;
                    
                    // save the email address sent
                    $insert = "INSERT INTO `email_address` (`sender_from`,`recipient_to`,`bcc`,`date_time`,`message_subject`,`message`,`cc`) VALUES (?,?,?,?,?,?,?)";
                    $stmt = $conn2->prepare($insert);
                    $dates = date("YmdHis",strtotime("3 hours"));
                    $stmt->bind_param("sssssss",$email_username,$student_email,$bcc,$dates,$subject,$messages,$cc);
                    $stmt->execute();
                }
            }

            // trigger email
            trigger_email_agent();
            echo "<p class='text-success'>Emails sent successfully <br>".$email_counts." Success! <br><span class='text-danger'>".$email_errors." Not Sent!</span></p>";
        }elseif (isset($_GET['parents_ids_excempt'])) {
            // get all student information excluding those transfered and the alumni
            $student_data = getStudentData($conn2);
            // var_dump($student_data);
            $which_parent = $_GET['to_whom'];
            $data = $_GET['parents_ids_excempt'];
            $exploded_data = explode(",",$data);
            $count = 0;
            // send my sms
            include("../../sms_apis/sms.php");
            include("../finance/financial.php");
            for ($index = 0; $index < count($student_data); $index++) {
                $primary_parent = $student_data[$index]['parentContacts'];
                $secondary_parent = $student_data[$index]['parent_contact2'];
                $student_contact = $student_data[$index]['student_contact'];
                if (checkPresnt($exploded_data, $student_data[$index]['adm_no']) == 1) {
                    // process message
                    $message = $_GET['messages'];
                    $message_status = "pending";
                    if(($which_parent == "both" || $which_parent == "all_three" || $which_parent == "primary") && (trim($primary_parent) != "none" && trim($primary_parent) != "")){
                        $message1 = process_sms($student_data,$message,$student_data[$index]['adm_no'],$conn2,"primary");
                        
                        // create message
                        $message1 = process_sms($student_data,$message,$student_data[$index]['adm_no'],$conn2,"primary");
                        
                        // save the data in the database
                        $insert = "INSERT INTO `sms_table` (`message_count`,`date_sent`,`message_sent_succesfully`,`message_undelivered`,`message_type`,`message_description`,`sender_no`,`message`,`number_collection`, `message_status`) VALUES (?,?,?,?,?,?,?,?,?,?)";
                        $stmt = $conn2->prepare($insert);
                        $message_undelivered = 0;
                        $message_type = "Broadcast";
                        $message_desc = $message1;
                        if (strlen($message1) > 43) {
                            $message_desc = substr($message1,0,45)."...";
                        }
                        $date = date("YmdHis");
                        $message_count = 1;
                        $stmt->bind_param("ssssssssss",$message_count,$date,$message_count,$message_undelivered,$message_type,$message_desc,$primary_parent,$message1,$primary_parent,$message_status);
                        $stmt->execute();
                        $count++;
                    }

                    if(($which_parent == "both" || $which_parent == "all_three" || $which_parent == "secondary") && (trim($secondary_parent) != "none" && trim($secondary_parent) != "")){
                        // message
                        $message2 = process_sms($student_data,$message,$student_data[$index]['adm_no'],$conn2,"secondary");
                        
                        // save the data in the database
                        $insert = "INSERT INTO `sms_table` (`message_count`,`date_sent`,`message_sent_succesfully`,`message_undelivered`,`message_type`,`message_description`,`sender_no`,`message`,`number_collection`,`message_status`) VALUES (?,?,?,?,?,?,?,?,?,?)";
                        $stmt = $conn2->prepare($insert);
                        $message_undelivered = 0;
                        $message_type = "Broadcast";
                        $message_desc = $message2;
                        if (strlen($message2) > 43) {
                            $message_desc = substr($message2,0,45)."...";
                        }
                        $date = date("YmdHis");
                        $message_count = 1;
                        $stmt->bind_param("ssssssssss",$message_count,$date,$message_count,$message_undelivered,$message_type,$message_desc,$secondary_parent,$message2,$secondary_parent, $message_status);
                        $stmt->execute();
                        $count++;
                    }

                    if(($which_parent == "all_three" || $which_parent == "student_contact") && (trim($student_contact) != "none" && trim($student_contact) != "")){
                        // message3
                        $message3 = process_sms($student_data,$message,$student_data[$index]['adm_no'],$conn2,$which_parent);
                        // save the data in the database
                        $insert = "INSERT INTO `sms_table` (`message_count`,`date_sent`,`message_sent_succesfully`,`message_undelivered`,`message_type`,`message_description`,`sender_no`,`message`,`number_collection`,`message_status`) VALUES (?,?,?,?,?,?,?,?,?,?)";
                        $stmt = $conn2->prepare($insert);
                        $message_undelivered = 0;
                        $message_type = "Broadcast";
                        $message_desc = $message3;
                        if (strlen($message3) > 43) {
                            $message_desc = substr($message3,0,45)."...";
                        }
                        $date = date("YmdHis");
                        $message_count = 1;
                        $stmt->bind_param("ssssssssss",$message_count,$date,$message_count,$message_undelivered,$message_type,$message_desc,$student_contact,$message3,$student_contact, $message_status);
                        $stmt->execute();
                        $count++;
                    }
                }
            }

            // trigger sms
            trigger_sms_agent();
            echo "<p class='text-success'>You have successfully sent ".$count." message(s)!</p>";
        }elseif (isset($_GET['get_my_trs'])) {
            $select = "SELECT `user_id`, `fullname` FROM `user_tbl` WHERE `school_code` = ? AND  NOT `user_id` = ?";
            $stmt = $conn->prepare($select);
            $stmt->bind_param("ss",$_SESSION['schoolcode'],$_SESSION['userids']);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $data_to_display = "<select id='select_staff_infors'>
                                        <option hidden value ='' >Select staff</option>";
                                        $xs = 0;
                while ($row = $result->fetch_assoc()) {
                    $xs++;
                    $data_to_display.="<option value ='".$row['user_id']."' >".$row['fullname']."</option>";
                }
                $data_to_display.="</select>";
                if ($xs > 0) {
                    echo $data_to_display;
                }else {
                    echo "<p class='red_notice'>No staff present</p>";
                }
            }
        }elseif (isset($_GET['send_message_notice'])) {
            $reciever_id = $_GET['recpt_id'];
            $message = $_GET['message'];
            $messageName = "New Message";
            $reciever_auth = 'all';
            $sender_ids = $_SESSION['userids'];
            $notice_stat = 0;
            insertNotifcation_sms($conn2,$messageName,$message,$notice_stat,$reciever_id,$reciever_auth,$sender_ids);
            echo "<p class='green_notice'>Message sent successfully!</p>";
        }elseif (isset($_GET['recent_messages'])) {
            $select = "SELECT `message_count`,`message_sent_succesfully`,`message_undelivered`,`message_type`,`date_sent`,`sender_no`,`message_description`,`message`,`charged` FROM `sms_table` ORDER BY `send_id` DESC LIMIT 7";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result){
                $data_to_display = "";
                $xs = 0;
                while ($row = $result->fetch_assoc()) {
                    $xs++;
                    $charged = "<div class='charged'>
                                        <img src='images/coined.png' alt='charged'>
                                    </div>";
                    if ($row['charged'] == 0) {
                        $charged = "<div class='chargedd'>
                                        <!-- <img src='images/coined.png' alt='charged'> -->
                                    </div>";
                    }
                    $data_to_display.="<div class='one_message'>
                                        <div class='status'>
                                            <div class='message_type'>
                                                <p>".$row['message_type']."</p>
                                            </div>
                                            <div class='message_status'>
                                                <p>Delivered <small>(".$row['message_sent_succesfully']."/".$row['message_count'].")</small></p>
                                            </div>
                                        </div>
                                        <div class='message_detail'>
                                            <p class ='norm_unseen'>".$row['message_description']."</p>
                                            <p class = 'norm_seen'>".$row['message']."</p>
                                        </div>
                                        <div class='date_sent'>
                                            <div class='conts'>
                                                <p>".date("M-d-Y",strtotime($row['date_sent']))."</p>
                                            </div>
                                            ".$charged."
                                        </div>
                                    </div>";
                }
                if ($xs > 0) {
                    echo $data_to_display;
                }else {
                    echo "<p style='color:gray;font-weight:600;'>No recent messages!</p>";
                }
            }
        }elseif (isset($_GET['sms_history'])) {
            $from_date = isset($_GET['from']) ? $_GET['from'] : "";
            // $to_date = strlen(isset($_GET['to'])) > 0 ? $_GET['to'] : "";
            $to_date = isset($_GET['to']) ? $_GET['to'] : null;
            // echo $to_date." pine";
            $select = "SELECT * FROM `sms_table` ORDER BY `send_id` DESC";
            if (isset($from_date) && isset($to_date)) {
                $select = "SELECT * FROM `sms_table` WHERE date_sent BETWEEN '$from_date' AND '$to_date'  ORDER BY `send_id` DESC";
            }
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $data_to_display = "<div class = 'recent_messages'>";
                $xs = 0;
                $totalMessages = 0;
                $message_undelivered = 0;
                $message_delivered = 0;
                $to_charged_sh = 0;
                $deduct_charge = 0;
                $sms_data = [];
                while ($row = $result->fetch_assoc()) {
                    $xs++;
                    $charged = "<div class='charged'>
                                        <img src='images/coined.png' alt='charged'>
                                    </div>";
                    if ($row['charged'] == 0) {
                        $charged = "<div class='chargedd'>
                                        <!-- <img src='images/coined.png' alt='charged'> -->
                                    </div>";
                    }
                    if ($row['charged'] == 1){
                        $deduct_charge+=1;
                    }
                    $date = date("dS M Y @ H:i:sA",strtotime($row['date_sent']));
                    $row = array_merge($row,array("date_sent2"=> $date));
                    $sms_recipients = $row['number_collection'];
                    $row = array_merge($row,array("recipients" => $sms_recipients));
                    array_push($sms_data,$row);
                    $totalMessages+= ($row['message_count']*1);
                    $message_delivered+=($row['message_sent_succesfully']*1);
                    $to_charged_sh+=($row['message_sent_succesfully']*1);//this is the amount to be charged
                    $message_undelivered+=($row['message_undelivered']*1);
                    $data_to_display.="<div class='one_message'>
                                        <div class='status'>
                                            <div class='message_type'>
                                                <p>".$row['message_type']."</p>
                                            </div>
                                            <div class='message_status'>
                                                <p>Delivered <small>(".$row['message_sent_succesfully']."/".$row['message_count'].")</small></p>
                                            </div>
                                        </div>
                                        <div class='message_detail'>
                                            <p class ='norm_unseen'>".$row['message_description']."</p>
                                            <p class = 'norm_seen'>".$row['message']."</p>
                                        </div>
                                        <div class='date_sent'>
                                            <div class='conts'>
                                                <p>".date("M-d-Y",strtotime($row['date_sent']))."</p>
                                            </div>
                                            ".$charged."
                                        </div>
                                    </div>";
                }
                $sms_data = json_encode($sms_data);
                // echo $sms_data;
                if ($xs > 0) {
                    $data_to_display.="</div>";
                    $result_title = "<h6>Results</h6>";
                    if (isset($from_date) && isset($to_date)) {
                        $result_title = "<h6>Results from ".date("M-dS-Y",strtotime($from_date))." to ".date("M-dS-Y",strtotime($to_date))."</h6>";
                    }
                    $counters = "<div class='short_detail'>
                                            ".$result_title."
                                            <p >Total Messages sent:  ".$totalMessages." SMS(s) <br> Delivered Messages:  ".$message_delivered." SMS(s) <br><span class='hide'> Not delivered:  ".$message_undelivered." SMS(s)</span> <br></p>
                                            <p class='hide'>Charged units: ".($deduct_charge).".<br>Units to charge :  ".($to_charged_sh - $deduct_charge)." (Ksh ".($to_charged_sh - $deduct_charge).")<br></p>
                                        </div>";
                    echo $counters."<p class='hide' id='sms_data_results'>".$sms_data."</p>";
                }else {
                    echo "<p style='color:gray;font-weight:600;'>No messages found!</p>";
                }
            }
        }elseif (isset($_GET['email_history'])) {
            $select = "SELECT * FROM `email_address` ORDER BY `id` DESC";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $email_data = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    array_push($email_data,$row);
                }
            }

            // send email 
            $email_new_date = json_encode($email_data);
            echo "<p class='hide' id='email_data_results'>".rawurlencode($email_new_date)."</p>";
        }elseif (isset($_GET['email_data'])) {
            // echo $_GET['email_data'];
            $select = "SELECT * FROM `email_address` WHERE `id` = '".$_GET['email_data']."'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    // echo json_encode($row);
                    $attachment_name = "No Name";
                    if (isset($row['attachments']) && strlen(trim($row['attachments'])) > 0) {
                        $attachment_name = $row['attachments'];
                        $attachment_name = explode("/",$attachment_name);
                        $new_attachment_name = $attachment_name[count($attachment_name)-1];
                        $attachment_name = $new_attachment_name;
                    }
                    $data_to_display = "
                                <div class='message_contents'>
                                    <p class='text-left' ><b>Sender :</b> ".(isset($row['sender_from']) ? $row['sender_from']:"None")."</p>
                                    <p><b>Recepient :</b> ".((isset($row['recipient_to']) && strlen(trim($row['recipient_to'])) > 0) ? $row['recipient_to']:"None")."</p>
                                    <p><b>BCC :</b> ".(isset($row['bcc']) && strlen(trim($row['bcc'])) > 0 ? $row['bcc']:"None")."</p>
                                    <p><b>CC :</b> ".(isset($row['cc']) && strlen(trim($row['cc'])) > 0 ? $row['cc']:"None")."</p>
                                    <p><b>Date Time :</b> ".date("D dS M Y H:i:s",strtotime($row['date_time']))."</p>
                                </div>
                                <div class='add_expense' id='exp_names'>
                                    <div class='conts'>
                                        <label class='form-control-label' for='exp_name'>Message Subject:".(isset($row['message_subject']) && strlen(trim($row['message_subject'])) > 0 ? "<h5>".$row['message_subject']."</h5>":"<p class='text-danger'>No message subject")."</p></label>
                                        <div class='container p-2'></div>
                                    </div>  
                                    <hr class='my-0'>
                                    <div class='conts'>
                                        <label class='form-control-label' for='exp_name'>Message Contents: <br></label>
                                        <div class='container p-2'>".(isset($row['message']) && strlen(trim($row['message'])) > 0 ? $row['message']:"<p class='text-danger'>No message to display")."</p></div>
                                    </div>
                                    <div class='conts ".(isset($row['attachments']) && strlen(trim($row['attachments'])) > 0 ? "":"hide")."'>
                                        <label class='form-control-label' for='exp_name'>Message Attachment: <br></label>
                                        <a href='sims/".$row['attachments']."' target='_blank'>".$attachment_name."</a>
                                    </div>
                                    <hr class='my-0'>
                                    <span class='hide' id='emai_id_delete'>".$_GET['email_data']."</span>
                                    <label class='text-danger form-control-label' for=''>Delete Messages:</label><br><span class='hide' id='delete_email_loader'><img src='images/ajax_clock_small.gif'></span>
                                    <span id='email_text_holder'></span>
                                    <br><span class='link' id='delete_email'><i class='fas fa-trash-alt'></i> Delete</span>
                                </div>";
                                echo $data_to_display;
                }else{
                    $data_to_display = "
                                    <div class='message_contents'>
                                        <p class='text-left' ><b>Sender :</b> No Data</p>
                                        <p><b>Recepient :</b> No Data</p>
                                        <p><b>BCC :</b> No Data</p>
                                        <p><b>CC :</b> No Data</p>
                                    </div>
                                    <div class='add_expense' id='exp_names'>
                                        <span class='link' id='delete_email'><i class='fas fa-trash-alt'></i></span>
                                        <div class='conts'>
                                            <label class='form-control-label' for='exp_name'>Message Contents: <br></label>
                                            <div class='container p-2'><p class='text-danger'>No message to display</p></div>
                                        </div>
                                    </div>";
                                    echo $data_to_display;
                }
            }else{
                $data_to_display = "
                                <div class='message_contents'>
                                    <p class='text-left' ><b>Sender :</b> No Data</p>
                                    <p><b>Recepient :</b> No Data</p>
                                    <p><b>BCC :</b> No Data</p>
                                    <p><b>CC :</b> No Data</p>
                                </div>
                                <div class='add_expense' id='exp_names'>
                                    <span class='link' id='delete_email'><i class='fas fa-trash-alt'></i></span>
                                    <div class='conts'>
                                        <label class='form-control-label' for='exp_name'>Message Contents: <br></label>
                                        <div class='container p-2'><p class='text-danger'>No message to display</p></div>
                                    </div>
                                </div>";
                                echo $data_to_display;
            }
            
        }elseif (isset($_GET['delete_mail'])) {
            // delete the attachments if present
            $select = "SELECT * FROM `email_address` WHERE `id` = '".$_GET['delete_mail']."'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    // delete file
                    if (strlen(trim($row['attachments']))) {
                        $attachment = $row['attachments'];
                        unlink("../".$attachment);
                        // echo "../".$attachment;
                    }
                }
            }
            $delete = "DELETE FROM `email_address` WHERE `id` = '".$_GET['delete_mail']."'";
            $stmt = $conn2->prepare($delete);
            $stmt->execute();
            echo "Email Successfully deleted!";
        }
        $conn->close();
        $conn2->close();
    }
    function getTrNo($conn){
        $select = "SELECT `phone_number`,`nat_id` FROM `user_tbl` WHERE `school_code` = ?";
        $stmt = $conn->prepare($select);
        $stmt->bind_param("s",$_SESSION['schoolcode']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $data = "";
            while ($row = $result->fetch_assoc()) {
                $data.=$row['phone_number'].",";
            }
            $data = substr($data,0,(strlen($data) - 1));
            return $data;
        }
        return 0;
    }
    function checkPresnt($array, $string){
        if (count($array)>0) {
            for ($i=0; $i < count($array); $i++) { 
                if ($string == $array[$i]) {
                    return 1;
                    break;
                }
            }
        }
        return 0;
    }
    function getApiKeySms($conn){
        $select = "SELECT `sms_api_key` FROM `sms_api`";
        $stmt = $conn->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['sms_api_key'];
            }
        }
        return 0;
    }
    function getPatnerIdSms($conn){
        $select = "SELECT `patner_id` FROM `sms_api`";
        $stmt = $conn->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['patner_id'];
            }
        }
        return 0;
    }
    function arr_to_string($array){
        if(count($array) == 0){
            return "No Recipients";
        }
        $data_to_display = "";
        for ($i=0; $i < count($array); $i++) {
            if ((count($array) - 1) == $i) {
                $data_to_display.=$array[$i];
            }else{
                $data_to_display.=$array[$i].", ";
            }
        }
        // echo $data_to_display;
        return $data_to_display;
    }
    function getShortCodeSms($conn){
        $select = "SELECT `short_code` FROM `sms_api`";
        $stmt = $conn->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['short_code'];
            }
        }
        return 0;
    }
    function getUrlSms($conn){
        $select = "SELECT `send_sms_url` FROM `sms_api`";
        $stmt = $conn->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['send_sms_url'];
            }
        }
        return 0;
    }
    function majinaDarasa($data){
        if ($data == "-3") {
            return "Student Enquiries";
        }
        if ($data == "-1") {
            return "Alumnis";
        }
        if (strlen($data)>1) {
            return $data;
        }else {
            return "Grade ".$data;
        }
        return $data;
    }
    function getParentsNo($conn2,$arrays_id){
        $select = "SELECT `adm_no`,`parentContacts` FROM `student_data` WHERE `adm_no` != 'alumni'";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = "";
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $presnt = checkPresnt($arrays_id,$row['adm_no']);
                if ($presnt == 0) {
                    $data.=$row['parentContacts'].",";
                }
            }
            $data = substr($data,0,(strlen($data)-1));
        }
        return $data;
    }
    function insertNotifcation_sms($conn2,$messageName,$messagecontent,$notice_stat,$reciever_id,$reciever_auth,$sender_ids){
        $insert = "INSERT INTO `tblnotification`  (`notification_name`,`Notification_content`,`sender_id`,`notification_status`,`notification_reciever_id`,`notification_reciever_auth`) VALUES (?,?,?,?,?,?)";
        $stmt = $conn2->prepare($insert);
        $stmt->bind_param("ssssss",$messageName,$messagecontent,$sender_ids,$notice_stat,$reciever_id,$reciever_auth);
        if($stmt->execute()){
            
        }
    }
    function getStudentData($conn2){
        $select = "SELECT * FROM `student_data` WHERE `stud_class` != '-1' AND `stud_class` != '-2';";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        $student_data = [];
        if ($result) {
            while($row = $result->fetch_assoc()){
                array_push($student_data,$row);
            }
        }
        return $student_data;
    }
    function process_sms($student_data,$message,$adm_no = "0",$conn2,$which_parent){
        $final_message = $message;
        if ($adm_no == "0") {
            return $final_message;
        }
        $term = getTerm($conn2);
        for ($index=0; $index < count($student_data); $index++) { 
            if ($student_data[$index]['adm_no'] == $adm_no) {
                $final_message = str_replace("{stud_fullname}",ucwords(strtolower($student_data[$index]['first_name']." ".$student_data[$index]['second_name'])),$final_message);
                $final_message = str_replace("{stud_first_name}",ucwords(strtolower($student_data[$index]['first_name'])),$final_message);
                $final_message = str_replace("{stud_class}",majinaDarasa($student_data[$index]['stud_class']),$final_message);
                $dob = date_create($student_data[$index]['D_O_B']);
                $today = date_create(date("Y-m-d"));
                $date_diff = date_diff($dob,$today);
                $date_diff = $date_diff->format("%y Yr(s)");
                $raw_balance = (int) getBalance($student_data[$index]['adm_no'],$term,$conn2);
                $balance = number_format($raw_balance);
                $fees_paid = number_format(getFeespaidByStudent($student_data[$index]['adm_no'],$conn2));
                $fees_to_pay = number_format(getFeesAsPerTermBoarders($term,$conn2,$student_data[$index]['stud_class'],$student_data[$index]['adm_no']));
                $next_module_cost = getNextModuleFees($student_data[$index],$conn2);
                $next_module_fees = number_format($raw_balance + $next_module_cost);
                $final_message = str_replace("{stud_age}",$date_diff,$final_message);
                $final_message = str_replace("{stud_fees_balance}",$balance,$final_message);
                $final_message = str_replace("{stud_fees_to_pay}",$fees_to_pay,$final_message);
                $final_message = str_replace("{stud_adm}",$student_data[$index]['adm_no'],$final_message);
                $final_message = str_replace("{stud_fees_paid}",$fees_paid,$final_message);
                $final_message = str_replace("{next_module_fees}",$next_module_fees,$final_message);
                $final_message = str_replace("{stud_noun}",($student_data[$index]['gender'] == "Female" ?"daughter":"son"),$final_message);
                if ($which_parent == "primary") {
                    $final_message = str_replace("{par_fullname}",ucwords(strtolower($student_data[$index]['parentName'])),$final_message);
                    $final_message = str_replace("{par_first_name}",ucwords(strtolower(explode(" ",$student_data[$index]['parentName'])[0])),$final_message);
                    $final_message = str_replace("{title_1}",(((strtolower($student_data[$index]['parent_relation']) == "guardian") ? "":"check") == "check") ? (strtolower($student_data[$index]['parent_relation']) == "father" ? "Mr" : "Mrs"):"",$final_message);
                    $final_message = str_replace("{title_2}",(((strtolower($student_data[$index]['parent_relation']) == "guardian") ? "":"check") == "check") ? (strtolower($student_data[$index]['parent_relation']) == "father" ? "Sir" : "Madam"):"",$final_message);   
                }else{
                    $final_message = str_replace("{par_fullname}",ucwords(strtolower($student_data[$index]['parent_name2'])),$final_message);
                    $final_message = str_replace("{par_first_name}",ucwords(strtolower(explode(" ",$student_data[$index]['parent_name2'])[0])),$final_message);
                    $final_message = str_replace("{title_1}",(((strtolower($student_data[$index]['parent_relation2']) == "guardian") ? "":"check") == "check") ? (strtolower($student_data[$index]['parent_relation2']) == "father" ? "Mr" : "Mrs"):"",$final_message);
                    $final_message = str_replace("{title_2}",(((strtolower($student_data[$index]['parent_relation2']) == "guardian") ? "":"check") == "check") ? (strtolower($student_data[$index]['parent_relation2']) == "father" ? "Sir" : "Madam"):"",$final_message);
                }
                $today = date("D dS M, Y");
                $final_message = str_replace("{today}",$today,$final_message);
            }
        }
        return $final_message;
    }
    function getNextModuleFees($student_row, $conn2) {
        $my_course_list = isJson_report($student_row['my_course_list']) ? json_decode($student_row['my_course_list']) : [];
        $next_module_cost = 0;

        foreach ($my_course_list as $course) {
            if ($course->course_status == 1) {
                $modules = $course->module_terms;
                for ($m = 0; $m < count($modules); $m++) {
                    if ($modules[$m]->status == 1) {
                        $next = $m + 1;
                        if ($next >= count($modules)) {
                            // on last module — no next module cost
                            break 2;
                        }
                        $next_mod = $modules[$next];
                        $study_mode = strtolower($student_row['study_mode'] ?? '');

                        // Base cost from module definition
                        $omit_base = false;
                        $other_vh = [];
                        $issetup = false;
                        if (isset($next_mod->voteheads)) {
                            $issetup = true;
                            foreach ($next_mod->voteheads as $vh) {
                                if ($vh->votehead == "0" && !$vh->pay) {
                                    $omit_base = true;
                                }
                                if ($vh->votehead != "0" && $vh->pay) {
                                    array_push($other_vh, $vh->votehead);
                                }
                            }
                        }
                        if (!$omit_base) {
                            $next_module_cost = $study_mode == "weekend"
                                ? ($next_mod->weekend_cost ?? 0)
                                : ($study_mode == "evening"
                                    ? ($next_mod->evening_cost ?? 0)
                                    : ($study_mode == "fulltime"
                                        ? ($next_mod->fulltime_cost ?? 0)
                                        : ($next_mod->termly_cost ?? 0)));
                        }

                        // Fees structure component
                        $fs_col = $study_mode == "weekend" ? "sum(`TERM_3`)" : ($study_mode == "evening" ? "sum(`TERM_2`)" : "sum(`TERM_1`)");
                        if ($issetup && count($other_vh) > 0) {
                            $select = "SELECT $fs_col AS 'TOTALS' FROM `fees_structure` WHERE ids IN (" . join(',', $other_vh) . ")";
                        } elseif (!$issetup) {
                            $class = $student_row['stud_class'];
                            $course_done = $student_row['course_done'];
                            $select = "SELECT $fs_col AS 'TOTALS' FROM `fees_structure` WHERE `classes` = '" . mysqli_real_escape_string($conn2, $class) . "' AND `course` = '" . mysqli_real_escape_string($conn2, $course_done) . "' AND `activated` = 1 AND `roles` = 'regular'";
                        } else {
                            $select = null;
                        }
                        if ($select) {
                            $stmt = $conn2->prepare($select);
                            $stmt->execute();
                            $res = $stmt->get_result();
                            if ($res && ($row = $res->fetch_assoc())) {
                                $next_module_cost += (int)($row['TOTALS'] ?? 0);
                            }
                            $stmt->close();
                        }

                        // Discounts
                        $discounts = getDiscount($student_row['adm_no'], $conn2);
                        if ($discounts[0] > 0) {
                            $next_module_cost = round(($next_module_cost * (100 - $discounts[0])) / 100);
                        } elseif ($discounts[1] > 0) {
                            $next_module_cost = $next_module_cost - $discounts[1];
                        }

                        break 2;
                    }
                }
            }
        }
        return $next_module_cost;
    }
    function getTermStart_sms($conn2,$term){
        $select = "SELECT * FROM `academic_calendar` WHERE `term` = '".$term."';";
        $stmt =$conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return [$row['start_time'],$row['end_time']];
            }
        }
        return [date('Y')."-01-01",date("Y")."-01-01"];
    }
    function isJson_report($string)
    {
        return ((is_string($string) &&
            (is_object(json_decode($string)) ||
                is_array(json_decode($string))))) ? true : false;
    }
    function trigger_email_agent(){
        $ch = curl_init("https://nuelas.ladybirdsmis.com/ajax//sms/email_agent.php?database=".$_SESSION['dbname']."&school_code=".$_SESSION['schoolcode']);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_TIMEOUT_MS     => 200,   // return immediately
            CURLOPT_NOSIGNAL       => 1,
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_FORBID_REUSE   => true,
        ]);
        curl_exec($ch);
        curl_close($ch);
    }
    function trigger_sms_agent(){
        $ch = curl_init("https://nuelas.ladybirdsmis.com/ajax//sms/sms_agent.php?database=".$_SESSION['dbname']."&school_code=".$_SESSION['schoolcode']);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_TIMEOUT_MS     => 200,   // return immediately
            CURLOPT_NOSIGNAL       => 1,
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_FORBID_REUSE   => true,
        ]);
        curl_exec($ch);
        curl_close($ch);
    }
    
    function log_SMS($text){
        $full_text = date("dS M Y H:i:sA")." : ".$text." - {".$_SESSION['username']."}\n";
        $file_location = "../../ajax/logs/".$_SESSION['dbname']."/logs.txt";
        if (file_exists($file_location)) {
            $content = file_get_contents($file_location);

            // Open the file for writing
            $file = fopen($file_location, 'w');
            
            if ($file) {
                fwrite($file, $full_text.$content);
                fclose($file);
            }else {
                return "File not found!";
            }
        } else {
            $directory = dirname($file_location);
            if (!file_exists($directory)) {
                $pwu_data = posix_getpwuid(posix_geteuid());
                $username = $pwu_data['name'];
                mkdir($directory, 0777, true);

                // Change ownership of the directory to daemon
                chown($directory, $username);
            }
    
            // Open the file for writing
            $file = fopen($file_location, 'w');
            
            if ($file){
                fwrite($file, $full_text);
                fclose($file);
            }else {
                return "File not found!";
            }
        }
    }
?>