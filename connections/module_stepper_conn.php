<?php
        if(session_status()==PHP_SESSION_NONE){
            //session is not started
            session_start();
        }
        $dbname = $database != null ? $database : "";
        $hostname = 'localhost';
        $dbusername ='hillary';
        // $dbpassword = '';
        $dbpassword = '2000hILARY';
        if (isset($dbname)) {            
        $conn2 = new mysqli($hostname,$dbusername,$dbpassword,$dbname);
            if(mysqli_connect_error()){
                echo "<p style='color:red;'>Connection was lost.</p>";
                //die("Connect Error ( ".mysqli_connect_errno()." ) ".mysqli_connect_error());
            }
        }
?>