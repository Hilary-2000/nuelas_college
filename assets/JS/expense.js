
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});
// get the data from the database
// var student_data = data;
// get an object by id 
function cObj(id) {
    return document.getElementById(id);
}

function stopInterval(id) {
    clearInterval(id);
}

// The expenses table is a DataTable (search, sort and paging). Each row's
// data stays in expensesTableRows so Edit works on every page of the table.
var expensesTableRows = [];

// load the user data
function getExpensesNDisplay(student_data) {
    expensesTableRows = [];
    if (student_data.length > 0) {
        for (let index = 0; index < student_data.length; index++) {
            const element = student_data[index];
            // the same positions editExpense() reads
            expensesTableRows.push([
                element['exp_amount'], element['expense_name'], element['exp_name'], element['exp_quantity'],
                element['exp_time'], element['exp_unit_cost'], element['expense_date'], element['unit_name'],
                (index + 1), element['expid'], element['expense_categories'], element['date'],
                element['exp_category'], element['document_number'], element['expense_description'], element['exp_sub_category'],
                element['approval_status'], element['approval_comment'], element['record_type'], element['fullname'],
                element['requested_fullname']
            ]);
        }
        showExpenseSummary(expensesTableRows);
        cObj("transDataReciever_expenses").innerHTML = displayRecord_expenses(expensesTableRows);
        if ($.fn.DataTable.isDataTable('#expenses_table')) {
            $('#expenses_table').DataTable().destroy();
        }
        $('#expenses_table').DataTable({
            order: [],
            pageLength: 25,
            columnDefs: [{ orderable: false, targets: -1 }],
            language: { search: "", searchPlaceholder: "Search expenses...", emptyTable: "No expenses recorded yet." },
            drawCallback: function () { initiateTooltip(); }
        });
        setEvents();
    } else {
        cObj("expense_summary").innerHTML = "";
        cObj("transDataReciever_expenses").innerHTML = "<p class='ex_hint'><i class='fas fa-inbox'></i> No expenses recorded yet. Use Add Expense to record the first one.</p>";
    }
}

// Edit buttons are handled once on the table holder, so rows on any page of
// the DataTable work.
function setEvents() {
    var holder = cObj("transDataReciever_expenses");
    if (!holder._editWired) {
        holder._editWired = true;
        holder.addEventListener("click", function (e) {
            var button = e.target.closest(".edit_expense");
            if (button) {
                editExpense.call(button);
            }
        });
    }
    initiateTooltip();
}

// Totals across the loaded expenses: everything, approved, pending, declined.
function showExpenseSummary(rows) {
    var sums = { all: [0, 0], approved: [0, 0], pending: [0, 0], declined: [0, 0] };
    rows.forEach(function (row) {
        var amount = row[0] * 1 || 0;
        var key = row[16] == 1 ? "approved" : (row[16] == 0 ? "pending" : "declined");
        sums.all[0]++;
        sums.all[1] += amount;
        sums[key][0]++;
        sums[key][1] += amount;
    });
    var tile = function (cls, icon, label, pair) {
        return "<div class='ex_stat " + cls + "'><span class='ex_stat_icon'><i class='fas " + icon + "'></i></span><div><small>" + label + "</small><b>Kes " + Math.round(pair[1]).toLocaleString() + "</b><em>" + pair[0] + " expense" + (pair[0] == 1 ? "" : "s") + "</em></div></div>";
    };
    cObj("expense_summary").innerHTML = tile("", "fa-receipt", "All expenses", sums.all) +
        tile("ex_stat_ok", "fa-check-circle", "Approved", sums.approved) +
        tile("ex_stat_wait", "fa-hourglass-half", "Pending approval", sums.pending) +
        tile("ex_stat_bad", "fa-times-circle", "Declined", sums.declined);
}

function activate_amount() {
    var disable = true;
    if (cObj("expense_categories_value") != undefined && cObj("expense_categories_value") != null) {
        let expense_categories_value = valObj("expense_categories_value");
        console.log(expense_categories_value);
        if(hasJsonStructure(expense_categories_value)){
            expense_categories_value = JSON.parse(expense_categories_value);
            for (let index = 0; index < expense_categories_value.length; index++) {
                const element = expense_categories_value[index];
                if (element.expense_id == valObj("exp_cat")) {
                    if (element.running_balance >= this.value) {
                        disable = false;
                    }
                    break;
                }
            }
        }
    }
    if(disable){
        cObj("add_expenseed").classList.add("hide");
        cObj("error_message_expenses").innerHTML = "<p class='text-danger text-center'>You have exceeded your current running balance!</p>";
    }else{
        cObj("error_message_expenses").innerHTML = "";
        cObj("add_expenseed").classList.remove("hide");
    }
}
function editExpense() {
    var this_id = this.id.substr(12);
    var data = expensesTableRows[this_id];
    if (data) {
        cObj("edit_expense_name").value = data[2];
        cObj("total_unit_cost").value = data[0];
        cObj("expense_ids_in").value = data[9];
        cObj("edit_expense_record_date").value = data[11];
        cObj("edit_expense_description").value = data[14];
        cObj("edit_document_number").value = data[13];
        cObj("reason_for_payment_decline").innerText = data[17] != null ? (data[17].length > 0 ? data[17] : "Reason not stated!") : "Reason not stated!";
        console.log(data);
        if(data[18] == "Supplier Payment"){
            cObj("save_expense_details").classList.add("hide");
            cObj("no-edit-supplier-details").classList.remove("hide");
        }else{
            cObj("save_expense_details").classList.remove("hide");
            cObj("no-edit-supplier-details").classList.add("hide");
        }
        
        // set the expense activity
        var edit_expense_cash_activity = cObj("edit_expense_cash_activity").children;
        edit_expense_cash_activity[0].selected = true;
        for (let index = 0; index < edit_expense_cash_activity.length; index++) {
            const element = edit_expense_cash_activity[index];
            if (element.value == data[10]) {
                // console.log(element.value);
                edit_expense_cash_activity[index].selected = true;
            }
        }
        
        cObj("edit_expense_windows").classList.remove("hide");
        cObj("expense_status_view").innerHTML = data[16] == 1 ? "<span class='badge bg-success'>Payment Approved</span>" : (data[16] == 0 ? "<span class='badge bg-warning'>Payment Not Approved Yet!</span>" : "<span class='badge bg-danger'>Payment Declined!</span>");
        
        // disable the update button of payment is already approved
        if(data[16] == 1){
            cObj("delete_promt_expenses").classList.add("hide");
            cObj("total_unit_cost").disabled = true;
            cObj("reason_for_req_decline_window").classList.add("hide");
        }else{
            cObj("delete_promt_expenses").classList.remove("hide");
            cObj("total_unit_cost").disabled = false;
            if (data[16] == 2) {
                cObj("reason_for_req_decline_window").classList.remove("hide");
            }else{
                cObj("reason_for_req_decline_window").classList.add("hide");
            }
        }

        // send data to database
        var datapass = "getExpenseCategories=true&expense_value="+data[12];
        sendDataPost("POST","ajax/administration/admissions.php",datapass,cObj("show_expense_category"),cObj("expense_cat_egories"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }

                // expense category
                if (cObj("expense_cat_egories").classList.contains("hide")) {
                    var exp_cats_exp = document.getElementsByClassName("exp_cats_exp");
                    for (let index = 0; index < exp_cats_exp.length; index++) {
                        const element = exp_cats_exp[index];
                        if (element.value == data[12]) {
                            element.selected = true;
                            break;
                        }
                    }

                    // DISPLAY THE EXPENSE SUBCATEGORIES
                    // display_expense_sub_categories(data[15].split(":")[1]);
                    display_expense_sub_categories(data[12], (data[15] != null ? data[15].split(":")[1] : "-"));

                    // set the listener
                    cObj("edit_expense_category").addEventListener("change", function () {
                        display_expense_sub_categories(this.value,"-");
                    });
                    stopInterval(ids);
                }
            }, 10);
        }, 10);
    }
}

function display_expense_sub_categories(expense_category,expense_sub_category) {
    var datapass = "get_expense_sub_category="+expense_category+"&expense_sub_category="+expense_sub_category;
    sendDataPost("POST","ajax/administration/admissions.php",datapass,cObj("show_expense_sub_category"),cObj("expense_sub_cat_egories"));
}

// The expenses table, all rows at once; DataTables pages it.
function displayRecord_expenses(arrays) {
    var tableData = "<table class='table ex_table' id='expenses_table'><thead><tr><th>#</th><th>Expense</th><th>Category</th><th class='text-end'>Amount</th><th>Document No.</th><th>Date</th><th>Status</th><th>Action</th></tr></thead><tbody>";
    for (let index = 0; index < arrays.length; index++) {
        var row = arrays[index];
        var who = "Requested by " + escHtml(row[20] || "-");
        var status = row[16] == 1 ? "<span class='ex_status ex_status_ok' data-bs-toggle='tooltip' data-bs-html='true' title='" + who + "<br>Approved by " + escHtml(row[19] || "-") + "'><i class='fas fa-check'></i> Approved</span>"
            : (row[16] == 0 ? "<span class='ex_status ex_status_wait' data-bs-toggle='tooltip' data-bs-html='true' title='" + who + "<br>Waiting for approval'><i class='fas fa-hourglass-half'></i> Pending</span>"
            : "<span class='ex_status ex_status_bad' data-bs-toggle='tooltip' data-bs-html='true' title='" + who + "<br>Declined by " + escHtml(row[19] || "-") + "'><i class='fas fa-times'></i> Declined</span>");
        var name = row[2] != null && row[2] !== "" && row[2] !== "null" ? escHtml(row[2]) : "<span class='ex_muted'>" + (row[18] == "Supplier Payment" ? "Supplier payment" : "Unnamed expense") + "</span>";
        var category = row[1] != null && row[1] !== "" ? escHtml(row[1]) : "<span class='ex_muted'>-</span>";
        var amount = (row[0] * 1 || 0);
        tableData += "<tr>" +
            "<td>" + (index + 1) + "</td>" +
            "<td><b class='ex_name'>" + name + "</b>" + (row[18] == "Supplier Payment" ? " <span class='ex_type'>Supplier</span>" : "") + "</td>" +
            "<td>" + category + "</td>" +
            "<td class='text-end' data-order='" + amount + "'><b>Kes " + amount.toLocaleString() + "</b></td>" +
            "<td>" + (row[13] != null && row[13] !== "" ? escHtml(row[13]) : "<span class='ex_muted'>-</span>") + "</td>" +
            "<td data-order='" + escHtml(row[11] || "") + "'>" + escHtml(row[6] || "") + "</td>" +
            "<td>" + status + "</td>" +
            "<td><span class='edit_expense ex_edit' role='button' tabindex='0' id='edit_expense" + index + "'><i class='fas fa-pen'></i> Edit</span></td>" +
            "</tr>";
    }
    tableData += "</tbody></table>";
    return tableData;
}
function sortDesc(arrays,index){
    arrays = arrays.sort(sortFunction);
    function sortFunction(a, b) {
        if (a[index] === b[index]) {
            return 0;
        }
        else {
            return (a[index] > b[index]) ? -1 : 1;
        }
    }
    return arrays;
}
function sortAsc(arrays,index){
    arrays = arrays.sort(sortFunction);
    function sortFunction(a, b) {
        if (a[index] === b[index]) {
            return 0;
        }
        else {
            return (a[index] < b[index]) ? -1 : 1;
        }
    }
    return arrays;
}
// The expense and payment approval windows and their popups use
// role="button" elements for their actions, so Enter and Space click them like buttons.
var expense_key_windows = ["expenses_win", "payment_approval_window", "view_payment_request", "edit_expense_windows"];
expense_key_windows.forEach(function (id) {
    cObj(id).addEventListener("keydown", function (e) {
        var el = e.target;
        if (el.getAttribute && el.getAttribute("role") === "button" && (e.key === "Enter" || e.key === " ")) {
            e.preventDefault();
            el.click();
        }
    });
});
