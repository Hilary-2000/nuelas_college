<?php
    session_start();
    date_default_timezone_set('Africa/Nairobi');
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        include("../../connections/conn2.php");
        if (isset($_GET['get_action_logs'])) {
            $dbname = isset($_SESSION['dbname']) ? $_SESSION['dbname'] : $_SESSION['databasename'];
            $dbname = preg_replace('/[^a-zA-Z0-9_]/', '', $dbname);
            $file_location = "../logs/" . $dbname . "/logs.txt";
            $entries = [];
            if ($dbname != "" && file_exists($file_location)) {
                $lines = file($file_location, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    if (preg_match('/^(.*?) : (.*) - \{(.*)\}$/', $line, $matches)) {
                        $date_raw = preg_replace('/(AM|PM)$/i', '', trim($matches[1]));
                        $date_obj = DateTime::createFromFormat('dS M Y H:i:s', $date_raw);
                        if ($date_obj) {
                            array_push($entries, array(
                                "ts" => $date_obj->getTimestamp(),
                                "date_display" => trim($matches[1]),
                                "message" => $matches[2],
                                "username" => $matches[3]
                            ));
                        }
                    }
                }
            }
            header('Content-Type: application/json');
            echo json_encode($entries);
        }
    }
