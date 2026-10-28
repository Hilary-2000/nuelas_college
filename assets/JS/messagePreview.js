// Message samples share one look (see .msg_preview in mainpage.css). The code
// that fills each sample is unchanged; this watches every .msg_bubble and keeps
// a footer under it: the sample's length for an SMS, or an email note, plus a
// note when example tag values (the highlighted words) appear in it.
// The sample uses example values for tags, so the length is a close estimate
// of what recipients get rather than an exact figure.
(function () {
    function updateMeta(bubble) {
        var card = bubble.closest(".msg_preview");
        var meta = card ? card.querySelector(".msg_preview_meta") : null;
        if (!meta) return;
        var text = bubble.textContent.trim();
        if (text.length === 0) {
            meta.innerHTML = "";
            return;
        }
        var html = "";
        if (bubble.classList.contains("is_html")) {
            html += "<span class='msg_badge'><i class='fas fa-envelope'></i> Email preview</span>";
        } else {
            html += "<span class='msg_badge' title='Counted with the example values shown'><i class='fas fa-text-width'></i> ~" + text.length + " characters</span>";
        }
        if (bubble.querySelector("b.text-primary")) {
            html += "<span class='msg_badge msg_badge_tag'><span class='msg_tag_swatch'></span> Example tag values</span>";
        }
        meta.innerHTML = html;
    }

    function watch(bubble) {
        if (bubble._msgPreviewWatched) return;
        bubble._msgPreviewWatched = true;
        new MutationObserver(function () { updateMeta(bubble); })
            .observe(bubble, { childList: true, characterData: true, subtree: true, attributes: true, attributeFilter: ["class"] });
        updateMeta(bubble);
    }

    function init() {
        Array.prototype.forEach.call(document.querySelectorAll(".msg_bubble"), watch);
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();
