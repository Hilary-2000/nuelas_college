// ---------------------------------------------------------------------------
// Tag suggestions in message boxes. Typing "/" (at the start of the message or
// after a space) opens a list of tags at the cursor. Typing a few words after
// the slash, such as "/parent first", filters the list by tag name, label and
// group. Arrow keys move through it, Enter or Tab (or a click) replaces the
// "/words" with the chosen tag, and Escape closes it.
//
// attachTagSuggest(textarea, getTags, example) works on any textarea or text
// input. attachQuillTagSuggest(quillId, getTags, example) does the same for a
// Quill rich text (email) editor; it attaches the first time the editor is
// focused, so it does not matter when the editor was created.
// getTags returns [{tag: "{par_first_name}", label: "First name", group: "Parent"}, ...].
// With an example ({typed: "/parent first", tag: "{par_first_name}"}) a one
// line tip is shown under the box while it has tags to offer.
//
// The big tag panels start collapsed to a single line (see makeCollapsible);
// each viewer's open/closed choice is remembered in their browser. Clicking a
// chip is handled in assets/JS/sms.js (insertMsgTag).
// ---------------------------------------------------------------------------
(function () {
    var MAX_QUERY = 40;
    var popup = null;
    // The open list: {box, start, query, items, active}. "box" is the adapter
    // for the textarea or editor being typed in (see textareaBox/quillBox).
    var state = null;
    // The "/" the user closed with Escape. It stays closed while they keep
    // typing after it; only a new "/" opens the list again.
    var dismissed = null; // {box, start}

    // Where the caret sits inside a textarea, measured with a hidden copy of
    // the textarea that has the same font, padding and wrapping.
    var MIRROR_PROPS = ["boxSizing", "width", "height", "overflowX", "overflowY", "borderTopWidth", "borderRightWidth",
        "borderBottomWidth", "borderLeftWidth", "paddingTop", "paddingRight", "paddingBottom", "paddingLeft",
        "fontStyle", "fontVariant", "fontWeight", "fontStretch", "fontSize", "lineHeight", "fontFamily",
        "textAlign", "textTransform", "textIndent", "letterSpacing", "wordSpacing", "tabSize"];

    function caretCoordinates(textarea, position) {
        var style = window.getComputedStyle(textarea);
        var mirror = document.createElement("div");
        MIRROR_PROPS.forEach(function (prop) { mirror.style[prop] = style[prop]; });
        mirror.style.position = "absolute";
        mirror.style.visibility = "hidden";
        mirror.style.top = "0";
        mirror.style.left = "-9999px";
        // A single-line input never wraps; a textarea wraps like the text it holds.
        var singleLine = textarea.tagName === "INPUT";
        mirror.style.whiteSpace = singleLine ? "pre" : "pre-wrap";
        mirror.style.wordWrap = singleLine ? "normal" : "break-word";
        mirror.textContent = textarea.value.substring(0, position);
        var marker = document.createElement("span");
        marker.textContent = textarea.value.substring(position) || ".";
        mirror.appendChild(marker);
        document.body.appendChild(mirror);
        var lineHeight = parseFloat(style.lineHeight) || parseFloat(style.fontSize) * 1.4;
        var coords = { top: marker.offsetTop, left: marker.offsetLeft, height: lineHeight };
        document.body.removeChild(mirror);
        return coords;
    }

    function escapeHtml(text) {
        var div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML;
    }

    // The "/words" right before the caret in a piece of text, or null. The
    // slash must start the text or follow a space, so dates (1/2) and links
    // (https://) do not open the list, and "/ " is an ordinary slash in a
    // sentence ("and / or"), not a search.
    function findQuery(before) {
        var slash = before.lastIndexOf("/");
        if (slash === -1) return null;
        var query = before.substring(slash + 1);
        if (query.length > MAX_QUERY || /[\n{}\/]/.test(query)) return null;
        if (/^\s/.test(query)) return null;
        if (slash > 0 && !/\s/.test(before.charAt(slash - 1))) return null;
        return { start: slash, text: query };
    }

    // ----- Box adapters: how to read, place and replace text in each kind -----

    function textareaBox(textarea) {
        return {
            el: textarea,
            query: function () {
                if (textarea.selectionStart !== textarea.selectionEnd) return null;
                return findQuery(textarea.value.substring(0, textarea.selectionStart));
            },
            anchor: function (q) {
                var rect = textarea.getBoundingClientRect();
                var caret = caretCoordinates(textarea, q.start);
                var top = rect.top + caret.top - textarea.scrollTop;
                return { top: top, bottom: top + caret.height, left: rect.left + caret.left - textarea.scrollLeft };
            },
            replace: function (q, tag) {
                var caret = textarea.selectionStart;
                textarea.value = textarea.value.substring(0, q.start) + tag + textarea.value.substring(caret);
                var pos = q.start + tag.length;
                textarea.focus();
                textarea.setSelectionRange(pos, pos);
                // Refresh the message sample the same way typing does, whether the
                // box listens through onkeyup or addEventListener.
                textarea.dispatchEvent(new Event("keyup", { bubbles: true }));
                textarea.dispatchEvent(new Event("input", { bubbles: true }));
            }
        };
    }

    // A Quill editor keeps its own text model: the query is read from the text
    // before the caret, and the tag replaces that stretch through Quill so its
    // text-change listeners (the email previews) run as they do for typing.
    function quillBox(quill) {
        return {
            el: quill.root,
            query: function () {
                var range = quill.getSelection();
                if (!range || range.length > 0) return null;
                // Only the current line matters; Quill ends each line with "\n".
                var before = quill.getText(0, range.index);
                var lineStart = before.lastIndexOf("\n") + 1;
                var q = findQuery(before.substring(lineStart));
                if (q) q.start += lineStart;
                return q;
            },
            anchor: function (q) {
                var rect = quill.container.getBoundingClientRect();
                var b = quill.getBounds(q.start);
                var top = rect.top + b.top;
                return { top: top, bottom: top + b.height, left: rect.left + b.left };
            },
            replace: function (q, tag) {
                var range = quill.getSelection(true);
                var end = range ? range.index : q.start + 1 + q.text.length;
                quill.deleteText(q.start, end - q.start, "user");
                quill.insertText(q.start, tag, "user");
                quill.setSelection(q.start + tag.length, 0, "user");
            }
        };
    }

    function matchTags(tags, query) {
        var words = query.toLowerCase().split(/\s+/).filter(Boolean);
        var cleanTagQuery = query.toLowerCase().replace(/[{}\s]/g, "");
        return tags.map(function (t, index) {
            var haystack = (t.label + " " + t.group + " " + t.tag).toLowerCase();
            if (!words.every(function (w) { return haystack.indexOf(w) !== -1; })) return null;
            var tagName = t.tag.toLowerCase().replace(/[{}]/g, "");
            var label = t.label.toLowerCase();
            var score = 3;
            if (words.length === 0) score = 3;
            else if (tagName.indexOf(cleanTagQuery) === 0) score = 0;
            else if (label.indexOf(query.toLowerCase().trim()) === 0) score = 1;
            else if (label.indexOf(words[0]) !== -1) score = 2;
            return { tag: t, score: score, index: index };
        }).filter(Boolean).sort(function (a, b) {
            return a.score - b.score || a.index - b.index;
        }).map(function (m) { return m.tag; });
    }

    function ensurePopup() {
        if (popup) return popup;
        popup = document.createElement("div");
        popup.className = "tag_suggest hide";
        popup.setAttribute("role", "listbox");
        // Keep focus in the message box when an option is clicked.
        popup.addEventListener("mousedown", function (e) { e.preventDefault(); });
        popup.addEventListener("click", function (e) {
            var item = e.target.closest(".tag_suggest_item");
            if (item && state) choose(parseInt(item.getAttribute("data-index"), 10));
        });
        document.body.appendChild(popup);
        return popup;
    }

    function render() {
        var box = ensurePopup();
        box.innerHTML = "<div class='tag_suggest_head'>Insert a tag <span>&uarr;&darr; to move, Enter to insert, Esc to close</span></div>" +
            state.items.map(function (t, i) {
                return "<div class='tag_suggest_item" + (i === state.active ? " is_active" : "") + "' role='option' data-index='" + i + "'>" +
                    "<span class='tag_suggest_label'>" + escapeHtml(t.label) + "</span>" +
                    "<span class='tag_suggest_group'>" + escapeHtml(t.group) + "</span>" +
                    "<code>" + escapeHtml(t.tag) + "</code></div>";
            }).join("");
        var active = box.querySelector(".is_active");
        if (active) active.scrollIntoView({ block: "nearest" });
        position();
    }

    function position() {
        var at = state.box.anchor(state);
        var box = popup;
        box.classList.remove("hide");
        // Open below the line when there is room, otherwise on whichever side
        // has more space, and shrink the list to fit that space.
        var spaceBelow = window.innerHeight - at.bottom - 12;
        var spaceAbove = at.top - 12;
        var below = spaceBelow >= 200 || spaceBelow >= spaceAbove;
        box.style.maxHeight = Math.max(120, Math.min(290, below ? spaceBelow : spaceAbove)) + "px";
        var width = box.offsetWidth;
        var height = box.offsetHeight;
        var left = at.left;
        if (left + width > window.innerWidth - 8) left = window.innerWidth - width - 8;
        var top = below ? at.bottom + 4 : at.top - height - 4;
        box.style.left = Math.max(8, left) + "px";
        box.style.top = Math.max(8, top) + "px";
    }

    function close() {
        state = null;
        if (popup) popup.classList.add("hide");
    }

    function choose(index) {
        if (!state || !state.items[index]) return;
        var current = state;
        close();
        current.box.replace(current, current.items[index].tag);
    }

    function sameSlash(a, box, q) {
        return a && a.box === box && q && a.start === q.start;
    }

    function update(box, getTags) {
        var q = box.query();
        if (dismissed && dismissed.box === box && !sameSlash(dismissed, box, q)) dismissed = null;
        // No query, no tags for this box right now (e.g. a staff broadcast),
        // or the user closed this "/" with Escape.
        var tags = q ? getTags() : [];
        if (!q || tags.length === 0 || (dismissed && dismissed.box === box)) {
            if (state && state.box === box) close();
            return;
        }
        var items = matchTags(tags, q.text);
        // Nothing matches: the user is just writing, so get out of the way
        // instead of leaving an empty list over their text.
        if (items.length === 0) { if (state && state.box === box) close(); return; }
        // Keep the highlighted row only while the query itself is unchanged
        // (e.g. the caret was just clicked); new letters start from the top.
        var keep = state && sameSlash(state, box, q) && state.query === q.text;
        var active = keep ? Math.min(state.active, items.length - 1) : 0;
        state = { box: box, start: q.start, text: q.text, query: q.text, items: items, active: active };
        render();
    }

    function wireBox(box, getTags) {
        var el = box.el;
        el.addEventListener("input", function () { update(box, getTags); });
        el.addEventListener("click", function () { update(box, getTags); });
        el.addEventListener("keyup", function (e) {
            if (["ArrowLeft", "ArrowRight", "Home", "End"].indexOf(e.key) !== -1) update(box, getTags);
        });
        el.addEventListener("blur", function () { if (state && state.box === box) close(); });
        el.addEventListener("scroll", function () { if (state && state.box === box) position(); });
    }

    // The one-line tip, placed after "after" (the box, or for a Quill editor
    // its container, below the toolbar and the text).
    function addHint(after, getTags, example, isHidden) {
        var hint = document.createElement("p");
        hint.className = "tag_hint";
        hint.innerHTML = "<i class='fas fa-keyboard'></i> Type <kbd>/</kbd> to insert a tag, e.g. <code>" + escapeHtml(example.typed) +
            "</code> <i class='fas fa-arrow-right'></i> <code>" + escapeHtml(example.tag) + "</code>";
        after.insertAdjacentElement("afterend", hint);
        return function () { hint.classList.toggle("hide", getTags().length === 0 || isHidden()); };
    }

    window.attachTagSuggest = function (textarea, getTags, example) {
        if (!textarea || textarea.getAttribute("data-tag-suggest") === "1") return function () {};
        textarea.setAttribute("data-tag-suggest", "1");
        var refreshHint = example ? addHint(textarea, getTags, example, function () { return textarea.classList.contains("hide"); }) : function () {};
        refreshHint();
        textarea.addEventListener("focus", refreshHint);
        wireBox(textareaBox(textarea), getTags);
        return refreshHint;
    };

    // Quill editors are attached the first time one is focused.
    var quillConfigs = {}; // quillId -> getTags
    window.attachQuillTagSuggest = function (quillId, getTags, example) {
        var container = document.getElementById(quillId);
        if (!container || quillConfigs[quillId]) return function () {};
        quillConfigs[quillId] = getTags;
        var refreshHint = example ? addHint(container, getTags, example, function () { return false; }) : function () {};
        refreshHint();
        return refreshHint;
    };

    document.addEventListener("focusin", function (e) {
        if (typeof quillInstances === "undefined" || !e.target.closest) return;
        var root = e.target.closest(".ql-editor");
        if (!root || root.getAttribute("data-tag-suggest") === "1") return;
        Object.keys(quillConfigs).forEach(function (id) {
            var quill = quillInstances[id];
            if (quill && quill.root === root) {
                root.setAttribute("data-tag-suggest", "1");
                var box = quillBox(quill);
                var getTags = quillConfigs[id];
                // Quill reports both typing and caret moves here.
                quill.on("editor-change", function (eventName, range) {
                    if (eventName === "selection-change" && !range) {
                        if (state && state.box === box) close();
                        return;
                    }
                    update(box, getTags);
                });
            }
        });
    }, true);

    // While the list is open, its keys are handled here first, so neither the
    // page nor a rich editor can swallow Escape or turn Enter into a new line.
    document.addEventListener("keydown", function (e) {
        if (!state) return;
        var handled = true;
        if (e.key === "Escape" || e.key === "Esc") {
            dismissed = { box: state.box, start: state.start };
            close();
        } else if (e.key === "ArrowDown") {
            state.active = (state.active + 1) % state.items.length;
            render();
        } else if (e.key === "ArrowUp") {
            state.active = (state.active - 1 + state.items.length) % state.items.length;
            render();
        } else if (e.key === "Enter" || e.key === "Tab") {
            choose(state.active);
        } else {
            handled = false;
        }
        if (handled) {
            e.preventDefault();
            e.stopPropagation();
        }
    }, true);

    window.addEventListener("resize", function () { if (state) position(); });
    document.addEventListener("scroll", function () { if (state) position(); }, true);

    function storageGet(key) {
        try { return window.localStorage.getItem(key); } catch (e) { return null; }
    }
    function storageSet(key, value) {
        try { window.localStorage.setItem(key, value); } catch (e) { }
    }

    // Folds a tag panel to its header line: "Message Tags · 19 available ·
    // type / in a message box · Show all". The chips stay in the page (the
    // "/" list reads them), they are only hidden.
    function makeCollapsible(panel, key) {
        if (!panel || panel.getAttribute("data-collapsible") === "1") return;
        panel.setAttribute("data-collapsible", "1");
        var head = panel.querySelector(".msg_tags_head");
        var body = document.createElement("div");
        body.className = "msg_tags_body";
        Array.prototype.slice.call(panel.children).forEach(function (child) {
            if (child !== head) body.appendChild(child);
        });
        panel.appendChild(body);

        var count = panel.querySelectorAll(".msg_tag").length;
        var summary = document.createElement("span");
        summary.className = "msg_tags_summary";
        summary.innerHTML = count + " available &middot; type <kbd>/</kbd> in a message box to insert one";
        var toggle = document.createElement("span");
        toggle.className = "msg_tags_toggle";
        toggle.setAttribute("role", "button");
        toggle.setAttribute("tabindex", "0");
        head.appendChild(summary);
        head.appendChild(toggle);

        var storeKey = "cmis_msg_tags_open_" + key;
        function set(open) {
            panel.classList.toggle("is_collapsed", !open);
            toggle.innerHTML = open ? "Hide <i class='fas fa-chevron-up'></i>" : "Show all <i class='fas fa-chevron-down'></i>";
            toggle.setAttribute("aria-expanded", open ? "true" : "false");
        }
        set(storageGet(storeKey) === "1");
        function flip() {
            var open = panel.classList.contains("is_collapsed");
            set(open);
            storageSet(storeKey, open ? "1" : "0");
        }
        toggle.addEventListener("click", flip);
        toggle.addEventListener("keydown", function (e) {
            if (e.key === "Enter" || e.key === " ") { e.preventDefault(); flip(); }
        });
    }

    // ----- Reading tags from the page -----
    // Tags always come from the chips the page shows, so the "/" list matches them.

    function prettyTagName(tag) {
        return tag.replace(/[{}]/g, "").replace(/_/g, " ").replace(/^\w/, function (c) { return c.toUpperCase(); });
    }

    // The chip panels (.msg_tag): "Full name {stud_fullname}".
    function tagsFromChips(chips, fallbackGroup) {
        var seen = {};
        var tags = [];
        Array.prototype.forEach.call(chips, function (chip) {
            var tag = chip.getAttribute("data-tag") || (chip.querySelector("code") ? chip.querySelector("code").textContent.trim() : "");
            if (!tag || seen[tag]) return;
            seen[tag] = true;
            var label = "";
            for (var i = 0; i < chip.childNodes.length; i++) {
                if (chip.childNodes[i].nodeType === 3) label += chip.childNodes[i].textContent;
            }
            label = label.trim() || prettyTagName(tag);
            var groupEl = chip.closest(".msg_tags_group");
            var group = groupEl ? groupEl.querySelector(".msg_tags_group_title").textContent.trim() : fallbackGroup;
            group = group.charAt(0).toUpperCase() + group.slice(1).toLowerCase();
            tags.push({ tag: tag, label: label, group: group });
        });
        return tags;
    }

    // ----- Message Templates page -----
    function initMessageTemplates() {
        var page = document.getElementById("sms_templates_window");
        if (!page) return;
        var panel = page.querySelector(".msg_tags");
        var mainTags = panel ? tagsFromChips(panel.querySelectorAll(".msg_tag"), "Tags") : [];
        var example = { typed: "/parent first", tag: "{par_first_name}" };
        // Boxes with their own short tag row (module progression) offer only those tags.
        function ownTags(id) {
            var chips = page.querySelectorAll('.msg_tag[data-target="' + id + '"]');
            return chips.length ? tagsFromChips(chips, "Tags") : mainTags;
        }
        var boxes = panel ? panel.getAttribute("data-boxes").split(",") : [];
        boxes.forEach(function (id) {
            id = id.trim();
            var tags = ownTags(id);
            var getTags = function () { return tags; };
            if (/_email$/.test(id)) attachQuillTagSuggest(id, getTags, example);
            else attachTagSuggest(document.getElementById(id), getTags, example);
        });
        makeCollapsible(panel, "templates");
    }

    // ----- Broadcast Message -----
    // Tags are only filled in for parent broadcasts, so staff broadcasts get none.
    function initBroadcast() {
        var panel = document.getElementById("message_tags_window");
        if (!panel) return;
        var tags = tagsFromChips(panel.querySelectorAll(".msg_tag"), "Tags");
        var recipients = document.getElementById("select_recipients2");
        var getTags = function () { return recipients && recipients.value === "parents" ? tags : []; };
        var example = { typed: "/parent first", tag: "{par_first_name}" };
        var refreshSms = attachTagSuggest(document.getElementById("text_message2"), getTags, example);
        var refreshEmail = attachQuillTagSuggest("email_editored", getTags, example);
        if (recipients) {
            recipients.addEventListener("change", refreshSms);
            recipients.addEventListener("change", refreshEmail);
        }
        makeCollapsible(panel.querySelector(".msg_tags"), "broadcast");
    }

    // ----- Report cards: directors comments and the report card email -----
    function initReportCards() {
        var commentsPanel = document.querySelector('.msg_tags[data-scope="students_commentators"]');
        if (commentsPanel) {
            var commentTags = tagsFromChips(commentsPanel.querySelectorAll(".msg_tag"), "Tags");
            var scope = document.getElementById("students_commentators");
            // The comment boxes are loaded per class, so each is attached when first used.
            if (scope) {
                scope.addEventListener("focusin", function (e) {
                    if (e.target.tagName === "TEXTAREA") attachTagSuggest(e.target, function () { return commentTags; });
                });
            }
            makeCollapsible(commentsPanel, "report_comments");
        }
        var emailPanel = document.querySelector('.msg_tags[data-boxes="email_contents_exam_reports"]');
        if (emailPanel) {
            var emailTags = tagsFromChips(emailPanel.querySelectorAll(".msg_tag"), "Tags");
            attachQuillTagSuggest("email_contents_exam_reports", function () { return emailTags; },
                { typed: "/first", tag: "{firstname}" });
            makeCollapsible(emailPanel, "report_email");
        }
    }

    function initAll() {
        initMessageTemplates();
        initBroadcast();
        initReportCards();
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initAll);
    } else {
        initAll();
    }
})();
