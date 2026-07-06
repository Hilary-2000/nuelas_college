// Student Groups: build a saved, named cohort of students from a set of filters.
var groupsInitialised = false;

cObj("groups_btn").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("student_groups_win").classList.remove("hide");
    removesidebar();
    if (!groupsInitialised) {
        groupsInitialised = true;
        loadGroupFilterOptions();
        wireGroupsPanel();
    }
};

// Full course list (id, course_name, course_level) fetched once and cached here
// so the Course dropdown can be re-filtered by Course Level entirely client-side.
var allGroupCourses = [];

function loadGroupFilterOptions() {
    sendData1("GET", "finance/groups.php", "?get_group_filter_options=true", cObj("group_filter_options_holder"), function () {
        var holder = cObj("group_filter_options_holder");
        if (!holder || !holder.innerText) return;
        try {
            var opts = JSON.parse(holder.innerText);
            allGroupCourses = opts.courses || [];

            fillOptions("gf_branch", opts.branches, "id", "name");
            fillOptions("gf_course_level", opts.course_levels, "id", "classes");
            fillOptions("gf_intake_year", opts.intake_years);
            fillOptions("gf_intake_month", opts.intake_months);
            fillOptions("gf_dormitory", opts.dormitories, "dorm_name", "dorm_name");
            populateCourseOptions("gf_course", "all");

            // Same option sets are reused by the "Add Students" search inside
            // the Manage Members modal.
            fillOptions("mm_course_level", opts.course_levels, "id", "classes");
            fillOptions("mm_intake_year", opts.intake_years);
            fillOptions("mm_intake_month", opts.intake_months);
            fillOptions("mm_dormitory", opts.dormitories, "dorm_name", "dorm_name");
            populateCourseOptions("mm_course", "all");
        } catch (e) {}
    });
}

function fillOptions(selectId, items, valueKey, textKey) {
    var sel = cObj(selectId);
    if (!sel || !items) return;
    items.forEach(function (item) {
        var o = document.createElement("option");
        if (valueKey) {
            o.value = item[valueKey];
            o.text = item[textKey];
        } else {
            o.value = item;
            o.text = item;
        }
        sel.appendChild(o);
    });
}

// Course Level -> Course cascade: narrows the Course dropdown to only the
// courses that belong to the chosen level (same idea as the Course Level /
// Course pair on the admissions and SMS broadcast pages).
function populateCourseOptions(selectId, levelValue) {
    var sel = cObj(selectId);
    if (!sel) return;
    sel.innerHTML = "<option value='all'>All Courses</option>";
    allGroupCourses.forEach(function (c) {
        if (levelValue == "all" || String(c.course_level) == String(levelValue)) {
            var o = document.createElement("option");
            o.value = c.id;
            o.text = c.course_name;
            sel.appendChild(o);
        }
    });
}

function wireGroupsPanel() {
    cObj("show_create_group_btn").onclick = function () {
        cObj("create_group_panel").classList.remove("hide");
        cObj("manage_groups_panel").classList.add("hide");
    };
    cObj("show_manage_groups_btn").onclick = function () {
        cObj("create_group_panel").classList.add("hide");
        cObj("manage_groups_panel").classList.remove("hide");
        loadGroupsList();
    };

    wireFilterPanelToggle("group_filter_panel_toggle", "group_filter_panel_body", "group_filter_panel_chevron");
    wireFilterPanelToggle("mm_filter_panel_toggle", "mm_filter_panel_body", "mm_filter_panel_chevron");

    cObj("gf_course_level").onchange = function () {
        populateCourseOptions("gf_course", this.value);
    };
    cObj("mm_course_level").onchange = function () {
        populateCourseOptions("mm_course", this.value);
    };

    cObj("group_clear_filters").onclick = function () {
        ["gf_gender", "gf_course_level", "gf_study_mode", "gf_branch", "gf_boarding_status", "gf_dormitory", "gf_intake_year", "gf_intake_month", "gf_student_status"].forEach(function (id) {
            cObj(id).selectedIndex = 0;
        });
        populateCourseOptions("gf_course", "all");
        cObj("gf_doa_from").value = "";
        cObj("gf_doa_to").value = "";
    };

    cObj("group_apply_filters").onclick = applyGroupFilters;

    cObj("group_select_all_btn").onclick = function () {
        document.querySelectorAll(".group-student-chk").forEach(function (cb) { cb.checked = true; });
        updateGroupSelectedCount();
    };
    cObj("group_deselect_all_btn").onclick = function () {
        document.querySelectorAll(".group-student-chk").forEach(function (cb) { cb.checked = false; });
        updateGroupSelectedCount();
    };

    cObj("save_group_btn").onclick = saveGroup;

    // Event delegation for the create-group filtered list (rebuilt on every search)
    cObj("group_students_holder").addEventListener("change", function (e) {
        if (e.target.id === "group_select_all_visible") {
            document.querySelectorAll(".group-student-chk").forEach(function (cb) { cb.checked = e.target.checked; });
            updateGroupSelectedCount();
        } else if (e.target.classList.contains("group-student-chk")) {
            updateGroupSelectedCount();
        }
    });

    // Event delegation for the groups list (rebuilt every time it's loaded)
    cObj("student_groups_list_holder").addEventListener("click", function (e) {
        var manageBtn = e.target.closest(".manage_members_btn");
        var editBtn = e.target.closest(".edit_group_btn");
        var deleteBtn = e.target.closest(".delete_group_btn");
        var chargeBtn = e.target.closest(".charge_group_btn");

        if (manageBtn) {
            openManageMembersModal(manageBtn.getAttribute("data-group-id"), manageBtn.getAttribute("data-group-name"));
        } else if (editBtn) {
            openEditGroupModal(editBtn.getAttribute("data-group-id"), editBtn.getAttribute("data-group-name"), editBtn.getAttribute("data-group-description"));
        } else if (deleteBtn) {
            openDeleteGroupModal(deleteBtn.getAttribute("data-group-id"), deleteBtn.getAttribute("data-group-name"));
        } else if (chargeBtn) {
            openChargeCreationForGroup(chargeBtn.getAttribute("data-group-id"), chargeBtn.getAttribute("data-group-name"));
        }
    });

    // ---- Edit Group modal ----
    cObj("save_edit_group_btn").onclick = saveEditGroup;
    cObj("cancel_edit_group_btn").onclick = function () { cObj("edit_group_modal").classList.add("hide"); };
    cObj("close_edit_group_modal").onclick = function () { cObj("edit_group_modal").classList.add("hide"); };

    // ---- Delete Group modal ----
    cObj("confirm_delete_group_btn").onclick = confirmDeleteGroup;
    cObj("cancel_delete_group_btn").onclick = function () { cObj("delete_group_modal").classList.add("hide"); };

    // Type-to-filter the two "results" lists client-side, by name or admission number
    wireInlineSearchBox("group_search_box", ".group_student_row");
    wireInlineSearchBox("mm_results_search_box", ".mm_student_row");
    wireInlineSearchBox("mm_current_members_search_box", ".mm_current_member_row");

    // ---- Manage Members modal ----
    cObj("mm_search_btn").onclick = searchStudentsToAdd;
    cObj("mm_add_selected_btn").onclick = addSelectedMembers;
    cObj("close_manage_members_btn").onclick = closeManageMembersModal;
    cObj("close_manage_members_modal").onclick = closeManageMembersModal;

    cObj("manage_members_current_holder").addEventListener("click", function (e) {
        var removeBtn = e.target.closest(".remove_member_btn");
        if (removeBtn) {
            removeGroupMember(removeBtn.getAttribute("data-group-id"), removeBtn.getAttribute("data-adm-no"));
        }
    });

    cObj("manage_members_search_results").addEventListener("change", function (e) {
        if (e.target.id === "mm_select_all_visible") {
            document.querySelectorAll(".mm-student-chk").forEach(function (cb) { cb.checked = e.target.checked; });
        }
    });
}

// Collapsible bordered filter panel (blue header + chevron), shared by the
// Create Group filter panel and the Manage Members "Add Students" panel.
function wireFilterPanelToggle(toggleId, bodyId, chevronId) {
    cObj(toggleId).onclick = function () {
        var body = cObj(bodyId);
        var chevron = cObj(chevronId);
        if (body.classList.contains("hide")) {
            body.classList.remove("hide");
            chevron.classList.replace("fa-chevron-down", "fa-chevron-up");
        } else {
            body.classList.add("hide");
            chevron.classList.replace("fa-chevron-up", "fa-chevron-down");
        }
    };
}

// Type-to-filter a rendered results list by name/admission number, without
// another round trip -- each row carries its searchable text in data-search.
// Uses Bootstrap's "d-none" (display:none !important) rather than the
// custom "hide" class: these rows are ".staff_list .staff_dets", which sets
// "display:flex" with higher CSS specificity than the plain ".hide" class,
// so ".hide" alone would not actually hide them.
function wireInlineSearchBox(searchInputId, rowSelector) {
    var input = cObj(searchInputId);
    if (!input || input._wired) return;
    input._wired = true;
    input.addEventListener("keyup", function () {
        var term = input.value.trim().toLowerCase();
        document.querySelectorAll(rowSelector).forEach(function (row) {
            var haystack = row.getAttribute("data-search") || "";
            row.classList.toggle("d-none", term.length > 0 && haystack.indexOf(term) === -1);
        });
    });
}

function applyGroupFilters() {
    cObj("group_search_box").value = "";
    var datapass = "?get_filtered_students=true&prefix=group"
        + "&gender=" + encodeURIComponent(valObj("gf_gender"))
        + "&course=" + encodeURIComponent(valObj("gf_course"))
        + "&study_mode=" + encodeURIComponent(valObj("gf_study_mode"))
        + "&branch=" + encodeURIComponent(valObj("gf_branch"))
        + "&boarding_status=" + encodeURIComponent(valObj("gf_boarding_status"))
        + "&dormitory=" + encodeURIComponent(valObj("gf_dormitory"))
        + "&intake_year=" + encodeURIComponent(valObj("gf_intake_year"))
        + "&intake_month=" + encodeURIComponent(valObj("gf_intake_month"))
        + "&doa_from=" + encodeURIComponent(cObj("gf_doa_from").value)
        + "&doa_to=" + encodeURIComponent(cObj("gf_doa_to").value)
        + "&student_status=" + encodeURIComponent(valObj("gf_student_status"));

    sendData1("GET", "finance/groups.php", datapass, cObj("group_students_holder"), function () {
        cObj("group_student_list_wrap").classList.remove("hide");
        var totEl = cObj("group_filtered_total");
        cObj("group_filtered_count").innerText = totEl ? totEl.innerText : "0";
        updateGroupSelectedCount();
    });
}

function updateGroupSelectedCount() {
    var checked = document.querySelectorAll(".group-student-chk:checked").length;
    cObj("group_selected_count").innerText = checked;
}

function currentGroupFilters() {
    return {
        gender: valObj("gf_gender"),
        course: valObj("gf_course"),
        study_mode: valObj("gf_study_mode"),
        branch: valObj("gf_branch"),
        boarding_status: valObj("gf_boarding_status"),
        dormitory: valObj("gf_dormitory"),
        intake_year: valObj("gf_intake_year"),
        intake_month: valObj("gf_intake_month"),
        doa_from: cObj("gf_doa_from").value,
        doa_to: cObj("gf_doa_to").value,
        student_status: valObj("gf_student_status")
    };
}

function saveGroup() {
    var groupName = cObj("new_group_name").value.trim();
    var feedback = cObj("group_save_feedback");
    var admNos = [];
    document.querySelectorAll(".group-student-chk:checked").forEach(function (cb) {
        admNos.push(cb.value);
    });

    if (groupName.length == 0) {
        feedback.innerHTML = "<span class='text-danger'>Please give the group a name.</span>";
        return;
    }
    if (admNos.length == 0) {
        feedback.innerHTML = "<span class='text-danger'>Select at least one student.</span>";
        return;
    }

    feedback.innerHTML = "<span class='text-muted'>Saving...</span>";

    postToGroups("save_group=true"
        + "&group_name=" + encodeURIComponent(groupName)
        + "&description=" + encodeURIComponent(cObj("new_group_description").value.trim())
        + "&filters_json=" + encodeURIComponent(JSON.stringify(currentGroupFilters()))
        + "&adm_nos=" + encodeURIComponent(JSON.stringify(admNos)), function (resp) {
        if (resp.status == "success") {
            feedback.innerHTML = "<span class='text-success'>" + resp.message + "</span>";
            cObj("new_group_name").value = "";
            cObj("new_group_description").value = "";
            document.querySelectorAll(".group-student-chk").forEach(function (cb) { cb.checked = false; });
            updateGroupSelectedCount();
        } else {
            feedback.innerHTML = "<span class='text-danger'>" + resp.message + "</span>";
        }
    }, function () {
        feedback.innerHTML = "<span class='text-danger'>Something went wrong saving the group.</span>";
    });
}

function loadGroupsList() {
    sendData1("GET", "finance/groups.php", "?list_groups=true", cObj("student_groups_list_holder"), function () {
        // ordering:false -- keep the server's "latest first" (date_created DESC) order.
        // DataTables' own sort would otherwise compare the displayed "06 Jul 2026"
        // text lexically, which isn't true chronological order across months.
        $('#student_groups_table').DataTable({ ordering: false });
    });
}

// Generic small helper for the POST actions in this file: JSON in, JSON out.
function postToGroups(body, onSuccess, onError) {
    var xml = new XMLHttpRequest();
    xml.open("POST", "ajax/finance/groups.php", true);
    xml.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xml.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            try {
                onSuccess(JSON.parse(this.responseText));
            } catch (e) {
                if (onError) onError();
            }
        }
    };
    xml.send(body);
}

/* ---------------- Edit Group (name + description) ---------------- */

function openEditGroupModal(groupId, groupName, groupDescription) {
    cObj("edit_group_id").value = groupId;
    cObj("edit_group_name").value = groupName;
    cObj("edit_group_description").value = groupDescription || "";
    cObj("edit_group_feedback").innerHTML = "";
    cObj("edit_group_modal").classList.remove("hide");
}

function saveEditGroup() {
    var groupId = cObj("edit_group_id").value;
    var groupName = cObj("edit_group_name").value.trim();
    var feedback = cObj("edit_group_feedback");

    if (groupName.length == 0) {
        feedback.innerHTML = "<span class='text-danger'>Group name cannot be empty.</span>";
        return;
    }

    postToGroups("update_group_details=true"
        + "&group_id=" + encodeURIComponent(groupId)
        + "&group_name=" + encodeURIComponent(groupName)
        + "&description=" + encodeURIComponent(cObj("edit_group_description").value.trim()), function (resp) {
        if (resp.status == "success") {
            cObj("edit_group_modal").classList.add("hide");
            loadGroupsList();
        } else {
            feedback.innerHTML = "<span class='text-danger'>" + resp.message + "</span>";
        }
    }, function () {
        feedback.innerHTML = "<span class='text-danger'>Something went wrong updating the group.</span>";
    });
}

/* ---------------- Delete Group ---------------- */

function openDeleteGroupModal(groupId, groupName) {
    cObj("delete_group_id").value = groupId;
    cObj("delete_group_name_label").innerText = groupName;
    cObj("delete_group_modal").classList.remove("hide");
}

function confirmDeleteGroup() {
    var groupId = cObj("delete_group_id").value;
    postToGroups("delete_group=true&group_id=" + encodeURIComponent(groupId), function (resp) {
        cObj("delete_group_modal").classList.add("hide");
        loadGroupsList();
    });
}

/* ---------------- Manage Members (add/remove) ---------------- */

function openManageMembersModal(groupId, groupName) {
    cObj("manage_members_group_id").value = groupId;
    cObj("manage_members_group_name").innerText = groupName;
    cObj("manage_members_search_results").innerHTML = "";
    cObj("mm_search_results_wrap").classList.add("hide");
    cObj("mm_results_search_box").value = "";
    cObj("manage_members_feedback").innerHTML = "";
    loadCurrentMembers(groupId);
    cObj("manage_members_modal").classList.remove("hide");
}

function closeManageMembersModal() {
    cObj("manage_members_modal").classList.add("hide");
    loadGroupsList();
}

function loadCurrentMembers(groupId) {
    cObj("mm_current_members_search_box").value = "";
    sendData1("GET", "finance/groups.php", "?get_group_members=" + groupId, cObj("manage_members_current_holder"), function () {
        var totEl = cObj("manage_members_total_count");
        cObj("manage_members_count").innerText = totEl ? totEl.innerText : "0";
    });
}

function searchStudentsToAdd() {
    cObj("mm_results_search_box").value = "";
    var groupId = cObj("manage_members_group_id").value;
    var datapass = "?get_filtered_students=true&prefix=mm&exclude_group_id=" + encodeURIComponent(groupId)
        + "&gender=all"
        + "&course=" + encodeURIComponent(valObj("mm_course"))
        + "&boarding_status=" + encodeURIComponent(valObj("mm_boarding_status"))
        + "&dormitory=" + encodeURIComponent(valObj("mm_dormitory"))
        + "&intake_year=" + encodeURIComponent(valObj("mm_intake_year"))
        + "&intake_month=" + encodeURIComponent(valObj("mm_intake_month"))
        + "&doa_from=" + encodeURIComponent(cObj("mm_doa_from").value)
        + "&doa_to=" + encodeURIComponent(cObj("mm_doa_to").value);

    sendData1("GET", "finance/groups.php", datapass, cObj("manage_members_search_results"), function () {
        cObj("mm_search_results_wrap").classList.remove("hide");
    });
}

function addSelectedMembers() {
    var groupId = cObj("manage_members_group_id").value;
    var feedback = cObj("manage_members_feedback");
    var admNos = [];
    document.querySelectorAll(".mm-student-chk:checked").forEach(function (cb) {
        admNos.push(cb.value);
    });

    if (admNos.length == 0) {
        feedback.innerHTML = "<span class='text-danger'>Select at least one student to add.</span>";
        return;
    }

    postToGroups("add_group_members=true"
        + "&group_id=" + encodeURIComponent(groupId)
        + "&adm_nos=" + encodeURIComponent(JSON.stringify(admNos)), function (resp) {
        feedback.innerHTML = "<span class='" + (resp.status == "success" ? "text-success" : "text-danger") + "'>" + resp.message + "</span>";
        if (resp.status == "success") {
            loadCurrentMembers(groupId);
            cObj("manage_members_search_results").innerHTML = "";
            cObj("mm_search_results_wrap").classList.add("hide");
        }
    }, function () {
        feedback.innerHTML = "<span class='text-danger'>Something went wrong adding students.</span>";
    });
}

function removeGroupMember(groupId, admNo) {
    postToGroups("remove_group_member=true"
        + "&group_id=" + encodeURIComponent(groupId)
        + "&adm_no=" + encodeURIComponent(admNo), function (resp) {
        loadCurrentMembers(groupId);
    });
}
