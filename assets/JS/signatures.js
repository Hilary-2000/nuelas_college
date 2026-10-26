// Staff signatures and the college stamp.
// - The editor popup (#sig_editor_popup in homepage.php): draw a signature, or upload a
//   photo of ink on paper, crop/rotate it, and remove the paper so only the ink is kept
//   as a transparent PNG.
// - "My Signature" on the personal profile, the Signature card on the edit staff page,
//   and Settings > Signatures & College Stamp.
// Server side: ajax/signatures/signatures.php
(function () {
    var ENDPOINT = "ajax/signatures/signatures.php";
    var MAX_WORK_SIDE = 1400; // uploaded photos are scaled down to this before editing
    var MAX_OUTPUT_SIDE = 1200; // the saved PNG is never bigger than this

    // ---------------------------------------------------------------- helpers
    function guard(button) {
        if (!button) return;
        button.disabled = true;
        setTimeout(function () { button.disabled = false; }, 2000);
    }

    function message(element, text, ok) {
        if (!element) return;
        element.innerHTML = text ? "<span class='" + (ok ? "text-success" : "text-danger") + "'>" + text + "</span>" : "";
    }

    function getJSON(params) {
        return fetch(ENDPOINT + "?" + new URLSearchParams(params).toString(), { credentials: "same-origin" })
            .then(function (res) { return res.json(); });
    }

    function postJSON(action, fields) {
        var form = new FormData();
        form.append("action", action);
        Object.keys(fields || {}).forEach(function (key) { form.append(key, fields[key]); });
        return fetch(ENDPOINT, { method: "POST", body: form, credentials: "same-origin" })
            .then(function (res) { return res.json(); });
    }

    function failed(element) {
        return function () { message(element, "Could not reach the server. Check your connection and try again.", false); };
    }

    function renderPreview(holder, dataUrl, emptyText) {
        if (!holder) return;
        holder.innerHTML = dataUrl ? "<img alt='' src='" + dataUrl + "'>" : "<span class='sig_empty'>" + emptyText + "</span>";
    }

    // A remove button that asks for a second click instead of a browser confirm dialog.
    function confirmClick(button, label, action) {
        if (button.dataset.armed === "1") {
            button.dataset.armed = "0";
            button.innerHTML = label;
            action();
            return;
        }
        button.dataset.armed = "1";
        button.innerHTML = "<i class='fas fa-exclamation-triangle'></i> Click again to remove";
        setTimeout(function () {
            if (button.dataset.armed === "1") {
                button.dataset.armed = "0";
                button.innerHTML = label;
            }
        }, 4000);
    }

    // Cuts a canvas down to the area that has ink, with a small margin, and scales it
    // down if it is still very large. Returns null when there is no ink at all.
    function trimCanvas(source) {
        var ctx = source.getContext("2d");
        var data = ctx.getImageData(0, 0, source.width, source.height).data;
        var minX = source.width, minY = source.height, maxX = -1, maxY = -1;
        for (var y = 0; y < source.height; y++) {
            for (var x = 0; x < source.width; x++) {
                if (data[(y * source.width + x) * 4 + 3] > 20) {
                    if (x < minX) minX = x;
                    if (x > maxX) maxX = x;
                    if (y < minY) minY = y;
                    if (y > maxY) maxY = y;
                }
            }
        }
        if (maxX < 0) return null;
        var pad = 8;
        minX = Math.max(0, minX - pad);
        minY = Math.max(0, minY - pad);
        maxX = Math.min(source.width - 1, maxX + pad);
        maxY = Math.min(source.height - 1, maxY + pad);
        var w = maxX - minX + 1, h = maxY - minY + 1;
        var scale = Math.min(1, MAX_OUTPUT_SIDE / Math.max(w, h));
        var out = document.createElement("canvas");
        out.width = Math.max(1, Math.round(w * scale));
        out.height = Math.max(1, Math.round(h * scale));
        out.getContext("2d").drawImage(source, minX, minY, w, h, 0, 0, out.width, out.height);
        return out;
    }

    function hexToRgb(hex) {
        var n = parseInt(hex.replace("#", ""), 16);
        return [(n >> 16) & 255, (n >> 8) & 255, n & 255];
    }

    // ---------------------------------------------------------------- editor
    var editor = {
        mode: "signature",
        tab: "draw",
        onSave: null,
        strokes: [],
        work: null, // the uploaded photo after rotation, scaled down
        crop: null, // {x, y, w, h} in work canvas pixels
        dragStart: null,
        processTimer: null
    };

    function drawCanvas() { return cObj("sig_draw_canvas"); }

    function setTab(tab) {
        editor.tab = tab;
        cObj("sig_tab_draw").classList.toggle("sig_tab_active", tab === "draw");
        cObj("sig_tab_upload").classList.toggle("sig_tab_active", tab === "upload");
        cObj("sig_draw_panel").classList.toggle("hide", tab !== "draw");
        cObj("sig_upload_panel").classList.toggle("hide", tab !== "upload");
        message(cObj("sig_editor_error"), "");
    }

    function openEditor(mode, title, onSave) {
        editor.mode = mode;
        editor.onSave = onSave;
        editor.strokes = [];
        editor.work = null;
        editor.crop = null;
        cObj("sig_editor_title").innerText = title;
        cObj("sig_upload_file").value = "";
        cObj("sig_upload_work").classList.add("hide");
        cObj("sig_strength").value = 40;
        cObj("sig_strength_value").innerText = "40";
        // a stamp keeps its own ink colour; a signature is usually cleaner recoloured
        cObj("sig_ink_mode").value = mode === "stamp" ? "original" : "#1a3a8f";
        cObj("sig_tab_draw").classList.toggle("hide", mode === "stamp");
        cObj("sig_upload_hint").innerText = mode === "stamp"
            ? "Stamp on plain white paper, take a clear photo from straight above, then drag on the photo to crop around the stamp."
            : "Sign on plain white paper, take a clear photo, then drag on the photo to crop around the signature.";
        setTab(mode === "stamp" ? "upload" : "draw");
        redrawStrokes();
        cObj("sig_editor_popup").classList.remove("hide");
    }

    function closeEditor() {
        cObj("sig_editor_popup").classList.add("hide");
    }

    // ----- draw pad
    function redrawStrokes() {
        var canvas = drawCanvas();
        var ctx = canvas.getContext("2d");
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.strokeStyle = cObj("sig_draw_color").value;
        ctx.lineWidth = 5;
        ctx.lineCap = "round";
        ctx.lineJoin = "round";
        editor.strokes.forEach(function (points) {
            ctx.beginPath();
            ctx.moveTo(points[0][0], points[0][1]);
            if (points.length === 1) {
                ctx.lineTo(points[0][0] + 0.1, points[0][1] + 0.1);
            }
            for (var i = 1; i < points.length; i++) {
                // smooth through the midpoints so fast strokes do not look jagged
                var mid = [(points[i - 1][0] + points[i][0]) / 2, (points[i - 1][1] + points[i][1]) / 2];
                ctx.quadraticCurveTo(points[i - 1][0], points[i - 1][1], mid[0], mid[1]);
            }
            ctx.stroke();
        });
    }

    function canvasPoint(canvas, event) {
        var rect = canvas.getBoundingClientRect();
        return [(event.clientX - rect.left) * canvas.width / rect.width, (event.clientY - rect.top) * canvas.height / rect.height];
    }

    function initDrawPad() {
        var canvas = drawCanvas();
        // twice the CSS size so the saved signature stays sharp when printed
        canvas.width = 1400;
        canvas.height = 440;
        var drawing = false;
        canvas.addEventListener("pointerdown", function (e) {
            drawing = true;
            canvas.setPointerCapture(e.pointerId);
            editor.strokes.push([canvasPoint(canvas, e)]);
            redrawStrokes();
        });
        canvas.addEventListener("pointermove", function (e) {
            if (!drawing) return;
            editor.strokes[editor.strokes.length - 1].push(canvasPoint(canvas, e));
            redrawStrokes();
        });
        ["pointerup", "pointercancel"].forEach(function (name) {
            canvas.addEventListener(name, function () { drawing = false; });
        });
        cObj("sig_draw_clear").onclick = function () {
            editor.strokes = [];
            redrawStrokes();
        };
        cObj("sig_draw_color").onchange = redrawStrokes;
    }

    // ----- uploaded photo: load, rotate, crop
    function loadPhoto(file) {
        message(cObj("sig_editor_error"), "");
        if (!file) return;
        if (!/^image\//.test(file.type)) {
            message(cObj("sig_editor_error"), "Choose an image file (JPG or PNG).", false);
            return;
        }
        var url = URL.createObjectURL(file);
        var img = new Image();
        img.onload = function () {
            var scale = Math.min(1, MAX_WORK_SIDE / Math.max(img.naturalWidth, img.naturalHeight));
            var work = document.createElement("canvas");
            work.width = Math.round(img.naturalWidth * scale);
            work.height = Math.round(img.naturalHeight * scale);
            work.getContext("2d").drawImage(img, 0, 0, work.width, work.height);
            URL.revokeObjectURL(url);
            editor.work = work;
            editor.crop = null;
            cObj("sig_upload_work").classList.remove("hide");
            refreshUpload();
        };
        img.onerror = function () {
            URL.revokeObjectURL(url);
            message(cObj("sig_editor_error"), "That image could not be opened. Try a JPG or PNG photo.", false);
        };
        img.src = url;
    }

    function rotateWork(clockwise) {
        if (!editor.work) return;
        var src = editor.work;
        var out = document.createElement("canvas");
        out.width = src.height;
        out.height = src.width;
        var ctx = out.getContext("2d");
        ctx.translate(out.width / 2, out.height / 2);
        ctx.rotate((clockwise ? 90 : -90) * Math.PI / 180);
        ctx.drawImage(src, -src.width / 2, -src.height / 2);
        editor.work = out;
        editor.crop = null;
        refreshUpload();
    }

    function drawSource() {
        var canvas = cObj("sig_source_canvas");
        var work = editor.work;
        canvas.width = work.width;
        canvas.height = work.height;
        var ctx = canvas.getContext("2d");
        ctx.drawImage(work, 0, 0);
        if (editor.crop) {
            var c = editor.crop;
            // dim everything outside the crop
            ctx.fillStyle = "rgba(0,0,0,0.45)";
            ctx.fillRect(0, 0, work.width, c.y);
            ctx.fillRect(0, c.y + c.h, work.width, work.height - c.y - c.h);
            ctx.fillRect(0, c.y, c.x, c.h);
            ctx.fillRect(c.x + c.w, c.y, work.width - c.x - c.w, c.h);
            ctx.strokeStyle = "#03a9f4";
            ctx.lineWidth = Math.max(2, work.width / 300);
            ctx.strokeRect(c.x, c.y, c.w, c.h);
        }
    }

    function initCrop() {
        var canvas = cObj("sig_source_canvas");
        canvas.addEventListener("pointerdown", function (e) {
            if (!editor.work) return;
            canvas.setPointerCapture(e.pointerId);
            editor.dragStart = canvasPoint(canvas, e);
        });
        canvas.addEventListener("pointermove", function (e) {
            if (!editor.dragStart) return;
            var p = canvasPoint(canvas, e), s = editor.dragStart;
            var x = Math.max(0, Math.min(s[0], p[0])), y = Math.max(0, Math.min(s[1], p[1]));
            var w = Math.min(editor.work.width, Math.max(s[0], p[0])) - x;
            var h = Math.min(editor.work.height, Math.max(s[1], p[1])) - y;
            editor.crop = { x: Math.round(x), y: Math.round(y), w: Math.round(w), h: Math.round(h) };
            drawSource();
        });
        ["pointerup", "pointercancel"].forEach(function (name) {
            canvas.addEventListener(name, function () {
                if (!editor.dragStart) return;
                editor.dragStart = null;
                // a click without a real drag clears the crop
                if (editor.crop && (editor.crop.w < 10 || editor.crop.h < 10)) editor.crop = null;
                refreshUpload();
            });
        });
    }

    function refreshUpload() {
        if (!editor.work) return;
        drawSource();
        clearTimeout(editor.processTimer);
        editor.processTimer = setTimeout(function () {
            var result = processInk();
            var preview = cObj("sig_preview_canvas");
            preview.width = result.width;
            preview.height = result.height;
            preview.getContext("2d").drawImage(result, 0, 0);
        }, 60);
    }

    // The ink cleanup. Each pixel is compared with the brightness of the paper around
    // it (a large box average), so shadows and uneven light across a phone photo do
    // not leave grey patches. Pixels close to the paper's brightness become see-through,
    // clearly darker ones are kept, with a soft edge between.
    function processInk() {
        var work = editor.work;
        var c = editor.crop || { x: 0, y: 0, w: work.width, h: work.height };
        var w = c.w, h = c.h;
        var out = document.createElement("canvas");
        out.width = w;
        out.height = h;
        var ctx = out.getContext("2d");
        ctx.drawImage(work, c.x, c.y, w, h, 0, 0, w, h);
        var image = ctx.getImageData(0, 0, w, h);
        var px = image.data;
        var n = w * h;

        var lum = new Float32Array(n);
        var histogram = new Uint32Array(256);
        for (var i = 0; i < n; i++) {
            var l = 0.299 * px[i * 4] + 0.587 * px[i * 4 + 1] + 0.114 * px[i * 4 + 2];
            lum[i] = l;
            histogram[Math.min(255, Math.round(l))]++;
        }
        // the brightness most of the paper sits at (90th percentile)
        var seen = 0, paper = 255;
        for (var b = 0; b < 256; b++) {
            seen += histogram[b];
            if (seen >= n * 0.9) { paper = Math.max(b, 1); break; }
        }

        // summed-area table for fast box averages
        var integral = new Float64Array((w + 1) * (h + 1));
        for (var y = 0; y < h; y++) {
            var rowSum = 0;
            for (var x = 0; x < w; x++) {
                rowSum += lum[y * w + x];
                integral[(y + 1) * (w + 1) + (x + 1)] = integral[y * (w + 1) + (x + 1)] + rowSum;
            }
        }
        var radius = Math.max(15, Math.round(Math.max(w, h) / 12));

        var strength = parseInt(cObj("sig_strength").value, 10);
        var cut = 0.95 - strength * 0.0035; // 0 keeps faint ink, 100 keeps only dark ink
        var feather = 0.12;
        var mode = cObj("sig_ink_mode").value;
        var ink = mode === "original" ? null : hexToRgb(mode);

        for (var yy = 0; yy < h; yy++) {
            var y0 = Math.max(0, yy - radius), y1 = Math.min(h, yy + radius + 1);
            for (var xx = 0; xx < w; xx++) {
                var x0 = Math.max(0, xx - radius), x1 = Math.min(w, xx + radius + 1);
                var area = (x1 - x0) * (y1 - y0);
                var sum = integral[y1 * (w + 1) + x1] - integral[y0 * (w + 1) + x1] - integral[y1 * (w + 1) + x0] + integral[y0 * (w + 1) + x0];
                var background = Math.max(sum / area, paper * 0.7);
                var k = yy * w + xx;
                var ratio = lum[k] / background;
                var alpha = Math.max(0, Math.min(1, (cut - ratio) / feather));
                var p = k * 4;
                if (ink) {
                    px[p] = ink[0];
                    px[p + 1] = ink[1];
                    px[p + 2] = ink[2];
                    alpha = Math.min(1, alpha * 1.15);
                } else {
                    // deepen the original ink a little; photos wash it out
                    px[p] = px[p] * 0.85;
                    px[p + 1] = px[p + 1] * 0.85;
                    px[p + 2] = px[p + 2] * 0.85;
                }
                px[p + 3] = Math.round(alpha * 255);
            }
        }
        ctx.putImageData(image, 0, 0);
        return out;
    }

    function initUpload() {
        cObj("sig_upload_file").onchange = function () { loadPhoto(this.files[0]); };
        cObj("sig_rotate_left").onclick = function () { rotateWork(false); };
        cObj("sig_rotate_right").onclick = function () { rotateWork(true); };
        cObj("sig_crop_reset").onclick = function () { editor.crop = null; refreshUpload(); };
        cObj("sig_strength").oninput = function () {
            cObj("sig_strength_value").innerText = this.value;
            refreshUpload();
        };
        cObj("sig_ink_mode").onchange = refreshUpload;
        initCrop();
    }

    function editorResult() {
        if (editor.tab === "draw") {
            if (editor.strokes.length === 0) return { error: "Sign inside the box first." };
            redrawStrokes();
            var drawn = trimCanvas(drawCanvas());
            return drawn ? { image: drawn.toDataURL("image/png") } : { error: "Sign inside the box first." };
        }
        if (!editor.work) return { error: "Choose a photo first." };
        var cleaned = trimCanvas(processInk());
        if (!cleaned) return { error: "No ink is left. Lower the paper removal or crop around the ink." };
        return { image: cleaned.toDataURL("image/png") };
    }

    function initEditor() {
        if (!cObj("sig_editor_popup")) return;
        initDrawPad();
        initUpload();
        cObj("sig_tab_draw").onclick = function () { setTab("draw"); };
        cObj("sig_tab_upload").onclick = function () { setTab("upload"); };
        cObj("close_sig_editor").onclick = closeEditor;
        cObj("close_sig_editor2").onclick = function () {
            guard(this);
            closeEditor();
        };
        cObj("save_sig_editor").onclick = function () {
            guard(this);
            var result = editorResult();
            if (result.error) {
                message(cObj("sig_editor_error"), result.error, false);
                return;
            }
            message(cObj("sig_editor_error"), "Saving...", true);
            editor.onSave(result.image, function (error) {
                if (error) {
                    message(cObj("sig_editor_error"), error, false);
                } else {
                    message(cObj("sig_editor_error"), "");
                    closeEditor();
                }
            });
        };
    }

    // Saves a signature for a staff member (empty userId = yourself) and reports
    // back to the editor through done(errorText or null).
    function saveSignature(userId, preview, removeButton, status) {
        return function (image, done) {
            var fields = { image: image };
            if (userId) fields.user_id = userId;
            postJSON("save_signature", fields).then(function (data) {
                if (data.status !== "success") return done(data.message);
                renderPreview(preview, data.signature, "No signature yet");
                removeButton.classList.remove("hide");
                message(status, data.message, true);
                done(null);
            }).catch(function () { done("Could not reach the server. Check your connection and try again."); });
        };
    }

    // ---------------------------------------------------------------- my profile
    function loadMySignature() {
        if (!cObj("my_signature_preview")) return;
        getJSON({ action: "get_signature" }).then(function (data) {
            if (data.status !== "success") return message(cObj("my_signature_status"), data.message, false);
            renderPreview(cObj("my_signature_preview"), data.signature, "No signature yet");
            cObj("my_signature_remove").classList.toggle("hide", !data.signature);
        }).catch(failed(cObj("my_signature_status")));
    }

    function initMyProfile() {
        if (!cObj("my_signature_preview")) return;
        if (cObj("update_personal_profile")) cObj("update_personal_profile").addEventListener("click", loadMySignature);
        cObj("my_signature_change").onclick = function () {
            guard(this);
            message(cObj("my_signature_status"), "");
            openEditor("signature", "My Signature", saveSignature("", cObj("my_signature_preview"), cObj("my_signature_remove"), cObj("my_signature_status")));
        };
        cObj("my_signature_remove").onclick = function () {
            var button = this;
            confirmClick(button, "<i class='fas fa-trash'></i> Remove", function () {
                guard(button);
                postJSON("delete_signature", {}).then(function (data) {
                    message(cObj("my_signature_status"), data.message, data.status === "success");
                    if (data.status === "success") {
                        renderPreview(cObj("my_signature_preview"), null, "No signature yet");
                        button.classList.add("hide");
                    }
                }).catch(failed(cObj("my_signature_status")));
            });
        };
    }

    // ---------------------------------------------------------------- edit staff page
    var staffSignatureUser = "";

    window.loadStaffSignatureCard = function (userId) {
        var card = cObj("staff_signature_card");
        if (!card) return;
        staffSignatureUser = String(userId);
        card.classList.add("hide");
        message(cObj("staff_signature_status"), "");
        getJSON({ action: "get_signature", user_id: staffSignatureUser }).then(function (data) {
            // the staff list may have moved on to someone else while this loaded
            if (String(userId) !== staffSignatureUser) return;
            if (data.status !== "success" || !data.eligible || !data.can_change) return;
            renderPreview(cObj("staff_signature_preview"), data.signature, "No signature yet");
            cObj("staff_signature_remove").classList.toggle("hide", !data.signature);
            card.classList.remove("hide");
        }).catch(function () { });
    };

    function initStaffCard() {
        if (!cObj("staff_signature_card")) return;
        cObj("staff_signature_change").onclick = function () {
            guard(this);
            message(cObj("staff_signature_status"), "");
            var name = cObj("fullnamed") ? cObj("fullnamed").value : "";
            openEditor("signature", "Signature" + (name ? ": " + name : ""), saveSignature(staffSignatureUser, cObj("staff_signature_preview"), cObj("staff_signature_remove"), cObj("staff_signature_status")));
        };
        cObj("staff_signature_remove").onclick = function () {
            var button = this;
            confirmClick(button, "<i class='fas fa-trash mr-1'></i> Remove", function () {
                guard(button);
                postJSON("delete_signature", { user_id: staffSignatureUser }).then(function (data) {
                    message(cObj("staff_signature_status"), data.message, data.status === "success");
                    if (data.status === "success") {
                        renderPreview(cObj("staff_signature_preview"), null, "No signature yet");
                        button.classList.add("hide");
                    }
                }).catch(failed(cObj("staff_signature_status")));
            });
        };
    }

    // ---------------------------------------------------------------- settings
    function staffOptions(list, selected) {
        var html = "<option value=''>None selected</option>";
        list.forEach(function (staff) {
            html += "<option value='" + staff.user_id + "'" + (staff.user_id === selected ? " selected" : "") + ">" +
                staff.fullname.replace(/</g, "&lt;") + (staff.has_signature ? "" : " (no signature yet)") + "</option>";
        });
        return html;
    }

    function loadSignatureSettings() {
        if (!cObj("sig_settings_section")) return;
        cObj("sig_settings_loader").classList.remove("hide");
        message(cObj("sig_settings_status"), "");
        getJSON({ action: "get_settings" }).then(function (data) {
            cObj("sig_settings_loader").classList.add("hide");
            if (data.status !== "success") return message(cObj("sig_settings_status"), data.message, false);
            renderPreview(cObj("sig_stamp_preview"), data.stamp, "No stamp yet");
            cObj("sig_stamp_remove").classList.toggle("hide", !data.stamp || !data.can_edit);

            var rows = "";
            data.documents.forEach(function (doc) {
                rows += "<tr><td><b>" + doc.label + "</b></td><td>" + doc.description + "</td>" +
                    "<td><input type='checkbox' class='sig_stamp_document' value='" + doc.key + "'" + (doc.enabled ? " checked" : "") + "></td></tr>";
            });
            cObj("sig_documents_body").innerHTML = rows;

            cObj("sig_print_signatures").checked = data.print_signatures;
            cObj("sig_signing_headteacher").innerHTML = staffOptions(data.headteachers, data.signing_headteacher);
            cObj("sig_signing_deputy").innerHTML = staffOptions(data.deputies, data.signing_deputy);
            cObj("sig_receipt_style").value = data.receipt_style;

            // view only for roles that may open Settings but not change these
            var readOnly = !data.can_edit;
            cObj("sig_settings_readonly").classList.toggle("hide", !readOnly);
            document.querySelectorAll("#sig_settings_section .sig_edit_only").forEach(function (el) {
                if (readOnly) el.classList.add("hide");
                else if (el.id !== "sig_stamp_remove") el.classList.remove("hide");
            });
            document.querySelectorAll("#sig_settings_section input, #sig_settings_section select").forEach(function (el) {
                el.disabled = readOnly;
            });
        }).catch(function () {
            cObj("sig_settings_loader").classList.add("hide");
            failed(cObj("sig_settings_status"))();
        });
    }

    function initSettings() {
        if (!cObj("sig_settings_section")) return;
        if (cObj("set_btns")) cObj("set_btns").addEventListener("click", loadSignatureSettings);
        cObj("sig_stamp_change").onclick = function () {
            guard(this);
            message(cObj("sig_stamp_status"), "");
            openEditor("stamp", "College Stamp", function (image, done) {
                postJSON("save_stamp", { image: image }).then(function (data) {
                    if (data.status !== "success") return done(data.message);
                    renderPreview(cObj("sig_stamp_preview"), data.stamp, "No stamp yet");
                    cObj("sig_stamp_remove").classList.remove("hide");
                    message(cObj("sig_stamp_status"), data.message, true);
                    done(null);
                }).catch(function () { done("Could not reach the server. Check your connection and try again."); });
            });
        };
        cObj("sig_stamp_remove").onclick = function () {
            var button = this;
            confirmClick(button, "<i class='fas fa-trash'></i> Remove", function () {
                guard(button);
                postJSON("delete_stamp", {}).then(function (data) {
                    message(cObj("sig_stamp_status"), data.message, data.status === "success");
                    if (data.status === "success") {
                        renderPreview(cObj("sig_stamp_preview"), null, "No stamp yet");
                        button.classList.add("hide");
                    }
                }).catch(failed(cObj("sig_stamp_status")));
            });
        };
        cObj("sig_settings_save").onclick = function () {
            guard(this);
            var documents = [];
            document.querySelectorAll(".sig_stamp_document").forEach(function (box) {
                if (box.checked) documents.push(box.value);
            });
            message(cObj("sig_settings_status"), "Saving...", true);
            postJSON("save_settings", {
                stamp_documents: JSON.stringify(documents),
                print_signatures: cObj("sig_print_signatures").checked ? "1" : "0",
                signing_headteacher: cObj("sig_signing_headteacher").value,
                signing_deputy: cObj("sig_signing_deputy").value,
                receipt_style: cObj("sig_receipt_style").value
            }).then(function (data) {
                message(cObj("sig_settings_status"), data.message, data.status === "success");
            }).catch(failed(cObj("sig_settings_status")));
        };
    }

    function init() {
        initEditor();
        initMyProfile();
        initStaffCard();
        initSettings();
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();
