<?php
    // set timezone and other settings
    date_default_timezone_set('Africa/Nairobi');
    ignore_user_abort(true);
    set_time_limit(0);

    // verify required parameters
    if(!isset($_GET['database']) && !isset($_GET['school_code'])){
        exit();
    }

    // include the primary connection
    include_once("../../connections/conn1.php");

    // verify if the database is present
    $select = "SELECT * FROM school_information WHERE `database_name` = ? AND `school_code` = ?";
    $stmt = $conn->prepare($select);
    $stmt->bind_param("ss", $_GET['database'], $_GET['school_code']);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result || mysqli_num_rows($result) == 0) {
        exit();
    }

    // start session and include required files
    session_start();

    // SET THE SESSION VARIABLES
    $_SESSION['databasename'] = $_GET['database'];
    $_SESSION['dbname'] = $_GET['database'];
    $_SESSION['schoolcode'] = $_GET['school_code'];
    include_once("../../connections/conn2.php");
    include_once("../../sms_apis/sms.php");


    // Optional: flush output and close HTTP connection
    if (function_exists('fastcgi_finish_request')) {
        fastcgi_finish_request();
    }

    // get the latest processing id;
    $select = "SELECT MAX(`processing_id`) AS `max_id` FROM `sms_table`";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    $processing_id = 0;
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            $processing_id = $row['max_id'];
        }
    }

    // update all pending messages and assign processing id
    $update = "UPDATE `sms_table` SET `processing_id` = ? WHERE `message_status` = 'pending' AND `processing_id` = 0";
    $stmt = $conn2->prepare($update);
    $new_processing_id = $processing_id + 1;
    $stmt->bind_param("i", $new_processing_id);
    $stmt->execute();

    $api_key = getApiKeySms($conn2);
    $partnerID = getPatnerIdSms($conn2);
    $shortcodes = getShortCodeSms($conn2);
    $send_sms_url = getSmsUrlSms($conn2);
    $fup = 2500;
    
    // send sms
    $select = "SELECT * FROM `sms_table` WHERE message_status = 'pending' AND processing_id = ?";
    $stmt = $conn2->prepare($select);
    $stmt->bind_param("i", $new_processing_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        if($fup <= 0){
            break;
        }

        // recipient number and message
        $to = $row['sender_no'];
        $message = $row['message'];
        $output_name = sendSmsToClient($to, $message, $api_key, $partnerID, $shortcodes, $send_sms_url);
        $fup--;

        // update sms status
        $update = "UPDATE `sms_table` SET `message_status` = ? WHERE `send_id` = ?";
        $stmt = $conn2->prepare($update);
        $status = "sent";
        $stmt->bind_param("si", $status, $row['send_id']);
        $stmt->execute(); 
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
    function getSmsUrlSms($conn){
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
?>