<?php
// session_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
/**
 * These are the steps to follow when a transaction is recieved
 * 1. Check if the paybill number is registered
 * if the paybill is registered to a school pull the school database and check if the school has the student
 * admission number used  as the account number
 * 
 * IF THE PAYBILL IS REGISTERED
 * check if the student admission number used as the account number is present
 * 
 * IF THE STUDENT IS PRESENT:
 * record the transaction and the state will be assigned
 * send the parent an sms showing the student new balance and the amount they have payed
 * 
 * IF THE STUDENT IS NOT PRESENT 
 * record the transaction and the state will be unassigned
 * send the parent an sms showing that they have sent the payment the wrong account number
 * 
 * IF THE PAYBILL IS NOT REGISTERED
 * the system wont record the transaction
 */
    header("content-Type: application/json");

    //get the first database connection
    include("../../connections/conn1.php");
    
    // recieve the payment from safaricom
    $response = '{
        "ResultCode":0,
        "ResultDesc": "Confirmation Recieved Successfully"
        }';
    //  echo $response;

        //data
    $mpesaResponse = file_get_contents('php://input');
    // $mpesaResponse = "{
    //     \"TransactionType\": \"Pay Bill\",
    //     \"TransID\": \"PLR0QR0V56\",
    //     \"TransTime\": \"20220118121323\",
    //     \"TransAmount\": \"600.00\",
    //     \"BusinessShortCode\": \"4061913\",
    //     \"BillRefNumber\": \"54\",
    //     \"InvoiceNumber\": \"\",
    //     \"OrgAccountBalance\": \"5.00\",
    //     \"ThirdPartyTransID\": \"\",
    //     \"MSISDN\": \"254743551250\",
    //     \"FirstName\": \"OWEN\",
    //     \"MiddleName\": \"MALINGU\",
    //     \"LastName\": \"ADALA\" }";
    //     // echo $mpesaResponse;
    //  $logFile = "M_PESAConfimationResponse.txt";
        $jsonMpesaResponse = json_decode($mpesaResponse, true);
        $logFile = "M_PESAConfimationResponse.txt";

        $log = fopen($logFile, "a");
        fwrite($log, "[" . date("Y-m-d H:i:s") . "] " . $mpesaResponse . PHP_EOL);
        fclose($log);
        //check if the statement has the transaction id
        

        if (isset($jsonMpesaResponse['TransID'])) {
        //  check if the paybill used is present in the database
            $select = "SELECT * FROM `school_information` WHERE `paybill` = ?";
            $stmt = $conn->prepare($select);
            $stmt->bind_param("s",$jsonMpesaResponse['BusinessShortCode']);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $dbnamed = $row['database_name'];
                    $schoolName = $row['school_name'];
                    $school_contact = $row['school_contact'];
                    $school_mail = $row['school_mail'];
                }
            }

            if (isset($dbnamed)) {
                //  schoolname
                $_SESSION['schname'] = $schoolName;
                $_SESSION['school_contact'] = $school_contact;
                $_SESSION['school_mail'] = $school_mail;
                $_SESSION['databasename'] = $dbnamed;


                //  proceed and record the student payment information
                // set the database name
                include("../../connections/mpesaConn.php");
                include("../../sms_apis/sms.php");
                include("financial.php");
                if ($conn2) {
                    // get the students information
                    $studentName = "Null";
                    $select = "SELECT * FROM `student_data` WHERE `adm_no` = ?";
                    $stmt = $conn2->prepare($select);
                    $stmt->bind_param("s",$jsonMpesaResponse['BillRefNumber']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result) {
                        if ($row = $result->fetch_assoc()) {
                            $studentName = $row['first_name']." ".$row['second_name']." ".$row['surname'];
                            // get the students balance
                            $term = getTermV2($conn2);
                            $studentBalance = getBalance($jsonMpesaResponse['BillRefNumber'],$term,$conn2);
                            $newBalance = $studentBalance-$jsonMpesaResponse['TransAmount'];
                            
                            // insert the payments for the student
                            $insert = "INSERT INTO `finance` (`stud_admin`,`time_of_transaction`,`date_of_transaction`,`transaction_code`,`amount`,`balance`,`payment_for`,`payBy`,`mode_of_pay`) VALUES (?,?,?,?,?,?,?,?,?)";
                            $stmt = $conn2->prepare($insert);
                            $stud_admin = $jsonMpesaResponse['BillRefNumber'];
                            $transactionCode = $jsonMpesaResponse['TransID'];
                            $TransAmount = $jsonMpesaResponse['TransAmount']*1;
                            $time = date("H:i:s", strtotime("3 hour"));
                            $date = date("Y-m-d");
                            $paymentFor = json_encode(getPaymentFor($conn2, $row, ($jsonMpesaResponse['TransAmount']*1)));
                            $paidBy = "mpesa";
                            $stmt->bind_param("sssssssss",$stud_admin,$time,$date,$transactionCode,$TransAmount,$newBalance,$paymentFor,$paidBy,$paidBy);
                            $stmt->execute();
                            $transaction_id = $stmt->insert_id;

                            // RECORD MPESA TRANSACTIONS
                            $fullnames = $jsonMpesaResponse['FirstName'];
                            $trans_status = "1";
                            recordMpesaTrans($conn2,$transactionCode,$TransAmount,$stud_admin,$transaction_id, $jsonMpesaResponse['TransTime'],$jsonMpesaResponse['BusinessShortCode'],$jsonMpesaResponse['MSISDN'],$fullnames,$trans_status);
                            $send_sms = "student_contact";
                            if (isset($send_sms) && $send_sms != "none") {
                                $phone_number = getPhoneNumber($conn2,$row['adm_no']);
                                $message_parent_1 = null;
                                $message_parent_2 = null;
                                $message_student = null;
                                $phone_parent_1 = null;
                                $phone_parent_2 = null;
                                $phone_student = null;
                                if ($phone_number != 0) {
                                    if ($send_sms == "first_parent") {
                                        $message_category = "parent_account_confirmation_message";
                                        $message_parent_1 = get_message_template($message_category, $conn2, $row['adm_no'], $TransAmount, $newBalance, $send_sms, $transaction_id);
                                        $phone_parent_1 = validateKenyanNumber(explode(",",$phone_number)[1]);
                                    }else if ($send_sms == "second_parent") {
                                        $message_category = "parent_account_confirmation_message";
                                        $message_parent_1 = get_message_template($message_category, $conn2, $row['adm_no'], $TransAmount, $newBalance, $send_sms, $transaction_id);
                                        $phone_parent_2 = validateKenyanNumber(explode(",",$phone_number)[2]);
                                    }elseif ($send_sms == "student_contact") {
                                        $message_category = "confirmation_message";
                                        $message_student = get_message_template($message_category, $conn2, $row['adm_no'], $TransAmount, $newBalance, $send_sms, $transaction_id);
                                        $phone_student = validateKenyanNumber(explode(",",$phone_number)[0]);
                                    }elseif ($send_sms == "both_parent") {
                                        $message_category = "parent_account_confirmation_message";
                                        $message_parent_1 = get_message_template($message_category, $conn2, $row['adm_no'], $TransAmount, $newBalance, "first_parent", $transaction_id);
                                        $message_parent_2 = get_message_template($message_category, $conn2, $row['adm_no'], $TransAmount, $newBalance, "second_parent", $transaction_id);
                                        $phone_number = explode(",",$phone_number)[1].",".explode(",",$phone_number)[2];
                                        $phone_parent_1 = validateKenyanNumber(explode(",",$phone_number)[1]);
                                        $phone_parent_2 = validateKenyanNumber(explode(",",$phone_number)[2]);
                                    }elseif ($send_sms == "all_three") {
                                        $message_category = "parent_account_confirmation_message";
                                        $message_parent_1 = get_message_template($message_category, $conn2, $row['adm_no'], $TransAmount, $newBalance, "first_parent", $transaction_id);
                                        $message_parent_2 = get_message_template($message_category, $conn2, $row['adm_no'], $TransAmount, $newBalance, "second_parent", $transaction_id);
                                        $message_category = "confirmation_message";
                                        $message_student = get_message_template($message_category, $conn2, $row['adm_no'], $TransAmount, $newBalance, "student_contact", $transaction_id);
                                        $phone_number = $phone_number;
                                        $phone_parent_1 = validateKenyanNumber(explode(",",$phone_number)[1]);
                                        $phone_parent_2 = validateKenyanNumber(explode(",",$phone_number)[2]);
                                        $phone_student = validateKenyanNumber(explode(",",$phone_number)[0]);
                                    }else {
                                        $phone_number = "";
                                    }
                                    
                                    // echo $message;
                                    $api_key = getApiKey($conn2);

                                    //check if the school has its own api keys
                                    $school = 1;
                                    if ($api_key == 0) {
                                        $school = 0;
                                        $api_key = getApiKey($conn);
                                    }

                                    //echo $api_key;
                                    if ($api_key !== 0) {
                                        if ($school == 0) {
                                            $partnerID = getPatnerId($conn);
                                            $shortcodes = getShortCode($conn);
                                            $send_sms_url = getUrl($conn);
                                        }else {
                                            $partnerID = getPatnerId($conn2);
                                            $shortcodes = getShortCode($conn2);
                                            $send_sms_url = getUrl($conn2);
                                        }
                                        
                                        if($phone_parent_1 != null && ($send_sms == "first_parent" || $send_sms == "both_parent" || $send_sms == "all_three")){
                                            //send sms
                                            $response = sendSmsToClient($phone_parent_1,$message_parent_1,$api_key,$partnerID,$shortcodes,$send_sms_url);
                                            
                                            // insert the message
                                            $message_type = "Multicast";
                                            $message_count = 1;
                                            $recipient_no = $phone_parent_1;
                                            $text_message = $message_parent_1;
                                            $message_desc = strlen($message_parent_1) > 45 ? substr($message_parent_1,0,45)."..." : $message_parent_1;
                                            $date = date("Y-m-d");
                                            $select = "INSERT INTO `sms_table` (`message_count`,`date_sent`,`message_sent_succesfully`,`message_undelivered`,`message_type`,`sender_no`,`message_description`,`message`) VALUES ('$message_count','$date','$message_count','$message_count','$message_type','$recipient_no','$message_desc','$text_message')";
                                            $stmt = $conn2->prepare($select);
                                            $stmt->execute();
                                        }
                                        
                                        if($phone_parent_2 != null && ($send_sms == "second_parent" || $send_sms == "both_parent" || $send_sms == "all_three")){
                                            //send sms
                                            $response = sendSmsToClient($phone_parent_2,$message_parent_2,$api_key,$partnerID,$shortcodes,$send_sms_url);
                                            
                                            // insert the message
                                            $message_type = "Multicast";
                                            $message_count = count(explode(",",$phone_parent_2));
                                            $recipient_no = $phone_parent_2;
                                            $text_message = $message_parent_2;
                                            $message_desc = strlen($message_parent_2) > 45 ? substr($message_parent_2,0,45)."..." : $message_parent_2;
                                            $date = date("Y-m-d");
                                            $select = "INSERT INTO `sms_table` (`message_count`,`date_sent`,`message_sent_succesfully`,`message_undelivered`,`message_type`,`sender_no`,`message_description`,`message`) VALUES ('$message_count','$date','$message_count','$message_count','$message_type','$recipient_no','$message_desc','$text_message')";
                                            $stmt = $conn2->prepare($select);
                                            $stmt->execute();
                                        }
                                        
                                        if($phone_student != null && ($send_sms == "student_contact" || $send_sms == "all_three")){
                                            //send sms
                                            $response = sendSmsToClient($phone_student,$message_student,$api_key,$partnerID,$shortcodes,$send_sms_url);
                                            
                                            // insert the message
                                            $message_type = "Multicast";
                                            $message_count = count(explode(",",$phone_student));
                                            $recipient_no = $phone_student;
                                            $text_message = $message_student;
                                            $message_desc = strlen($message_student) > 45 ? substr($message_student,0,45)."..." : $message_student;
                                            $date = date("Y-m-d");
                                            $select = "INSERT INTO `sms_table` (`message_count`,`date_sent`,`message_sent_succesfully`,`message_undelivered`,`message_type`,`sender_no`,`message_description`,`message`) VALUES ('$message_count','$date','$message_count','$message_count','$message_type','$recipient_no','$message_desc','$text_message')";
                                            $stmt = $conn2->prepare($select);
                                            $stmt->execute();
                                        }
                                        echo '{
                                            "ResultCode":0,
                                            "ResultDesc": "Confirmation Recieved Successfully"
                                            }';
                                        return;
                                    }else {
                                        echo "<p class='red_notice'>Activate your sms account!</p>";
                                    }
                                }else {
                                    echo "Invalid parents phone number!";
                                }
                                //end of sms
                            }
                        }else {
                            $TransAmount = $jsonMpesaResponse['TransAmount']*1;
                            
                            // include("../../comma.php");
                            $message = "Confirmed Kes ".number_format($TransAmount).", Code: ".$jsonMpesaResponse['TransID']." has successfully been paid by ".$jsonMpesaResponse['FirstName'].". The admission number (".$jsonMpesaResponse['BillRefNumber'].") given was not valid.";
                            // record transactions
                            $fullnames = $jsonMpesaResponse['FirstName'];
                            $trans_status = "0";
                            recordMpesaTrans($conn2,$jsonMpesaResponse['TransID'],$jsonMpesaResponse['TransAmount'],$jsonMpesaResponse['BillRefNumber'],"0",$jsonMpesaResponse['TransTime'],$jsonMpesaResponse['BusinessShortCode'],$jsonMpesaResponse['MSISDN'],$fullnames,$trans_status);
                            $phone_number = $school_contact;
                            $api_key = getApiKey($conn2);

                            //check if the school has its own api keys
                            $school = 1;
                            if ($api_key == 0) {
                                $school = 0;
                                $api_key = getApiKey($conn);
                            }

                            //echo $api_key;
                            if ($api_key !== 0) {
                                if ($school == 0) {
                                    $partnerID = getPatnerId($conn);
                                    $shortcodes = getShortCode($conn);
                                    $send_sms_url = getSmsUrl($conn);
                                } else {
                                    $partnerID = getPatnerId($conn2);
                                    $shortcodes = getShortCode($conn2);
                                    $send_sms_url = getSmsUrl($conn2);
                                }
                                if ($message != null) {
                                    //send sms
                                    $response = sendSmsToClient($phone_number, $message, $api_key, $partnerID, $shortcodes, $send_sms_url);
                                    $decoded = json_decode($response);
                                    if (isset($decoded->{'message'})) {
                                        // echo $decoded->{'message'};
                                    } elseif (isset($decoded->{'response-description'})) {
                                        // echo $decoded->{'response-description'};
                                    }
                                    //recorded the sms information to the sms server
                                    $message_type = "Multicast";
                                    $message_count = count(explode(",", $phone_number));
                                    $text_message = $message;
                                    $message_desc = strlen($message) > 45 ? substr($message, 0, 45) . "..." : $message;
                                    $date = date("Y-m-d");
                                    $recepient = $phone_number;
                                    $recipient_no = $phone_number;
                                    $select = "INSERT INTO `sms_table` (`message_count`,`date_sent`,`message_sent_succesfully`,`message_undelivered`,`message_type`,`sender_no`,`message_description`,`message`,`number_collection`) VALUES ('$message_count','$date','$message_count','$message_count','$message_type','$recipient_no','$message_desc','$text_message','$recepient')";
                                    $stmt = $conn2->prepare($select);
                                    $stmt->execute();
                                }
                            } else {
                                echo "<p class='red_notice'>Activate your sms account!</p>";
                            }
                            echo '{
                                "ResultCode":0,
                                "ResultDesc": "Confirmation Recieved Successfully"
                                }';
                            return;
                        }
                    }
                }else {
                    // echo "No connection";
                }
            }else {
            //  echo "Database connected to that paybill is not found";
            }
        }
         
        function recordMpesaTrans($conn2,$mpesa_id,$TransAmount,$std_adm,$transaction_id, $trans_time,$shortcode,$MSIND,$fullnames,$trans_status){
        // RECORD MPESA TRANSACTION
            $insert = "INSERT INTO `mpesa_transactions` (`mpesa_id`,`amount`,`std_adm`,`assigned_transaction`,`transaction_time`,`short_code`,`payment_number`,`fullname`,`transaction_status`) VALUES (?,?,?,?,?,?,?,?,?)";
            $stmt = $conn2->prepare($insert);
            $stmt->bind_param("sssssssss",$mpesa_id,$TransAmount,$std_adm,$transaction_id, $trans_time,$shortcode,$MSIND,$fullnames,$trans_status);
            if($stmt->execute()){
            //  echo "executed";
            }else{
            //  echo "Not executed!";
            }
        }

        function getPaymentFor($conn2, $student, $TransAmount){
            $std_adm = $student['adm_no'];
            // check the student votehead status
            $student_data = $student;
            $all_course_fees = [];
            $my_course_list = $student['my_course_list'];
            if(isJson($my_course_list)){
                $vhs = [];
                $issetup = false;
                $course_fees = 0;
                $my_course_list = json_decode($my_course_list);
                foreach ($my_course_list as $course) {
                    if ($course->course_status == 1) {
                        $modules = $course->module_terms;
                        foreach ($modules as $module) {
                            if ($module->status == 1) {
                                $student_data['study_mode'] = strtolower($student_data['study_mode']);
                                $course_fees = $student_data['study_mode'] == "weekend" ? ($module->weekend_cost ?? 0) : ($student_data['study_mode'] == "evening" ? ($module->evening_cost ?? 0) : ($student_data['study_mode'] == "fulltime" ? ($module->fulltime_cost ?? 0) : ($module->termly_cost ?? 0)));
                            }
                            if ($module->status == 1 && isset($module->voteheads)) {
                                $vhs = $module->voteheads;
                                $issetup = true;
                                break;
                            }
                        }
                    }
                }

                $total = 0;
                if (($issetup && count($vhs) > 0) || !$issetup) {
                    $all_votehead = $vhs;
                    $vhs = [];
                    foreach ($all_votehead as $vh) {
                        if ($vh->pay) {
                            array_push($vhs, $vh->votehead);
                        }
                    }

                    if ((isPresent($vhs, "0") && $issetup) || !$issetup) {
                        // is the default course entry
                        $a_fee = new stdClass();
                        $a_fee->name = "Course Fees";
                        $a_fee->amount_paid = $course_fees;
                        $a_fee->id = 0;
                        $a_fee->roles = "Compulsory";
                        array_push($all_course_fees, $a_fee);
                        $total+= $course_fees;
                    }

                    $select = (count($vhs) > 0 && $issetup) ? "SELECT * FROM `fees_structure` WHERE ids IN (".join(',', $vhs).")" : "SELECT * FROM `fees_structure` WHERE `classes` = '".$student_data['stud_class']."' AND `course` = '".$student_data['course_done']."' AND `activated` = 1  and `roles` = 'regular';";
                    $stmt = $conn2->prepare($select);
                    $stmt->execute();
                    $results = $stmt->get_result();
                    if ($results) {
                        while ($row = $results->fetch_assoc()) {
                            $a_fee = new stdClass();
                            $a_fee->name = ucwords(strtolower($row['expenses']));
                            $a_fee->amount_paid = $student_data['study_mode'] == "weekend" ? $row['TERM_3'] : ($student_data['study_mode'] == "evening" ? $row['TERM_2'] : $row['TERM_1']);
                            $a_fee->id = $row['ids'];
                            $a_fee->roles = $issetup ? "Compulsory" : ($row['roles'] == "regular" ? "Compulsory" : $row['roles']);
                            $total+= $a_fee->amount_paid;
                            array_push($all_course_fees, $a_fee);
                        }
                    }
                }else{
                    // is the default course entry
                    $a_fee = new stdClass();
                    $a_fee->name = "Course Fees";
                    $a_fee->amount_paid = $TransAmount;
                    $a_fee->id = 0;
                    $a_fee->roles = "Compulsory";
                    array_push($all_course_fees, $a_fee);
                    $total = $TransAmount;
                }

                $paid_voteheads = [];
                foreach ($all_course_fees as $key => $votehead) {
                    $percentage = $votehead->amount_paid / $total * 100;
                    $amount = $percentage / 100  * $TransAmount;
                    $payment_data = array(
                        "id" => $votehead->id,
                        "name" => $votehead->name,
                        "amount_paid" => number_format($amount,2, '.', ''),
                        "roles" => $votehead->roles
                    );
                    array_push($paid_voteheads, $payment_data);
                }
                return $paid_voteheads;
            }else{
                // is the default course entry
                $a_fee = new stdClass();
                $a_fee->name = "Course Fees";
                $a_fee->amount_paid = $TransAmount;
                $a_fee->id = 0;
                $a_fee->roles = "Compulsory";
                array_push($all_course_fees, $a_fee);
                return $all_course_fees;
            }

            // check if their voteheads are defined or not
            $students_vh = $student_data['votehead_status'];
            $selected_voteheads = [];
            $student_class = $student_data['stud_class'];
            if(isJson($students_vh)){
                $student_votehead = json_decode($students_vh);
                foreach($student_votehead as $vhs){
                    if($vhs->class_name == $student_class){
                        $selected_voteheads = $vhs->voteheads;
                    }
                }
            }
        
            $statement = "";
            foreach($selected_voteheads as $votehead){
                if(is_int($votehead) || is_string($votehead)){
                    $statement .= "(classes LIKE '%|" . $student_class . "|%' AND ids = '".$votehead."') OR ";
                }else{
                    $statement .= "(classes LIKE '%|" . $student_class . "|%' AND ids = '".$votehead->votehead."') OR ";
                }
            }
            $statement = count($selected_voteheads) > 0 ? substr($statement, 0, strlen($statement)-4)."" : "";
            
            // for students who are boarding
            $boarding = isBoarding($std_adm, $conn2) ? "" : "AND roles != 'boarding'";

            $select = count($selected_voteheads) > 0 ? "SELECT * FROM `fees_structure` WHERE ".$statement : "SELECT * FROM `fees_structure` WHERE `activated` = 1 AND roles != 'provisional' and `classes` like '%|".$student['stud_class']."|%' ". $boarding;
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $results = $stmt->get_result();
            $term = getTermV2($conn2);
            $select = "<p style='color:green;'>There is no payment option set by the administrator</p>";
            if ($results) {
                $xs = 0;
                $all_votehead = [];
                $total = 0;
                while ($row = $results->fetch_assoc()) {
                    array_push($all_votehead, $row);
                    $total+=$row[$term];
                }

                if (isTransport($conn2, $std_adm) == true) {
                    $get_route_1 = routeName($conn2, $std_adm, "TERM_1");
                    $get_route_2 = routeName($conn2, $std_adm, "TERM_2");
                    $get_route_3 = routeName($conn2, $std_adm, "TERM_3");
                    
                    $row_data = array(
                        "expenses" => ucwords(strtolower($get_route_1[0])),
                        "display_name" => "Transport",
                        "roles" => "regular",
                        "TERM_1" => $get_route_1[1],
                        "TERM_2" => $get_route_2[1],
                        "TERM_3" => $get_route_3[1]
                    );
                    array_push($all_votehead, $row_data);
                    $total += $row_data[$term];
                }

                // get the percentage and device how each votehead will get a share
                $paid_voteheads = [];
                foreach ($all_votehead as $key => $votehead) {
                    $percentage = $votehead[$term] / $total * 100;
                    $amount = $percentage / 100  * $TransAmount;
                    $payment_data = array(
                        "id" => $key+1,
                        "name" => $votehead['display_name'],
                        "real_name" => $votehead['expenses'],
                        "amount_paid" => number_format($amount,2, '.', ''),
                        "roles" => $votehead['roles']
                    );

                    array_push($paid_voteheads, $payment_data);
                }
                return $paid_voteheads;

                // loop through the voteheads and get the total;
            }
            
            return [array(
                        "id" => 1,
                        "name" => "Tuition",
                        "real_name" => "Tuition",
                        "amount_paid" => number_format($TransAmount,2),
                        "roles" => "regular"
                    )];
        }