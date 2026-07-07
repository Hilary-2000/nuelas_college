// Charged Accounts: irregular one-off charges (lost items, exam fees, etc)
// billed to specific students or a saved Group, outside the normal fee structure.
var chargedAccountsInitialised = false;
var chargeAdmNos = []; // {adm_no, name} added to the "specific students" list

cObj("charged_accounts_btn").onclick = function () {
    openChargedAccountsPage();
};

// ---- Student profile: Charged Account eye-icon + modal ----
// Wired unconditionally (not inside wireChargedAccountsPanel()) since these
// elements live on the Manage Student page, which may be opened before the
// admin ever visits the Finance > Charged Accounts tab.
cObj("save_edit_charge_btn").onclick = saveEditChargeItem;
cObj("cancel_edit_charge_btn").onclick = function () {
    cObj("edit_charge_item_modal").classList.add("hide");
    cObj("view_student_charged_account_modal").classList.remove("hide");
};
cObj("confirm_delete_charge_btn").onclick = confirmDeleteChargeItem;
cObj("cancel_delete_charge_btn").onclick = function () {
    cObj("delete_charge_item_modal").classList.add("hide");
    cObj("view_student_charged_account_modal").classList.remove("hide");
};
cObj("charged_account_modal_items_holder").addEventListener("click", function (e) {
    var editBtn = e.target.closest(".edit_charge_item_btn");
    var deleteBtn = e.target.closest(".delete_charge_item_btn");
    if (editBtn) {
        openEditChargeItem(editBtn.getAttribute("data-adm-no"), editBtn.getAttribute("data-charge-id"), editBtn.getAttribute("data-description"), editBtn.getAttribute("data-amount"));
    } else if (deleteBtn) {
        openDeleteChargeItem(deleteBtn.getAttribute("data-adm-no"), deleteBtn.getAttribute("data-charge-id"), deleteBtn.getAttribute("data-description"));
    }
});

cObj("view_charged_account_btn").onclick = function () {
    cObj("charged_account_modal_adm_no").value = valObj("adminnos");
    cObj("charged_account_modal_description").value = "";
    cObj("charged_account_modal_period").value = "";
    cObj("charged_account_modal_amount").value = "";
    cObj("charged_account_modal_feedback").innerHTML = "";
    cObj("view_student_charged_account_modal").classList.remove("hide");
};
cObj("close_view_student_charged_account_modal").onclick = function () {
    cObj("view_student_charged_account_modal").classList.add("hide");
};
cObj("close_view_student_charged_account_btn").onclick = function () {
    cObj("view_student_charged_account_modal").classList.add("hide");
};
cObj("add_charged_account_item_btn").onclick = addChargedAccountItem;

// `afterGroupsLoaded` runs once the group dropdown has been (re)populated --
// there must only ever be one loadChargeGroupOptions() call per page-open,
// otherwise a second, uncoordinated reload race can wipe out a selection
// made right after the first one resolves.
function openChargedAccountsPage(afterGroupsLoaded) {
    hideWindow();
    unselectbtns();
    addselected("charged_accounts_btn");
    cObj("charged_accounts_win").classList.remove("hide");
    removesidebar();
    if (!chargedAccountsInitialised) {
        chargedAccountsInitialised = true;
        wireChargedAccountsPanel();
    }
    loadChargeGroupOptions(afterGroupsLoaded);
    // Populates the shared name/admission-number lookup arrays and wires the
    // type-a-name autocomplete on #charge_admno_input (see finance.js).
    getStudentNameAdmno();
}

// Called from groups.js when the "Charge this Group" shortcut is clicked.
function openChargeCreationForGroup(groupId, groupName) {
    openChargedAccountsPage(function () {
        var opt = cObj("charge_group_select").querySelector("option[value='" + groupId + "']");
        if (opt) {
            cObj("charge_group_select").value = groupId;
        }
    });
    cObj("create_charge_panel").classList.remove("hide");
    cObj("charge_history_panel").classList.add("hide");
    cObj("charge_target_group").checked = true;
    toggleChargeTargetPanels();
}

function loadChargeGroupOptions(callback) {
    sendData1("GET", "finance/charged_accounts.php", "?list_groups_light=true", cObj("charge_groups_holder"), function () {
        try {
            var groups = JSON.parse(cObj("charge_groups_holder").innerText);
            var select = cObj("charge_group_select");
            select.innerHTML = "<option value='' hidden>Select a group...</option>";
            groups.forEach(function (g) {
                var o = document.createElement("option");
                o.value = g.group_id;
                o.text = g.group_name;
                select.appendChild(o);
            });
        } catch (e) {}
        if (callback) callback();
    });
}

function wireChargedAccountsPanel() {
    cObj("show_create_charge_btn").onclick = function () {
        cObj("create_charge_panel").classList.remove("hide");
        cObj("charge_history_panel").classList.add("hide");
    };
    cObj("show_charge_history_btn").onclick = function () {
        cObj("create_charge_panel").classList.add("hide");
        cObj("charge_history_panel").classList.remove("hide");
        loadChargeBatches();
    };

    cObj("charge_target_students").onchange = toggleChargeTargetPanels;
    cObj("charge_target_group").onchange = toggleChargeTargetPanels;

    cObj("add_charge_admno_btn").onclick = addChargeAdmNo;
    cObj("charge_admno_input").addEventListener("keyup", function (e) {
        if (e.key === "Enter") addChargeAdmNo();
    });

    cObj("charge_admno_list").addEventListener("click", function (e) {
        var removeBtn = e.target.closest(".remove_charge_admno_btn");
        if (removeBtn) {
            var admNo = removeBtn.getAttribute("data-adm-no");
            chargeAdmNos = chargeAdmNos.filter(function (s) { return s.adm_no !== admNo; });
            renderChargeAdmNoList();
        }
    });

    cObj("create_charge_btn").onclick = createCharge;

    cObj("charge_batches_list_holder").addEventListener("click", function (e) {
        var viewBtn = e.target.closest(".view_batch_students_btn");
        if (viewBtn) {
            viewBatchStudents(viewBtn.getAttribute("data-batch-id"));
        }
    });

    cObj("close_view_batch_students_modal").onclick = function () {
        cObj("view_batch_students_modal").classList.add("hide");
    };
    cObj("close_view_batch_students_btn").onclick = function () {
        cObj("view_batch_students_modal").classList.add("hide");
    };

    // wireInlineSearchBox() is defined in groups.js, loaded before this file.
    wireInlineSearchBox("batch_students_search_box", ".batch_student_row");

}

/* ---------------- Student profile: Charged Account section ---------------- */

function loadChargedAccountSection(admNo) {
    sendData1("GET", "finance/charged_accounts.php", "?get_charged_account=" + encodeURIComponent(admNo), cObj("charged_account_holder_raw"), function () {
        try {
            var resp = JSON.parse(cObj("charged_account_holder_raw").innerText);
            renderChargedAccountSummary(resp.items || [], resp.total || 0);
            renderChargedAccountItemsTable(admNo, resp.items || [], resp.total || 0);
        } catch (e) {
            cObj("charged_account_summary").innerText = "Could not load";
        }
    });
}

function renderChargedAccountSummary(items, total) {
    var el = cObj("charged_account_summary");
    if (!el) return;
    el.innerText = items.length == 0 ? "No charges" : ("Kes " + Number(total).toLocaleString() + " - " + items.length + " item(s)");
}

function renderChargedAccountItemsTable(admNo, items, total) {
    var canEdit = cObj("charged_account_can_edit") && cObj("charged_account_can_edit").value == "1";
    var holder = cObj("charged_account_modal_items_holder");
    if (!holder) return;

    if (items.length == 0) {
        holder.innerHTML = "<p class='text-muted' style='font-size:13px;'>No charges on this student's active module.</p>";
        return;
    }

    var html = "<table class='table table-sm table-bordered'><thead><tr><th>Description</th><th>Period</th><th>Amount</th>" + (canEdit ? "<th>Actions</th>" : "") + "</tr></thead><tbody>";
    items.forEach(function (item) {
        var descEsc = String(item.description).replace(/"/g, "&quot;");
        html += "<tr>"
            + "<td>" + item.description + "</td>"
            + "<td>" + item.period + "</td>"
            + "<td>Kes " + Number(item.amount).toLocaleString() + "</td>";
        if (canEdit) {
            html += "<td>"
                + "<span class='link edit_charge_item_btn' data-adm-no='" + admNo + "' data-charge-id='" + item.charge_id + "' data-description=\"" + descEsc + "\" data-amount='" + item.amount + "'><i class='fas fa-pen'></i></span> "
                + "<span class='link text-danger delete_charge_item_btn' data-adm-no='" + admNo + "' data-charge-id='" + item.charge_id + "' data-description=\"" + descEsc + "\"><i class='fas fa-trash'></i></span>"
                + "</td>";
        }
        html += "</tr>";
    });
    html += "<tr><td colspan='2' class='text-right'><b>Total</b></td><td colspan='" + (canEdit ? 2 : 1) + "'><b>Kes " + Number(total).toLocaleString() + "</b></td></tr>";
    html += "</tbody></table>";
    holder.innerHTML = html;
}

function addChargedAccountItem() {
    var admNo = valObj("charged_account_modal_adm_no");
    var description = valObj("charged_account_modal_description").trim();
    var period = valObj("charged_account_modal_period").trim();
    var amount = valObj("charged_account_modal_amount");
    var feedback = cObj("charged_account_modal_feedback");

    if (description.length == 0 || period.length == 0 || !amount || parseInt(amount) <= 0) {
        feedback.innerHTML = "<span class='text-danger'>Description, period, and a positive amount are required.</span>";
        return;
    }

    var body = "create_charge=true&target_type=students"
        + "&description=" + encodeURIComponent(description)
        + "&period=" + encodeURIComponent(period)
        + "&amount=" + encodeURIComponent(amount)
        + "&student_list=" + encodeURIComponent(JSON.stringify([admNo]));

    feedback.innerHTML = "<span class='text-muted'>Adding...</span>";
    postToChargedAccounts(body, function (resp) {
        if (resp.status == "success") {
            feedback.innerHTML = "<span class='text-success'>" + resp.message + "</span>";
            cObj("charged_account_modal_description").value = "";
            cObj("charged_account_modal_period").value = "";
            cObj("charged_account_modal_amount").value = "";
            loadChargedAccountSection(admNo);
        } else {
            feedback.innerHTML = "<span class='text-danger'>" + resp.message + "</span>";
        }
    }, function () {
        feedback.innerHTML = "<span class='text-danger'>Something went wrong adding the charge.</span>";
    });
}

function openEditChargeItem(admNo, chargeId, description, amount) {
    cObj("edit_charge_adm_no").value = admNo;
    cObj("edit_charge_id").value = chargeId;
    cObj("edit_charge_description").value = description;
    cObj("edit_charge_amount").value = amount;
    cObj("edit_charge_feedback").innerHTML = "";
    cObj("view_student_charged_account_modal").classList.add("hide");
    cObj("edit_charge_item_modal").classList.remove("hide");
}

function saveEditChargeItem() {
    var admNo = cObj("edit_charge_adm_no").value;
    var chargeId = cObj("edit_charge_id").value;
    var description = cObj("edit_charge_description").value.trim();
    var amount = cObj("edit_charge_amount").value;
    var feedback = cObj("edit_charge_feedback");

    if (description.length == 0 || amount === "" || parseInt(amount) < 0) {
        feedback.innerHTML = "<span class='text-danger'>Description and a valid amount are required.</span>";
        return;
    }

    postToChargedAccounts("update_charge_item=true"
        + "&adm_no=" + encodeURIComponent(admNo)
        + "&charge_id=" + encodeURIComponent(chargeId)
        + "&description=" + encodeURIComponent(description)
        + "&amount=" + encodeURIComponent(amount), function (resp) {
        if (resp.status == "success") {
            cObj("edit_charge_item_modal").classList.add("hide");
            cObj("view_student_charged_account_modal").classList.remove("hide");
            loadChargedAccountSection(admNo);
        } else {
            feedback.innerHTML = "<span class='text-danger'>" + resp.message + "</span>";
        }
    }, function () {
        feedback.innerHTML = "<span class='text-danger'>Something went wrong updating the charge.</span>";
    });
}

function openDeleteChargeItem(admNo, chargeId, description) {
    cObj("delete_charge_adm_no").value = admNo;
    cObj("delete_charge_id").value = chargeId;
    cObj("delete_charge_description_label").innerText = description;
    cObj("view_student_charged_account_modal").classList.add("hide");
    cObj("delete_charge_item_modal").classList.remove("hide");
}

function confirmDeleteChargeItem() {
    var admNo = cObj("delete_charge_adm_no").value;
    var chargeId = cObj("delete_charge_id").value;
    postToChargedAccounts("delete_charge_item=true"
        + "&adm_no=" + encodeURIComponent(admNo)
        + "&charge_id=" + encodeURIComponent(chargeId), function (resp) {
        cObj("delete_charge_item_modal").classList.add("hide");
        cObj("view_student_charged_account_modal").classList.remove("hide");
        loadChargedAccountSection(admNo);
    });
}

// Generic small helper for the POST actions in this file: JSON in, JSON out.
function postToChargedAccounts(body, onSuccess, onError) {
    var xml = new XMLHttpRequest();
    xml.open("POST", "ajax/finance/charged_accounts.php", true);
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

function toggleChargeTargetPanels() {
    var isGroup = cObj("charge_target_group").checked;
    cObj("charge_students_target_panel").classList.toggle("hide", isGroup);
    cObj("charge_group_target_panel").classList.toggle("hide", !isGroup);
}

function addChargeAdmNo() {
    var input = cObj("charge_admno_input");
    var admNo = input.value.trim();
    var feedback = cObj("charge_admno_feedback");
    feedback.innerHTML = "";

    if (admNo.length == 0) return;
    if (chargeAdmNos.some(function (s) { return s.adm_no.toLowerCase() === admNo.toLowerCase(); })) {
        feedback.innerHTML = "<span class='text-danger'>That admission number has already been added.</span>";
        return;
    }

    sendData1("GET", "finance/charged_accounts.php", "?lookup_student=" + encodeURIComponent(admNo), cObj("charge_lookup_holder"), function () {
        try {
            var resp = JSON.parse(cObj("charge_lookup_holder").innerText);
            if (resp.status == "success") {
                chargeAdmNos.push({ adm_no: resp.adm_no, name: resp.name });
                renderChargeAdmNoList();
                input.value = "";
                input.focus();
            } else {
                feedback.innerHTML = "<span class='text-danger'>" + resp.message + "</span>";
            }
        } catch (e) {
            feedback.innerHTML = "<span class='text-danger'>Something went wrong looking up that student.</span>";
        }
    });
}

function renderChargeAdmNoList() {
    var holder = cObj("charge_admno_list");
    if (chargeAdmNos.length == 0) {
        holder.innerHTML = "<p class='text-muted' style='font-size:13px;'>No students added yet.</p>";
        return;
    }
    var html = "<ul class='list-group'>";
    chargeAdmNos.forEach(function (s) {
        html += "<li class='list-group-item d-flex justify-content-between align-items-center'>"
            + s.name + " <small style='color:red;'>(" + s.adm_no + ")</small>"
            + "<button type='button' class='btn btn-sm btn-outline-danger remove_charge_admno_btn' data-adm-no='" + s.adm_no + "'><i class='fas fa-times'></i></button>"
            + "</li>";
    });
    html += "</ul>";
    holder.innerHTML = html;
}

function createCharge() {
    var feedback = cObj("create_charge_feedback");
    var targetType = cObj("charge_target_group").checked ? "group" : "students";
    var description = cObj("charge_description").value.trim();
    var period = cObj("charge_period").value.trim();
    var amount = cObj("charge_amount").value;

    if (description.length == 0 || period.length == 0 || !amount || parseInt(amount) <= 0) {
        feedback.innerHTML = "<span class='text-danger'>Description, period, and a positive amount are required.</span>";
        return;
    }

    var body = "create_charge=true"
        + "&target_type=" + encodeURIComponent(targetType)
        + "&description=" + encodeURIComponent(description)
        + "&period=" + encodeURIComponent(period)
        + "&amount=" + encodeURIComponent(amount);

    if (targetType == "group") {
        var groupId = cObj("charge_group_select").value;
        if (!groupId) {
            feedback.innerHTML = "<span class='text-danger'>Select a group.</span>";
            return;
        }
        body += "&group_id=" + encodeURIComponent(groupId);
    } else {
        if (chargeAdmNos.length == 0) {
            feedback.innerHTML = "<span class='text-danger'>Add at least one admission number.</span>";
            return;
        }
        body += "&student_list=" + encodeURIComponent(JSON.stringify(chargeAdmNos.map(function (s) { return s.adm_no; })));
    }

    feedback.innerHTML = "<span class='text-muted'>Creating...</span>";

    postToChargedAccounts(body, function (resp) {
        if (resp.status == "success") {
            feedback.innerHTML = "<span class='text-success'>" + resp.message + "</span>";
            chargeAdmNos = [];
            renderChargeAdmNoList();
            cObj("charge_description").value = "";
            cObj("charge_period").value = "";
            cObj("charge_amount").value = "";
            cObj("charge_group_select").value = "";
        } else {
            feedback.innerHTML = "<span class='text-danger'>" + resp.message + "</span>";
        }
    }, function () {
        feedback.innerHTML = "<span class='text-danger'>Something went wrong creating the charge.</span>";
    });
}

function loadChargeBatches() {
    sendData1("GET", "finance/charged_accounts.php", "?list_charge_batches=true", cObj("charge_batches_list_holder"), function () {
        // ordering:false -- keep the server's "latest first" (date_created DESC) order.
        // DataTables' own sort would otherwise compare the displayed "06 Jul 2026"
        // text lexically, which isn't true chronological order across months.
        $('#charge_batches_table').DataTable({ ordering: false });
    });
}

function viewBatchStudents(batchId) {
    cObj("batch_students_search_box").value = "";
    sendData1("GET", "finance/charged_accounts.php", "?get_batch_students=" + batchId, cObj("batch_students_holder"), function () {
        cObj("view_batch_students_modal").classList.remove("hide");
    });
}
