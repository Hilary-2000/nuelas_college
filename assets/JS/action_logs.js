// Action Logs: read-only viewer for this school's audit trail
// (ajax/logs/{database_name}/logs.txt, written by log_administration / log_finance /
// log_academic / log_SMS / log_login across the app). Client-side date-range,
// keyword, and username filters over the full log.

var ACTION_LOG_ENTRIES = [];

function escActionLogHtml(text) {
    var div = document.createElement("div");
    div.innerText = text == null ? "" : text;
    return div.innerHTML;
}

function renderActionLogs() {
    var from = cObj("al_from").value ? new Date(cObj("al_from").value + "T00:00:00").getTime() / 1000 : null;
    var to = cObj("al_to").value ? new Date(cObj("al_to").value + "T23:59:59").getTime() / 1000 : null;
    var user = cObj("al_user").value;
    var keyword = cObj("al_keyword").value.trim().toLowerCase();

    var filtered = ACTION_LOG_ENTRIES.filter(function (entry) {
        if (from != null && (entry.ts == null || entry.ts < from)) {
            return false;
        }
        if (to != null && (entry.ts == null || entry.ts > to)) {
            return false;
        }
        if (user && entry.username != user) {
            return false;
        }
        if (keyword && entry.message.toLowerCase().indexOf(keyword) == -1) {
            return false;
        }
        return true;
    });

    cObj("al_count").innerText = "Showing " + filtered.length + " of " + ACTION_LOG_ENTRIES.length + " entries";

    var listEl = cObj("al_list");
    if (filtered.length == 0) {
        listEl.innerHTML = "";
        cObj("al_no_results").classList.remove("hide");
        return;
    }
    cObj("al_no_results").classList.add("hide");

    listEl.innerHTML = filtered.map(function (entry) {
        return "<div style='background:#fff;border-left:3px solid rgb(3,131,135);border-radius:6px;padding:10px 14px;margin-bottom:8px;box-shadow:0 1px 2px rgba(0,0,0,0.06);'>" +
            "<div style='font-size:13.5px;color:#333;'>" + escActionLogHtml(entry.message) + "</div>" +
            "<div style='font-size:11px;color:#999;margin-top:4px;'>" + escActionLogHtml(entry.date_display) + " &bull; <b style='color:rgb(3,131,135);'>" + escActionLogHtml(entry.username) + "</b></div>" +
            "</div>";
    }).join("");
}

function loadActionLogs() {
    cObj("al_list").innerHTML = "<p class='text-muted text-center' style='padding:40px 0;'>Loading…</p>";
    cObj("al_no_results").classList.add("hide");
    fetch("ajax/administration/action_logs.php?get_action_logs=true")
        .then(function (r) { return r.json(); })
        .then(function (entries) {
            ACTION_LOG_ENTRIES = entries || [];

            var users = [];
            ACTION_LOG_ENTRIES.forEach(function (entry) {
                if (entry.username && users.indexOf(entry.username) == -1) {
                    users.push(entry.username);
                }
            });
            users.sort();
            cObj("al_user").innerHTML = "<option value=''>All Users</option>" + users.map(function (u) {
                return "<option value='" + escActionLogHtml(u) + "'>" + escActionLogHtml(u) + "</option>";
            }).join("");

            renderActionLogs();
        })
        .catch(function () {
            cObj("al_list").innerHTML = "<p class='text-danger text-center' style='padding:40px 0;'>Could not load logs.</p>";
        });
}

if (cObj("action_logs_btn")) {
    cObj("action_logs_btn").onclick = function () {
        this.disabled = true;
        setTimeout(() => { this.disabled = false; }, 2000);

        hideWindow();
        unselectbtns();
        addselected(this.id);
        cObj("action_logs_page").classList.remove("hide");
        removesidebar();

        loadActionLogs();
    };
}

if (cObj("al_from")) {
    cObj("al_from").addEventListener("change", renderActionLogs);
    cObj("al_to").addEventListener("change", renderActionLogs);
    cObj("al_user").addEventListener("change", renderActionLogs);
    cObj("al_keyword").addEventListener("input", renderActionLogs);
    cObj("al_clear_filters").onclick = function () {
        cObj("al_from").value = "";
        cObj("al_to").value = "";
        cObj("al_keyword").value = "";
        cObj("al_user").value = "";
        renderActionLogs();
    };
}
