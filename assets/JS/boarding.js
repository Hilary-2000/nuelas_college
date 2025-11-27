
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
        // house_list_table
        if (cObj("house_list_table") != undefined && cObj("house_list_table") != null) {
            $(document).ready(function() {
                $('#house_list_table').DataTable();  // Just one line!
            });
        }
    });
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

function dorm_delete_func() {
    cObj("delete_the_hostel_window").classList.remove("hide");
    cObj("hostel_name_delete").innerText = hasJsonStructure(cObj("hostel_data_"+this.id.substr(12)).value) ? JSON.parse(cObj("hostel_data_"+this.id.substr(12)).value).dorm_name : "Unknown";
    cObj("hostel_id_delete").value = hasJsonStructure(cObj("hostel_data_"+this.id.substr(12)).value) ? JSON.parse(cObj("hostel_data_"+this.id.substr(12)).value).dorm_id : "0";
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
            element.addEventListener("click",changeDormitory);
        }

        if(cObj("hostel-occupancy") != null && cObj("hostel-occupancy") != null){
            $(document).ready(function() {
                $('#hostel-occupancy').DataTable();  // Just one line!
            });
        }
    });
}
function changeDormitory() {
    var identity = this.id;
    var dorm_id = identity.split("|")[0];
    var student_id = identity.split("|")[1];
    cObj("my_student_id").innerText = student_id;
    cObj("my_dorm_id").innerText = dorm_id;
    cObj("my_student_name").innerText = cObj("mystud"+student_id).innerText;
    var datapass = "?get_dorm_list=true&current_dorm="+dorm_id+"&student_ids="+student_id;
    sendData2("GET","boarding/boarding.php",datapass,cObj("dorms_lists"),cObj("dorm_list_monitor"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                //show window
                cObj("change_student_dorm").classList.remove("hide");
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
function changeTables() {
    //hide the window
    cObj("dormitory_list").classList.remove("hide");
    cObj("dorm_occupancy_details").classList.add("hide");    
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

        // set the table as datatable
        // student_list_enroll_boarding
        if (cObj("student_list_enroll_boarding") != undefined && cObj("student_list_enroll_boarding") != null) {
            $(document).ready(function() {
                $('#student_list_enroll_boarding').DataTable();  // Just one line!
            });
        }
    });
}
cObj("display_unenrolled").onclick = function () {
    var err = checkBlank("admission_number");
    if (err == 0) {
        cObj("err_handler_enroll").innerHTML = "";
        var datapass = "?get_enrolled_boarders=true&use_adm="+cObj("admission_number").value;
        sendData1("GET","boarding/boarding.php",datapass,cObj("unenrolled_student_list"));
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
                    //set listeners to the buttons
                    var elements = document.getElementsByClassName("save_boarder");
                    for (let index = 0; index < elements.length; index++) {
                        const element = elements[index];
                        element.addEventListener("click",saveBoarders)
                    }
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }else{
        cObj("err_handler_enroll").innerHTML = "<p style='color:red;font-size:12px;font-weight:600;'>Fill all fields with red border</p>";
    }
}
function saveBoarders() {
    //check for errors on the unselected options
    if(typeof(cObj("select"+this.id.substr(2))) != 'undefined' && cObj("select"+this.id.substr(2)) != null){
        var err = checkBlank("select"+this.id.substr(2));
        if (err == 0) {
            //get the dorm id and the students id and save the information in the boardings table
            var stud_id = this.id.substr(2);
            var dorm_id = cObj("select"+stud_id).value;
            var datapass = "?save_boarder_infor=true&boarder_id="+stud_id+"&house_id="+dorm_id;
            sendData1("GET","boarding/boarding.php",datapass,cObj("outer"+stud_id));
            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout==1200) {
                        stopInterval(ids);                        
                    }
                    if (cObj("loadings").classList.contains("hide")) {
                        cObj("display_all_present").click();
                        stopInterval(ids);
                    }
                }, 100);
            }, 200);
        }
    }
}
cObj("close_dorm_change_btn").onclick = function () {
    cObj("change_student_dorm").classList.add("hide");
}
cObj("change_student_close").onclick = function () {
    cObj("change_student_dorm").classList.add("hide");
}
cObj("change_dormitory_btn").onclick = function () {
    if(typeof(cObj("dorm_list_change")) != 'undefined' && cObj("dorm_list_change") != null) {
        var err = checkBlank("dorm_list_change");
        if (err == 0) {
            cObj("chage_dorms_err_handlers").innerHTML = "";
            //send data to the database
            var datapass = "?change_student_dorm=true&student_id="+cObj("my_student_id").innerText+"&new_dorm_id="+cObj("dorm_list_change").value+"&current_dorm_id="+cObj("my_dorm_id").innerText;
            sendData1("GET","boarding/boarding.php",datapass,cObj("chage_dorms_err_handlers"));
            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout==1200) {
                        stopInterval(ids);                        
                    }
                    if (cObj("loadings").classList.contains("hide")) {
                        cObj("chage_dorms_err_handlers").innerHTML = "";
                        cObj("change_student_dorm").classList.add("hide");
                        cObj("back_to_dormlist").click();
                        cObj("refresh_dorm_list").click();
                        stopInterval(ids);
                    }
                }, 100);
            }, 200);
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