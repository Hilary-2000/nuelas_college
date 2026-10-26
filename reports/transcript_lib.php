<?php
// Student transcripts in the navy and gold look, with the deputy principal's and
// principal's signatures and the college stamp.
//
// A semester is one of the course's module terms ("MODULE 1", "MODULE 2", ...). Its
// units come from course_unit_assignment, and each unit's result is the student's
// mark in the latest exam they sat for that unit while in that module.
//   Semester-wise: one module of the course.
//   Year-wise:     every module the student started in the chosen year.

const TR_NAVY = [24, 62, 120];
const TR_GOLD = [214, 168, 46];
// lowest y the unit tables may reach before the signatures need a new page
const TR_TABLE_BOTTOM = 232;

// Students taking the course at this level (from their course list), or just the
// one asked for. Returns [[student row, course entry], ...].
function tr_students($conn2, $course_level, $course_id, $reg_no = "")
{
    if (strlen(trim($reg_no)) > 0) {
        $stmt = $conn2->prepare("SELECT * FROM `student_data` WHERE `adm_no` = ? AND `deleted` = 0");
        $stmt->bind_param("s", $reg_no);
    } else {
        $stmt = $conn2->prepare("SELECT * FROM `student_data` WHERE `deleted` = 0 ORDER BY `first_name`, `second_name`, `surname`");
    }
    $stmt->execute();
    $result = $stmt->get_result();
    // a course list may hold the level by name ("Diploma") or by its id in the 'class' setting
    $level_keys = [(string) $course_level];
    $level_stmt = $conn2->prepare("SELECT `valued` FROM `settings` WHERE `sett` = 'class' LIMIT 1");
    $level_stmt->execute();
    $level_row = $level_stmt->get_result()->fetch_assoc();
    foreach (($level_row && isJson_report($level_row['valued'])) ? json_decode($level_row['valued']) : [] as $level) {
        if ((string) $level->classes === (string) $course_level) $level_keys[] = (string) $level->id;
    }
    $students = [];
    while ($result && $row = $result->fetch_assoc()) {
        $course_list = isJson_report($row['my_course_list']) ? json_decode($row['my_course_list'], true) : [];
        foreach ($course_list as $entry) {
            if ((string) ($entry['course_name'] ?? '') === (string) $course_id && in_array((string) ($entry['course_level'] ?? ''), $level_keys, true)) {
                $students[] = [$row, $entry];
                break;
            }
        }
    }
    return $students;
}

// Units of a module, in name order.
function tr_module_units($conn2, $course_id, $module_number)
{
    static $cache = [];
    $key = $course_id . "|" . $module_number;
    if (isset($cache[$key])) return $cache[$key];
    $stmt = $conn2->prepare("SELECT `table_subject`.* FROM `course_unit_assignment` JOIN `table_subject` ON `table_subject`.`subject_id` = `course_unit_assignment`.`unit_id` WHERE `course_unit_assignment`.`course_id` = ? AND `course_unit_assignment`.`module_number` = ? ORDER BY `table_subject`.`display_name`");
    $stmt->bind_param("ss", $course_id, $module_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $units = [];
    while ($result && $row = $result->fetch_assoc()) {
        $units[$row['subject_id']] = $row;
    }
    return $cache[$key] = array_values($units);
}

// The student's mark in the latest exam they sat for this unit while in this module.
function tr_unit_result($conn2, $adm_no, $course_id, $module_name, $unit_id)
{
    $stmt = $conn2->prepare("SELECT `exam_record_tbl`.`exam_marks`, `exam_record_tbl`.`exam_grade`, `exams_tbl`.`exams_name` FROM `exam_record_tbl` JOIN `examinees` ON `examinees`.`examinee_id` = `exam_record_tbl`.`examinee_id` JOIN `exams_tbl` ON `exams_tbl`.`exams_id` = `exam_record_tbl`.`exam_id` WHERE `exam_record_tbl`.`student_id` = ? AND `exam_record_tbl`.`unit_id` = ? AND `examinees`.`course_id` = ? AND `examinees`.`active_module` = ? AND (`exams_tbl`.`deleted` IS NULL OR `exams_tbl`.`deleted` = 0) ORDER BY `exams_tbl`.`end_date` DESC, `exams_tbl`.`exams_id` DESC LIMIT 1");
    $stmt->bind_param("ssss", $adm_no, $unit_id, $course_id, $module_name);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Grade for a mark out of the unit's maximum, from the unit's own grading.
function tr_grade_for($unit, $percent)
{
    $grading = isJson_report($unit['grading'] ?? '') ? json_decode($unit['grading'], true) : [];
    foreach ($grading as $grade) {
        if ($percent >= $grade['min'] * 1 && $percent <= $grade['max'] * 1) return $grade['grade_name'];
    }
    return "-";
}

// "A 80-100   B 60-79 ..." from the first unit that has a grading.
function tr_grading_key($units)
{
    foreach ($units as $unit) {
        $grading = isJson_report($unit['grading'] ?? '') ? json_decode($unit['grading'], true) : [];
        if (count($grading) == 0) continue;
        usort($grading, function ($a, $b) { return $b['max'] * 1 <=> $a['max'] * 1; });
        return implode("     ", array_map(function ($g) { return $g['grade_name'] . ": " . $g['min'] . " to " . $g['max']; }, $grading));
    }
    return "";
}

// Rows for one module: [[#, code, unit, exam, marks, grade], ...] and the mean percent.
function tr_module_rows($conn2, $adm_no, $course_id, $module)
{
    $units = tr_module_units($conn2, $course_id, $module['id']);
    $rows = [];
    $percents = [];
    foreach ($units as $index => $unit) {
        $result = tr_unit_result($conn2, $adm_no, $course_id, $module['term_name'], $unit['subject_id']);
        $max = $unit['max_marks'] > 0 ? $unit['max_marks'] * 1 : 100;
        if ($result && strlen((string) $result['exam_marks']) > 0) {
            $percent = $result['exam_marks'] * 100 / $max;
            $percents[] = $percent;
            $grade = strlen(trim((string) $result['exam_grade'])) > 0 ? $result['exam_grade'] : tr_grade_for($unit, $percent);
            $rows[] = [$index + 1, $unit['timetable_id'], $unit['display_name'], ucwords(strtolower($result['exams_name'])), $result['exam_marks'] . " / " . $max, $grade];
        } else {
            $rows[] = [$index + 1, $unit['timetable_id'], $unit['display_name'], "Not examined", "-", "-"];
        }
    }
    $mean = count($percents) > 0 ? array_sum($percents) / count($percents) : null;
    return [$units, $rows, $mean, count($percents)];
}

// "MODULE 1" and its dates as "Module 1 (Jan 2026 to Jun 2026)".
function tr_module_title($module)
{
    $title = ucwords(strtolower($module['term_name']));
    $start = strlen((string) ($module['start_date'] ?? '')) >= 8 ? date("M Y", strtotime(substr($module['start_date'], 0, 8))) : "";
    $end = strlen((string) ($module['end_date'] ?? '')) >= 8 ? date("M Y", strtotime(substr($module['end_date'], 0, 8))) : "";
    if ($start !== "" && $end !== "") return $title . " (" . $start . " to " . $end . ")";
    if ($start !== "") return $title . " (from " . $start . ")";
    return $title;
}

function tr_fonts($pdf)
{
    $pdf->AddFont('Nunito', '', 'Nunito-Regular.php');
    $pdf->AddFont('Nunito', 'B', 'Nunito-Bold.php');
    $pdf->AddFont('Nunito', 'I', 'Nunito-Italic.php');
}

// Thin navy and gold bars at the top and bottom of the page.
function tr_frame($pdf)
{
    $pdf->SetFillColor(TR_NAVY[0], TR_NAVY[1], TR_NAVY[2]);
    $pdf->Rect(10, 7, 190, 1.6, 'F');
    $pdf->Rect(10, 288.5, 190, 1.6, 'F');
    $pdf->SetFillColor(TR_GOLD[0], TR_GOLD[1], TR_GOLD[2]);
    $pdf->Rect(10, 8.6, 190, 0.6, 'F');
    $pdf->Rect(10, 287.9, 190, 0.6, 'F');
}

// The student's photo fitted inside the box, or an empty photo frame.
function tr_photo($pdf, $student, $x, $y, $w, $h)
{
    $pdf->SetDrawColor(214, 224, 238);
    $pdf->SetFillColor(244, 247, 252);
    $pdf->Rect($x, $y, $w, $h, 'DF');
    $photo = trim((string) ($student['student_image'] ?? ''));
    $path = strlen($photo) > 0 ? dirname(__FILE__) . "/../" . $photo : "";
    $size = ($path !== "" && is_file($path)) ? @getimagesize($path) : false;
    if ($size && $size[0] > 0 && $size[1] > 0) {
        $scale = min($w / $size[0], $h / $size[1]);
        try {
            $pdf->Image($path, $x + ($w - $size[0] * $scale) / 2, $y + ($h - $size[1] * $scale) / 2, $size[0] * $scale, $size[1] * $scale);
            return;
        } catch (Exception $e) {
        }
    }
    $pdf->SetXY($x, $y + $h / 2 - 3);
    $pdf->SetTextColor(150, 160, 175);
    $pdf->SetFont('Nunito', '', 8);
    $pdf->Cell($w, 6, 'PHOTO', 0, 0, 'C');
}

// New page with the frame, college logo, college details, the student's photo (when
// $student is given) and the title on a navy band. Returns the y where the page
// content starts.
function tr_header($pdf, $conn, $student, $title)
{
    $pdf->AddPage();
    tr_frame($pdf);
    $logo = schoolLogo($conn);
    if (strlen($logo) > 0 && is_file(dirname(__FILE__) . "/../" . $logo)) {
        try { $pdf->Image(dirname(__FILE__) . "/../" . $logo, 13, 14, 28); } catch (Exception $e) {}
    }
    if ($student !== null) tr_photo($pdf, $student, 172, 13, 25, 30);

    $pdf->SetXY(44, 14);
    $pdf->SetTextColor(TR_NAVY[0], TR_NAVY[1], TR_NAVY[2]);
    $pdf->SetFont('Nunito', 'B', 16);
    $pdf->Cell(126, 8, strtoupper(trim($_SESSION['schname'])), 0, 1, 'C');
    if (isset($_SESSION['smotto']) && strlen(trim($_SESSION['smotto'])) > 0) {
        $pdf->SetX(44);
        $pdf->SetTextColor(170, 120, 20);
        $pdf->SetFont('Nunito', 'I', 9.5);
        $pdf->Cell(126, 5, ucfirst(trim($_SESSION['smotto'])), 0, 1, 'C');
    }
    $pdf->SetTextColor(60, 60, 60);
    $pdf->SetX(44);
    $pdf->SetFont('Nunito', '', 9);
    $location = trim((string) ($_SESSION['sch_countys'] ?? ''));
    $pdf->Cell(126, 4.6, 'P.O. Box ' . $_SESSION['po_boxs'] . '-' . $_SESSION['box_codes'] . (strlen($location) > 0 ? ', ' . $location : '') . '.', 0, 1, 'C');
    $pdf->SetX(44);
    $contact_line = 'Tel: ' . $_SESSION['school_contact'];
    if (isset($_SESSION['school_mail']) && strlen(trim($_SESSION['school_mail'])) > 0) {
        $contact_line .= '   |   Email: ' . $_SESSION['school_mail'];
    }
    $pdf->Cell(126, 4.6, $contact_line, 0, 1, 'C');

    $pdf->SetFillColor(TR_NAVY[0], TR_NAVY[1], TR_NAVY[2]);
    $pdf->Rect(10, 47, 190, 8, 'F');
    $pdf->SetXY(10, 47);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Nunito', 'B', 11);
    $pdf->Cell(190, 8, $title, 0, 1, 'C');
    $pdf->SetTextColor(0, 0, 0);
    return 58;
}

// A page that carries on a long transcript: frame and a short heading.
function tr_continue_page($pdf, $student, $title)
{
    $pdf->AddPage();
    tr_frame($pdf);
    $pdf->SetXY(10, 13);
    $pdf->SetTextColor(TR_NAVY[0], TR_NAVY[1], TR_NAVY[2]);
    $pdf->SetFont('Nunito', 'B', 10);
    $name = ucwords(strtolower(trim($student['first_name'] . " " . $student['second_name'] . " " . $student['surname'])));
    $pdf->Cell(190, 6, $title . ": " . $name . " (" . $student['adm_no'] . "), continued", 0, 1, 'L');
    $pdf->SetTextColor(0, 0, 0);
    return 22;
}

// Shaded box of "LABEL value" pairs, two per row. Returns the y below the box.
function tr_details($pdf, $y, $pairs)
{
    $rows = array_chunk($pairs, 2);
    $height = 2 + 8 * count($rows);
    $pdf->SetFillColor(244, 247, 252);
    $pdf->SetDrawColor(214, 224, 238);
    $pdf->SetLineWidth(0.2);
    $pdf->Rect(10, $y, 190, $height, 'DF');
    foreach ($rows as $r => $row) {
        foreach ($row as $c => $pair) {
            $x = 14 + $c * 95;
            $pdf->SetXY($x, $y + 2 + $r * 8);
            $pdf->SetTextColor(TR_NAVY[0], TR_NAVY[1], TR_NAVY[2]);
            $pdf->SetFont('Nunito', 'B', 8.5);
            $pdf->Cell(28, 7, $pair[0], 0, 0, 'L');
            $pdf->SetTextColor(0, 0, 0);
            rc_fit_text($pdf, 60, 7, $pair[1], 'L', 'Times', '', 11, 7);
        }
    }
    return $y + $height;
}

// Navy band with a module title.
function tr_band($pdf, $y, $text)
{
    $pdf->SetFillColor(TR_NAVY[0], TR_NAVY[1], TR_NAVY[2]);
    $pdf->Rect(10, $y, 190, 7, 'F');
    $pdf->SetXY(10, $y);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Nunito', 'B', 10);
    $pdf->Cell(190, 7, $text, 0, 1, 'C');
    $pdf->SetTextColor(0, 0, 0);
    return $y + 7;
}

const TR_COLUMNS = [['#', 9, 'C'], ['Unit Code', 26, 'L'], ['Unit Name', 0, 'L'], ['Exam', 46, 'L'], ['Marks', 22, 'C'], ['Grade', 16, 'C']];

function tr_table_head($pdf, $y)
{
    $fixed = 0;
    foreach (TR_COLUMNS as $column) $fixed += $column[1];
    $pdf->SetDrawColor(150, 160, 175);
    $pdf->SetFillColor(214, 226, 242);
    $pdf->SetTextColor(TR_NAVY[0], TR_NAVY[1], TR_NAVY[2]);
    $pdf->SetFont('Nunito', 'B', 8);
    $pdf->SetXY(10, $y);
    foreach (TR_COLUMNS as $column) {
        $cw = $column[1] > 0 ? $column[1] : 190 - $fixed;
        $pdf->Cell($cw, 6.5, ($column[2] == 'L' ? ' ' : '') . $column[0], 1, 0, $column[2], true);
    }
    $pdf->SetTextColor(0, 0, 0);
    return $y + 6.5;
}

// One module: band, unit table and its summary line. Long tables carry on to new pages.
function tr_module_section($pdf, $student, $title, $y, $module_title, $rows, $mean, $examined)
{
    if ($y + 7 + 6.5 + 6.5 * min(3, max(1, count($rows))) > TR_TABLE_BOTTOM) {
        $y = tr_continue_page($pdf, $student, $title);
    }
    $y = tr_band($pdf, $y, $module_title);
    $y = tr_table_head($pdf, $y);
    $fixed = 0;
    foreach (TR_COLUMNS as $column) $fixed += $column[1];
    if (count($rows) == 0) {
        $pdf->SetXY(10, $y);
        $pdf->SetFont('Times', 'I', 10);
        $pdf->Cell(190, 6.5, 'No units have been assigned to this module yet.', 1, 1, 'C');
        return $y + 9;
    }
    foreach ($rows as $index => $cells) {
        if ($y + 6.5 > TR_TABLE_BOTTOM + 30) {
            $y = tr_table_head($pdf, tr_continue_page($pdf, $student, $title));
        }
        $pdf->SetFillColor($index % 2 ? 246 : 255, $index % 2 ? 248 : 255, $index % 2 ? 252 : 255);
        $pdf->SetXY(10, $y);
        foreach (TR_COLUMNS as $ci => $column) {
            $cw = $column[1] > 0 ? $column[1] : 190 - $fixed;
            $text = ($column[2] == 'L' ? ' ' : '') . $cells[$ci];
            $size = 9.5;
            $pdf->SetFont('Times', $ci == 5 ? 'B' : '', $size);
            while ($size > 6 && $pdf->GetStringWidth($text) > $cw - 1.5) {
                $size -= 0.5;
                $pdf->SetFont('Times', $ci == 5 ? 'B' : '', $size);
            }
            $pdf->Cell($cw, 6.5, $text, 1, 0, $column[2], true);
        }
        $y += 6.5;
    }
    $pdf->SetXY(10, $y + 1);
    $pdf->SetTextColor(70, 70, 70);
    $pdf->SetFont('Nunito', '', 8.5);
    $summary = "Units examined: " . $examined . " of " . count($rows) . ($mean !== null ? "     Mean score: " . number_format($mean, 1) . "%" : "");
    $pdf->Cell(190, 5, $summary, 0, 0, 'R');
    $pdf->SetTextColor(0, 0, 0);
    return $y + 9;
}

// Deputy principal and principal signature boxes, the stamp over the principal's.
function tr_signatures($pdf, $conn, $conn2, $y, $stamp_key = 'transcript')
{
    $config = sig_get_config($conn2);
    $principal = sig_signatures_enabled($conn2) ? sig_user_signature_file($conn, $config['signing_headteacher']) : null;
    $deputy = sig_deputy_signature_file($conn, $conn2);
    $height = 28;
    $pdf->SetFillColor(244, 247, 252);
    $pdf->SetDrawColor(214, 224, 238);
    $pdf->SetLineWidth(0.2);
    $pdf->Rect(10, $y, 92, $height, 'DF');
    $pdf->Rect(108, $y, 92, $height, 'DF');
    $pdf->SetTextColor(TR_NAVY[0], TR_NAVY[1], TR_NAVY[2]);
    $pdf->SetFont('Nunito', 'B', 9);
    $pdf->SetXY(10, $y + 2);
    $pdf->Cell(92, 6, "DEPUTY PRINCIPAL'S SIGNATURE", 0, 0, 'C');
    $pdf->SetXY(108, $y + 2);
    $pdf->Cell(92, 6, "PRINCIPAL'S SIGNATURE", 0, 1, 'C');
    sig_pdf_image_in_box($pdf, $deputy, 14, $y + 8, 84, $height - 16, true);
    sig_pdf_image_in_box($pdf, $principal, 112, $y + 8, 84, $height - 16, true);
    $pdf->SetDrawColor(150, 160, 175);
    $pdf->Line(21, $y + $height - 6, 91, $y + $height - 6);
    $pdf->Line(119, $y + $height - 6, 189, $y + $height - 6);
    $pdf->SetXY(10, $y + $height - 5.5);
    $pdf->SetTextColor(120, 120, 120);
    $pdf->SetFont('Nunito', '', 7);
    $pdf->Cell(92, 4, 'Date: ' . date("jS M Y"), 0, 0, 'C');
    $pdf->SetXY(108, $y + $height - 5.5);
    $pdf->Cell(92, 4, 'Date: ' . date("jS M Y"), 0, 0, 'C');
    sig_pdf_stamp($pdf, sig_stamp_file($conn2, $stamp_key), 36, 198, $y - 3, SIG_REPORT_CARD_STAMP_ANGLE);
    $pdf->SetTextColor(0, 0, 0);
    return $y + $height;
}

// One student's transcript. $modules are the module terms to include.
function tr_student_transcript($pdf, $conn, $conn2, $student, $entry, $modules, $title, $period_label, $period_value)
{
    $course_id = $entry['course_name'];
    $y = tr_header($pdf, $conn, $student, $title);
    $name = ucwords(strtolower(trim($student['first_name'] . " " . $student['second_name'] . " " . $student['surname'])));
    $intake = trim(ucwords(strtolower((string) $student['intake_month'])) . " " . $student['intake_year']);
    $y = tr_details($pdf, $y, [
        ["NAME", $name], ["REG NO.", $student['adm_no']],
        ["COURSE", rc_receipt_course($conn2, ['course_done' => $course_id, 'stud_class' => $entry['course_level']])], ["LEVEL", $entry['course_level']],
        [$period_label, $period_value], ["INTAKE", strlen($intake) > 0 ? $intake : "N/A"],
        ["GENDER", strlen(trim((string) $student['gender'])) > 0 ? $student['gender'] : "N/A"], ["ISSUED ON", date("jS M Y")],
    ]) + 4;

    $all_units = [];
    $all_percents = [];
    foreach ($modules as $module) {
        [$units, $rows, $mean, $examined] = tr_module_rows($conn2, $student['adm_no'], $course_id, $module);
        $all_units = array_merge($all_units, $units);
        if ($mean !== null) {
            for ($i = 0; $i < $examined; $i++) $all_percents[] = $mean;
        }
        $y = tr_module_section($pdf, $student, $title, $y, tr_module_title($module), $rows, $mean, $examined);
    }
    if (count($modules) == 0) {
        $pdf->SetXY(10, $y);
        $pdf->SetFont('Times', 'I', 10);
        $pdf->Cell(190, 8, 'The student has no modules in this period.', 1, 1, 'C');
        $y += 11;
    }

    // overall mean across modules on the year-wise transcript
    if (count($modules) > 1 && count($all_percents) > 0) {
        $pdf->SetFillColor(214, 226, 242);
        $pdf->Rect(10, $y, 190, 8, 'F');
        $pdf->SetFillColor(TR_NAVY[0], TR_NAVY[1], TR_NAVY[2]);
        $pdf->Rect(10, $y, 1.2, 8, 'F');
        $pdf->SetXY(14, $y);
        $pdf->SetTextColor(TR_NAVY[0], TR_NAVY[1], TR_NAVY[2]);
        $pdf->SetFont('Nunito', 'B', 9.5);
        $pdf->Cell(120, 8, 'OVERALL MEAN SCORE (' . count($all_percents) . (count($all_percents) == 1 ? ' unit' : ' units') . ' examined)', 0, 0, 'L');
        $pdf->Cell(64, 8, number_format(array_sum($all_percents) / count($all_percents), 1) . '%', 0, 0, 'R');
        $pdf->SetTextColor(0, 0, 0);
        $y += 11;
    }

    // signatures and the stamp stay together on the last page
    if ($y + 46 > 283) {
        $y = tr_continue_page($pdf, $student, $title);
    }
    $key = tr_grading_key($all_units);
    if ($key !== "") {
        $pdf->SetXY(10, $y);
        $pdf->SetTextColor(TR_NAVY[0], TR_NAVY[1], TR_NAVY[2]);
        $pdf->SetFont('Nunito', 'B', 8);
        $pdf->Cell(22, 5, 'GRADING KEY', 0, 0, 'L');
        $pdf->SetTextColor(60, 60, 60);
        rc_fit_text($pdf, 168, 5, $key, 'L', 'Times', '', 9, 6.5);
        $y += 8;
    }
    $y = tr_signatures($pdf, $conn, $conn2, max($y, 238)) + 3;
    $pdf->SetXY(10, min($y, 281));
    $pdf->SetTextColor(120, 120, 120);
    $pdf->SetFont('Nunito', 'I', 8);
    $pdf->Cell(190, 5, 'This transcript is valid only with the college stamp and an authorised signature.', 0, 0, 'C');
    $pdf->SetTextColor(0, 0, 0);
}

// Prints the semester-wise ($kind "transcripts") or year-wise ("report_card") transcripts.
function tr_print_transcripts($conn, $conn2, $kind, $course_level, $course_id, $module_number, $year, $reg_no)
{
    if (strlen(trim($course_level)) == 0 || strlen(trim($course_id)) == 0) {
        echo "<p style='color:red;'>Select the course level and the course to print transcripts for.</p>";
        return;
    }
    $year_wise = $kind == "report_card";
    if ($year_wise && !preg_match('/^\d{4}$/', (string) $year)) {
        echo "<p style='color:red;'>Select the year to print the transcripts for.</p>";
        return;
    }
    if (!$year_wise && strlen(trim($module_number)) == 0) {
        echo "<p style='color:red;'>Select the module (semester) to print the transcripts for.</p>";
        return;
    }

    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetAutoPageBreak(false);
    tr_fonts($pdf);
    $printed = 0;
    foreach (tr_students($conn2, $course_level, $course_id, $reg_no) as [$student, $entry]) {
        $modules = [];
        foreach (($entry['module_terms'] ?? []) as $module) {
            if ($year_wise) {
                $start = (string) ($module['start_date'] ?? '');
                if (strlen($start) >= 4 && substr($start, 0, 4) === (string) $year) $modules[] = $module;
            } elseif ((string) $module['id'] === (string) $module_number) {
                $modules[] = $module;
            }
        }
        // a semester transcript is only for students who have reached that module
        if (!$year_wise && (count($modules) == 0 || (string) ($modules[0]['status'] ?? '0') === '0')) continue;
        if ($year_wise && count($modules) == 0) continue;
        // print the level by name even when the course list holds its id
        $entry['course_level'] = $course_level;
        if ($year_wise) {
            tr_student_transcript($pdf, $conn, $conn2, $student, $entry, $modules, "ACADEMIC TRANSCRIPT, YEAR " . $year, "YEAR", $year);
        } else {
            tr_student_transcript($pdf, $conn, $conn2, $student, $entry, $modules, "SEMESTER TRANSCRIPT", "SEMESTER", ucwords(strtolower($modules[0]['term_name'])));
        }
        $printed++;
    }
    if ($printed == 0) {
        echo "<p style='color:red;'><b>Note: </b><br>No students on that course " . ($year_wise ? "started a module in " . htmlspecialchars($year) : "have reached that module") . (strlen(trim($reg_no)) > 0 ? " with that registration number" : "") . ".</p>";
        return;
    }
    $pdf->SetTitle(($year_wise ? "Year " . $year : "Semester") . " Transcripts");
    $pdf->Output();
}
