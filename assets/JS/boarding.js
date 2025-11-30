
cObj("register_exams").onclick = function () {
    //get the teacher lists that are not present at the dorm list
    var datapass = "?get_dorm_captain=true";
    sendData2("GET","boarding/boarding.php",datapass,cObj("tr_list"),cObj("tr_lists"));
    cObj("dorm_registrations").classList.remove("hide");
}
cObj("close_dorm_reg_btn").onclick = function () {
    cObj("dorm_registrations").classList.add("hide");
}
cObj("close_dorm_reg").onclick = function () {
    cObj("dorm_registrations").classList.add("hide");
}
cObj("close_dorm_edit_btn").onclick = function () {
    cObj("dorm_edits").classList.add("hide");
}
cObj("close_dorm_edit").onclick = function () {
    cObj("dorm_edits").classList.add("hide");
}
cObj("add_dormitory").onclick = function () {
    var err = 0;
    err = checkBlank("dorm_name");
    err = checkBlank("dorm_capacity");
    if (err == 0) {
        cObj("add_dorm_err_handler").innerHTML = "";
        //send data to the database
        var datapass = "?add_dormitory=true&dorm_name="+encodeURIComponent(cObj("dorm_name").value)+"&dorm_capacity="+cObj("dorm_capacity").value+"&dorm_captain="+cObj("dorm_captain").value+"&bed_capacity="+cObj("dorm_bed_capacity").value+"&room_capacity="+cObj("dorm_room_count").value+"&matress_count="+cObj("matress_count").value+"&comment="+encodeURIComponent(cObj("hostel_comment").value);
        sendData1("GET","boarding/boarding.php",datapass,cObj("add_dorm_err_handler"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout==1200) {
                    stopInterval(ids);                        
                }
                if (cObj("loadings").classList.contains("hide")) {
                    //reset form
                    cObj("reg_dorm_form").reset();
                    //refesh
                    cObj("refresh_dorm_list").click();
                    //close window
                    cObj("dorm_registrations").classList.add("hide");
                    cObj("add_dorm_err_handler").innerHTML = "";
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }else{
        cObj("add_dorm_err_handler").innerHTML = "<p style='color:red;font-size:13px;font-weight:600;'>Fill the fields with a red border!</p>";
    }
}
cObj("refresh_dorm_list").onclick = function () {
    //get the dormitory list
    changeTables();
    var datapass = "?get_dormitory_list=true";
    sendData1("GET","boarding/boarding.php",datapass,cObj("dormitory_list"), function () {
        var dorm_edit = document.getElementsByClassName("dorm_edit");
        for (let index = 0; index < dorm_edit.length; index++) {
            const element = dorm_edit[index];
            element.addEventListener("click",dormEditListener);
        }
        var linked_occupancy = document.getElementsByClassName("linked_occupancy");
        for (let index = 0; index < linked_occupancy.length; index++) {
            const element = linked_occupancy[index];
            element.addEventListener("click",view_Occupancy)
        }
        var dorm_delete = document.getElementsByClassName("dorm_delete");
        for (let index = 0; index < dorm_delete.length; index++) {
            const element = dorm_delete[index];
            element.addEventListener("click",dorm_delete_func)
        }

        var room_mgt = document.getElementsByClassName("room_mgt");
        for (let index = 0; index < room_mgt.length; index++) {
            const element = room_mgt[index];
            element.addEventListener("click", room_mgt_func)
        }

        // house_list_table
        if (cObj("house_list_table") != undefined && cObj("house_list_table") != null) {
            $(document).ready(function() {
                $('#house_list_table').DataTable();  // Just one line!
            });
        }
    });
}

function room_mgt_func() {
    var datapass = "?display_rooms=true&hostel_id="+this.id.substr(9);
    var hostel_id = this.id.substr(9);
    cObj("hostel_id_room").value = hostel_id;
    sendData1("GET", "boarding/boarding.php", datapass, cObj("room_lists_and_mgmt"), function () {
        cObj("room_lists_window").classList.remove("hide");
        cObj("dormitory_list").classList.add("hide");
        cObj("hostel_name_for_room").innerText = cObj("dn"+hostel_id).innerText;

        // set listerner for the edit button
        var room_edit = document.getElementsByClassName("room_edit");
        for (let index = 0; index < room_edit.length; index++) {
            const element = room_edit[index];
            element.addEventListener("click", function () {
                var room_data = cObj("room_data_"+this.id.substr(10)).value;
                if (hasJsonStructure(room_data)) {
                    cObj("edit_hostel_room_window").classList.remove("hide");
                    var json_room_data = JSON.parse(room_data);
                    cObj("edit_room_name").value = json_room_data.room_name;
                    cObj("edit_room_capacity").value = json_room_data.room_capacity;
                    cObj("edit_room_comment").value = json_room_data.room_comment;
                    cObj("edit_room_id").value = json_room_data.room_id;
                }
            });
        }

        // set listerner to delete the room
        var delete_room = document.getElementsByClassName("delete_room");
        for (let index = 0; index < delete_room.length; index++) {
            const element = delete_room[index];
            element.addEventListener("click", function () {
                var room_data = cObj("room_data_"+this.id.substr(12)).value;
                if (hasJsonStructure(room_data)) {
                    cObj("delete_the_hostel_room_window").classList.remove("hide");
                    var json_room_data = JSON.parse(room_data);
                    cObj("hostel_room_name_delete").innerText = json_room_data.room_name;
                    cObj("hostel_room_id_delete").value = json_room_data.room_id;
                }
            });
        }
        
        $(document).ready(function() {
            $('#rooms_display_table').DataTable();  // Just one line!
        });
    });
}

cObj("no_delete_the_hostel_room").onclick = function () {
    cObj("delete_the_hostel_room_window").classList.add("hide");
}

cObj("yes_delete_the_hostel_room").onclick = function () {
    var datapass = "?delete_hostel_room=true&room_id="+valObj("hostel_room_id_delete");
    sendData1("GET", "boarding/boarding.php", datapass, cObj("error_handler_room_list"), function () {
        setTimeout(() => {
            cObj("error_handler_room_list").innerHTML = "";
        }, 2000);
        cObj("no_delete_the_hostel_room").click();
        var data = cObj("room_data_"+valObj("hostel_room_id_delete")).value;
        if (hasJsonStructure(data)) {
            var room_data = JSON.parse(data);
            cObj("room_mgt_"+room_data.hostel_id).click();
        }
    });
}

cObj("update_hostel_changes").onclick = function () {
    var datapass = "?update_hostel=true&hostel_name="+valObj("edit_room_name")+"&hostel_capacity="+valObj("edit_room_capacity")+"&room_comment="+valObj("edit_room_comment")+"&room_id="+valObj("edit_room_id");
    sendData1("GET","boarding/boarding.php", datapass, cObj("edit_room_details_error"), function () {
        cObj("edit_hostel_room_form").reset();
        cObj("close_hostel_edit_2").click();
        cObj("edit_room_details_error").innerText = "";

        var data = cObj("room_data_"+valObj("edit_room_id")).value;
        if (hasJsonStructure(data)) {
            var room_data = JSON.parse(data);
            cObj("room_mgt_"+room_data.hostel_id).click();
        }
    });
}

cObj("close_hostel_edit_2").onclick = function () {
    cObj("edit_hostel_room_window").classList.add("hide");
}

cObj("close_hostel_edit_1").onclick = function () {
    cObj("edit_hostel_room_window").classList.add("hide");
}

cObj("add_new_room").onclick = function () {
    cObj("add_hostel_room_window").classList.remove("hide");
}
cObj("close_add_hostel_room_1").onclick = function () {
    cObj("add_hostel_room_window").classList.add("hide");
}
cObj("close_add_hostel_room_2").onclick = function () {
    cObj("add_hostel_room_window").classList.add("hide");
}

cObj("room_addition_method").onchange = function () {
    if (this.value == "singular") {
        cObj("singular_room_addition").classList.remove("hide");
        cObj("multiple_room_addition").classList.add("hide");
    }else if (this.value == "multiple") {
        cObj("singular_room_addition").classList.add("hide");
        cObj("multiple_room_addition").classList.remove("hide");
    }else{
        cObj("singular_room_addition").classList.add("hide");
        cObj("multiple_room_addition").classList.add("hide");
    }
}

cObj("room_name_prefix").onkeyup = function () {
    if(cObj("room_number").value*1 >= 1){
        cObj("full_room_name").innerText = cObj("room_name_prefix").value+""+cObj("room_number").value+""+cObj("room_name_sufix").value+" <> "+(cObj("room_name_prefix").value+""+(((cObj("room_number").value*1)+(cObj("number_of_rooms").value*1))-1)+""+cObj("room_name_sufix").value);
    }
}
cObj("room_number").onkeyup = function () {
    if(cObj("room_number").value*1 >= 1){
        cObj("full_room_name").innerText = cObj("room_name_prefix").value+""+cObj("room_number").value+""+cObj("room_name_sufix").value+" <> "+(cObj("room_name_prefix").value+""+(((cObj("room_number").value*1)+(cObj("number_of_rooms").value*1))-1)+""+cObj("room_name_sufix").value);
    }
}
cObj("room_name_sufix").onkeyup = function () {
    if(cObj("room_number").value*1 >= 1){
        cObj("full_room_name").innerText = cObj("room_name_prefix").value+""+cObj("room_number").value+""+cObj("room_name_sufix").value+" <> "+(cObj("room_name_prefix").value+""+(((cObj("room_number").value*1)+(cObj("number_of_rooms").value*1))-1)+""+cObj("room_name_sufix").value);
    }
}

cObj("back_to_dormlist_from_rooms").onclick = function () {
    cObj("room_lists_window").classList.add("hide");
    cObj("dormitory_list").classList.remove("hide");
    cObj("refresh_dorm_list").click();
}

function dorm_delete_func() {
    cObj("delete_the_hostel_window").classList.remove("hide");
    cObj("hostel_name_delete").innerText = hasJsonStructure(cObj("hostel_data_"+this.id.substr(12)).value) ? JSON.parse(cObj("hostel_data_"+this.id.substr(12)).value).dorm_name : "Unknown";
    cObj("hostel_id_delete").value = hasJsonStructure(cObj("hostel_data_"+this.id.substr(12)).value) ? JSON.parse(cObj("hostel_data_"+this.id.substr(12)).value).dorm_id : "0";
}

cObj("add_rooms_submit").onclick = function () {
    var err = checkBlank("room_addition_method");
    if(err == 0){
        if(cObj("room_addition_method").value == "multiple"){
            err += checkBlank("number_of_rooms");
            err += checkBlank("room_number");
            err += checkBlank("multiple_room_capacity");
        }else{
            err += checkBlank("room_name");
            err += checkBlank("room_capacity");
        }

        if(cObj("room_number").value*1 > 1000){
            err++;
            cObj("edit_room_err_handler").innerHTML = "<p class='text-danger'>Room number can`t be more than 1000!</p>";
            redBorder(cObj("room_number"));
        }else{
            cObj("edit_room_err_handler").innerHTML = "";
            grayBorder(cObj("room_number"));
        }

        // error count
        if(err == 0){
            var datapass = "?add_new_room="+cObj("room_addition_method").value+"&room_number="+valObj("number_of_rooms")+"&room_prefix="+valObj("room_name_prefix")+"&room_name_number="+valObj("room_number")+"&room_sufix="+valObj("room_name_sufix")+"&multiple_room_capacity="+valObj("multiple_room_capacity")+"&room_name="+valObj("room_name")+"&room_capacity="+valObj("room_capacity")+"&room_comment="+valObj("room_comment")+"&hostel_id="+cObj("hostel_id_room").value;
            sendData1("GET","boarding/boarding.php", datapass, cObj("edit_room_err_handler"), function () {
                setTimeout(() => {
                    cObj("edit_room_err_handler").innerHTML = "";
                }, 2500);
                cObj("close_add_hostel_room_2").click();
                cObj("add_hostel_room_form").reset();
                cObj("room_mgt_"+cObj("hostel_id_room").value).click();
            });
        }
    }
}

cObj("no_delete_the_hostel").onclick = function () {
    cObj("delete_the_hostel_window").classList.add("hide");
}

cObj("yes_delete_the_hostel").onclick = function () {
    var datapass = "?delete_hostel=true&hostel_id="+cObj("hostel_id_delete").value;
    sendData1("GET", "boarding/boarding.php", datapass, cObj("dorm_list_messenger"), function () {
        cObj("no_delete_the_hostel").click();
        cObj("refresh_dorm_list").click();
        setTimeout(() => {
            cObj("dorm_list_messenger").innerText = "";
        }, 3000);
    });
}

function dormEditListener() {
    //view the examination information
    //set the values to the exam setting window
    var hostel_id = this.id.substr(4);
    var hostel_data = cObj("hostel_data_"+hostel_id).value;
    if (hasJsonStructure(hostel_data)) {
        var json_hostel_data = JSON.parse(hostel_data);
        cObj("dormitory_id").innerText = hostel_id;
        cObj("dorm_name_edit").value = json_hostel_data.dorm_name;
        cObj("cap_name").innerText = json_hostel_data.dorm_captain_name;
        cObj("dorm_capacity_edit").value = json_hostel_data.dorm_capacity;
        cObj("dorm_bed_capacity_edit").value = json_hostel_data.bed_capacity;
        cObj("matress_count_edit").value = json_hostel_data.matress_count;
        cObj("dorm_room_count_edit").value = json_hostel_data.cube_count;
        cObj("hostel_comment_edit").value = json_hostel_data.comment;

        // 
        var prefic_id = "dorm_captain_edit";

        //get existing dorm list
        var datapass = "?get_dorm_captain=true&class_name="+prefic_id+"&selected_captain="+json_hostel_data.dorm_captain;
        sendData2("GET","boarding/boarding.php",datapass,cObj("teacher_list"),cObj("teacher_lists"), function () {
            //show the window
            cObj("dorm_edits").classList.remove("hide");
        });
    }
}
function view_Occupancy() {
    //get the dorm id
    var dorm_id = this.id.substr(8);
    var datapass = "?get_occupancy=true&dormitory_id="+dorm_id;
    sendData1("GET","boarding/boarding.php",datapass,cObj("dorm_occupancy_details"), function () {
        //hide the window
        cObj("dormitory_list").classList.add("hide");
        cObj("dorm_occupancy_details").classList.remove("hide");
        if(typeof(cObj("back_to_dormlist")) != 'undefined' && cObj("back_to_dormlist") != null){
            cObj("back_to_dormlist").addEventListener("click",changeTables);
        }
        var change_dormitory = document.getElementsByClassName("change_dormitory");
        for (let index = 0; index < change_dormitory.length; index++) {
            const element = change_dormitory[index];
            element.addEventListener("click",change_hostel_student);
        }

        if(cObj("hostel-occupancy") != null && cObj("hostel-occupancy") != null){
            $(document).ready(function() {
                $('#hostel-occupancy').DataTable();  // Just one line!
            });
        }
    });
}
function changeTables() {
    //hide the window
    cObj("dormitory_list").classList.remove("hide");
    cObj("dorm_occupancy_details").classList.add("hide");
    cObj("room_lists_window").classList.add("hide");
    cObj("refresh_dorm_list").click();
}
cObj("update_dormitory").onclick = function () {
    //check for errors 
    var err = 0;
    err+=checkBlank("dorm_name_edit");
    err+=checkBlank("dorm_capacity_edit");
    if (err == 0) {
        cObj("edit_dorm_err_handler").innerHTML = "";
        var datapass = "?change_dorm_data=true&dorm_name="+encodeURIComponent(cObj("dorm_name_edit").value)+"&dorm_capacity="+cObj("dorm_capacity_edit").value+"&dorm_id="+cObj("dormitory_id").innerText+"&bed_capacity="+cObj("dorm_bed_capacity_edit").value+"&room_capacity="+cObj("dorm_room_count_edit").value+"&matress_count="+cObj("matress_count_edit").value+"&comment="+encodeURIComponent(cObj("hostel_comment_edit").value);
        if(checkBlank("dorm_captain_edit") == 0){
            datapass = "?change_dorm_data=true&dorm_name="+encodeURIComponent(cObj("dorm_name_edit").value)+"&dorm_capacity="+cObj("dorm_capacity_edit").value+"&dorm_captain="+cObj("dorm_captain_edit").value+"&dorm_id="+cObj("dormitory_id").innerText+"&bed_capacity="+cObj("dorm_bed_capacity_edit").value+"&room_capacity="+cObj("dorm_room_count_edit").value+"&matress_count="+cObj("matress_count_edit").value+"&comment="+encodeURIComponent(cObj("hostel_comment_edit").value);
        }
        sendData1("GET","boarding/boarding.php",datapass,cObj("edit_dorm_err_handler"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout==1200) {
                    stopInterval(ids);                        
                }
                if (cObj("loadings").classList.contains("hide")) {
                    //show the window
                    cObj("dorm_edits").classList.add("hide");
                    cObj("edit_dorm_form").reset();
                    cObj("refresh_dorm_list").click();
                    cObj("edit_dorm_err_handler").innerHTML = "";
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }else{
        cObj("edit_dorm_err_handler").innerHTML = "<p style='color:red;font-size:13px;font-weight:600;'>Fill the fields with a red border!</p>";
    }
}
cObj("un_assign_captain_btn").onclick = function () {
    var datapass = "?un_assign_dorm="+cObj("dormitory_id").innerText;
    sendData1("GET","boarding/boarding.php",datapass,cObj("edit_dorm_err_handler"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                //show the window
                cObj("dorm_edits").classList.add("hide");
                cObj("edit_dorm_form").reset();
                cObj("refresh_dorm_list").click();
                cObj("edit_dorm_err_handler").innerHTML = "";
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

cObj("display_all_present").onclick = function () {
    var datapass = "?get_enrolled_boarders=true";
    sendData1("GET","boarding/boarding.php",datapass,cObj("unenrolled_student_list"), function () {
        var elements = document.getElementsByClassName("save_boarder");
        for (let index = 0; index < elements.length; index++) {
            const element = elements[index];
            element.addEventListener("click",saveBoarders)
        }

        var my_dorms_name = document.getElementsByClassName("my_dorms_name");
        for (let index = 0; index < my_dorms_name.length; index++) {
            const element = my_dorms_name[index];
            element.addEventListener("change", displayRooms);
        }

        // set the table as datatable
        // student_list_enroll_boarding
        if (cObj("student_list_enroll_boarding") != undefined && cObj("student_list_enroll_boarding") != null) {
            $(document).ready(function() {
                $('#student_list_enroll_boarding').DataTable();  // Just one line!
            });
        }
    });
}

function displayRooms() {
    var dropdown_id = "room_dropdown_"+this.id.substr(6);
    var datapass = "?display_rooms_dropdown=true&hostel_id="+this.value+"&dropdown_id="+dropdown_id;
    sendData1("GET","boarding/boarding.php", datapass, cObj("room_dropdown_holder_"+this.id.substr(6)), function () {
        if (cObj(dropdown_id) != undefined) {
            $('#'+dropdown_id).select2();
        }
    });
}
cObj("display_unenrolled").onclick = function () {
    var err = checkBlank("admission_number");
    if (err == 0) {
        cObj("err_handler_enroll").innerHTML = "";
        var datapass = "?get_enrolled_boarders=true&use_adm="+cObj("admission_number").value;
        sendData1("GET","boarding/boarding.php",datapass,cObj("unenrolled_student_list"), function () {
            //show the window
            //set listeners to the buttons
            var elements = document.getElementsByClassName("save_boarder");
            for (let index = 0; index < elements.length; index++) {
                const element = elements[index];
                element.addEventListener("click",saveBoarders)
            }
            
        });
    }else{
        cObj("err_handler_enroll").innerHTML = "<p style='color:red;font-size:12px;font-weight:600;'>Fill all fields with red border</p>";
    }
}
function saveBoarders() {
    //check for errors on the unselected options
    if(typeof(cObj("select"+this.id.substr(2))) != 'undefined' && cObj("select"+this.id.substr(2)) != null){
        var err = checkBlank("select"+this.id.substr(2));
        err += checkBlank("room_dropdown_"+this.id.substr(2));
        if (err == 0) {
            //get the dorm id and the students id and save the information in the boardings table
            var stud_id = this.id.substr(2);
            var dorm_id = cObj("select"+stud_id).value;
            var datapass = "?save_boarder_infor=true&boarder_id="+stud_id+"&house_id="+dorm_id+"&room_number="+valObj("room_dropdown_"+this.id.substr(2));
            sendData1("GET", "boarding/boarding.php", datapass, cObj("outer"+stud_id), function () {
                cObj("display_all_present").click();
            });
        }
    }
}
cObj("close_dorm_change_btn").onclick = function () {
    cObj("change_student_dorm").classList.add("hide");
    cObj("hostel_rooms").innerHTML = '<span class="text-danger">Select hostel to display rooms!</span>';
}
cObj("change_student_close").onclick = function () {
    cObj("change_student_dorm").classList.add("hide");
    cObj("hostel_rooms").innerHTML = '<span class="text-danger">Select hostel to display rooms!</span>';
}
cObj("change_dormitory_btn").onclick = function () {
    if(typeof(cObj("dorm_list_change")) != 'undefined' && cObj("dorm_list_change") != null) {
        var err = checkBlank("dorm_list_change");
        if (err == 0) {
            cObj("chage_dorms_err_handlers").innerHTML = "";
            //send data to the database
            var datapass = "?change_student_dorm=true&student_id="+cObj("my_student_id").innerText+"&new_dorm_id="+cObj("dorm_list_change").value+"&current_dorm_id="+cObj("my_dorm_id").innerText+"&room_id="+valObj("room_change_update");
            sendData1("GET","boarding/boarding.php",datapass,cObj("chage_dorms_err_handlers"), function () {
                cObj("chage_dorms_err_handlers").innerHTML = "";
                cObj("change_student_dorm").classList.add("hide");
                cObj(cObj("after_request_action").value).click();
                // cObj("occupied"+cObj("my_dorm_id").innerText).click();
                cObj("hostel_rooms").innerHTML = '<span class="text-danger">Select hostel to display rooms!</span>';
            });
        }else{
            cObj("chage_dorms_err_handlers").innerHTML = "<p class ='errors' style='color:red;'>No house is selected for the students!</p>";
        }
    }else{
        cObj("chage_dorms_err_handlers").innerHTML = "<p class ='errors' style='color:red;'>No house is selected for the students!</p>";
    }
}
cObj("un_assign_boarder_btn").onclick = function () {
    //get the student admission number and the dormitory number
    var studentid = cObj("my_student_id").innerText;
    var dorm_id = cObj("my_dorm_id").innerText;
    var datapass = "?delete_student_information=true&student_id="+studentid+"&dormitory_id="+dorm_id;
    sendData1("GET","boarding/boarding.php",datapass,cObj("change_dorm_err_handler"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                changeTables();
                cObj("refresh_dorm_list").click();
                cObj("change_student_dorm").classList.add("hide");
                cObj("change_dorm_err_handler").innerHTML = "";
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
cObj("un_assign_dorm_btn").onclick = function () {
    //delete the student from the dorm list and update the student data to enroll
    var studentid = cObj("my_student_id").innerText;
    var dorm_id = cObj("my_dorm_id").innerText;
    var datapass = "?un_assign_dormitory=true&student_id="+studentid+"&dormids="+dorm_id;
    sendData1("GET","boarding/boarding.php",datapass,cObj("change_dorm_err_handler"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                changeTables();
                cObj("refresh_dorm_list").click();
                cObj("change_student_dorm").classList.add("hide");
                cObj("change_dorm_err_handler").innerHTML = "";
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

cObj("display_all_students_present").onclick = function () {
    var datapass = "?get_boarding_students=true";
    sendData1("GET", "boarding/boarding.php", datapass, cObj("student_enrolled_list"), function () {
        
        var view_boarder_profile = document.getElementsByClassName("view_boarder_profile");
        for (let index = 0; index < view_boarder_profile.length; index++) {
            const element = view_boarder_profile[index];
            element.addEventListener("click", show_boarder_profile);
        }
        var change_hostel = document.getElementsByClassName("change_hostel");
        for (let index = 0; index < change_hostel.length; index++) {
            const element = change_hostel[index];
            element.addEventListener("click", change_hostel_student);
        }

        if (cObj("boarding_students_list") != undefined && cObj("boarding_students_list") != null) {
            $(document).ready(function() {
                $('#boarding_students_list').DataTable();  // Just one line!
            });
        }
    });
}

function show_boarder_profile() {
    var boarding_data = cObj("boarding_data_"+this.id.substr(21)).value;
    if (hasJsonStructure(boarding_data)) {
        var json_boarding = JSON.parse(boarding_data);
        cObj("boarder-fullname").value = json_boarding.first_name+" "+json_boarding.second_name;
        cObj("boarder-reg-no").value = json_boarding.adm_no;
        cObj("boarder-hostel-residence").value = json_boarding.dorm_name;
        cObj("boarder-room-number").value = json_boarding.room_name;
        cObj("boarder-reg-date").value = json_boarding.date_of_enrollment;
        cObj("boarder-course-level").value = json_boarding.stud_class;
        cObj("boarder-course-name").value = json_boarding.course_name;

        // show boarder profile
        cObj("show_boarder_profile_modal").classList.remove("hide");
        // get the boarding fees.
        var datapass = "?get_boarding_fees=true&admission_no="+json_boarding.adm_no;
        sendData1("GET", "boarding/boarding.php", datapass, cObj("boarding_fees_holder_modal"), function () {
            cObj("boarder-fees").value = cObj("boarding_fees_holder_modal").innerText;
        });
    }
}

cObj("close_show_boarder_profile_modal").onclick = function () {
    cObj("show_boarder_profile_modal").classList.add("hide");
}

cObj("close_show_boarder_profile_modal_1").onclick = function () {
    cObj("show_boarder_profile_modal").classList.add("hide");
}

function change_hostel_student() {
    var boarding_data = "";
    if(this.id.substr(0,16) == "change_dormitory"){
        var boarding_data = cObj("dorm_data_"+this.id.substr(17)).value;
        cObj("after_request_action").value = "occupied"+this.dataset.hostelId;
    }else{
        var boarding_data = cObj("boarding_data_"+this.id.substr(14)).value;
        cObj("after_request_action").value = "display_all_students_present";
    }
    if (hasJsonStructure(boarding_data)) {
        var json_boarding = JSON.parse(boarding_data);
        var dorm_id = json_boarding.dorm_id;
        var student_id = json_boarding.adm_no;
        cObj("my_student_id").innerText = student_id;
        cObj("my_dorm_id").innerText = dorm_id;
        cObj("my_student_name").innerText = json_boarding.first_name+" "+json_boarding.second_name;
        var datapass = "?get_dorm_list=true&current_dorm="+dorm_id+"&student_ids="+student_id;
        sendData2("GET","boarding/boarding.php",datapass,cObj("dorms_lists"),cObj("dorm_list_monitor"), function () {
            //show window
            cObj("change_student_dorm").classList.remove("hide");
            cObj("dorm_list_change").addEventListener("change", function () {
                var dropdown_id = "room_change_update";
                var datapass = "?display_rooms_dropdown=true&hostel_id="+this.value+"&dropdown_id="+dropdown_id;
                sendData1("GET","boarding/boarding.php", datapass, cObj("hostel_rooms"), function () {
                    if (cObj(dropdown_id) != undefined) {
                        $('#'+dropdown_id).select2();
                    }
                });
            });
        });
    }
}

cObj("more_action_hostels").onclick = function () {
    cObj("more_actions_window").classList.toggle("hide");
}

cObj("discipline_tab").onclick = display_incidents;

function display_incidents() {
    var datapass = "?display_incidents=true";
    sendData1("GET","boarding/boarding.php", datapass, cObj("incident_table_holder"), function () {
        $(document).ready(function() {
            $('#incident_discipline_table').DataTable();  // Just one line!
        });
    });
}

cObj("report_new_incidents").onclick = function () {
    cObj("record_new_incident_modal").classList.remove("hide");
}

cObj("close_new_incident_modal_1").onclick = function () {
    cObj("record_new_incident_modal").classList.add("hide");
}

cObj("close_new_incident_modal_2").onclick = function () {
    cObj("record_new_incident_modal").classList.add("hide");
}