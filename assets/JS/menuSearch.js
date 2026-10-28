// Menu search (homepage.php #menu_search). Filters the sidebar buttons by their
// label or section name as you type. Enter opens the highlighted match, the up and
// down arrows move between matches and Escape clears the search. Buttons the
// user's role hides stay hidden, since only the ones showing before the search
// are matched.
(function () {
    function init() {
        var input = document.getElementById("menu_search");
        var sidebar = document.getElementById("sideme");
        if (!input || !sidebar) return;
        var clearBtn = document.getElementById("menu_search_clear");
        var empty = document.getElementById("menu_search_empty");
        var active = -1;
        var matches = [];

        function sections() {
            return Array.prototype.filter.call(sidebar.children, function (el) {
                return el.classList.contains("conts") && el.querySelector(".sidebtns");
            });
        }

        function reset() {
            sidebar.querySelectorAll(".ms_nomatch").forEach(function (el) { el.classList.remove("ms_nomatch"); });
            sidebar.querySelectorAll(".ms_active").forEach(function (el) { el.classList.remove("ms_active"); });
        }

        function setActive(index) {
            if (matches[active]) matches[active].classList.remove("ms_active");
            active = index;
            if (matches[active]) {
                matches[active].classList.add("ms_active");
                matches[active].scrollIntoView({ block: "nearest" });
            }
        }

        function filter() {
            var query = input.value.trim().toLowerCase();
            reset();
            matches = [];
            active = -1;
            clearBtn.classList.toggle("hide", query.length === 0);
            empty.classList.add("hide");
            if (query.length === 0) return;

            sections().forEach(function (section) {
                if (getComputedStyle(section).display === "none") return;
                var head = section.querySelector(".navButs");
                var sectionName = head ? head.textContent.trim().toLowerCase() : "";
                var found = 0;
                section.querySelectorAll(".sidebtns").forEach(function (btn) {
                    if (getComputedStyle(btn).display === "none") return;
                    var label = btn.textContent.trim().toLowerCase();
                    if (label.indexOf(query) !== -1 || sectionName.indexOf(query) !== -1) {
                        matches.push(btn);
                        found++;
                    } else {
                        btn.classList.add("ms_nomatch");
                    }
                });
                if (found === 0) section.classList.add("ms_nomatch");
            });

            if (matches.length === 0) {
                empty.classList.remove("hide");
            } else {
                setActive(0);
            }
        }

        function clear() {
            input.value = "";
            filter();
        }

        input.addEventListener("input", filter);
        input.addEventListener("keydown", function (event) {
            if (event.key === "ArrowDown" && matches.length > 0) {
                event.preventDefault();
                setActive((active + 1) % matches.length);
            } else if (event.key === "ArrowUp" && matches.length > 0) {
                event.preventDefault();
                setActive((active - 1 + matches.length) % matches.length);
            } else if (event.key === "Enter" && matches[active]) {
                event.preventDefault();
                var btn = matches[active];
                clear();
                input.blur();
                btn.click();
            } else if (event.key === "Escape") {
                clear();
            }
        });
        clearBtn.addEventListener("click", function () {
            clear();
            input.focus();
        });
        // picking a match with the mouse clears the search so the full menu returns
        sidebar.addEventListener("click", function (event) {
            if (input.value !== "" && event.target.closest(".sidebtns")) clear();
        });
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();
