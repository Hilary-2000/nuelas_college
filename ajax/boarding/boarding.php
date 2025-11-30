<?php
    session_start();
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        include("../../connections/conn2.php");
        if (isset($_GET['get_dorm_captain'])) {
            // selected captain
            $selected_captain = isset($_GET['selected_captain']) ? $_GET['selected_captain'] : "0";

            //get the teacher list in the hostel table
            $select = "SELECT `dorm_captain` FROM `dorm_list` WHERE `deleted` = 0 AND `activated` = 1";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $captain_list = "";
                while ($row = $result->fetch_assoc()) {
                    $captain_list.=$row['dorm_captain'].",";
                }
                $captain_list = removeComma($captain_list);
                include("../../connections/conn1.php");
                //get the school teachers list
                $select = "SELECT `fullname`, `user_id` FROM  `user_tbl` WHERE `school_code` = ? AND `deleted` = 0 AND `activated` = 1";
                $stmt = $conn->prepare($select);
                $schoolcode = $_SESSION['schoolcode'];
                $stmt->bind_param("s",$schoolcode);
                $stmt->execute();
                $result = $stmt->get_result();
                $teachers_list = "";
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $teachers_list.=$row['user_id'].",";
                    }
                    $teachers_list = removeComma($teachers_list);
                }
                if (isset($_GET['class_name'])) {
                    $teacher_list_dropdown = "<select name='".$_GET['class_name']."' id='".$_GET['class_name']."'><option value='' hidden>Select..</option>";
                }else {
                    $teacher_list_dropdown = "<select name='dorm_captain' id='dorm_captain'><option value='' hidden>Select..</option>";
                }
                if (strlen($captain_list) > 0) {
                    $captain_list = explode(",",$captain_list);
                    $teachers_list = explode(",",$teachers_list);
                    for ($ind=0; $ind < count($teachers_list); $ind++) { 
                        $present = checkPresnt($captain_list,$teachers_list[$ind]);
                        if ($present == 0 || $selected_captain == $teachers_list[$ind]) {
                            $teacher_list_dropdown.="<option ".($selected_captain == $teachers_list[$ind] ? "selected" : "")." value='".$teachers_list[$ind]."'>".getTeacherName($teachers_list[$ind])."</option>";
                        }
                    }
                    $teacher_list_dropdown.="</select>";
                    echo $teacher_list_dropdown;
                }else {
                    $teachers_list = explode(",",$teachers_list);
                    for ($ind=0; $ind < count($teachers_list); $ind++) { 
                        $teacher_list_dropdown.="<option value='".$teachers_list[$ind]."'>".getTeacherName($teachers_list[$ind])."</option>";
                    }
                    $teacher_list_dropdown.="</select>";
                    echo $teacher_list_dropdown;
                }
            }else {
                echo "<p style='color:red;font-size:12px;font-weight:600;'>No teachers available to assign the hostel</p>";
            }
        }elseif (isset($_GET['add_dormitory'])) {
            $dorm_capacity = $_GET['dorm_capacity'];
            $dorm_name = $_GET['dorm_name'];
            $dorm_captain = $_GET['dorm_captain'];
            $bed_capacity = $_GET['bed_capacity'];
            $room_capacity = $_GET['room_capacity'];
            $matress_count = $_GET['matress_count'];
            $comment = $_GET['comment'];
            $select = "INSERT INTO `dorm_list` (`dorm_name`,`dorm_capacity`,`dorm_captain`,`activated`,`deleted`,`bed_capacity`, `cube_count`, `matress_count`, `comment`) VALUES (?,?,?,?,?,?,?,?,?)";
            $stmt = $conn2->prepare($select);
            $activated = 1;
            $deleted = 0;
            $stmt->bind_param("sssssssss", $dorm_name, $dorm_capacity, $dorm_captain, $activated, $deleted, $bed_capacity, $room_capacity, $matress_count, $comment);
            if($stmt->execute()){
                echo "<p style='color:green;font-size:12px;font-weight:600;'>Hostel registered successfully!</p>";
            }else {
                echo "<p style='color:red;font-size:12px;font-weight:600;'>An error occured during registration!</p>";
            }
        }elseif (isset($_GET['get_dormitory_list'])) {
            $select = "SELECT * FROM `dorm_list` WHERE `deleted` = 0 and `activated` = 1";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $data_to_display = "<h6 style='font-size:17px;font-weight:500;text-align:center;margin: 5px 0;'><u>Hostel List</u></h6><div class='table_holders'><table class='table' id='house_list_table'><thead>
                <tr>
                    <th>No.</th>
                    <th>House Name</th>
                    <th>House Captain</th>
                    <th>Capacity</th>
                    <th>Occupied</th>
                    <th>Available</th>
                    <th>Options</th>
                    <th>Occupancy</th>
                </tr></thead><tbody>";
                $xs=0;

                while ($row = $result->fetch_assoc()) {
                    $xs++;
                    $capacity = $row['dorm_capacity'];
                    $dorm_id = $row['dorm_id'];
                    $occupied = getOccupancy($dorm_id,$conn2);
                    $available = $capacity - $occupied;
                    $trname = "None";
                    if (strlen($row['dorm_captain']) > 0) {
                        $trname = getTeacherName($row['dorm_captain']);
                    }
                    $row['dorm_captain_name'] = $trname;
                    $data_to_display.="
                    <tr>
                        <td><input type='hidden' value='".json_encode($row)."' id='hostel_data_".$dorm_id."'>".$xs.". </td>
                        <td id = 'dn".$dorm_id."' >".ucwords(strtolower($row['dorm_name']))."</td>
                        <td id = 'dc".$dorm_id."' >".ucwords(strtolower($trname))."</td>
                        <td id = 'cap".$dorm_id."' >".$row['dorm_capacity']."</td>
                        <td>".$occupied."</td>
                        <td>".$available."</td>
                        <td><span class = 'dorm_edit link'  id = 'd_nm".$dorm_id."' style='font-size:12px; width: fit-content;' ><i class='fa fa-pen'></i> Edit</span> <span class = 'dorm_delete link'  id = 'dorm_delete_".$dorm_id."' style='font-size:12px; width: fit-content;' ><i class='fa fa-trash'></i> Del</span></td>
                        <td><span id='occupied".$dorm_id."' class = 'link linked_occupancy' style='font-size:12px;'><i class='fa fa-bed'></i> Members</span> <span id='room_mgt_".$dorm_id."' class = 'link room_mgt' style='font-size:12px;'><i class='fa fa-door-closed'></i> Rooms</span></td>
                    </tr>";
                }

                $data_to_display.="</tbody></table></div>";
                if ($xs>0) {
                    echo $data_to_display;
                }else {
                    echo "<div class='displaydata'>
                            <img class='' src='images/error.png'>
                            <p style='color:red;font-size:12px;font-weight:600;'>No hostel results!</p>
                        </div>";
                }
            }
        }elseif(isset($_GET['display_rooms'])){
            $hostel_id = $_GET['hostel_id'];
            $select = "SELECT hostel_rooms.*, (SELECT COUNT(*) FROM boarding_list WHERE boarding_list.room_id = hostel_rooms.room_id) AS 'members' FROM `hostel_rooms` WHERE hostel_id = ?;";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s", $hostel_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "<div class='tableme'><table id='rooms_display_table' class='table'><thead><tr>
                                                        <th>No.</th>
                                                        <th>Room Name</th>
                                                        <th>Room Capacity</th>
                                                        <th>Members Count</th>
                                                        <th>Actions</th>
                                                    </tr></thead><tbody>";
            if ($result) {
                $counter = 1;
                while ($row = $result->fetch_assoc()) {
                    $data_to_display .= "<tr>
                        <td>".$counter.".<input type='hidden' value='".json_encode($row)."' id='room_data_".$row['room_id']."'></td>
                        <td>".strtoupper(strtolower($row['room_name']))."</td>
                        <td>".$row['room_capacity']." Members</td>
                        <td>".$row['members']." Admitted</td>
                        <td><span id='room_edit_".$row['room_id']."' class='link room_edit' style='font-size:12px;'><i class='fa fa-pen-fancy'></i> Edit</span> <span id='delete_room_".$row['room_id']."' class='link delete_room' style='font-size:12px;'><i class='fa fa-trash'></i> Delete</span></td>
                    </tr>";
                    $counter++;
                }
            }
            $data_to_display .= "</tbody></table></div>";
            echo $data_to_display;
        }elseif(isset($_GET['update_hostel'])){
            $update_hostel= $_GET['update_hostel'];
            $hostel_name = $_GET['hostel_name'];
            $hostel_capacity = $_GET['hostel_capacity'];
            $room_comment = $_GET['room_comment'];
            $room_id = $_GET['room_id'];

            $update = "UPDATE hostel_rooms SET room_name = ?, room_capacity = ?, room_comment = ? WHERE room_id = ?";
            $stmt = $conn2->prepare($update);
            $stmt->bind_param("ssss", $hostel_name, $hostel_capacity, $room_comment, $room_id);
            $stmt->execute();

            echo "<p class='text-success'>Room data have been updated successfully!</p>";
        }elseif(isset($_GET['delete_hostel_room'])){
            $delete_hostel_room = $_GET['delete_hostel_room'];
            $room_id = $_GET['room_id'];

            // delete hostel
            $delete = "DELETE FROM hostel_rooms WHERE room_id = ?";
            $stmt = $conn2->prepare($delete);
            $stmt->bind_param("s", $room_id);
            $stmt->execute();
            
            // unassign the students assigned that room
            $select = "SELECT * FROM `boarding_list` WHERE room_id = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s", $room_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result){
                while ($row = $result->fetch_assoc()) {
                    $student_id = $row['student_id'];
                    $delete = "DELETE FROM `boarding_list` WHERE `student_id` = ? AND `room_id` = ?";
                    $statement_1 = $conn2->prepare($delete);
                    $statement_1->bind_param("ss",$student_id,$room_id);
                    if($statement_1->execute()){
                        $update = "UPDATE `student_data` set `dormitory` = 'none', `boarding` = 'enroll' WHERE `adm_no` = ?";
                        $statement_2 = $conn2->prepare($update);
                        $statement_2->bind_param("s",$student_id);
                        $statement_2->execute();
                    }
                }
            }
            echo "<p class='text-success'>Room has been deleted successfully!</p>";
        }elseif(isset($_GET['get_boarding_students'])){
            $select = "SELECT boarding_list.*, student_data.*, hostel_rooms.room_name, dorm_list.dorm_name FROM `boarding_list` LEFT JOIN student_data ON student_data.adm_no = boarding_list.student_id LEFT JOIN hostel_rooms ON hostel_rooms.room_id = boarding_list.room_id LEFT JOIN dorm_list ON dorm_list.dorm_id = boarding_list.dorm_id;";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "<h6 class='text-center mt-2'><u>Student Boarding List</u></h6><div class='tableme'><table class='table' id='boarding_students_list'><thead><tr><th>No.</th><th>Student Name</th><th>Adm No.</th><th>Hostel</th><th>Room</th><th>Enroll Date</th><th>Action</th></tr></thead><tbody>";
            if ($result) {
                $index = 1;
                while($row = $result->fetch_assoc()){
                    $course_name = get_course_name($row['course_done'], $conn2);
                    $row['course_name'] = ucwords(strtolower($course_name));
                    $data_to_display.="
                    <tr>
                        <td> <input type='hidden' value='".json_encode($row)."' id='boarding_data_".$row['id']."'> ".$index.".</td>
                        <td>".ucwords(strtolower($row['first_name']." ".$row['second_name']))."</td>
                        <td>".$row['adm_no']."</td>
                        <td>".$row['dorm_name']."</td>
                        <td>".$row['room_name']."</td>
                        <td>".date("D dS M Y",strtotime($row['date_of_enrollment']))."</td>
                        <td><span id='view_boarder_profile_".$row['id']."' class='link view_boarder_profile' style='font-size:12px;'><i class='fa fa-eye'></i> View</span> | <span id='change_hostel_".$row['id']."' class='link change_hostel' style='font-size:12px;'><i class='fa fa-pen-fancy'></i> Change</span></td>
                    </tr>";
                    $index++;
                }
            }
            $data_to_display .= "</tbody></table></div>";
            echo $data_to_display;
        }elseif(isset($_GET['get_boarding_fees'])){
            include("../finance/financial.php");
            $admission_no = $_GET['admission_no'];
            $is_boarding = isBoarding($admission_no,$conn2);
            $boarding_fees = 0;
            if($is_boarding){
                $student_data = students_details($admission_no, $conn2);
                if (count($student_data) > 0) {
                    $boarding_fees = getBoardingFees($conn2, $student_data);
                }
            }
            echo "Kes ".number_format($boarding_fees);
        }elseif(isset($_GET['add_new_room'])){
            $add_new_room = $_GET['add_new_room'];
            $room_prefix = $_GET['room_prefix'];
            $room_name_number = $_GET['room_name_number'];
            $room_sufix = $_GET['room_sufix'];
            $multiple_room_capacity = $_GET['multiple_room_capacity'];
            $room_name = $_GET['room_name'];
            $room_capacity = $_GET['room_capacity'];
            $room_comment = $_GET['room_comment'];
            $hostel_id = $_GET['hostel_id'];

            if($add_new_room == "multiple"){
                $room_number = $_GET['room_number']+($room_name_number*1);
                for ($index=$room_name_number; $index < ($room_number*1); $index++) { 
                    $insert = "INSERT INTO hostel_rooms (room_name, room_capacity,hostel_id) VALUES (?,?,?)";
                    $stmt = $conn2->prepare($insert);
                    $full_room_name = ($room_prefix."".($index <= 9 ? "00".$index : ($index <= 99 ? "0".$index : $index))."".$room_sufix);
                    $stmt->bind_param("sss", $full_room_name, $multiple_room_capacity, $hostel_id);
                    $stmt->execute();
                }
                echo "<p class='text-success' id='hostel_room_success'>Hostel rooms have been added successfully!</p>";
            }else{
                $insert = "INSERT INTO hostel_rooms (room_name, room_capacity, hostel_id, room_comment) VALUES (?,?,?,?)";
                $stmt = $conn2->prepare($insert);
                $stmt->bind_param("ssss", $room_name, $room_capacity, $hostel_id, $room_comment);
                $stmt->execute();
                echo "<p class='text-success' id='hostel_room_success'>Hostel room has been added successfully!</p>";
            }
        }elseif(isset($_GET['delete_hostel'])){
            $delete_hostel = $_GET['delete_hostel'];
            $hostel_id = $_GET['hostel_id'];
            $delete = "DELETE FROM dorm_list WHERE dorm_id = ?";
            $stmt = $conn2->prepare($delete);
            $stmt->bind_param("s", $hostel_id);
            $stmt->execute();

            // select the students in the dormlist
            $select = "SELECT * FROM `boarding_list` WHERE dorm_id = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s", $hostel_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result){
                while ($row = $result->fetch_assoc()) {
                    $student_id = $row['student_id'];
                    $dormids = $hostel_id;
                    $delete = "DELETE FROM `boarding_list` WHERE `student_id` = ? AND `dorm_id` = ?";
                    $statement_1 = $conn2->prepare($delete);
                    $statement_1->bind_param("ss",$student_id,$dormids);
                    if($statement_1->execute()){
                        $update = "UPDATE `student_data` set `dormitory` = 'none', `boarding` = 'enroll' WHERE `adm_no` = ?";
                        $statement_2 = $conn2->prepare($update);
                        $statement_2->bind_param("s",$student_id);
                        $statement_2->execute();
                    }
                }
            }
            echo "<p class='text-success'>Hostels have been deleted successfully!</p>";
        }elseif (isset($_GET['change_dorm_data'])) {
            $update = "UPDATE `dorm_list` SET `dorm_name` = ?,`dorm_capacity` = ?, `dorm_captain` = ?, bed_capacity = ?, cube_count = ?, matress_count = ?, comment = ? WHERE `dorm_id` = ? ";
            $update2 = "UPDATE `dorm_list` SET `dorm_name` = ?,`dorm_capacity` = ?, bed_capacity = ?, cube_count = ?, matress_count = ?, comment = ? WHERE `dorm_id` = ?";
            if (isset($_GET['dorm_captain'])) {
                $stmt = $conn2->prepare($update);
                $dorm_name = $_GET['dorm_name'];
                $dorm_capacity = $_GET['dorm_capacity'];
                $dorm_captain = $_GET['dorm_captain'];
                $dorm_id = $_GET['dorm_id'];
                $bed_capacity = $_GET['bed_capacity'];
                $room_capacity = $_GET['room_capacity'];
                $matress_count = $_GET['matress_count'];
                $comment = $_GET['comment'];
                $stmt->bind_param("ssssssss",$dorm_name,$dorm_capacity,$dorm_captain, $bed_capacity, $room_capacity, $matress_count, $comment ,$dorm_id);
                if($stmt->execute()){
                    echo "<p style='color:green;font-size:12px;font-weight:600;'>Change done successfully!</p>";
                }else {
                    echo "<p style='color:red;font-size:12px;font-weight:600;'>An error occured during Updating!</p>";
                }
            }else {
                $stmt = $conn2->prepare($update2);
                $dorm_name = $_GET['dorm_name'];
                $dorm_capacity = $_GET['dorm_capacity'];
                $dorm_id = $_GET['dorm_id'];
                $bed_capacity = $_GET['bed_capacity'];
                $room_capacity = $_GET['room_capacity'];
                $matress_count = $_GET['matress_count'];
                $comment = $_GET['comment'];
                $stmt->bind_param("sssssss",$dorm_name,$dorm_capacity, $bed_capacity, $room_capacity, $matress_count, $comment, $dorm_id);
                if($stmt->execute()){
                    echo "<p style='color:green;font-size:12px;font-weight:600;'>Change done successfully!</p>";
                }else {
                    echo "<p style='color:red;font-size:12px;font-weight:600;'>An error occured during Updating!</p>";
                }
            }
        }elseif (isset($_GET['un_assign_dorm'])) {
            $dorm_id = $_GET['un_assign_dorm'];
            $update = "UPDATE `dorm_list` SET `dorm_captain` = '' WHERE `dorm_id` = ?";
            $stmt = $conn2->prepare($update);
            $stmt->bind_param("s",$dorm_id);
            if($stmt->execute()){
                echo "<p style='color:green;font-size:12px;font-weight:600;'>Change done successfully!</p>";
            }else {
                echo "<p style='color:red;font-size:12px;font-weight:600;'>An error occured during Updating!</p>";
            }
        }elseif (isset($_GET['get_enrolled_boarders'])) {
            $select = "SELECT `adm_no`, `first_name`,`second_name` , `gender` ,`stud_class`,`boarding` FROM `student_data` WHERE `boarding` = 'enroll' AND deleted = 0 AND activated = 1";
            $select2 = "SELECT `adm_no`, `first_name`,`second_name` , `gender` ,`stud_class`,`boarding` FROM `student_data` WHERE `adm_no` = ? AND `boarding` = 'enroll' AND deleted = 0 AND activated = 1";
            $result;
            if (isset($_GET['use_adm'])){
                $stmt = $conn2->prepare($select2);
                $admno = $_GET['use_adm'];
                $stmt->bind_param("s",$admno);
                $stmt->execute();
                $result = $stmt->get_result();
            }else{
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
            }
            $data_to_display = "<h6 style='margin-top:10px;text-align:center;font-size:17px;font-weight:500;'>Students to enroll</h6><div class='table_holders'><table class='table' id='student_list_enroll_boarding'>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Adm no</th>
                    <th>Student Name</th>
                    <th>Gender</th>
                    <th>Select hostel</th>
                    <th>Room Number</th>
                    <th>Save</th>
                </tr>
            </thead><tbody>";
            if ($result) {
                $xs = 0;
                $number = 1;
                while ($row = $result->fetch_assoc()) {
                    $xs++;
                    $adm_no = $row['adm_no'];
                    $first_name = $row['first_name'];
                    $second_name = $row['second_name'];
                    $gender = $row['gender'];
                    $stud_class = $row['stud_class'];
                    $boarding = $row['boarding'];
                    $sel_id = "select".$adm_no;
                    $data_to_display.="<tr>
                                        <td>".$number."</td>
                                        <td>".$adm_no."</td>
                                        <td>".ucwords(strtolower($first_name." ".$second_name))."</td>
                                        <td>".$gender."</td>
                                        <td id='outer".$adm_no."'>".getDormitory($conn2,$sel_id)."</td>
                                        <td id='room_dropdown_holder_".$adm_no."'><span class='text-danger'>Select hostel to display rooms!</span></td>
                                        <td><span class='save_boarder link' id='sd".$adm_no."' style='margin:0;font-size:12px;'><i class='fa fa-save'></i> Save</span></td>
                                    </tr>";
                                    $number++;
                }
                $data_to_display.="</tbody></table></div>";
            }
            $data_to_display.="</tbody></table></div>";
            echo $data_to_display;
        }elseif(isset($_GET['display_rooms_dropdown'])){
            // hostel id
            $hostel_id = $_GET['hostel_id'];
            $dropdown_id = $_GET['dropdown_id'];
            $select = "SELECT hostel_rooms.*, (SELECT COUNT(*) AS 'occupants' FROM boarding_list WHERE boarding_list.room_id = hostel_rooms.room_id) AS members FROM `hostel_rooms` WHERE hostel_id = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s", $hostel_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $drop_down_list = "<select class='form-control' id='".$dropdown_id."'><option hidden value=''>Select Room</option>";
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    if($row['members'] < $row['room_capacity'])
                    $drop_down_list .= "<option value='".$row['room_id']."'>".$row['room_name']."</option>";
                }
            }
            $drop_down_list .= "</select>";
            echo $drop_down_list;
        }elseif (isset($_GET['save_boarder_infor'])) {
            $boarder_id = $_GET['boarder_id'];
            $house_id = $_GET['house_id'];
            $room_number = $_GET['room_number'];
            $insert = "update `student_data` set `boarding` = 'enrolled' , `dormitory` = ? WHERE `adm_no` = ?";
            $stmt = $conn2->prepare($insert);
            $stmt->bind_param("ss",$house_id,$boarder_id);
            if($stmt->execute()){
                $insert = "INSERT INTO `boarding_list` (`student_id`, `dorm_id`, `date_of_enrollment`, `room_id`, `deleted`, `activated`) values (?,?,?,?,?,?)";
                $date = date("Y-m-d");
                $stmt = $conn2->prepare($insert);
                $deleted = 0;
                $activated = 1;
                $stmt->bind_param("ssssss",$boarder_id, $house_id, $date, $room_number, $deleted, $activated);
                if($stmt->execute()){
                    echo "<p style='color:green;'>Enrolled ✔</p>";
                }
            }
        }elseif (isset($_GET['get_occupancy'])) {
            $dorm_id = $_GET['dormitory_id'];
            $select = "SELECT boarding_list.*, student_data.*, (SELECT hostel_rooms.room_name FROM hostel_rooms WHERE hostel_rooms.room_id = boarding_list.room_id) AS room_name FROM `boarding_list` LEFT JOIN student_data ON student_data.adm_no = boarding_list.student_id WHERE `dorm_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$dorm_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $dorm_name = getDormName($dorm_id,$conn2);
            $data_to_display = "<h6 style='text-align:center;'>".$dorm_name." Members</h6>
                                <div class='tableHolder'>
                                    <table class='table' id='hostel-occupancy'>
                                        <thead><tr>
                                            <th>No.</th>
                                            <th>Adm no</th>
                                            <th>Student Name</th>
                                            <th>Gender</th>
                                            <th>Room Name</th>
                                            <th>Date Enrolled</th>
                                            <th>Change hostel</th>
                                        </tr></thead><tbody>";
            if ($result) {
                $xs = 0;
                while ($row = $result->fetch_assoc()) {
                    $xs++;
                    $student = getStudentName($row['student_id'],$conn2);
                    $date = $row['date_of_enrollment'];
                    $date = date("M-d-Y",strtotime($date));
                    $data_to_display.="<tr>
                                        <td><input type='hidden' value='".json_encode($row)."' id='dorm_data_".$row['student_id']."'>".($xs)."</td>
                                        <td>".$row['student_id']."</td>
                                        <td id='mystud".$row['student_id']."'>".$student[0]."</td>
                                        <td >".$student[1]."</td>
                                        <td >".$row['room_name']."</td>
                                        <td>".$date."</td>
                                        <td style='text-align:center;'><span class='link change_dormitory' data-hostel-id='".$dorm_id."' id='change_dormitory_".$row['student_id']."' style='font-size:12px;text-align:center;' ><i class='fa fa-pen'></i> Change</span></td>
                                    </tr>";
                }
            }
            $data_to_display.="</tbody></table></div>
                                <div class='btns'>
                                    <button type='button' id='back_to_dormlist'><i class='fas fa-arrow-left'></i> Back</button>
                                </div>";
            echo $data_to_display;
        }elseif (isset($_GET['get_dorm_list'])) {
            $current_dorm = $_GET['current_dorm'];
            // $select = "SELECT  `dorm_id`,`dorm_name`,`dorm_capacity` FROM `dorm_list` WHERE `dorm_id` != ?";
            $select = "SELECT  `dorm_id`,`dorm_name`,`dorm_capacity` FROM `dorm_list`";
            $stmt = $conn2->prepare($select);
            // $stmt->bind_param("s",$current_dorm);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $return_string = "<select class='form-control'  name='dorm_list_change' id='dorm_list_change'><option value='' hidden>Select Hostel..</option>";
                $xs = 0;
                while ($row = $result->fetch_assoc()) {
                    $xs++;
                    $return_string.="<option value='".$row['dorm_id']."'>".$row['dorm_name']." <small>(".($row['dorm_capacity']-getOccupancy($row['dorm_id'],$conn2)).")</small></option>";
                }
                $return_string.="</select>";
                if ($xs > 0) {
                    echo $return_string;
                }else {
                    echo "<p style='color:red;font-size:13px;font-weight:500;'>No other hostel</p>";
                }
            }
        }elseif(isset($_GET['display_incidents'])){
            $select = "SELECT discipline_incidents.*, student_data.*, ladybird_smis.user_tbl.* FROM `discipline_incidents` LEFT JOIN student_data ON student_data.adm_no = discipline_incidents.student_id LEFT JOIN ladybird_smis.user_tbl ON ladybird_smis.user_tbl.user_id = discipline_incidents.reported_by ORDER BY incident_id DESC;";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "<table class='table' id='incident_discipline_table'><thead><tr><th>No</th><th>Student Name</th><th>Incident</th><th>Reported By</th><th>Reported Date</th><th>Action</th></tr></thead><tbody>";
            if ($result) {
                $counter = 1;
                while ($row = $result->fetch_assoc()) {
                    $data_to_display.="
                    <tr>
                        <th>".$counter.". </th>
                        <th>".$row['first_name']." ".$row['second_name']."</th>
                        <th>".$row['incident_type']."</th>
                        <th>".$row['fullname']."</th>
                        <th>".date("D dS M Y", strtotime($row['date_reported']))."</th>
                        <th>Action</th>
                    </tr>";
                }
            }
            $data_to_display.="</tbody></table>";
            echo $data_to_display;
        }elseif (isset($_GET['change_student_dorm'])) {
            $student_id = $_GET['student_id'];
            $new_dorm_id = $_GET['new_dorm_id'];
            $current_dorm_id = $_GET['current_dorm_id'];
            $room_id = $_GET['room_id'];
            $select = "UPDATE `boarding_list` SET `dorm_id` = ?, `room_id` = ? WHERE `dorm_id` = ? AND `student_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ssss",$new_dorm_id, $room_id, $current_dorm_id, $student_id);
            if($stmt->execute()){
                $update = "UPDATE `student_data` SET `dormitory` = ? WHERE `adm_no` = ?";
                $stmt = $conn2->prepare($update);
                $stmt->bind_param("ss",$new_dorm_id,$student_id);
                if($stmt->execute()){
                    echo "<p class='errors' style='color:green;'>Changes done successfully!</p>";
                }else {
                    echo "<p class='errors' style='color:red;'>An error has occured during updating!</p>";
                }
            }else {
                echo "<p class='errors' style='color:red;'>An error has occured during updating!</p>";
            }
        }elseif (isset($_GET['delete_student_information'])) {
            $student_id = $_GET['student_id'];
            $dormitory_id = $_GET['dormitory_id'];
            $delete = "DELETE FROM `boarding_list` WHERE `student_id` = ? AND `dorm_id` = ?";
            $stmt = $conn2->prepare($delete);
            $stmt->bind_param("ss",$student_id,$dormitory_id);
            if($stmt->execute()){
                $update = "UPDATE `student_data` set `dormitory` = 'none', `boarding` = 'none' WHERE `adm_no` = ?";
                $stmt = $conn2->prepare($update);
                $stmt->bind_param("s",$student_id);
                if($stmt->execute()){
                    echo "<p class='errors' style='color:green;'>Changes done successfully!</p>";
                }else {
                    echo "<p class='errors' style='color:red;'>An error has occured during updating!</p>";
                }
            }else {
                echo "<p class='errors' style='color:red;'>An error has occured during updating!</p>";
            }
        }elseif (isset($_GET['un_assign_dormitory'])) {
            $student_id = $_GET['student_id'];
            $dormids = $_GET['dormids'];
            $delete = "DELETE FROM `boarding_list` WHERE `student_id` = ? AND `dorm_id` = ?";
            $stmt = $conn2->prepare($delete);
            $stmt->bind_param("ss",$student_id,$dormids);
            if($stmt->execute()){
                $update = "UPDATE `student_data` set `dormitory` = 'none', `boarding` = 'enroll' WHERE `adm_no` = ?";
                $stmt = $conn2->prepare($update);
                $stmt->bind_param("s",$student_id);
                if($stmt->execute()){
                    echo "<p class='errors' style='color:green;'>Changes done successfully!</p>";
                }else {
                    echo "<p class='errors' style='color:red;'>An error has occured during updating!</p>";
                }
            }else {
                echo "<p class='errors' style='color:red;'>An error has occured during updating!</p>";
            }
        }
    }

    function removeComma($string){
        if (strlen($string) > 1) {
            return substr($string,0,strlen($string)-1);
        }
        return $string;
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
    function getTeacherName($tr_id){
        $schoolcode = $_SESSION['schoolcode'];
        include("../../connections/conn1.php");
        $select = "SELECT `fullname`, `gender` FROM `user_tbl` WHERE `school_code` = ? AND `user_id` = ?";
        $stmt = $conn->prepare($select);
        $stmt->bind_param("ss",$schoolcode,$tr_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                if ($row['gender'] == "F") {
                    return "Mrs. ".$row['fullname'];
                }elseif($row['gender'] == "M") {
                    return "Mr. ".$row['fullname'];
                }
            }
        }
        return "Null";
    }
    function getOccupied($dorm_id,$conn2){
        $select = "SELECT COUNT(`dorm_id`) AS 'Dorm_count' FROM `boarding_list` WHERE  `deleted` = 0 AND `activated` = 1";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['Dorm_count'];
            }
        }
        return 0;
    }
    function getOccupancy($dorm_id,$conn2){
        $select = "SELECT COUNT(`dorm_id`) AS 'Dorm_count' FROM `boarding_list` WHERE `dorm_id` = ? AND `deleted` = 0 AND `activated` = 1";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$dorm_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['Dorm_count'];
            }
        }
        return 0;
    }
    function getDormitory($conn2,$object_id){
        $select = "SELECT `dorm_id`,`dorm_name`,`dorm_capacity` FROM `dorm_list` WHERE `activated` = 1 and `deleted` = 0";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res) {
            $string_return = "<select class='my_dorms_name'  name='".$object_id."' id='".$object_id."'><option value='' hidden>Select..</option>";
            $xdc = 0;
            while ($row = $res->fetch_assoc()) {
                $occupancy = $row['dorm_capacity']-getOccupancy($row['dorm_id'],$conn2);
                if ($occupancy > 0) {
                    $xdc++;
                    $string_return.="<option value='".$row['dorm_id']."'>".$row['dorm_name']. " - (<small>".$occupancy."</small>) </option>";
                }
            }
            $string_return.="</select>";
            if ($xdc > 0) {
                return $string_return;
            }else {
                return "<p style='color:red;font-size:12px;font-weight:600;'>All dormitories are occupied!</p>";
            }
        }
        return "<p style='color:red;font-size:12px;font-weight:600;'>No dormitories available!</p>";
    }
    function getStudentName($student_id,$conn2){
        $select = "SELECT `first_name`,`second_name`,`gender` FROM `student_data` WHERE `adm_no` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return explode(",",ucwords(strtolower($row['first_name']." ".$row['second_name'])).",".$row['gender']);
            }
        }
        return explode(",","Null,Unknown");
    }
    function getDormName($dorm_id,$conn2){
        $select = "SELECT `dorm_name` FROM `dorm_list` WHERE `dorm_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$dorm_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['dorm_name'];
            }
        }
        return "Null";
    }

    // get the course id when given the name
    function get_course_name($course_id, $conn2){
        // get all courses
        $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $course_levels = [];
        $result = $stmt->get_result();
        if($result){
            if($row = $result->fetch_assoc()){
                $course_levels = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
            }
        }

        foreach ($course_levels as $key => $value) {
            if(strtolower($course_id) == strtolower($value->id)){
                return $value->course_name;
            }
        }
        return "";
    }

    function isJson_report($string) {
        return ((is_string($string) &&
                (is_object(json_decode($string)) ||
                is_array(json_decode($string))))) ? true : false;
    }
?>