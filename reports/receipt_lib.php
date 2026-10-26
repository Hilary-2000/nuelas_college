<?php
// Fee receipts in the new navy and gold design, with the principal's signature and
// the college stamp. The classic receipt is still drawn by the original code in
// reports.php; rc_receipt_style() decides which one a receipt uses.

// Smallest row height (mm) a receipt table may shrink to before it needs a whole page.
define('RC_RECEIPT_MIN_ROW', 4.2);

// Receipt design: the print window's choice, else the college default from Settings.
// Receipts opened from the SMS link are GET requests, so they always follow Settings.
function rc_receipt_style($conn2)
{
    $chosen = $_POST['receipt_style'] ?? 'default';
    if (in_array($chosen, ['new', 'classic'], true)) return $chosen;
    return sig_get_config($conn2)['receipt_style'] === 'classic' ? 'classic' : 'new';
}

function rc_receipt_mode_text($mode)
{
    $mode = strtolower(trim((string) $mode));
    if ($mode == "mpesa") return "M-Pesa";
    return ucwords($mode);
}

// First name of the staff member who received the payment.
function rc_receipt_served_by($conn, $user_id)
{
    if (strtolower((string) $user_id) == "mpesa") return "M-Pesa";
    $staff = getStaffInformations_report($conn, $user_id);
    if (count($staff) == 0) return "N/A";
    return explode(" ", ucwords(strtolower($staff['fullname'])))[0];
}

// Payment instructions the college has chosen to show.
function rc_receipt_payment_options($conn2)
{
    $stmt = $conn2->prepare("SELECT `valued` FROM `settings` WHERE `sett` = 'payment details'");
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $options = [];
    if ($row && isJson_reports($row['valued'])) {
        foreach (json_decode($row['valued']) as $option) {
            if ($option->show == "true") $options[] = trim($option->description);
        }
    }
    return $options;
}

// The student's course name from the college's course list, or their course level.
function rc_receipt_course($conn2, $student)
{
    if (!$student) return "N/A";
    $stmt = $conn2->prepare("SELECT `valued` FROM `settings` WHERE `sett` = 'courses' LIMIT 1");
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $courses = ($row && isJson_report($row['valued'])) ? json_decode($row['valued']) : [];
    foreach ($courses as $course) {
        if ((string) $course->id === (string) $student['course_done']) {
            return ucwords(strtolower($course->course_name));
        }
    }
    return classNameReport($student['stud_class']);
}

// Votehead rows for a payment, and how much of it was not provisional
// (provisional voteheads do not count towards the fee balance).
function rc_receipt_voteheads($conn2, $payments_for, $amount, $adm_no)
{
    $rows = [];
    $counted = 0;
    if (isJson_report($payments_for)) {
        foreach (json_decode($payments_for, true) as $index => $votehead) {
            $role = (string) ($votehead['roles'] ?? '');
            $rows[] = [$index + 1, ucwords(strtolower($votehead['name'])), ucwords(strtolower($role)), "Kes " . number_format($votehead['amount_paid'] * 1, 2)];
            if (strtolower($role) != "provisional") {
                $counted += $votehead['amount_paid'] * 1;
            }
        }
    } else {
        $display_name = ucwords(strtolower($payments_for));
        $role = "Regular";
        $stmt = $conn2->prepare("SELECT `roles` FROM `fees_structure` WHERE `expenses` = ? LIMIT 1");
        $stmt->bind_param("s", $payments_for);
        $stmt->execute();
        if ($fee = $stmt->get_result()->fetch_assoc()) {
            $role = ucwords(strtolower($fee['roles']));
        }
        $rows[] = [1, $display_name, $role, "Kes " . number_format($amount, 2)];
        if (isProvisionalFullname($payments_for, $conn2, $adm_no) == "false") {
            $counted += $amount;
        }
    }
    return [$rows, $counted];
}

// Receipt array for one student payment.
function rc_receipt_student($conn2, $p)
{
    [$rows, $counted] = rc_receipt_voteheads($conn2, $p['payments_for'], $p['amount'], $p['adm_no']);
    $fields = [
        ["STUDENT NAME", $p['name']], ["REG NO.", $p['adm_no']], ["COURSE", $p['course']], ["DATE", $p['date']],
        ["PAYMENT MODE", $p['mode']], ["TRANSACTION CODE", strlen(trim((string) $p['code'])) > 0 ? $p['code'] : "-"], ["SERVED BY", $p['served_by']], ["TIME", $p['time']],
    ];
    $totals = [["Previous Balance", "Kes " . number_format($counted + $p['balance'], 2)], ["Amount Paid", "Kes " . number_format($p['amount'], 2)]];
    $totals[] = ["New Balance", "Kes " . number_format($p['balance'], 2), true];
    return [
        'title' => "FEES RECEIPT", 'doc_key' => 'fees_receipt', 'receipt_no' => $p['receipt_no'],
        'fields' => $fields, 'amount' => $p['amount'],
        'columns' => [['#', 9, 'C'], ['Votehead', 0, 'L'], ['Role', 26, 'C'], ['Amount', 32, 'R']],
        'rows' => $rows, 'totals' => $totals, 'options' => rc_receipt_payment_options($conn2),
    ];
}

// Receipt array from a saved payment (receipts opened from the SMS link).
function rc_receipt_from_finance($conn, $conn2, $transaction_id)
{
    $stmt = $conn2->prepare("SELECT * FROM `finance` WHERE `transaction_id` = ? LIMIT 1");
    $stmt->bind_param("s", $transaction_id);
    $stmt->execute();
    $payment = $stmt->get_result()->fetch_assoc();
    if (!$payment) return null;
    $stmt = $conn2->prepare("SELECT * FROM `student_data` WHERE `adm_no` = ? LIMIT 1");
    $stmt->bind_param("s", $payment['stud_admin']);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();
    if (!$student) return null;
    return rc_receipt_student($conn2, [
        'receipt_no' => receiptNo($payment['transaction_id']),
        'date' => date("D jS M Y", strtotime($payment['date_of_transaction'])),
        'time' => date("h:i A", strtotime($payment['time_of_transaction'])),
        'name' => ucwords(strtolower(trim($student['first_name'] . " " . $student['second_name'] . " " . $student['surname']))),
        'adm_no' => $student['adm_no'], 'course' => rc_receipt_course($conn2, $student),
        'amount' => $payment['amount'] * 1, 'balance' => $payment['balance'] * 1,
        'payments_for' => $payment['payment_for'],
        'mode' => rc_receipt_mode_text($payment['mode_of_pay']),
        'code' => $payment['transaction_code'], 'served_by' => rc_receipt_served_by($conn, $payment['payBy']),
    ]);
}

// College details for the receipt header: the logged-in college, or the college
// that owns $database for the SMS link.
function rc_receipt_school($conn, $database = null)
{
    if ($database === null) return getSchoolInfo($conn);
    $stmt = $conn->prepare("SELECT * FROM `school_information` WHERE `database_name` = ? LIMIT 1");
    $stmt->bind_param("s", $database);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc() ?: [];
}

// Text broken into lines that fit $width in the current font.
function rc_wrap_lines($pdf, $text, $width)
{
    $lines = [];
    $line = "";
    foreach (preg_split('/\s+/', trim((string) $text)) as $word) {
        $candidate = $line === "" ? $word : $line . " " . $word;
        if ($pdf->GetStringWidth($candidate) <= $width || $line === "") {
            $line = $candidate;
        } else {
            $lines[] = $line;
            $line = $word;
        }
    }
    if ($line !== "") $lines[] = $line;
    return $lines;
}

// Text shrunk (down to $min) to fit $w, without a border.
function rc_fit_text($pdf, $w, $h, $text, $align, $family, $style, $size, $min = 6)
{
    $text = (string) $text;
    while ($size > $min) {
        $pdf->SetFont($family, $style, $size);
        if ($pdf->GetStringWidth($text) <= $w) break;
        $size -= 0.5;
    }
    $pdf->SetFont($family, $style, $size);
    $pdf->Cell($w, $h, $text, 0, 0, $align);
}

// Where each detail field sits: [[field, column, row, span], ...], rows used, columns.
function rc_receipt_field_layout($fields, $w)
{
    $cols = $w >= 165 ? 4 : 3;
    $placed = [];
    $col = 0; $row = 0;
    foreach ($fields as $field) {
        $span = min($cols, $field[2] ?? 1);
        if ($col + $span > $cols) { $col = 0; $row++; }
        $placed[] = [$field, $col, $row, $span];
        $col += $span;
        if ($col >= $cols) { $col = 0; $row++; }
    }
    return [$placed, $col == 0 ? $row : $row + 1, $cols];
}

// Height taken by everything above the table, and by the totals and signature below it.
function rc_receipt_heights($r, $w, $k)
{
    [, $rows_used] = rc_receipt_field_layout($r['fields'], $w - 8);
    $above = 4 + 19 * $k + 3 + $rows_used * 7.6 * $k + 4 + 11 * $k + 3;
    $below = 3 + count($r['totals']) * 5.6 * $k + 3 * $k + 14 * $k + 4;
    return [$above, $below];
}

// Whether all the table rows fit in a box of this size.
function rc_receipt_fits($r, $w, $h, $k = 1)
{
    [$above, $below] = rc_receipt_heights($r, $w, $k);
    return ($h - $above - $below - 6 * $k) >= max(1, count($r['rows'])) * RC_RECEIPT_MIN_ROW;
}

// Table header row.
function rc_receipt_table_head($pdf, $r, $x, $y, $w, $k)
{
    $fixed = 0;
    foreach ($r['columns'] as $column) $fixed += $column[1];
    $pdf->SetDrawColor(150, 160, 175);
    $pdf->SetFillColor(214, 226, 242);
    $pdf->SetTextColor(24, 62, 120);
    $pdf->SetFont('Nunito', 'B', 7.5 * $k);
    $pdf->SetXY($x, $y);
    foreach ($r['columns'] as $column) {
        $cw = $column[1] > 0 ? $column[1] : $w - $fixed;
        $pdf->Cell($cw, 6 * $k, ($column[2] == 'L' ? ' ' : '') . $column[0] . ($column[2] == 'R' ? ' ' : ''), 1, 0, $column[2], true);
    }
    return $y + 6 * $k;
}

// One receipt inside the box ($x, $y, $w, $h); $k scales the text (A4 full page is larger).
// A box that cannot hold every row is only ever given when $continue is true (whole
// pages): the table then carries on over as many pages as it needs.
function rc_receipt_draw($pdf, $conn, $conn2, $school, $r, $x, $y, $w, $h, $copy_label = "", $k = 1, $continue = false)
{
    $navy = [24, 62, 120];
    $pdf->AddFont('Nunito', '', 'Nunito-Regular.php');
    $pdf->AddFont('Nunito', 'B', 'Nunito-Bold.php');
    $pdf->AddFont('Nunito', 'I', 'Nunito-Italic.php');
    $box = [$x, $y, $w, $h];
    $pdf->SetLineWidth(0.2);
    $pdf->SetDrawColor(214, 224, 238);
    $pdf->Rect($x, $y, $w, $h);
    $x += 4; $w -= 8;
    $top = $y + 4;
    $bottom = $y + $h - 4;

    // header: logo, college, and the receipt number on the right
    $logo = isset($school['school_profile_image']) ? dirname(__FILE__) . "/../" . $school['school_profile_image'] : "";
    if (strlen($logo) > 0 && is_file($logo)) {
        try { $pdf->Image($logo, $x, $top, 17 * $k); } catch (Exception $e) {}
    }
    $tx = $x + 20 * $k;
    $tw = $w - 20 * $k - 46;
    $pdf->SetXY($tx, $top);
    $pdf->SetTextColor($navy[0], $navy[1], $navy[2]);
    rc_fit_text($pdf, $tw, 6 * $k, strtoupper($school['school_name'] ?? ''), 'L', 'Nunito', 'B', 12.5 * $k, 8);
    if (strlen(trim((string) ($school['school_motto'] ?? ''))) > 0) {
        $pdf->SetXY($tx, $top + 6 * $k);
        $pdf->SetTextColor(170, 120, 20);
        rc_fit_text($pdf, $tw, 4 * $k, ucfirst(strtolower(trim($school['school_motto']))), 'L', 'Nunito', 'I', 7.5 * $k);
    }
    $pdf->SetTextColor(70, 70, 70);
    $address = 'P.O. Box ' . ($school['po_box'] ?? '') . '-' . ($school['box_code'] ?? '') . (strlen(trim((string) ($school['county'] ?? ''))) > 0 ? ', ' . $school['county'] : '') . '   |   Tel: ' . ($school['school_contact'] ?? '');
    $pdf->SetXY($tx, $top + 10 * $k);
    rc_fit_text($pdf, $tw, 3.8 * $k, $address, 'L', 'Nunito', '', 7 * $k);
    $web = 'Email: ' . ($school['school_mail'] ?? '') . (strlen(trim((string) ($school['website_name'] ?? ''))) > 0 ? '   |   ' . $school['website_name'] : '');
    $pdf->SetXY($tx, $top + 13.8 * $k);
    rc_fit_text($pdf, $tw, 3.8 * $k, $web, 'L', 'Nunito', '', 7 * $k);

    $rx = $x + $w - 46;
    $pdf->SetXY($rx, $top);
    $pdf->SetTextColor($navy[0], $navy[1], $navy[2]);
    $pdf->SetFont('Nunito', 'B', 12 * $k);
    $pdf->Cell(46, 6 * $k, $r['title'], 0, 2, 'R');
    $pdf->SetTextColor(40, 40, 40);
    $pdf->SetFont('Nunito', 'B', 10 * $k);
    $pdf->Cell(46, 5 * $k, 'No. ' . $r['receipt_no'], 0, 2, 'R');
    if (strlen($copy_label) > 0) {
        $pdf->SetTextColor(170, 120, 20);
        $pdf->SetFont('Nunito', 'B', 7 * $k);
        $pdf->Cell(46, 4 * $k, $copy_label, 0, 2, 'R');
    }
    $cy = $top + 19 * $k;
    $pdf->SetFillColor($navy[0], $navy[1], $navy[2]);
    $pdf->Rect($x, $cy, $w, 0.7, 'F');
    $pdf->SetFillColor(214, 168, 46);
    $pdf->Rect($x, $cy + 0.7, $w, 0.35, 'F');
    $cy += 3;

    // details grid: label above value; a field with a third item spans that many cells
    [$placed, $rows_used, $cols] = rc_receipt_field_layout($r['fields'], $w);
    $cell_w = $w / $cols;
    $cell_h = 7.6 * $k;
    $pdf->SetFillColor(244, 247, 252);
    $pdf->SetDrawColor(214, 224, 238);
    $pdf->Rect($x, $cy, $w, $rows_used * $cell_h + 1.5, 'DF');
    foreach ($placed as [$field, $fc, $fr, $span]) {
        $fx = $x + $fc * $cell_w + 2.5;
        $fy = $cy + 1 + $fr * $cell_h;
        $pdf->SetXY($fx, $fy);
        $pdf->SetTextColor($navy[0], $navy[1], $navy[2]);
        $pdf->SetFont('Nunito', 'B', 6.3 * $k);
        $pdf->Cell($cell_w * $span - 4, 3.2 * $k, $field[0], 0, 0, 'L');
        $pdf->SetXY($fx, $fy + 3.4 * $k);
        $pdf->SetTextColor(0, 0, 0);
        rc_fit_text($pdf, $cell_w * $span - 4, 4.2 * $k, $field[1], 'L', 'Times', '', 9.5 * $k, 6.5);
    }
    $cy += $rows_used * $cell_h + 4;

    // amount received, in figures and words
    $band_h = 11 * $k;
    $pdf->SetFillColor(214, 226, 242);
    $pdf->Rect($x, $cy, $w, $band_h, 'F');
    $pdf->SetFillColor($navy[0], $navy[1], $navy[2]);
    $pdf->Rect($x, $cy, 1.2, $band_h, 'F');
    $pdf->SetXY($x + 4, $cy + 1.3);
    $pdf->SetTextColor($navy[0], $navy[1], $navy[2]);
    $pdf->SetFont('Nunito', 'B', 7 * $k);
    $pdf->Cell($w - 60, 3.5 * $k, 'AMOUNT RECEIVED', 0, 0, 'L');
    $words = new NUmbers();
    $amount = $r['amount'] * 1;
    $in_words = ($amount < 0 ? "Negative " : "") . $words->convert_number(abs($amount)) . " Kenya Shillings Only";
    $pdf->SetXY($x + 4, $cy + 5.2 * $k);
    $pdf->SetTextColor(50, 50, 50);
    rc_fit_text($pdf, $w - 62, 4 * $k, $in_words, 'L', 'Times', 'I', 8.5 * $k, 6);
    $pdf->SetXY($x + $w - 58, $cy);
    $pdf->SetTextColor($navy[0], $navy[1], $navy[2]);
    $pdf->SetFont('Nunito', 'B', 14 * $k);
    $pdf->Cell(55, $band_h, 'Kes ' . number_format($amount, 2), 0, 0, 'R');
    $cy += $band_h + 3;

    // the votehead table; rows shrink to fit the box, and on whole pages a long
    // list carries on to the next page
    [, $below] = rc_receipt_heights($r, $w + 8, $k);
    $n_rows = max(1, count($r['rows']));
    $row_h = min(6.2 * $k, max(RC_RECEIPT_MIN_ROW, ($bottom - $below - $cy - 6 * $k) / $n_rows));
    $fixed = 0;
    foreach ($r['columns'] as $column) $fixed += $column[1];
    $cy = rc_receipt_table_head($pdf, $r, $x, $cy, $w, $k);
    $pdf->SetTextColor(0, 0, 0);
    foreach ($r['rows'] as $index => $cells) {
        if ($continue && $cy + $row_h > $bottom) {
            // carry the table on to a new page with a short heading
            $pdf->SetXY($x, $bottom - 4);
            $pdf->SetTextColor(120, 120, 120);
            $pdf->SetFont('Nunito', 'I', 7);
            $pdf->Cell($w, 4, 'Continued on the next page', 0, 0, 'R');
            $pdf->AddPage();
            $pdf->SetLineWidth(0.2);
            $pdf->SetDrawColor(214, 224, 238);
            $pdf->Rect($box[0], $box[1], $box[2], $box[3]);
            $pdf->SetXY($x, $top);
            $pdf->SetTextColor($navy[0], $navy[1], $navy[2]);
            $pdf->SetFont('Nunito', 'B', 10 * $k);
            $pdf->Cell($w, 6 * $k, $r['title'] . ' No. ' . $r['receipt_no'] . ' (continued)', 0, 0, 'L');
            $cy = rc_receipt_table_head($pdf, $r, $x, $top + 8 * $k, $w, $k);
            $pdf->SetTextColor(0, 0, 0);
        }
        $pdf->SetFillColor($index % 2 ? 246 : 255, $index % 2 ? 248 : 255, $index % 2 ? 252 : 255);
        $pdf->SetXY($x, $cy);
        foreach ($r['columns'] as $ci => $column) {
            $cw = $column[1] > 0 ? $column[1] : $w - $fixed;
            $text = ($column[2] == 'L' ? ' ' : '') . ($cells[$ci] ?? '') . ($column[2] == 'R' ? ' ' : '');
            $size = $row_h < 5 ? 7.5 : 8.8 * $k;
            $pdf->SetFont('Times', '', $size);
            while ($size > 6 && $pdf->GetStringWidth($text) > $cw - 1) {
                $size -= 0.5;
                $pdf->SetFont('Times', '', $size);
            }
            $pdf->Cell($cw, $row_h, $text, 1, 0, $column[2], true);
        }
        $cy += $row_h;
    }
    $cy += 3;
    if ($continue && $cy + $below > $bottom + 4) {
        // the totals and signature go together on a fresh page
        $pdf->AddPage();
        $pdf->SetLineWidth(0.2);
        $pdf->SetDrawColor(214, 224, 238);
        $pdf->Rect($box[0], $box[1], $box[2], $box[3]);
        $pdf->SetXY($x, $top);
        $pdf->SetTextColor($navy[0], $navy[1], $navy[2]);
        $pdf->SetFont('Nunito', 'B', 10 * $k);
        $pdf->Cell($w, 6 * $k, $r['title'] . ' No. ' . $r['receipt_no'] . ' (continued)', 0, 0, 'L');
        $cy = $top + 9 * $k;
    }

    // right column: totals, then the principal's signature with the stamp;
    // left column: payment options (cut short if space runs out), thanks and print date
    $tot_w = min(80, $w * 0.45);
    $tot_x = $x + $w - $tot_w;
    $ty = $cy;
    foreach ($r['totals'] as $total) {
        $strong = $total[2] ?? false;
        $pdf->SetFillColor($strong ? $navy[0] : 244, $strong ? $navy[1] : 247, $strong ? $navy[2] : 252);
        $pdf->Rect($tot_x, $ty, $tot_w, 5.6 * $k - 0.6, 'F');
        $pdf->SetXY($tot_x + 2, $ty);
        $pdf->SetTextColor($strong ? 255 : 60, $strong ? 255 : 60, $strong ? 255 : 60);
        $pdf->SetFont('Nunito', $strong ? 'B' : '', 7.5 * $k);
        $pdf->Cell($tot_w / 2, 5 * $k, $total[0], 0, 0, 'L');
        $pdf->SetTextColor($strong ? 255 : 0, $strong ? 255 : 0, $strong ? 255 : 0);
        $pdf->SetFont('Times', 'B', 9.5 * $k);
        $pdf->Cell($tot_w / 2 - 4, 5 * $k, $total[1], 0, 0, 'R');
        $ty += 5.6 * $k;
    }
    $sig_top = min($bottom - 14 * $k, $ty + 3 * $k);
    // the signature section is left out until a signature or stamp has been set
    $signature = sig_headteacher_signature_file($conn, $conn2);
    $stamp = sig_stamp_file($conn2, $r['doc_key']);
    $has_sig_block = ($signature && @getimagesize($signature)) || ($stamp && @getimagesize($stamp));
    if ($has_sig_block) {
        sig_pdf_image_in_box($pdf, $signature, $tot_x + 4, $sig_top, $tot_w - 8, 9 * $k, true);
        $pdf->SetDrawColor(150, 160, 175);
        $pdf->Line($tot_x, $sig_top + 9.5 * $k, $tot_x + $tot_w, $sig_top + 9.5 * $k);
        $pdf->SetXY($tot_x, $sig_top + 10 * $k);
        $pdf->SetTextColor($navy[0], $navy[1], $navy[2]);
        $pdf->SetFont('Nunito', 'B', 6.8 * $k);
        $pdf->Cell($tot_w, 3.5 * $k, "PRINCIPAL'S SIGNATURE", 0, 0, 'C');
        // stamp at the far right of the column; sig_pdf_stamp keeps it 5mm in from the edge given
        sig_pdf_stamp($pdf, $stamp, 26 * $k, $tot_x + $tot_w + 5, $sig_top - 4 * $k);
    }

    $opt_w = $w - $tot_w - 4;
    $thanks_y = $has_sig_block ? max($sig_top + 6 * $k, $cy + 2) : $cy + 2;
    if (count($r['options']) > 0) {
        $pdf->SetFont('Times', '', 7.4 * $k);
        $lines = [];
        foreach ($r['options'] as $i => $option) {
            foreach (rc_wrap_lines($pdf, ($i + 1) . ". " . $option, $opt_w - 5) as $line) $lines[] = $line;
        }
        $line_h = 3.3 * $k;
        $room = (int) floor(($bottom - 9 * $k - 2 - $cy - 6.5 * $k) / $line_h);
        if ($room > 0) {
            if (count($lines) > $room) {
                $lines = array_slice($lines, 0, $room);
                $lines[$room - 1] = rtrim($lines[$room - 1], ' .') . '...';
            }
            $box_h = 5 * $k + count($lines) * $line_h + 1.5;
            $pdf->SetFillColor(250, 246, 234);
            $pdf->SetDrawColor(226, 206, 150);
            $pdf->Rect($x, $cy, $opt_w, $box_h, 'DF');
            $pdf->SetXY($x + 2.5, $cy + 1);
            $pdf->SetTextColor(150, 105, 15);
            $pdf->SetFont('Nunito', 'B', 6.8 * $k);
            $pdf->Cell($opt_w - 5, 3.5 * $k, 'PAYMENT OPTIONS', 0, 0, 'L');
            $pdf->SetTextColor(50, 50, 50);
            $pdf->SetFont('Times', '', 7.4 * $k);
            foreach ($lines as $li => $line) {
                $pdf->SetXY($x + 2.5, $cy + 4.8 * $k + $li * $line_h);
                $pdf->Cell($opt_w - 5, $line_h, $line, 0, 0, 'L');
            }
            $thanks_y = max($thanks_y, $cy + $box_h + 2);
        }
    }
    $pdf->SetXY($x, min($thanks_y, $bottom - 8 * $k));
    $pdf->SetTextColor(110, 110, 110);
    $pdf->SetFont('Nunito', 'I', 8 * $k);
    $pdf->Cell($opt_w, 4 * $k, 'Received with thanks. Payment once made is not refundable.', 0, 2, 'L');
    $pdf->SetFont('Nunito', '', 6.5 * $k);
    $pdf->Cell($opt_w, 3.5 * $k, 'Printed on ' . date("D jS M Y, h:i A"), 0, 0, 'L');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetDrawColor(0, 0, 0);
}

// A plain page with no header or footer; the receipt draws its own.
function rc_receipt_pdf($size = 'A4')
{
    $pdf = new FPDF('P', 'mm', $size);
    $pdf->SetMargins(0, 0, 0);
    $pdf->SetAutoPageBreak(false);
    return $pdf;
}

// Dashed cut line across the middle of an A4 page.
function rc_receipt_cut_line($pdf)
{
    $pdf->SetDrawColor(150, 150, 150);
    $pdf->SetLineWidth(0.2);
    for ($dx = 8; $dx < 202; $dx += 4) {
        $pdf->Line($dx, 148.5, min($dx + 2, 202), 148.5);
    }
    $pdf->SetDrawColor(0, 0, 0);
}

// Places receipts on the page:
//   "full"  one receipt on a whole A4 page (a long list carries on to more pages)
//   "top"   one receipt on the top half of an A4 page
//   "both"  the same receipt twice on one A4 page, with a cut line ($labels: [top, bottom])
// A receipt whose list is too long for half a page is printed on whole pages instead.
function rc_receipt_sheet($pdf, $conn, $conn2, $school, $r, $layout, $labels = ["", ""])
{
    $half = rc_receipt_fits($r, 194, 136);
    if ($layout == "full" || !$half) {
        $copies = $layout == "both" ? $labels : [$labels[0]];
        foreach ($copies as $label) {
            $pdf->AddPage();
            // a short list keeps the compact receipt; a long one uses the whole page
            $compact = rc_receipt_fits($r, 190, 172, 1.15);
            rc_receipt_draw($pdf, $conn, $conn2, $school, $r, 10, 10, 190, $compact ? 172 : 277, $label, $compact ? 1.15 : 1, true);
        }
        return;
    }
    $pdf->AddPage();
    rc_receipt_draw($pdf, $conn, $conn2, $school, $r, 8, 7, 194, 136, $labels[0]);
    if ($layout == "both") {
        rc_receipt_cut_line($pdf);
        rc_receipt_draw($pdf, $conn, $conn2, $school, $r, 8, 154, 194, 136, $labels[1]);
    }
}

// ── Thermal receipt: 80mm roll, black and white, page as long as the receipt ──

define('RC_THERMAL_WIDTH', 80);
define('RC_THERMAL_MARGIN', 4);

// Draws the receipt down a strip RC_THERMAL_WIDTH wide and returns the y where it ends.
function rc_receipt_thermal_draw($pdf, $school, $r)
{
    $x = RC_THERMAL_MARGIN;
    $w = RC_THERMAL_WIDTH - 2 * RC_THERMAL_MARGIN;
    $y = 4;
    $line = function ($text, $style = '', $size = 8, $align = 'C', $h = 4) use ($pdf, $x, $w, &$y) {
        $pdf->SetFont('Helvetica', $style, $size);
        foreach (rc_wrap_lines($pdf, $text, $w) as $part) {
            $pdf->SetXY($x, $y);
            $pdf->Cell($w, $h, $part, 0, 0, $align);
            $y += $h;
        }
    };
    $pair = function ($label, $value, $style = '', $size = 8) use ($pdf, $x, $w, &$y) {
        $pdf->SetFont('Helvetica', $style, $size);
        $value_w = min($pdf->GetStringWidth($value) + 1, $w * 0.62);
        $pdf->SetXY($x, $y);
        rc_fit_text($pdf, $w - $value_w, 4.2, $label, 'L', 'Helvetica', $style, $size, 6);
        $pdf->SetXY($x + $w - $value_w, $y);
        rc_fit_text($pdf, $value_w, 4.2, $value, 'R', 'Helvetica', $style, $size, 6);
        $y += 4.2;
    };
    $rule = function ($dashed = true) use ($pdf, $x, $w, &$y) {
        $y += 1;
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(0.2);
        if ($dashed) {
            for ($dx = $x; $dx < $x + $w; $dx += 2) $pdf->Line($dx, $y, min($dx + 1, $x + $w), $y);
        } else {
            $pdf->Line($x, $y, $x + $w, $y);
        }
        $y += 2;
    };

    // header: logo, college and contacts
    $logo = isset($school['school_profile_image']) ? dirname(__FILE__) . "/../" . $school['school_profile_image'] : "";
    if (strlen($logo) > 0 && is_file($logo)) {
        try {
            $pdf->Image($logo, $x + ($w - 16) / 2, $y, 16);
            $y += 18;
        } catch (Exception $e) {}
    }
    $line(strtoupper(trim($school['school_name'] ?? '')), 'B', 10, 'C', 4.6);
    if (strlen(trim((string) ($school['school_motto'] ?? ''))) > 0) {
        $line(ucfirst(strtolower(trim($school['school_motto']))), 'I', 7.5);
    }
    $line('P.O. Box ' . ($school['po_box'] ?? '') . '-' . ($school['box_code'] ?? '') . (strlen(trim((string) ($school['county'] ?? ''))) > 0 ? ', ' . $school['county'] : ''), '', 7.5);
    $line('Tel: ' . ($school['school_contact'] ?? ''), '', 7.5);
    if (strlen(trim((string) ($school['school_mail'] ?? ''))) > 0) $line($school['school_mail'], '', 7.5);
    $rule(false);
    $line($r['title'], 'B', 10, 'C', 4.8);
    $line('No. ' . $r['receipt_no'], 'B', 9);
    $rule();

    // payment details
    foreach ($r['fields'] as $field) {
        $pair(ucwords(strtolower($field[0])), (string) $field[1]);
    }
    $rule();

    // voteheads: name on the left, amount on the right
    $pdf->SetFont('Helvetica', 'B', 8);
    $pair('Votehead', 'Amount', 'B');
    foreach ($r['rows'] as $row) {
        $pair($row[1] . (strlen((string) $row[2]) > 0 ? ' (' . $row[2] . ')' : ''), $row[3]);
    }
    $rule();

    // amount received, in figures and words, then the balances
    $pair('AMOUNT PAID', 'Kes ' . number_format($r['amount'] * 1, 2), 'B', 10);
    $words = new NUmbers();
    $amount = $r['amount'] * 1;
    $line(($amount < 0 ? "Negative " : "") . $words->convert_number(abs($amount)) . " Kenya Shillings Only", 'I', 7.5, 'L', 3.6);
    $y += 1;
    foreach ($r['totals'] as $total) {
        if ($total[0] == 'Amount Paid') continue;
        $pair($total[0], $total[1], ($total[2] ?? false) ? 'B' : '');
    }

    if (count($r['options']) > 0) {
        $rule();
        $line('PAYMENT OPTIONS', 'B', 7.5, 'L');
        foreach ($r['options'] as $i => $option) {
            $line(($i + 1) . '. ' . $option, '', 7.5, 'L', 3.6);
        }
    }
    $rule();
    $line('Received with thanks.', 'I', 8);
    $line('Payment once made is not refundable.', '', 7);
    $line('Printed on ' . date("D jS M Y, h:i A"), '', 7);
    return $y + 4;
}

// The thermal receipt on a page cut to its own length.
function rc_receipt_thermal_pdf($school, $r)
{
    // draw once on a throwaway page to measure, then on a page of exactly that length
    $measure = new FPDF('P', 'mm', [RC_THERMAL_WIDTH, 2000]);
    $measure->SetAutoPageBreak(false);
    $measure->AddPage();
    $height = rc_receipt_thermal_draw($measure, $school, $r);

    $pdf = new FPDF('P', 'mm', [RC_THERMAL_WIDTH, max($height, RC_THERMAL_WIDTH + 10)]);
    $pdf->SetMargins(0, 0, 0);
    $pdf->SetAutoPageBreak(false);
    $pdf->AddPage();
    rc_receipt_thermal_draw($pdf, $school, $r);
    return $pdf;
}
