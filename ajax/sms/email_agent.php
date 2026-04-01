<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require '../administration/phpmailer/src/Exception.php';
    require '../administration/phpmailer/src/PHPMailer.php';
    require '../administration/phpmailer/src/SMTP.php';
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
    $_SESSION['schoolcode'] = $_GET['school_code'];
    include_once("../../connections/conn2.php");
    include_once("../../sms_apis/sms.php");


    // Optional: flush output and close HTTP connection
    if (function_exists('fastcgi_finish_request')) {
        fastcgi_finish_request();
    }
    $email_setup = get_email_setup($conn2);
    if($email_setup == null){
        exit();
    }

    // GET THE LATEST EMAIL PROCESSING ID;
    $select = "SELECT MAX(`processing_id`) AS `max_id` FROM `email_address`";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    $processing_id = 0;
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            $processing_id = $row['max_id'];
        }
    }

    // UPDATE ALL PENDING EMAILS AND ASSIGN PROCESSING ID
    $update = "UPDATE `email_address` SET `processing_id` = ? WHERE `email_status` = 'pending' AND `processing_id` = 0";
    $stmt = $conn2->prepare($update);
    $new_processing_id = $processing_id + 1;
    $stmt->bind_param("i", $new_processing_id);
    $stmt->execute();
    
    // send EMAILS
    $select = "SELECT * FROM email_address WHERE email_status = 'pending' AND processing_id = ?";
    $stmt = $conn2->prepare($select);
    $stmt->bind_param("i", $new_processing_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        // recipient number and message
        $recipient = $row['recipient_to'];
        $cc = $row['cc'];
        $bcc = $row['bcc'];
        $subject = $row['message_subject'];
        $attachement_location = $row['attachments'];
        $message = $row['message'];
        
        // EMAIL SETTINGS
        $sender_name = $email_setup['sender_name'];
        $email_host_addr = $email_setup['email_host_addr'];
        $email_username = $email_setup['email_username'];
        $email_password = $email_setup['email_password'];
        $tester_mail = $email_setup['tester_mail'];
        
        // send email
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $email_host_addr;
            $mail->SMTPAuth = true;
            $mail->Username = $email_username;
            $mail->Password = $email_password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port = 587;
            $mail->setFrom($email_username,$sender_name);
            strlen($bcc) > 2 ?  $mail->addBCC($bcc,$sender_name) : "";
            strlen($cc) > 2 ?  $mail->addCC($cc,$sender_name) : "";

            // attach the file if present
            strlen($attachement_location) > 2 ? $mail->AddAttachment($attachement_location, '', $encoding = 'base64', $type = 'application/pdf') : "";
            
            // email parameters
            $mail->addAddress($recipient);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->send();
        } catch (Exception $th) {
            continue;
        }

        // update sms status
        $update = "UPDATE `email_address` SET `email_status` = ? WHERE `id` = ?";
        $stmt = $conn2->prepare($update);
        $status = "sent";
        $stmt->bind_param("si", $status, $row['id']);
        $stmt->execute(); 
    }

    // DELETE THE EMAIL ATTACHMENTS AFTER SENDING THE EMAILS
    $select = "SELECT * FROM email_address WHERE processing_id = ?";
    $stmt = $conn2->prepare($select);
    $stmt->bind_param("i", $new_processing_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $attachement_location = $row['attachments'];
        // delete the email
        if(strlen($attachement_location) > 2){
            // delete the attachment after sending
            if (file_exists($attachement_location)) {
                unlink($attachement_location);
            }
        }
    }

    function get_email_setup($conn2){
        $select = "SELECT * FROM `settings` WHERE `sett` = 'email_setup'";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()){
                $email_sets = $row['valued'];

                // send email
                $json_mail = json_decode($email_sets);
                $sender_name = $json_mail->sender_name;
                $email_host_addr = $json_mail->email_host_addr;
                $email_username = $json_mail->email_username;
                $email_password = $json_mail->email_password;
                $tester_mail = $json_mail->tester_mail;

                return array(
                    "sender_name" => $sender_name,
                    "email_host_addr" => $email_host_addr,
                    "email_username" => $email_username,
                    "email_password" => $email_password,
                    "tester_mail" => $tester_mail
                );
            }
        }
        return null;
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