cObj("select_recipients1").onchange = function () {
    if (cObj("sms_information").classList.contains("hide")) {
        cObj("sms_information").classList.remove("hide");
    }
    if (this.value == "phone_no") {
        cObj("enter_phone").classList.remove("hide");
        cObj("select_tr").classList.add("hide");
    } else if (this.value == "my_staff") {
        cObj("enter_phone").classList.add("hide");
        cObj("select_tr").classList.remove("hide");
        //get the staff lists
        if (cObj("select_staff_sms") == null || cObj("select_staff_sms") == "undefined") {
            var datapass = "?getMyStaff=true";
            sendData1("GET", "sms/sms.php", datapass, cObj("my_staff_info"));
        }
    }
}

cObj("email_recipient").onchange = function () {
    var value = this.value;
    if (value == "email") {
        cObj("select_tr_email").classList.add("hide");
        cObj("enter_client_email_addr").classList.remove("hide");
    } else if (value == "my_staff") {
        cObj("select_tr_email").classList.remove("hide");
        cObj("enter_client_email_addr").classList.add("hide");

        // get staff data
        var datapass = "?getStaffMailData=true";
        sendData1("GET", "sms/sms.php", datapass, cObj("my_staff_emails"));
    }
}

cObj("send_email_button").onclick = function () {
    var err = 0;
    var myContent = CKEDITOR.instances['email_messages'].getData();
    console.log(myContent);
    err += checkBlank("email_recipient");
    if (err == 0) {
        cObj("email_send_errors").innerHTML = "";
        var option = valObj("email_recipient");
        if (option == "email") {
            err = 0;
            err += checkBlank("staff_email_addressess");
            err += myContent.length > 0 ? 0 : 1;
            err += checkBlank("email_header");
            if (err == 0) {
                cObj("email_send_errors").innerHTML = "";
                var datapass = "send_mail_to=" + valObj("staff_email_addressess") + "&cc=" + valObj("carbon_copy1") + "&bcc=" + valObj("blind_carbon_copy1") + "&message=" + encodeURIComponent(myContent) + "&email_header=" + valObj("email_header");
                sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("email_send_errors"), cObj("load_email_sending"), function () {
                    CKEDITOR.instances['email_messages'].setData('');
                    cObj("staff_email_addressess").value = "";
                    cObj("carbon_copy1").value = "";
                    cObj("blind_carbon_copy1").value = "";
                    cObj("email_header").value = "";
                    setTimeout(() => {
                        cObj("email_send_errors").innerHTML = "";
                    }, 4000);
                });
            } else {
                cObj("email_send_errors").innerHTML = "<p class='text-danger'>Please ensure that all the neccessary fields are filled!</p>";
            }
        } else if (option == "my_staff") {
            err = 0;
            err += checkBlank("select_staff_emails");
            err += myContent.length > 0 ? 0 : 1;
            err += checkBlank("email_header");
            if (err == 0) {
                cObj("email_send_errors").innerHTML = "";
                var datapass = "send_mail_to=" + valObj("select_staff_emails") + "&cc=" + valObj("carbon_copy1") + "&bcc=" + valObj("blind_carbon_copy1") + "&message=" + encodeURIComponent(myContent) + "&email_header=" + valObj("email_header");
                sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("email_send_errors"), cObj("load_email_sending"), function () {
                    CKEDITOR.instances['email_messages'].setData('');
                    // cObj("select_staff_emails").value = "";
                    cObj("carbon_copy1").value = "";
                    cObj("blind_carbon_copy1").value = "";
                    cObj("email_header").value = "";
                    setTimeout(() => {
                        cObj("email_send_errors").innerHTML = "";
                    }, 4000);
                });
            } else {
                cObj("email_send_errors").innerHTML = "<p class='text-danger'>Please ensure that all the neccessary fields are filled!</p>";
            }
        }
    } else {
        cObj("email_send_errors").innerHTML = "<p class='text-danger'>Select an option before proceeding</p>";
    }
}

cObj("send_sms_btns").onclick = function () {
    //check if an option is selected
    var err = checkBlank("select_recipients1");
    if (err == 0) {
        //check whats selected
        var select = cObj("select_recipients1").value;
        if (select == "phone_no") {
            err += checkBlank("staff_phones");
            err += checkBlank("text_message");
            if (err == 0) {
                cObj("out_put22").innerHTML = "";
                //send data to the database
                var datapass = "?send_sms=true&phone_no=" + valObj("staff_phones") + "&message=" + encodeURIComponent(valObj("text_message"));
                sendData1("GET", "sms/sms.php", datapass, cObj("out_put22"), function () {
                    cObj("text_message").value = "";
                    cObj("staff_phones").value = "";
                    setTimeout(() => {
                        cObj("out_put22").innerHTML = "";
                    }, 3000);
                });
            } else {
                cObj("out_put22").innerHTML = "<p class='red_notice'>Please fill all field covered with a red border</p>";
            }
        } else if (select == "my_staff") {
            err += checkBlank("select_staff_sms");
            err += checkBlank("text_message");
            if (err == 0) {
                cObj("out_put22").innerHTML = "";
                //send data to the database
                var datapass = "?send_sms=true&phone_no=" + valObj("select_staff_sms") + "&message=" + encodeURIComponent(valObj("text_message"));
                sendData1("GET", "sms/sms.php", datapass, cObj("out_put22"), function () {
                    cObj("text_message").value = "";
                    cObj("select_staff_sms").children[0].selected = true;
                    setTimeout(() => {
                        cObj("out_put22").innerHTML = "";
                    }, 3000);
                });
            } else {
                cObj("out_put22").innerHTML = "<p class='red_notice'>Please fill all field covered with a red border</p>";
            }
        }
    } else {
        cObj("out_put22").innerHTML = "<p class='red_notice'>Please fill all field covered with a red border</p>";
    }
}
cObj("text_message").onkeyup = function () {
    cObj("char_count").innerText = this.value.length;
}
cObj("select_recipients2").onchange = function () {
    if (this.value == "my_staff") {
        cObj("students_parents").classList.add("hide");
        cObj("staffs_list_ms").classList.remove("hide");
        //get my staff information
        getStaffLists1();
        cObj("parent_selections").classList.add("hide");
        cObj("message_tags_window").classList.add("d-none");
    } else if (this.value == "parents") {
        cObj("students_parents").classList.remove("hide");
        cObj("staffs_list_ms").classList.add("hide");
        cObj("parent_selections").classList.remove("hide");
        cObj("message_tags_window").classList.remove("d-none");
        getStudentsParent();
    }
}
function getStaffLists1() {
    //get staff lists
    var datapass = "?mystaff_list=true";
    sendData1("GET", "sms/sms.php", datapass, cObj("staff_my_lists"));
}
function getStudentsParent() {
    // Load course level dropdown
    var datapass = "?parents_lists=true";
    sendData2("GET", "sms/sms.php", datapass, cObj("cl_list_msg"), cObj("loading_my_sms_here"), function () {
        if (cObj("my-class") != null) {
            cObj("my-class").classList.add("w-100");
            cObj("my-class").addEventListener("change", getCourseListSMS);
        }
    });
    // Load total student count
    sendData1("GET", "sms/sms.php", "?all_parents=true", cObj("all_parents"));
    // Load filter options (branches, intake years/months)
    sendData1("GET", "sms/sms.php", "?get_broadcast_filter_options=true", cObj("broadcast_filter_options_holder"), function () {
        var holder = cObj("broadcast_filter_options_holder");
        if (!holder || !holder.innerText) return;
        try {
            var opts = JSON.parse(holder.innerText);
            var branchSel = cObj("filter_branch");
            if (branchSel && opts.branches) {
                opts.branches.forEach(function (b) {
                    var o = document.createElement("option");
                    o.value = b.id;
                    o.text = b.name;
                    branchSel.appendChild(o);
                });
            }
            var yearSel = cObj("filter_intake_year");
            if (yearSel && opts.intake_years) {
                opts.intake_years.forEach(function (y) {
                    var o = document.createElement("option");
                    o.value = y; o.text = y;
                    yearSel.appendChild(o);
                });
            }
            var monthSel = cObj("filter_intake_month");
            if (monthSel && opts.intake_months) {
                opts.intake_months.forEach(function (m) {
                    var o = document.createElement("option");
                    o.value = m; o.text = m;
                    monthSel.appendChild(o);
                });
            }
        } catch (e) {}
    });
    // Wire filter panel controls
    initBroadcastFilterPanel();
}

function initBroadcastFilterPanel() {
    // Panel toggle
    var toggle = cObj("filter_panel_toggle");
    if (toggle && !toggle._wired) {
        toggle._wired = true;
        toggle.addEventListener("click", function () {
            var body = cObj("filter_panel_body");
            var chevron = cObj("filter_panel_chevron");
            if (body.classList.contains("hide")) {
                body.classList.remove("hide");
                chevron.classList.replace("fa-chevron-down", "fa-chevron-up");
            } else {
                body.classList.add("hide");
                chevron.classList.replace("fa-chevron-up", "fa-chevron-down");
            }
        });
    }
    // Apply Filters button
    var applyBtn = cObj("broadcast_apply_filters");
    if (applyBtn && !applyBtn._wired) {
        applyBtn._wired = true;
        applyBtn.addEventListener("click", function () {
            getParentsList();
        });
    }
    // Clear All button
    var clearBtn = cObj("broadcast_clear_filters");
    if (clearBtn && !clearBtn._wired) {
        clearBtn._wired = true;
        clearBtn.addEventListener("click", function () {
            clearAllBroadcastFilters();
        });
    }
    // Select All / Deselect All buttons
    var selAllBtn = cObj("broadcast_select_all_btn");
    if (selAllBtn && !selAllBtn._wired) {
        selAllBtn._wired = true;
        selAllBtn.addEventListener("click", function () { selectVisibleStudents(); });
    }
    var deselAllBtn = cObj("broadcast_deselect_all_btn");
    if (deselAllBtn && !deselAllBtn._wired) {
        deselAllBtn._wired = true;
        deselAllBtn.addEventListener("click", function () { deselectVisibleStudents(); });
    }
    // Live balance filter
    ["filter_balance_min", "filter_balance_max"].forEach(function (id) {
        var el = cObj(id);
        if (el && !el._wired) {
            el._wired = true;
            el.addEventListener("keyup", applyClientFilters);
            el.addEventListener("change", applyClientFilters);
        }
    });
    // Track active filter count on any filter change
    document.querySelectorAll('input[name="filter_status"], input[name="filter_gender"]').forEach(function (el) {
        if (!el._wired) {
            el._wired = true;
            el.addEventListener("change", updateActiveFilterCount);
        }
    });
    ["filter_branch","filter_intake_year","filter_intake_month","filter_module_end_date"].forEach(function (id) {
        var el = cObj(id);
        if (el && !el._wired) {
            el._wired = true;
            el.addEventListener("change", updateActiveFilterCount);
        }
    });
}

function updateActiveFilterCount() {
    var count = 0;
    var status = document.querySelector('input[name="filter_status"]:checked');
    if (status && status.value != 'all') count++;
    var gender = document.querySelector('input[name="filter_gender"]:checked');
    if (gender && gender.value != 'all') count++;
    if (cObj("my-class") && cObj("my-class").value && cObj("my-class").value != '') count++;
    if (cObj("course_list_sms") && cObj("course_list_sms").value && cObj("course_list_sms").value != '') count++;
    if (cObj("filter_branch") && cObj("filter_branch").value != 'all') count++;
    if (cObj("filter_intake_year") && cObj("filter_intake_year").value != 'all') count++;
    if (cObj("filter_intake_month") && cObj("filter_intake_month").value != 'all') count++;
    if (cObj("filter_module_end_date") && cObj("filter_module_end_date").value) count++;
    if (cObj("filter_balance_min") && cObj("filter_balance_min").value) count++;
    if (cObj("filter_balance_max") && cObj("filter_balance_max").value) count++;
    var badge = cObj("active_filter_count");
    if (badge) {
        if (count > 0) {
            badge.innerText = count + " active";
            badge.style.display = '';
        } else {
            badge.style.display = 'none';
        }
    }
}

function clearAllBroadcastFilters() {
    var fsAll = cObj("fs_all");
    if (fsAll) fsAll.checked = true;
    var fgAll = cObj("fg_all");
    if (fgAll) fgAll.checked = true;
    if (cObj("my-class")) cObj("my-class").selectedIndex = 0;
    cObj("course_list_sms_holder").innerHTML = '<select class="form-control form-control-sm w-100" disabled><option>Select a course level first...</option></select>';
    if (cObj("filter_branch")) cObj("filter_branch").selectedIndex = 0;
    if (cObj("filter_intake_year")) cObj("filter_intake_year").selectedIndex = 0;
    if (cObj("filter_intake_month")) cObj("filter_intake_month").selectedIndex = 0;
    if (cObj("filter_module_end_date")) cObj("filter_module_end_date").value = "";
    if (cObj("filter_balance_min")) cObj("filter_balance_min").value = "";
    if (cObj("filter_balance_max")) cObj("filter_balance_max").value = "";
    updateActiveFilterCount();
}

function applyClientFilters() {
    var keyword = cObj("search_student_sms") ? cObj("search_student_sms").value.toLowerCase() : "";
    var balMin = (cObj("filter_balance_min") && cObj("filter_balance_min").value !== "") ? parseFloat(cObj("filter_balance_min").value) : null;
    var balMax = (cObj("filter_balance_max") && cObj("filter_balance_max").value !== "") ? parseFloat(cObj("filter_balance_max").value) : null;
    var rows = document.getElementsByClassName("hide_students");
    var visible = 0;
    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var nameEl = row.querySelector(".students_sms_names");
        var nameText = nameEl ? nameEl.innerText.toLowerCase() : "";
        var balance = parseFloat(row.getAttribute("data-balance")) || 0;
        var showSearch = keyword.length === 0 || nameText.includes(keyword);
        var showBalMin = balMin === null || balance >= balMin;
        var showBalMax = balMax === null || balance <= balMax;
        if (showSearch && showBalMin && showBalMax) {
            row.classList.remove("d-none");
            visible++;
        } else {
            row.classList.add("d-none");
        }
    }
    if (cObj("filtered_students_count")) cObj("filtered_students_count").innerText = visible;
    if (cObj("filtered_students_count2")) cObj("filtered_students_count2").innerText = visible;
}

function selectVisibleStudents() {
    var rows = document.getElementsByClassName("hide_students");
    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        if (!row.classList.contains("d-none")) {
            var cb = row.querySelector(".student-class-par");
            if (cb && !cb.checked) {
                cb.checked = true;
                addAdmNo(cb.id.substr(3));
            }
        }
    }
    updateBroadcastSelectionCount();
    syncSelectAllCheckbox();
}

function deselectVisibleStudents() {
    var rows = document.getElementsByClassName("hide_students");
    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        if (!row.classList.contains("d-none")) {
            var cb = row.querySelector(".student-class-par");
            if (cb && cb.checked) {
                cb.checked = false;
                removeAdmNo(cb.id.substr(3));
            }
        }
    }
    updateBroadcastSelectionCount();
    syncSelectAllCheckbox();
}

function updateBroadcastSelectionCount() {
    var selected = cObj("seleceted_class") ? cObj("seleceted_class").innerText : "";
    if (selected && selected.length > 0) {
        var parts = selected.split(",").filter(function(s) { return s.length > 0; });
        cObj("excempt_list").innerText = parts.length;
    } else {
        cObj("excempt_list").innerText = 0;
    }
}

function syncSelectAllCheckbox() {
    var s123 = cObj("staff123s");
    if (!s123) return;
    var cbs = document.getElementsByClassName("student-class-par");
    var checked = 0;
    for (var i = 0; i < cbs.length; i++) { if (cbs[i].checked) checked++; }
    if (checked === 0) { s123.checked = false; s123.indeterminate = false; }
    else if (checked === cbs.length) { s123.checked = true; s123.indeterminate = false; }
    else { s123.checked = false; s123.indeterminate = true; }
}

function getCourseListSMS() {
    var datapass = "?get_course_list=true&course_level=" + this.value + "&object_id=course_list_sms";
    sendData2("GET", "administration/admissions.php", datapass, cObj("course_list_sms_holder"), cObj("loadings"), function () {
        if (cObj("course_list_sms") != null) {
            cObj("course_list_sms").classList.add("w-100");
        }
    });
    updateActiveFilterCount();
}

function getParentsList() {
    var classVal  = (cObj("my-class") && cObj("my-class").value) ? cObj("my-class").value : "all";
    var courseVal = (cObj("course_list_sms") && cObj("course_list_sms").value) ? cObj("course_list_sms").value : "";
    var status    = document.querySelector('input[name="filter_status"]:checked');
    var gender    = document.querySelector('input[name="filter_gender"]:checked');
    var datapass  = "?get_parents_list=" + encodeURIComponent(classVal)
        + "&course_selected="         + encodeURIComponent(courseVal)
        + "&filter_status="           + (status ? status.value : "all")
        + "&filter_gender="           + (gender ? gender.value : "all")
        + "&filter_branch="           + (cObj("filter_branch") ? cObj("filter_branch").value : "all")
        + "&filter_intake_year="      + (cObj("filter_intake_year") ? cObj("filter_intake_year").value : "all")
        + "&filter_intake_month="     + (cObj("filter_intake_month") ? cObj("filter_intake_month").value : "all")
        + "&filter_module_end_date="  + (cObj("filter_module_end_date") ? cObj("filter_module_end_date").value : "")
        + "&filter_balance_min="      + (cObj("filter_balance_min") ? cObj("filter_balance_min").value : "")
        + "&filter_balance_max="      + (cObj("filter_balance_max") ? cObj("filter_balance_max").value : "");

    sendData2("GET", "sms/sms.php", datapass, cObj("parents_lists_nm"), cObj("loading_my_sms_here"), function () {
        // Reveal student list wrapper
        cObj("broadcast_student_list_wrap").classList.remove("hide");

        // Update filtered count from PHP-embedded hidden element
        var totEl = cObj("sms_filtered_total");
        var tot = totEl ? totEl.innerText : "0";
        if (cObj("filtered_students_count")) cObj("filtered_students_count").innerText = tot;
        if (cObj("filtered_students_count2")) cObj("filtered_students_count2").innerText = tot;

        // Restore previously selected students
        checkSelected();

        // Wire staff123s select-all checkbox
        if (cObj("staff123s") != null) {
            cObj("staff123s").addEventListener("change", selectAll);
        }

        // Wire individual student checkboxes
        var studentslist = document.getElementsByClassName("student-class-par");
        for (var i = 0; i < studentslist.length; i++) {
            studentslist[i].addEventListener("change", getStudentId);
        }

        // Wire search + live balance filter
        var searchEl = cObj("search_student_sms");
        if (searchEl) {
            searchEl.removeEventListener("keyup", applyClientFilters);
            searchEl.addEventListener("keyup", applyClientFilters);
        }

        // Apply any balance filter values already typed
        applyClientFilters();
        syncSelectAllCheckbox();
    });
}
function triggerEvent(element, eventName) {
    // Create a new event
    const event = new Event(eventName);
  
    // Dispatch the event on the specified element
    element.dispatchEvent(event);
}

function selectAll() {
    var rows = document.getElementsByClassName("hide_students");
    if (this.checked == true) {
        for (var i = 0; i < rows.length; i++) {
            if (!rows[i].classList.contains("d-none")) {
                var cb = rows[i].querySelector(".student-class-par");
                if (cb) { cb.checked = true; addAdmNo(cb.id.substr(3)); }
            }
        }
    } else {
        for (var i = 0; i < rows.length; i++) {
            if (!rows[i].classList.contains("d-none")) {
                var cb = rows[i].querySelector(".student-class-par");
                if (cb) { cb.checked = false; removeAdmNo(cb.id.substr(3)); }
            }
        }
    }
    updateBroadcastSelectionCount();
}
function checkSelected() {
    var selected_class = cObj("seleceted_class").innerText.split(",");
    var selects = document.getElementsByClassName("student-class-par");
    var counts = selects.length;
    var counter1 = 0;
    for (let index = 0; index < selects.length; index++) {
        var element = selects[index];
        var present = checkPresents(element.id.substr(3), selected_class);
        if (present == 1) {
            element.checked = true;
            counter1++;
        }
    }
    if (counts == counter1) {
        // cObj("staff123s").checked = true;
    }
}
function getStudentId() {
    if (this.checked == true) {
        addAdmNo(this.id.substr(3));
    } else {
        removeAdmNo(this.id.substr(3));
    }
    updateBroadcastSelectionCount();
    syncSelectAllCheckbox();
}
function addAdmNo(adm_no) {
    var selected_class = cObj("seleceted_class").innerText;
    if (selected_class.length > 0) {
        var split = selected_class.split(",");
        if (split.length > 0) {
            var present = checkPresents(adm_no, split);
            if (present == 0) {
                selected_class += "," + adm_no;
                cObj("seleceted_class").innerText = selected_class;
            }
        } else {
            cObj("seleceted_class").innerText = adm_no;
        }
    } else {
        cObj("seleceted_class").innerText = adm_no;
    }
}
function removeAdmNo(adm_no) {
    var seleceted_class = cObj("seleceted_class").innerText;
    if (seleceted_class.length > 0) {
        var splits = seleceted_class.split(",");
        if (splits.length > 0) {
            var data = "";
            for (let index = 0; index < splits.length; index++) {
                var elements = splits[index];
                if (elements == adm_no) { continue; }
                data += elements + ",";
            }
            cObj("seleceted_class").innerText = data.substr(0, data.length - 1);
        }
    }
}
function checkPresents(value1, array1) {
    if (array1.length > 0) {
        for (let index = 0; index < array1.length; index++) {
            var element = array1[index];
            if (element == value1) {
                return 1
            }
        }
    }
    return 0;
}
cObj("text_message2").onkeyup = function () {
    cObj("chr_counts_in").innerText = this.value.length;
    messageData();
}
cObj("send_msg_btns").onclick = function () {
    // check if its sms or email
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        //check error
        var err = checkBlank("text_message2");
        if (err == 0) {
            cObj("err_hands_error").innerHTML = "";
            //check if its parent or staff
            var selection = valObj("select_recipients2");
            if (selection == "my_staff") {
                //get selected staff
                var data = "";
                //get the selected staff
                var selected_staff = document.getElementsByClassName("snamesd112e");
                var checker = 0;
                for (let index = 0; index < selected_staff.length; index++) {
                    var element = selected_staff[index];
                    if (element.checked == true) {
                        var elem = element.id.substr(1, element.id.length);
                        data += elem + ",";
                        checker++;
                    }
                }
                if (checker > 0) {
                    cObj("err_hands_error").innerHTML = "<p class= 'red_notice'></p>";
                    data = data.substr(0, data.length - 1);
                    var datapass = "?tr_ids_excempt=" + data + "&messages=" + encodeURIComponent(valObj("text_message2"));
                    sendData1("GET", "sms/sms.php", datapass, cObj("err_hands_error"), function () {
                        cObj("text_message2").value = "";
                        cObj("message_samples").innerHTML = "";
                        setTimeout(() => {
                            cObj("err_hands_error").innerText = "";
                        }, 4000);
                    });
                } else {
                    cObj("err_hands_error").innerHTML = "<p class= 'red_notice'>Select atleast one staff to send a message!</p>";
                }
            } else if (selection == "parents") {
                var err = checkBlank("send_to_whom");
                if (err == 0) {
                    cObj("err_hands_error").innerHTML = "";
                    var data = cObj("seleceted_class").innerText;
                    var datapass = "?parents_ids_excempt=" + data + "&messages=" + encodeURIComponent(valObj("text_message2")) + "&to_whom=" + valObj("send_to_whom");
                    sendData1("GET", "sms/sms.php", datapass, cObj("err_hands_error"), function () {
                        cObj("text_message2").value = "";
                        cObj("message_samples").innerHTML = "";
                        cObj("send_to_whom").children[0].selected = true;
                        setTimeout(() => {
                            cObj("err_hands_error").innerText = "";
                        }, 4000);
                    });
                } else {
                    cObj("err_hands_error").innerHTML = "<p class= 'red_notice'>Select which parents you will want to send SMS.</p>";
                }
            }
        } else {
            cObj("err_hands_error").innerHTML = "<p class= 'red_notice'>Fill all the fields colored with a red border</p>";
        }
    } else if (send_options == "send_emails") {
        //check error
        var err = checkBlank("email_bulk_subject");
        err += CKEDITOR.instances.email_editored.getData().length > 0 ? 0 : 1;
        if (err == 0) {
            cObj("err_hands_error").innerHTML = "";
            //check if its parent or staff
            var selection = valObj("select_recipients2");
            cObj("err_hands_error").innerHTML = "";
            if (selection == "my_staff") {
                //get selected staff
                var data = "";
                //get the selected staff
                var selected_staff = document.getElementsByClassName("snamesd112e");
                var checker = 0;
                for (let index = 0; index < selected_staff.length; index++) {
                    var element = selected_staff[index];
                    if (element.checked == true) {
                        var elem = element.id.substr(1, element.id.length);
                        data += elem + ",";
                        checker++;
                    }
                }
                if (checker > 0) {
                    cObj("err_hands_error").innerHTML = "<p class= 'red_notice'></p>";
                    data = data.substr(0, data.length - 1);
                    var datapass = "?teacher_sms_id_group=" + data + "&messages=" + encodeURIComponent(CKEDITOR.instances.email_editored.getData()) + "&email_subject=" + valObj("email_bulk_subject") + "&email_cc=" + valObj("cc_email_bulk") + "&email_bcc=" + valObj("bcc_email_bulk");
                    // console.log(datapass);
                    sendData1("GET", "sms/sms.php", datapass, cObj("err_hands_error"), function () {
                        CKEDITOR.instances['email_editored'].setData("");
                        setTimeout(() => {
                            cObj("err_hands_error").innerText = "";
                            cObj("message_samples").innerHTML = "";
                        }, 4000);
                    });
                } else {
                    cObj("err_hands_error").innerHTML = "<p class= 'red_notice'>Select atleast one staff so that you can send the email!</p>";
                }
            } else if (selection == "parents") {
                var err = checkBlank("send_to_whom");
                var emeil_message = CKEDITOR.instances.email_editored.getData();
                err += emeil_message.length > 0 ? 0 : 1;
                if (err == 0) {
                    cObj("err_hands_error").innerHTML = "";
                    var data = cObj("seleceted_class").innerText;
                    var datapass = "?parents_ids_excempt_email=" + data + "&messages=" + encodeURIComponent(emeil_message) + "&to_whom=" + valObj("send_to_whom") + "&cc=" + valObj("cc_email_bulk") + "&bcc=" + valObj("bcc_email_bulk") + "&subject=" + valObj("email_bulk_subject");
                    sendData2("GET", "sms/sms.php", datapass, cObj("err_hands_error"), cObj("load_bulk_emails_sending"), function () {
                        CKEDITOR.instances['email_editored'].setData('');
                        setTimeout(() => {
                            cObj("err_hands_error").innerText = "";
                            cObj("message_samples").innerHTML = "";
                        }, 4000);
                    });
                    cObj("err_hands_error").innerHTML = "<p class='text-success'>Sending bulk E-Mails can take some time. <br>Kindly be patient as the process is done by the system</p>";
                } else {
                    cObj("err_hands_error").innerHTML = "<p class= 'red_notice'>Select which parents you will want to send SMS.</p>";
                }
            } else {
                cObj("err_hands_error").innerHTML = "<p class= 'red_notice'>Select who to send email.</p>";
            }
        } else {
            cObj("err_hands_error").innerHTML = "<p class= 'red_notice'>Fill all the fields that are left blank</p>";
        }
    }
}
cObj("type_notice_here").onkeyup = function () {
    cObj("chr_counts_in1").innerText = this.value.length;
}
function displayTeacherNotice() {
    var datapass = "?get_my_trs=true";
    sendData1("GET", "sms/sms.php", datapass, cObj("staffs_l_s"));
}
cObj("send_post").onclick = function () {
    if (cObj("select_staff_infors") != null) {
        var err = checkBlank("select_staff_infors");
        err += checkBlank("type_notice_here");
        if (err == 0) {
            cObj("notice_errors").innerHTML = "";
            var datapass = "?send_message_notice=true&recpt_id=" + cObj("select_staff_infors").value + "&message=" + encodeURIComponent(cObj("type_notice_here").value);
            sendData1("GET", "sms/sms.php", datapass, cObj("notice_errors"));
            setTimeout(() => {
                var timeout = 0;
                var id23w = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(id23w);
                    }
                    if (cObj("loadings").classList.contains("hide")) {
                        cObj("type_notice_here").value = "";
                        setTimeout(() => {
                            cObj("notice_errors").innerText = "";
                        }, 15000);
                        stopInterval(id23w);
                    }
                }, 100);
            }, 200);
        } else {
            cObj("notice_errors").innerHTML = "<p class='red_notice'>Type your message in the box above!</p>";
        }
    } else {
        cObj("notice_errors").innerHTML = "<p class='red_notice'>No staff present!!</p>";
    }
}
cObj("view_sms_history").onclick = function () {
    //check if the dates are blank
    var err = checkBlank("from_msg_sent");
    err += checkBlank("to_msg_sent");
    if (err == 0) {
        cObj("sms_checker_evt_handlers").innerHTML = "";
        var datapass = "?sms_history=true&from=" + valObj("from_msg_sent") + "&to=" + valObj("to_msg_sent");
        sendData1("GET", "sms/sms.php", datapass, cObj("histotysms"));
        setTimeout(() => {
            var timeout = 0;
            var id23w = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(id23w);
                }
                if (cObj("loadings").classList.contains("hide")) {
                    if (cObj("sms_data_results") != undefined && cObj("sms_data_results") != null) {
                        var sms_data = cObj("sms_data_results").innerText;
                        sms_data = JSON.parse(sms_data);
                        // console.log(sms_data);
                        create_smsdata_table(sms_data);
                    }
                    stopInterval(id23w);
                }
            }, 100);
        }, 200);
    } else {
        cObj("sms_checker_evt_handlers").innerHTML = "<p class='red_notice'>Fill all the dates to proceed!</p>";
    }
}
//function toget the recent messages sent
function getRecentMessage() {
    var datapass = "?sms_history=true";
    sendData2("GET", "sms/sms.php", datapass, cObj("histotysms"), cObj("sms_loaders_window"));
    setTimeout(() => {
        var timeout = 0;
        var id23w = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(id23w);
            }
            if (cObj("sms_loaders_window").classList.contains("hide")) {
                stopInterval(id23w);
                if (cObj("sms_data_results") != null && cObj("sms_data_results") != undefined) {
                    var sms_data = cObj("sms_data_results").innerText;
                    sms_data = JSON.parse(sms_data);
                    // console.log(sms_data);
                    create_smsdata_table(sms_data);
                }else {
                    cObj("transDataReciever_sms").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! No results found!</p>";
                    cObj("tablefooter_sms").classList.add("invisible");
                }
            }
        }, 100);
    }, 200);
}

function stopInterval(id) {
    clearInterval(id);
}

// from here we set the table for sms
var rowsColStudents_sms = [];
var rowsNCols_original_sms = [];
var pagecountTransaction = 0; //this are the number of pages for transaction
var pagecounttrans = 1; //the current page the user is
var startpage_sms = 0; // this is where we start counting the page number

// load the user data
function create_smsdata_table(student_data) {
    rowsColStudents_sms = [];
    rowsNCols_original_sms = [];
    pagecountTransaction = 0; //this are the number of pages for transaction
    pagecounttrans = 1; //the current page the user is
    startpage_sms = 0; // this is where we start counting the page number
    // console.log(student_data.length);
    // get the arrays
    if (student_data.length > 0) {
        var rows = student_data;
        //create a column now
        for (let index = 0; index < rows.length; index++) {
            const element = rows[index];
            // create the collumn array that will take the row value
            var col = [];
            // console.log(element);
            col.push(element['charged']);
            col.push(element['date_sent']);
            col.push(element['message']);
            col.push(element['message_count']);
            col.push(element['message_description']);
            col.push(element['message_sent_succesfully']);
            col.push(element['message_type']);
            col.push(element['message_undelivered']);
            col.push(element['send_id']);
            col.push(element['sender_no']);
            col.push(index + 1);
            col.push(element['date_sent2']);
            col.push(element['number_collection']);
            col.push(element['recipients']);
            // var col = element.split(":");
            rowsColStudents_sms.push(col);
        }
        rowsNCols_original_sms = rowsColStudents_sms;
        cObj("tot_records_sms").innerText = rows.length;
        //create the display table
        //get the number of pages
        cObj("transDataReciever_sms").innerHTML = displayRecord_sms(0, rowsColStudents_sms.length, rowsColStudents_sms);
        view_and_edit_listeners();
        if ($.fn && $.fn.DataTable) {
            if ($.fn.DataTable.isDataTable("#sms_history_dt")) $("#sms_history_dt").DataTable().destroy();
            $("#sms_history_dt").DataTable({ order: [], pageLength: 25, columnDefs: [{ orderable: false, targets: [6] }] });
        }

    } else {
        cObj("transDataReciever_sms").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! No results found!</p>";
        cObj("tablefooter_sms").classList.add("invisible");
    }
}

function view_and_edit_listeners() {
    var view_sms_details = document.getElementsByClassName("view_sms_details");
    for (let index = 0; index < view_sms_details.length; index++) {
        const element = view_sms_details[index];
        element.addEventListener("click",viewPhoneNumbers);
    }
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
        new bootstrap.Tooltip(el, { trigger: 'hover' });
    });
}

function viewPhoneNumbers() {
    var this_id = "numbers_value_"+this.id.substr(17);
    var phone_numbers = hasJsonStructure(valObj(this_id)) ? JSON.parse(valObj(this_id)) : [];
    // fill in the message details
    cObj("message_recipients").innerText = phone_numbers.length > 0 ?  displayRecipients(hasJsonStructure(phone_numbers[12]) ? JSON.parse(phone_numbers[12]) : []) : "Not-Set";
    cObj("message_contents_view").innerText = phone_numbers.length > 0 ?  phone_numbers[2] : "Not-Set";
    cObj("date_sent_view").innerText = phone_numbers.length > 0 ?  phone_numbers[11] : "Not-Set";

    // display the window
    cObj("message_details_window").classList.remove("hide");
}

function displayRecipients(arrays) {
    if (arrays.length == 0) {
        return "No Recipient";
    }

    let data_to_display = "";
    for (let index = 0; index < arrays.length; index++) {
        const element = arrays[index];
        data_to_display+=element+", ";
    }
    return data_to_display.substring(0,data_to_display.length-2);
}

cObj("close_message_details").onclick = function () {
    cObj("message_details_window").classList.add("hide");
}

function displayRecord_sms(start, finish, arrays) {
    var typeColors = {
        broadcast: '#17a2b8', multicast: '#007bff',
        parent: '#fd7e14', fee: '#fd7e14', staff: '#6f42c1', absent: '#e74a3b'
    };
    function smsTypeColor(t) {
        t = (t || '').toLowerCase();
        for (var k in typeColors) { if (t.indexOf(k) !== -1) return typeColors[k]; }
        return '#6c757d';
    }
    function smsStatusBadge(count, delivered, failed) {
        count = parseInt(count) || 0; delivered = parseInt(delivered) || 0; failed = parseInt(failed) || 0;
        if (count === 0) return "<span class='badge badge-secondary'>Pending</span>";
        if (delivered === count) return "<span class='badge badge-success'><i class='fas fa-check-circle'></i> Delivered " + delivered + "/" + count + "</span>";
        if (delivered > 0) return "<span class='badge badge-warning'><i class='fas fa-exclamation-circle'></i> Partial " + delivered + "/" + count + "</span>";
        return "<span class='badge badge-danger'><i class='fas fa-times-circle'></i> Failed " + delivered + "/" + count + "</span>";
    }

    var tableData = "<table id='sms_history_dt' class='table table-sm table-bordered table-hover' style='width:100%;font-size:13px;'>" +
        "<thead><tr>" +
        "<th style='background:#f8f9fa;'>#</th>" +
        "<th style='background:#f8f9fa;'>Type</th>" +
        "<th style='background:#f8f9fa;'>Message</th>" +
        "<th style='background:#f8f9fa;'>Sender / Recipients</th>" +
        "<th style='background:#f8f9fa;'>Status</th>" +
        "<th style='background:#f8f9fa;'>Date Sent</th>" +
        "<th style='background:#f8f9fa;'></th>" +
        "</tr></thead><tbody>";

    for (var i = 0; i < arrays.length; i++) {
        var a = arrays[i];
        var charged = a[0] == 1
            ? "<span class='text-success ml-1' title='Charged'><i class='fas fa-coins'></i></span>"
            : "<span class='text-muted ml-1' title='Not charged'><i class='fas fa-coins'></i></span>";
        var tc = smsTypeColor(a[6]);
        var typeBadge = "<span style='background:" + tc + ";color:#fff;padding:2px 8px;border-radius:10px;font-size:11px;white-space:nowrap;'>" + (a[6] || '—') + "</span>";
        var statusBadge = smsStatusBadge(a[3], a[5], a[7]);
        var msg = (a[2] || '');
        var msgSafe = msg.replace(/'/g, '&#39;').replace(/"/g, '&quot;');
        var msgCell = "<span data-bs-toggle='tooltip' data-bs-placement='top' data-bs-title='" + msgSafe + "' style='display:block;max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;cursor:default;'>" + msg + "</span>";
        var jsonVal = JSON.stringify(a).replace(/'/g, "&#39;");

        tableData += "<tr>" +
            "<td>" + (i + 1) + charged + "</td>" +
            "<td>" + typeBadge + "</td>" +
            "<td>" + msgCell + "</td>" +
            "<td style='font-size:12px;'><input hidden value='" + jsonVal + "' id='numbers_value_" + a[8] + "'>" + (a[9] || '—') + "</td>" +
            "<td>" + statusBadge + "</td>" +
            "<td style='white-space:nowrap;'>" + (a[11] || '') + "</td>" +
            "<td><span style='font-size:12px;cursor:pointer;' class='link view_sms_details' id='view_sms_details_" + a[8] + "' title='View details'><i class='fas fa-eye'></i></span></td>" +
            "</tr>";
    }

    tableData += "</tbody></table>";
    return tableData;
}
//next record 
//add the page by one and the number os rows to dispay by 50
cObj("tonextNav_sms").onclick = function () {
    console.log(pagecounttrans + " " + pagecountTransaction);
    if (pagecounttrans < pagecountTransaction) { // if the current page is less than the total number of pages add a page to go to the next page
        startpage_sms += 50;
        pagecounttrans++;
        var endpage = startpage_sms + 50;
        cObj("transDataReciever_sms").innerHTML = displayRecord_sms(startpage_sms, endpage, rowsColStudents_sms);
        view_and_edit_listeners();
    } else {
        pagecounttrans = pagecountTransaction;
    }
}
// end of next records
cObj("toprevNac_sms").onclick = function () {
    if (pagecounttrans > 1) {
        pagecounttrans--;
        startpage_sms -= 50;
        var endpage = startpage_sms + 50;
        cObj("transDataReciever_sms").innerHTML = displayRecord_sms(startpage_sms, endpage, rowsColStudents_sms);
        view_and_edit_listeners();
    }
}
cObj("tofirstNav_sms").onclick = function () {
    if (pagecountTransaction > 0) {
        pagecounttrans = 1;
        startpage_sms = 0;
        var endpage = startpage_sms + 50;
        cObj("transDataReciever_sms").innerHTML = displayRecord_sms(startpage_sms, endpage, rowsColStudents_sms);
        view_and_edit_listeners();
    }
}
cObj("tolastNav_sms").onclick = function () {
    if (pagecountTransaction > 0) {
        pagecounttrans = pagecountTransaction;
        startpage_sms = (pagecounttrans * 50) - 50;
        var endpage = startpage_sms + 50;
        cObj("transDataReciever_sms").innerHTML = displayRecord_sms(startpage_sms, endpage, rowsColStudents_sms);
        view_and_edit_listeners();
    }
}

// seacrh keyword at the table
cObj("searchkey_sms").onkeyup = function () {
    searchMySms(this.value);
}
//create a function to check if the array has the keyword being searched for
function searchMySms(keyword) {
    rowsColStudents_sms = rowsNCols_original_sms;
    pagecounttrans = 1;
    if (keyword.length > 0) {
        // cObj("tablefooter").classList.add("invisible");
    } else {
        // cObj("tablefooter").classList.remove("invisible");
    }
    // console.log(keyword.toLowerCase());
    var rowsNcol2 = [];
    var keylower = keyword.toLowerCase();
    var keyUpper = keyword.toUpperCase();
    //row break
    for (let index = 0; index < rowsColStudents_sms.length; index++) {
        const element = rowsColStudents_sms[index];
        //column break
        var present = 0;
        if (element[0].toString().toLowerCase().includes(keylower) || element[0].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[2].toString().toLowerCase().includes(keylower) || element[2].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[3].toString().toLowerCase().includes(keylower) || element[3].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[5].toString().toLowerCase().includes(keylower) || element[5].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[6].toString().toLowerCase().includes(keylower) || element[6].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[7].toString().toLowerCase().includes(keylower) || element[7].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[8].toString().toLowerCase().includes(keylower) || element[8].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[9].toString().toLowerCase().includes(keylower) || element[9].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[10].toString().toLowerCase().includes(keylower) || element[10].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[11].toString().toLowerCase().includes(keylower) || element[11].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[13].toString().toLowerCase().includes(keylower) || element[13].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        //here you can add any other columns to be searched for
        // console.log(element[6]==keyword);
        if (present > 0) {
            rowsNcol2.push(element);
        }
    }
    // console.log(rowsNcol2.length);
    if (rowsNcol2.length > 0) {
        rowsColStudents_sms = rowsNcol2;
        var counted = rowsNcol2.length / 50;
        pagecountTransaction = Math.ceil(counted);
        cObj("transDataReciever_sms").innerHTML = displayRecord_sms(0, 50, rowsNcol2);
        view_and_edit_listeners();
        cObj("tot_records_sms").innerText = rowsNcol2.length;
    } else {
        cObj("transDataReciever_sms").innerHTML = "<div class='displaydata'><img class='' src='images/error.png'></div><p class='sm-text text-danger text-bold text-center'><br>Ooops! your search for \"" + keyword + "\" was not found</p>";
        // cObj("tablefooter").classList.add("invisible");
        cObj("startNo_sms").innerText = 0;
        cObj("finishNo_sms").innerText = 0;
        cObj("tot_records_sms").innerText = 0;
        pagecountTransaction = 1;
    }
}

// sort in ascending or descending order
var sortall_sms_status = 1;
var sort_message_type_status = 1;
var sort_content_status = 1;
function sortTable_sms() {
    cObj("sortall_sms_th").addEventListener("click", function () {
        // sort all in ascending order
        if (sortall_sms_status == 0) {
            // asc up to down
            sortall_sms_status = 1;
            //WITH FIRST COLUMN
            rowsColStudents_sms = rowsNCols_original_sms;
            rowsColStudents_sms = sortDesc(rowsColStudents_sms, 10);
            var counted = rowsColStudents_sms.length / 50;
            pagecountTransaction = Math.ceil(counted);
            // console.log(rowsColStudents_sms);
            cObj("transDataReciever_sms").innerHTML = displayRecord_sms(0, 50, rowsColStudents_sms);
            view_and_edit_listeners();
            cObj("tot_records_sms").innerText = rowsColStudents_sms.length;
            cObj("sortall_sms").innerHTML = "- <i class='fas fa-caret-down'></i>";
        } else {
            // desc down to up
            sortall_sms_status = 0;
            //WITH FIRST COLUMN
            rowsColStudents_sms = rowsNCols_original_sms;
            rowsColStudents_sms = sortAsc(rowsColStudents_sms, 10);
            var counted = rowsColStudents_sms.length / 50;
            // console.log(rowsColStudents_sms);
            pagecountTransaction = Math.ceil(counted);
            cObj("transDataReciever_sms").innerHTML = displayRecord_sms(0, 50, rowsColStudents_sms);
            view_and_edit_listeners();
            cObj("tot_records_sms").innerText = rowsColStudents_sms.length;
            cObj("sortall_sms").innerHTML = "- <i class='fas fa-caret-up'></i>";
        }
    });
    cObj("sort_message_type_th").addEventListener("click", function () {
        // sort all in ascending order
        if (sort_message_type_status == 0) {
            // asc up to down
            sort_message_type_status = 1;
            // console.log(cObj("sortadmno").innerHTML);
            //WITH FIRST COLUMN
            rowsColStudents_sms = rowsNCols_original_sms;
            rowsColStudents_sms = sortDesc(rowsColStudents_sms, 6);
            var counted = rowsColStudents_sms.length / 50;
            pagecountTransaction = Math.ceil(counted);
            // console.log(rowsColStudents_sms);
            cObj("transDataReciever_sms").innerHTML = displayRecord_sms(0, 50, rowsColStudents_sms);
            view_and_edit_listeners();
            cObj("tot_records_sms").innerText = rowsColStudents_sms.length;
            cObj("sort_message_type").innerHTML = "- <i class='fas fa-caret-down'></i>";
        } else {
            // desc down to up
            sort_message_type_status = 0;
            //WITH FIRST COLUMN
            rowsColStudents_sms = rowsNCols_original_sms;
            rowsColStudents_sms = sortAsc(rowsColStudents_sms, 6);
            var counted = rowsColStudents_sms.length / 50;
            // console.log(rowsColStudents_sms);
            pagecountTransaction = Math.ceil(counted);
            cObj("transDataReciever_sms").innerHTML = displayRecord_sms(0, 50, rowsColStudents_sms);
            view_and_edit_listeners();
            cObj("tot_records_sms").innerText = rowsColStudents_sms.length;
            cObj("sort_message_type").innerHTML = "- <i class='fas fa-caret-up'></i>";
        }
    });
    cObj("sort_content_th").addEventListener("click", function () {
        // sort all in ascending order
        if (sort_content_status == 0) {
            // asc up to down
            sort_content_status = 1;
            // console.log(cObj("sortfeeamount").innerHTML);
            //WITH FIRST COLUMN
            rowsColStudents_sms = rowsNCols_original_sms;
            rowsColStudents_sms = sortDesc(rowsColStudents_sms, 2);
            var counted = rowsColStudents_sms.length / 50;
            pagecountTransaction = Math.ceil(counted);
            // console.log(rowsColStudents_sms);
            cObj("transDataReciever_sms").innerHTML = displayRecord_sms(0, 50, rowsColStudents_sms);
            view_and_edit_listeners();
            cObj("tot_records_sms").innerText = rowsColStudents_sms.length;
            cObj("sort_content").innerHTML = "- <i class='fas fa-caret-down'></i>";
        } else {
            // desc down to up
            sort_content_status = 0;
            //WITH FIRST COLUMN
            rowsColStudents_sms = rowsNCols_original_sms;
            rowsColStudents_sms = sortAsc(rowsColStudents_sms, 2);
            var counted = rowsColStudents_sms.length / 50;
            // console.log(rowsColStudents_sms);
            pagecountTransaction = Math.ceil(counted);
            cObj("transDataReciever_sms").innerHTML = displayRecord_sms(0, 50, rowsColStudents_sms);
            view_and_edit_listeners();
            cObj("tot_records_sms").innerText = rowsColStudents_sms.length;
            cObj("sort_content").innerHTML = "- <i class='fas fa-caret-up'></i>";
        }
    });
    cObj("sortdate_sms").addEventListener("click", function () {
        cObj("sortall_sms_th").click();
    });
}

cObj("insert_tag1").onclick = function () {
    // check if its email or sms first
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {stud_fullname}";
        messageData();
    } else {
        var valued = CKEDITOR.instances.email_editored.getData();
        CKEDITOR.instances['email_editored'].setData(valued + " {stud_fullname}");
        html_messageData(CKEDITOR.instances.email_editored.getData());
    }
}
cObj("insert_tag2").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {stud_first_name}";
        messageData();
    } else {
        var valued = CKEDITOR.instances.email_editored.getData();
        CKEDITOR.instances['email_editored'].setData(valued + " {stud_first_name}");
        html_messageData(CKEDITOR.instances.email_editored.getData());
    }
}
cObj("insert_tag3").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {stud_class}";
        messageData();
    } else {
        var valued = CKEDITOR.instances.email_editored.getData();
        CKEDITOR.instances['email_editored'].setData(valued + " {stud_class}");
        html_messageData(CKEDITOR.instances.email_editored.getData());
    }
}
cObj("insert_tag4").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {stud_age}";
        messageData();
    } else {
        var valued = CKEDITOR.instances.email_editored.getData();
        CKEDITOR.instances['email_editored'].setData(valued + " {stud_age}");
        html_messageData(CKEDITOR.instances.email_editored.getData());
    }
}
cObj("insert_tag5").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {stud_fees_balance}";
        messageData();
    } else {
        var valued = CKEDITOR.instances.email_editored.getData();
        CKEDITOR.instances['email_editored'].setData(valued + " {stud_fees_balance}");
        html_messageData(CKEDITOR.instances.email_editored.getData());
    }
}
cObj("insert_tag6").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {stud_fees_to_pay}";
        messageData();
    } else {
        var valued = CKEDITOR.instances.email_editored.getData();
        CKEDITOR.instances['email_editored'].setData(valued + " {stud_fees_to_pay}");
        html_messageData(CKEDITOR.instances.email_editored.getData());
    }
}
cObj("insert_tag7").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {stud_fees_paid}";
        messageData();
    } else {
        var valued = CKEDITOR.instances.email_editored.getData();
        CKEDITOR.instances['email_editored'].setData(valued + " {stud_fees_paid}");
        html_messageData(CKEDITOR.instances.email_editored.getData());
    }
}
cObj("insert_tag8").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {par_fullname}";
        messageData();
    } else {
        var valued = CKEDITOR.instances.email_editored.getData();
        CKEDITOR.instances['email_editored'].setData(valued + " {par_fullname}");
        html_messageData(CKEDITOR.instances.email_editored.getData());
    }
}
cObj("insert_tag9").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {today}";
        messageData();
    } else {
        var valued = CKEDITOR.instances.email_editored.getData();
        CKEDITOR.instances['email_editored'].setData(valued + " {today}");
        html_messageData(CKEDITOR.instances.email_editored.getData());
    }
}
cObj("insert_tag10").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {par_first_name}";
        messageData();
    } else {
        var valued = CKEDITOR.instances.email_editored.getData();
        CKEDITOR.instances['email_editored'].setData(valued + " {par_first_name}");
        html_messageData(CKEDITOR.instances.email_editored.getData());
    }
}
cObj("insert_tag11").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {title_1}";
        messageData();
    } else {
        var valued = CKEDITOR.instances.email_editored.getData();
        CKEDITOR.instances['email_editored'].setData(valued + " {title_1}");
        html_messageData(CKEDITOR.instances.email_editored.getData());
    }
}
cObj("insert_tag12").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {title_2}";
        messageData();
    } else {
        var valued = CKEDITOR.instances.email_editored.getData();
        CKEDITOR.instances['email_editored'].setData(valued + " {title_2}");
        html_messageData(CKEDITOR.instances.email_editored.getData());
    }
}
cObj("insert_tag13").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {stud_noun}";
        messageData();
    } else {
        var valued = CKEDITOR.instances.email_editored.getData();
        CKEDITOR.instances['email_editored'].setData(valued + " {stud_noun}");
        html_messageData(CKEDITOR.instances.email_editored.getData());
    }
}
cObj("insert_tag14").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {stud_adm}";
        messageData();
    } else {
        var valued = CKEDITOR.instances.email_editored.getData();
        CKEDITOR.instances['email_editored'].setData(valued + " {stud_adm}");
        html_messageData(CKEDITOR.instances.email_editored.getData());
    }
}
cObj("insert_tag15").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {next_module_fees}";
        messageData();
    } else {
        var valued = CKEDITOR.instances.email_editored.getData();
        CKEDITOR.instances['email_editored'].setData(valued + " {next_module_fees}");
        html_messageData(CKEDITOR.instances.email_editored.getData());
    }
}
function process_messages(data) {
    var message = data;
    message = message.replace(/{stud_fullname}/g, "<b class='text-primary'>Esmond Adala</b>");
    message = message.replace(/{stud_first_name}/g, "<b class='text-primary'>Esmond</b>");
    message = message.replace(/{stud_class}/g, "<b class='text-primary'>Grade 7</b>");
    message = message.replace(/{stud_age}/g, "<b class='text-primary'>12 yrs</b>");
    message = message.replace(/{stud_fees_balance}/g, "<b class='text-primary'>1,000</b>");
    message = message.replace(/{stud_fees_to_pay}/g, "<b class='text-primary'>36,578</b>");
    message = message.replace(/{amount_paid}/g, "<b class='text-primary'>4,578</b>");
    message = message.replace(/{stud_fees_paid}/g, "<b class='text-primary'>22,121</b>");
    message = message.replace(/{par_fullname}/g, "<b class='text-primary'>Mathias Adala</b>");
    message = message.replace(/{par_first_name}/g, "<b class='text-primary'>Mathias</b>");
    message = message.replace(/{title_1}/g, "<b class='text-primary'>Mr</b>");
    message = message.replace(/{title_2}/g, "<b class='text-primary'>Sir</b>");
    message = message.replace(/{today}/g, "<b class='text-primary'>30th Jun 2022</b>");
    message = message.replace(/{stud_noun}/g, "<b class='text-primary'>Son</b>");
    message = message.replace(/{stud_adm}/g, "<b class='text-primary'>NULLADM</b>");
    message = message.replace(/{next_module_fees}/g, "<b class='text-primary'>12,000</b>");
    message = message.replace(/{school_name}/g, "<b class='text-primary'>"+sms_school_name+"</b>");
    message = message.replace(/{time}/g, "<b class='text-primary'>10:00AM</b>");
    message = message.replace(/{school_contact}/g, "<b class='text-primary'>"+valObj("school_contacts_sms")+"</b>");
    message = message.replace(/{school_email}/g, "<b class='text-primary'>"+valObj("school_contacts_email")+"</b>");
    message = message.replace(/{receipt_url}/g, "<b class='text-primary'>"+"https://receipt-url.com"+"</b>");
    message = message.replace(/{children}/g, "<b class='text-primary'>3</b>");
    return message;
}

function messageData() {
    var my_message = cObj("text_message2").value;
    my_message = process_messages(my_message);
    cObj("message_samples").innerHTML = my_message;
}

function html_messageData(content) {
    var my_message = content;
    my_message = process_messages(my_message);
    cObj("message_samples").innerHTML = my_message;
}

// email messages table

//function toget the recent messages sent
function getRecentEmail() {
    var datapass = "?email_history=true";
    sendData2("GET", "sms/sms.php", datapass, cObj("histotyemail"), cObj("email_loaders_window"));
    setTimeout(() => {
        var timeout = 0;
        var id23w = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(id23w);
            }
            if (cObj("email_loaders_window").classList.contains("hide")) {
                stopInterval(id23w);
                if (cObj("email_data_results") != undefined && cObj("email_data_results") != null) {
                    var emails_data = cObj("email_data_results").innerText;
                    emails_data = decodeURIComponent(emails_data);
                    emails_data = JSON.parse(emails_data);
                    // console.log(emails_data);
                    create_email_data_table(emails_data);
                }
            }
        }, 100);
    }, 200);
}


// from here we set the table for sms
var rowsColStudents_email = [];
var rowsNCols_original_email = [];
var pagecountTransaction_emails = 0; //this are the number of pages for transaction
var pagecounttrans_emails = 1; //the current page the user is
var startpage_email = 0; // this is where we start counting the page number

// load the user data
function create_email_data_table(email_data) {
    rowsColStudents_email = [];
    rowsNCols_original_email = [];
    pagecountTransaction_emails = 0; //this are the number of pages for transaction
    pagecounttrans_emails = 1; //the current page the user is
    startpage_email = 0; // this is where we start counting the page number
    // console.log(email_data.length);
    // get the arrays
    if (email_data.length > 0) {
        var rows = email_data;
        //create a column now
        for (let index = 0; index < rows.length; index++) {
            const element = rows[index];
            // create the collumn array that will take the row value
            var col = [];
            // console.log(element);
            col.push(element['charged']);
            col.push(element['date_time']);
            col.push(element['sender_from']);
            col.push(element['recipient_to']);
            col.push(element['message_subject']);
            col.push(element['bcc']);
            col.push(element['message']);
            col.push(element['attachments']);
            col.push(element['cc']);
            col.push(index + 1);
            col.push(element['id']);
            // var col = element.split(":");
            rowsColStudents_email.push(col);
        }
        rowsNCols_original_email = rowsColStudents_email;
        cObj("tot_records_email").innerText = rows.length;
        //create the display table
        //get the number of pages
        cObj("transDataReciever_email").innerHTML = displayRecord_email(0, rowsColStudents_email.length, rowsColStudents_email);
        viewEmailAddress();
        if ($.fn && $.fn.DataTable) {
            if ($.fn.DataTable.isDataTable("#email_history_dt")) $("#email_history_dt").DataTable().destroy();
            $("#email_history_dt").DataTable({ order: [], pageLength: 25, columnDefs: [{ orderable: false, targets: [4] }] });
        }

    } else {
        cObj("transDataReciever_email").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! No results found!</p>";
        cObj("tablefooter_email").classList.add("invisible");
    }
}

function setDateEmail(email_date) {
    var our_date = email_date;
    var year = our_date.substr(0, 4);
    var month = our_date.substr(4, 2);
    var day = our_date.substr(6, 2);
    var hour = our_date.substr(8, 2);
    var minute = our_date.substr(10, 2);

    var months = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return day + "-" + months[month] + "-" + year + " @ " + hour + ":" + minute;
}

function displayRecord_email(start, finish, arrays) {
    var tableData = "<table id='email_history_dt' class='table table-sm table-bordered table-hover' style='width:100%;font-size:13px;'>" +
        "<thead><tr>" +
        "<th style='background:#f8f9fa;'>#</th>" +
        "<th style='background:#f8f9fa;'>From</th>" +
        "<th style='background:#f8f9fa;'>To</th>" +
        "<th style='background:#f8f9fa;'>Subject / Date</th>" +
        "<th style='background:#f8f9fa;'></th>" +
        "</tr></thead><tbody>";

    for (var i = 0; i < arrays.length; i++) {
        var a = arrays[i];
        var hasAttach = a[7] && a[7].length > 0 && a[7] !== 'null' && a[7] !== '[]';
        var attachIcon = hasAttach ? " <i class='fas fa-paperclip text-muted' title='Has attachment'></i>" : '';
        var dateStr = setDateEmail(a[1]);
        var subjectCell = "<div style='font-weight:600;font-size:12px;'>" + (a[4] || '(No subject)') + attachIcon + "</div>" +
                          "<small class='text-muted'>" + dateStr + "</small>";
        var fromCell = "<span style='font-size:12px;'>" + (a[2] || '—') + "</span>";
        var toCell   = "<span style='font-size:12px;'>" + (a[3] || '—') + "</span>";

        tableData += "<tr>" +
            "<td>" + (i + 1) + "</td>" +
            "<td>" + fromCell + "</td>" +
            "<td>" + toCell + "</td>" +
            "<td>" + subjectCell + "</td>" +
            "<td><span id='email_data" + a[10] + "' class='email_data link' style='cursor:pointer;' title='View'><i class='fas fa-eye'></i></span></td>" +
            "</tr>";
    }

    tableData += "</tbody></table>";
    return tableData;
}
// sort in ascending or descending order
var sortall_email_status = 1;
var sort_email_type_status = 1;
var sort_mails_status = 1;
function sortTable_email() {
    cObj("sortall_email_th").addEventListener("click", function () {
        // sort all in ascending order
        if (sortall_email_status == 0) {
            // asc up to down
            sortall_email_status = 1;
            //WITH FIRST COLUMN
            rowsColStudents_email = rowsNCols_original_sms;
            rowsColStudents_email = sortDesc(rowsColStudents_email, 10);
            var counted = rowsColStudents_email.length / 50;
            pagecountTransaction_emails = Math.ceil(counted);
            // console.log(rowsColStudents_email);
            cObj("transDataReciever_email").innerHTML = displayRecord_email(0, 50, rowsColStudents_email);
            viewEmailAddress();
            cObj("tot_records_email").innerText = rowsColStudents_email.length;
            cObj("sortall_email").innerHTML = "- <i class='fas fa-caret-down'></i>";
        } else {
            // desc down to up
            sortall_email_status = 0;
            //WITH FIRST COLUMN
            rowsColStudents_email = rowsNCols_original_sms;
            rowsColStudents_email = sortAsc(rowsColStudents_email, 10);
            var counted = rowsColStudents_email.length / 50;
            // console.log(rowsColStudents_email);
            pagecountTransaction_emails = Math.ceil(counted);
            cObj("transDataReciever_email").innerHTML = displayRecord_email(0, 50, rowsColStudents_email);
            viewEmailAddress();
            cObj("tot_records_email").innerText = rowsColStudents_email.length;
            cObj("sortall_email").innerHTML = "- <i class='fas fa-caret-up'></i>";
        }
    });
    cObj("sort_email_type_th").addEventListener("click", function () {
        // sort all in ascending order
        if (sort_email_type_status == 0) {
            // asc up to down
            sort_email_type_status = 1;
            // console.log(cObj("sortadmno").innerHTML);
            //WITH FIRST COLUMN
            rowsColStudents_email = rowsNCols_original_sms;
            rowsColStudents_email = sortDesc(rowsColStudents_email, 6);
            var counted = rowsColStudents_email.length / 50;
            pagecountTransaction_emails = Math.ceil(counted);
            // console.log(rowsColStudents_email);
            cObj("transDataReciever_email").innerHTML = displayRecord_email(0, 50, rowsColStudents_email);
            viewEmailAddress();
            cObj("tot_records_email").innerText = rowsColStudents_email.length;
            cObj("sort_email_type").innerHTML = "- <i class='fas fa-caret-down'></i>";
        } else {
            // desc down to up
            sort_email_type_status = 0;
            //WITH FIRST COLUMN
            rowsColStudents_email = rowsNCols_original_sms;
            rowsColStudents_email = sortAsc(rowsColStudents_email, 6);
            var counted = rowsColStudents_email.length / 50;
            // console.log(rowsColStudents_email);
            pagecountTransaction_emails = Math.ceil(counted);
            cObj("transDataReciever_email").innerHTML = displayRecord_email(0, 50, rowsColStudents_email);
            viewEmailAddress();
            cObj("tot_records_email").innerText = rowsColStudents_email.length;
            cObj("sort_email_type").innerHTML = "- <i class='fas fa-caret-up'></i>";
        }
    });
    cObj("sortdate_sms").addEventListener("click", function () {
        cObj("sortall_email_th").click();
    });
}


//next record 
//add the page by one and the number os rows to dispay by 50
cObj("tonextNav_email").onclick = function () {
    // console.log(pagecounttrans_emails+" "+pagecountTransaction_emails);
    if (pagecounttrans_emails < pagecountTransaction_emails) { // if the current page is less than the total number of pages add a page to go to the next page
        startpage_email += 50;
        pagecounttrans_emails++;
        var endpage = startpage_email + 50;
        cObj("transDataReciever_email").innerHTML = displayRecord_email(startpage_email, endpage, rowsColStudents_email);
        viewEmailAddress();
    } else {
        pagecounttrans_emails = pagecountTransaction_emails;
    }
}
// end of next records
cObj("toprevNac_email").onclick = function () {
    if (pagecounttrans_emails > 1) {
        pagecounttrans_emails--;
        startpage_email -= 50;
        var endpage = startpage_email + 50;
        cObj("transDataReciever_email").innerHTML = displayRecord_email(startpage_email, endpage, rowsColStudents_email);
        viewEmailAddress();
    }
}
cObj("tofirstNav_email").onclick = function () {
    if (pagecountTransaction_emails > 0) {
        pagecounttrans_emails = 1;
        startpage_email = 0;
        var endpage = startpage_email + 50;
        cObj("transDataReciever_email").innerHTML = displayRecord_email(startpage_email, endpage, rowsColStudents_email);
        viewEmailAddress();
    }
}
cObj("tolastNav_email").onclick = function () {
    if (pagecountTransaction_emails > 0) {
        pagecounttrans_emails = pagecountTransaction_emails;
        startpage_email = (pagecounttrans_emails * 50) - 50;
        var endpage = startpage_email + 50;
        cObj("transDataReciever_email").innerHTML = displayRecord_email(startpage_email, endpage, rowsColStudents_email);
        viewEmailAddress();
    }
}

// seacrh keyword at the table
cObj("searchkey_email").onkeyup = function () {
    searchMyEmail(this.value);
}
//create a function to check if the array has the keyword being searched for
function searchMyEmail(keyword) {
    rowsColStudents_email = rowsNCols_original_email;
    pagecounttrans_emails = 1;
    if (keyword.length > 0) {
        // cObj("tablefooter").classList.add("invisible");
    } else {
        // cObj("tablefooter").classList.remove("invisible");
    }
    // console.log(keyword.toLowerCase());
    var rowsNcol2 = [];
    var keylower = keyword.toLowerCase();
    var keyUpper = keyword.toUpperCase();
    //row break
    for (let index = 0; index < rowsColStudents_email.length; index++) {
        const element = rowsColStudents_email[index];
        //column break
        var present = 0;
        if (element[2].toString().toLowerCase().includes(keylower) || element[2].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[3] != null) {
            if (element[3].toString().toLowerCase().includes(keylower) || element[3].toString().toUpperCase().includes(keyUpper)) {
                present++;
            }
        }
        if (element[4] != null) {
            if (element[4].toString().toLowerCase().includes(keylower) || element[4].toString().toUpperCase().includes(keyUpper)) {
                present++;
            }
        }
        if (element[5] != null) {
            if (element[5].toString().toLowerCase().includes(keylower) || element[5].toString().toUpperCase().includes(keyUpper)) {
                present++;
            }
        }
        if (element[6] != null) {
            if (element[6].toString().toLowerCase().includes(keylower) || element[6].toString().toUpperCase().includes(keyUpper)) {
                present++;
            }
        }
        if (element[8] != null) {
            if (element[8].toString().toLowerCase().includes(keylower) || element[8].toString().toUpperCase().includes(keyUpper)) {
                present++;
            }
        }
        //here you can add any other columns to be searched for
        // console.log(element[6]==keyword);
        if (present > 0) {
            rowsNcol2.push(element);
        }
    }
    // console.log(rowsNcol2.length);
    if (rowsNcol2.length > 0) {
        rowsColStudents_email = rowsNcol2;
        var counted = rowsNcol2.length / 50;
        pagecountTransaction_emails = Math.ceil(counted);
        cObj("transDataReciever_email").innerHTML = displayRecord_email(0, 50, rowsNcol2);
        viewEmailAddress();
        cObj("tot_records_email").innerText = rowsNcol2.length;
    } else {
        cObj("transDataReciever_email").innerHTML = "<div class='displaydata'><img class='' src='images/error.png'></div><p class='sm-text text-danger text-bold text-center'><br>Ooops! your search for \"" + keyword + "\" was not found</p>";
        // cObj("tablefooter").classList.add("invisible");
        cObj("startNo_sms").innerText = 0;
        cObj("finishNo_sms").innerText = 0;
        cObj("tot_records_email").innerText = 0;
        pagecountTransaction_emails = 1;
    }
}

function viewEmailAddress() {
    var email_data = document.getElementsByClassName("email_data");
    for (let index = 0; index < email_data.length; index++) {
        const element = email_data[index];
        element.onclick = function () {
            var email_id = element.id.substring(10);
            // get the email data from the 
            var datapass = "?email_data=" + email_id;
            cObj("email_data_read_window").classList.remove("hide");
            sendData2("GET", "sms/sms.php", datapass, cObj("email_contents"), cObj("read_email_loader"));
            setTimeout(() => {
                var timeout = 0;
                var id23w = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(id23w);
                    }
                    if (cObj("read_email_loader").classList.contains("hide")) {
                        stopInterval(id23w);
                        if (cObj("delete_email") != undefined || cObj("delete_email") != null) {
                            cObj("delete_email").onclick = function () {
                                // delete the email
                                var datapass = "?delete_mail=" + cObj("emai_id_delete").innerText;
                                sendData2("GET", "sms/sms.php", datapass, cObj("email_text_holder"), cObj("delete_email_loader"));
                                setTimeout(() => {
                                    var timeout = 0;
                                    var id23w1 = setInterval(() => {
                                        timeout++;
                                        //after two minutes of slow connection the next process wont be executed
                                        if (timeout == 1200) {
                                            stopInterval(id23w1);
                                        }
                                        if (cObj("read_email_loader").classList.contains("hide")) {
                                            stopInterval(id23w1);
                                            cObj("email_data_read_window").classList.add("hide");
                                            getRecentEmail();
                                        }
                                    }, 100);
                                }, 200);
                            }
                        }
                    }
                }, 100);
            }, 200);
        }
    }
}

cObj("close_email_data_read_window").onclick = function () {
    cObj("email_data_read_window").classList.add("hide");
}
cObj("close_email_data_windows").onclick = function () {
    cObj("email_data_read_window").classList.add("hide");
}

cObj("rather_view_email_history").onclick = function () {
    getRecentEmail();
}
cObj("rather_view_sms_history").onclick = function () {
    // getRecentMessage()
    getRecentMessage();
}

cObj("welcome_message_editor").onkeyup = function () {
    cObj("welcome_message_viewer").innerHTML = process_messages(valObj("welcome_message_editor"));
}
// get message 
function get_message_samples() {
    var datapass = "?get_messages_samples=true";
    sendData2("GET", "sms/sms.php", datapass, cObj("messages_holder_templates"), cObj("class_loader_tags"));
    setTimeout(() => {
        var timeout = 0;
        var id23w = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(id23w);
            }
            if (cObj("class_loader_tags").classList.contains("hide")) {
                stopInterval(id23w);
                if (hasJsonStructure(cObj("messages_holder_templates").innerText)) {
                    var messages_holder = JSON.parse(cObj("messages_holder_templates").innerText);
                    
                    // set the welcome message template
                    var welcome_message = null;
                    var confirmation_message = null;
                    var parent_account_confirmation_message = null
                    var student_welcome_message = null;
                    for (let index = 0; index < messages_holder.length; index++) {
                        const element = messages_holder[index];
                        if(element['message_type'] == "welcome_message"){
                            welcome_message = element['message_content'];
                        }

                        if(element['message_type'] == "student_welcome_message"){
                            student_welcome_message = element['message_content'];
                        }

                        if(element['message_type'] == "confirmation_message"){
                            confirmation_message = element['message_content'];
                        }

                        if(element['message_type'] == "parent_account_confirmation_message"){
                            parent_account_confirmation_message = element['message_content'];
                        }
                    }

                    // set the welcome message if its not null
                    if (student_welcome_message) {
                        cObj("student_welcome_message_editor").value = student_welcome_message;
                        cObj("student_welcome_message_viewer").innerHTML = process_messages(student_welcome_message);
                    }else{
                        // default value for the welcome message
                        cObj("student_welcome_message_viewer").innerHTML = process_messages(cObj("student_welcome_message_editor").value);
                    }

                    // set the welcome message if its not null
                    if (welcome_message) {
                        cObj("welcome_message_editor").value = welcome_message;
                        cObj("welcome_message_viewer").innerHTML = process_messages(welcome_message);
                    }else{
                        // default value for the welcome message
                        cObj("welcome_message_viewer").innerHTML = process_messages(cObj("welcome_message_editor").value);
                    }

                    // set the welcome message if its not null
                    if (confirmation_message) {
                        cObj("confirmation_message_editor").value = confirmation_message;
                        cObj("confirmation_message_viewer").innerHTML = process_messages(confirmation_message);
                    }else{
                        // default value for the welcome message
                        cObj("confirmation_message_viewer").innerHTML = process_messages(cObj("confirmation_message_editor").value);
                    }

                    if(parent_account_confirmation_message){
                        cObj("parent_confirmation_message_editor").value = parent_account_confirmation_message;
                        cObj("parent_confirmation_message_viewer").innerHTML = process_messages(parent_account_confirmation_message);
                    }else{
                        cObj("parent_confirmation_message_viewer").innerHTML = process_messages(cObj("parent_confirmation_message_editor").value);
                    }
                }else{
                    // default value for the welcome message
                    cObj("welcome_message_viewer").innerHTML = process_messages(cObj("welcome_message_editor").value);
                    cObj("confirmation_message_viewer").innerHTML = process_messages(cObj("confirmation_message_editor").value);
                    cObj("parent_confirmation_message_viewer").innerHTML = process_messages(cObj("parent_confirmation_message_editor").value);
                }
            }
        }, 100);
    }, 200);
}

cObj("save_welcome_message").onclick = function () {
    // save the welcome message
    var datapass = "?save_welcome_message=true&welcome_message="+valObj("welcome_message_editor");
    sendData2("GET", "sms/sms.php", datapass, cObj("welcome_message_template_holder"), cObj("class_loader_tags"));
}
cObj("student_save_welcome_message").onclick = function () {
    // save the welcome message
    var datapass = "?student_save_welcome_message=true&welcome_message="+valObj("student_welcome_message_editor");
    sendData2("GET", "sms/sms.php", datapass, cObj("student_welcome_message_template_holder"), cObj("class_loader_tags"));
}
cObj("save_confirmation_message").onclick = function () {
    // save the welcome message
    var datapass = "?save_confirmation_message=true&confirmation_message="+valObj("confirmation_message_editor");
    sendData2("GET", "sms/sms.php", datapass, cObj("confirmation_message_template_holder"), cObj("class_loader_tags"));
}
cObj("save_parent_confirmation_message").onclick = function () {
    // save the welcome message
    var datapass = "?save_parent_confirmation=true&parent_account_msg="+valObj("parent_confirmation_message_editor");
    sendData2("GET", "sms/sms.php", datapass, cObj("parent_confirmation_message_template_holder"), cObj("class_loader_tags"));
}
cObj("parent_confirmation_message_editor").onkeyup = function () {
    cObj("parent_confirmation_message_viewer").innerHTML = process_messages(this.value);
}
cObj("back_to_message_dash").onclick = function () {
    cObj("sms_broadcast").click();
}