// ---------------------------------------------------------------------------
// Idle screen lock. After the school's idle timeout (default 5 minutes, set
// under Settings > Idle Screen Lock) the page is covered by a blurred lock
// popup that asks for the user's password. The user is not logged out; the
// lock only restricts access until the password is entered again.
//
// Activity and lock state are shared between open tabs through localStorage,
// so working in one tab keeps the others unlocked, and locking or unlocking
// one tab does the same to the rest. The server also keeps a lock flag in the
// session so reloading the page does not skip the password prompt.
// ---------------------------------------------------------------------------
(function () {
    var IDLE_ENDPOINT = "ajax/login/idle_lock.php";
    var KEY_ACTIVITY = "cmis_idle_last_activity";
    var KEY_LOCKED = "cmis_idle_locked";
    var KEY_MINUTES = "cmis_idle_minutes";

    var idleMinutes = 5;
    var canEditSettings = false;
    var lastActivity = Date.now();
    var lastActivityShared = 0;
    var locked = false;
    var inertedNodes = [];

    function storageGet(key) {
        try { return window.localStorage.getItem(key); } catch (e) { return null; }
    }
    function storageSet(key, value) {
        try { window.localStorage.setItem(key, value); } catch (e) { }
    }

    function postIdle(params) {
        var body = new URLSearchParams(params);
        return fetch(IDLE_ENDPOINT, {
            method: "POST",
            credentials: "same-origin",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: body.toString()
        }).then(function (res) { return res.json(); });
    }

    function minutesLabel(minutes) {
        if (minutes % 60 === 0 && minutes >= 60) {
            var hours = minutes / 60;
            return hours + (hours === 1 ? " hour" : " hours");
        }
        return minutes + (minutes === 1 ? " minute" : " minutes");
    }

    function renderLockNotes() {
        cObj("idle_lock_minutes_label").innerText = minutesLabel(idleMinutes);
        cObj("idle_lock_settings_hint").classList.toggle("hide", !canEditSettings);
    }

    // Keep keyboard focus and screen readers out of the page behind the lock.
    function setPageInert(on) {
        if (on) {
            var overlay = cObj("idle_lock_window");
            Array.prototype.forEach.call(document.body.children, function (node) {
                if (node !== overlay && !node.hasAttribute("inert") && node.tagName !== "SCRIPT") {
                    node.setAttribute("inert", "");
                    inertedNodes.push(node);
                }
            });
        } else {
            inertedNodes.forEach(function (node) { node.removeAttribute("inert"); });
            inertedNodes = [];
        }
    }

    function showLock() {
        if (locked) return;
        locked = true;
        renderLockNotes();
        cObj("idle_lock_password").value = "";
        cObj("idle_lock_error").innerText = "";
        cObj("idle_lock_window").classList.remove("hide");
        setPageInert(true);
        setTimeout(function () { cObj("idle_lock_password").focus(); }, 50);
    }

    function hideLock() {
        locked = false;
        setPageInert(false);
        cObj("idle_lock_window").classList.add("hide");
        cObj("idle_lock_password").value = "";
        cObj("idle_lock_error").innerText = "";
        markActivity(true);
    }

    function lockNow() {
        if (locked) return;
        showLock();
        storageSet(KEY_LOCKED, "1");
        postIdle({ action: "lock" }).catch(function () { });
    }

    function idleExpired() {
        var shared = parseInt(storageGet(KEY_ACTIVITY) || "0", 10);
        var latest = Math.max(lastActivity, isNaN(shared) ? 0 : shared);
        return Date.now() - latest >= idleMinutes * 60000;
    }

    function markActivity(force) {
        if (locked) return;
        // Browsers pause or slow the idle check while the computer sleeps or
        // the tab sits in the background, so the user's first move on return
        // can arrive before the overdue check runs. Check first, so that
        // move locks the page instead of resetting the timer.
        if (!force && idleExpired()) {
            lockNow();
            return;
        }
        lastActivity = Date.now();
        // Writing on every mouse move would be wasteful; other tabs only need
        // to know roughly when this one was last used.
        if (force || lastActivity - lastActivityShared > 5000) {
            lastActivityShared = lastActivity;
            storageSet(KEY_ACTIVITY, String(lastActivity));
        }
    }

    function checkIdle() {
        if (locked) return;
        if (idleExpired()) {
            lockNow();
        }
    }

    function attemptUnlock() {
        var btn = cObj("idle_lock_unlock");
        btn.disabled = true;
        setTimeout(function () { btn.disabled = false; }, 2000);

        var password = cObj("idle_lock_password").value;
        if (password.length === 0) {
            cObj("idle_lock_error").innerText = "Enter your password to continue.";
            cObj("idle_lock_password").focus();
            return;
        }
        cObj("idle_lock_loader").classList.remove("hide");
        postIdle({ action: "unlock", password: password }).then(function (data) {
            cObj("idle_lock_loader").classList.add("hide");
            if (data.status === "success") {
                storageSet(KEY_LOCKED, "0");
                hideLock();
            } else {
                cObj("idle_lock_error").innerText = data.message || "Incorrect password. Try again.";
                cObj("idle_lock_password").value = "";
                cObj("idle_lock_password").focus();
            }
        }).catch(function () {
            cObj("idle_lock_loader").classList.add("hide");
            cObj("idle_lock_error").innerText = "Could not verify your password. Check your connection and try again.";
        });
    }

    function logOutFromLock() {
        var btn = cObj("idle_lock_logout");
        btn.disabled = true;
        setTimeout(function () { btn.disabled = false; }, 2000);

        storageSet(KEY_LOCKED, "0");
        fetch("ajax/login/changevalues.php?changeval=true", { credentials: "same-origin" })
            .catch(function () { })
            .then(function () { window.location.href = "login.php"; });
    }

    // ----- Settings > Idle Screen Lock card -----
    function loadIdleSettingsCard() {
        if (!cObj("idle_lock_timeout_input")) return;
        cObj("idle_lock_settings_loader").classList.remove("hide");
        fetch(IDLE_ENDPOINT + "?action=get_settings", { credentials: "same-origin" })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                cObj("idle_lock_settings_loader").classList.add("hide");
                if (data.status === "success") {
                    cObj("idle_lock_timeout_input").value = data.minutes;
                }
            }).catch(function () {
                cObj("idle_lock_settings_loader").classList.add("hide");
            });
    }

    function saveIdleSettingsCard() {
        var btn = cObj("save_idle_lock_timeout");
        btn.disabled = true;
        setTimeout(function () { btn.disabled = false; }, 2000);

        var output = cObj("idle_lock_settings_output");
        var minutes = parseInt(cObj("idle_lock_timeout_input").value, 10);
        if (isNaN(minutes) || minutes < 1 || minutes > 1440) {
            output.innerHTML = "<p class='text-danger'>Enter a timeout between 1 and 1440 minutes.</p>";
            return;
        }
        cObj("idle_lock_settings_loader").classList.remove("hide");
        postIdle({ action: "save_timeout", minutes: minutes }).then(function (data) {
            cObj("idle_lock_settings_loader").classList.add("hide");
            if (data.status === "success") {
                idleMinutes = data.minutes;
                storageSet(KEY_MINUTES, String(idleMinutes));
                output.innerHTML = "<p class='text-success'>" + data.message + "</p>";
            } else {
                output.innerHTML = "<p class='text-danger'>" + (data.message || "Could not save the timeout.") + "</p>";
            }
            setTimeout(function () { output.innerHTML = ""; }, 3000);
        }).catch(function () {
            cObj("idle_lock_settings_loader").classList.add("hide");
            output.innerHTML = "<p class='text-danger'>Could not save the timeout. Check your connection and try again.</p>";
        });
    }

    function init() {
        if (!cObj("idle_lock_window")) return;

        ["mousemove", "mousedown", "keydown", "touchstart", "wheel", "scroll"].forEach(function (evt) {
            document.addEventListener(evt, function () { markActivity(false); }, { capture: true, passive: true });
        });

        document.addEventListener("visibilitychange", function () {
            if (document.visibilityState === "visible") checkIdle();
        });
        window.addEventListener("pageshow", checkIdle);

        window.addEventListener("storage", function (e) {
            if (e.key === KEY_LOCKED) {
                if (e.newValue === "1") showLock();
                else if (e.newValue === "0" && locked) hideLock();
            } else if (e.key === KEY_MINUTES && e.newValue) {
                var m = parseInt(e.newValue, 10);
                if (!isNaN(m) && m > 0) idleMinutes = m;
            }
        });

        cObj("idle_lock_unlock").onclick = attemptUnlock;
        cObj("idle_lock_logout").onclick = logOutFromLock;
        // Listen on the whole document so Enter still unlocks after the user
        // clicks somewhere on the card and focus falls back to <body>.
        document.addEventListener("keydown", function (e) {
            if (locked && e.key === "Enter" && e.target.tagName !== "BUTTON") {
                e.preventDefault();
                if (!cObj("idle_lock_unlock").disabled) attemptUnlock();
            }
        });

        if (cObj("set_btns")) cObj("set_btns").addEventListener("click", loadIdleSettingsCard);
        if (cObj("save_idle_lock_timeout")) cObj("save_idle_lock_timeout").onclick = saveIdleSettingsCard;

        // homepage.php renders the popup already visible when the session is
        // locked, so the page never shows before the password prompt.
        if (!cObj("idle_lock_window").classList.contains("hide")) {
            locked = true;
            setPageInert(true);
            cObj("idle_lock_password").focus();
        } else {
            markActivity(true);
        }

        fetch(IDLE_ENDPOINT + "?action=get_settings", { credentials: "same-origin" })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data.status !== "success") return;
                idleMinutes = data.minutes;
                canEditSettings = !!data.can_edit;
                if (data.locked) {
                    if (locked) renderLockNotes();
                    else showLock();
                } else if (locked) {
                    // Unlocked from another tab between the page render and now.
                    storageSet(KEY_LOCKED, "0");
                    hideLock();
                } else if (storageGet(KEY_LOCKED) === "1") {
                    // A stale flag from a tab that closed while locked; the
                    // server says this session is unlocked, so trust that.
                    storageSet(KEY_LOCKED, "0");
                }
            }).catch(function () { })
            .then(function () { setInterval(checkIdle, 5000); });
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();
