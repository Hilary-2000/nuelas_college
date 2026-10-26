<?php
// Exam and CAT mark sheets (Student Exam Performance, Student CAT Performance and the
// blank sheets for filling marks) in the navy and gold look of the transcripts, with
// the deputy principal's and principal's signatures and the college stamp.
// Uses the page pieces in transcript_lib.php.

// $sheet: title, details ([[label, value], ...]), columns ([[name, width, align], ...],
// one with width 0 takes the space left), rows, summary (text or ""), remarks (bool).
function ms_print_sheet($conn, $conn2, $sheet)
{
    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetAutoPageBreak(false);
    tr_fonts($pdf);
    $pdf->SetTitle($sheet['file_title']);
    $y = tr_details($pdf, tr_header($pdf, $conn, null, $sheet['title']), $sheet['details']) + 4;

    if (strlen($sheet['summary']) > 0) {
        $pdf->SetFillColor(214, 226, 242);
        $pdf->Rect(10, $y, 190, 8, 'F');
        $pdf->SetFillColor(TR_NAVY[0], TR_NAVY[1], TR_NAVY[2]);
        $pdf->Rect(10, $y, 1.2, 8, 'F');
        $pdf->SetXY(14, $y);
        $pdf->SetTextColor(TR_NAVY[0], TR_NAVY[1], TR_NAVY[2]);
        $pdf->SetFont('Nunito', 'B', 9);
        $pdf->Cell(182, 8, $sheet['summary'], 0, 0, 'L');
        $pdf->SetTextColor(0, 0, 0);
        $y += 11;
    }

    $y = ms_table_head($pdf, $sheet['columns'], $y);
    $row_h = $sheet['blank'] ? 7.5 : 6.5;
    foreach ($sheet['rows'] as $index => $cells) {
        if ($y + $row_h > 280) {
            $pdf->AddPage();
            tr_frame($pdf);
            $pdf->SetXY(10, 13);
            $pdf->SetTextColor(TR_NAVY[0], TR_NAVY[1], TR_NAVY[2]);
            $pdf->SetFont('Nunito', 'B', 10);
            $pdf->Cell(190, 6, $sheet['title'] . ", continued", 0, 1, 'L');
            $y = ms_table_head($pdf, $sheet['columns'], 22);
        }
        $pdf->SetFillColor($index % 2 ? 246 : 255, $index % 2 ? 248 : 255, $index % 2 ? 252 : 255);
        $pdf->SetXY(10, $y);
        foreach ($sheet['columns'] as $ci => $column) {
            $text = ($column[2] == 'L' ? ' ' : '') . ($cells[$ci] ?? '');
            $size = 9.5;
            $pdf->SetFont('Times', '', $size);
            while ($size > 6 && $pdf->GetStringWidth($text) > ms_width($sheet['columns'], $ci) - 1.5) {
                $size -= 0.5;
                $pdf->SetFont('Times', '', $size);
            }
            $pdf->Cell(ms_width($sheet['columns'], $ci), $row_h, $text, 1, 0, $column[2], true);
        }
        $y += $row_h;
    }
    $y += 5;

    // remarks lines, then the signatures and stamp, kept together on the last page
    $needed = ($sheet['remarks'] ? 22 : 0) + 30;
    if ($y + $needed > 283) {
        $pdf->AddPage();
        tr_frame($pdf);
        $y = 15;
    }
    if ($sheet['remarks']) {
        $pdf->SetXY(10, $y);
        $pdf->SetTextColor(TR_NAVY[0], TR_NAVY[1], TR_NAVY[2]);
        $pdf->SetFont('Nunito', 'B', 9);
        $pdf->Cell(190, 6, "HEAD OF ACADEMICS' REMARKS", 0, 1, 'L');
        $pdf->SetDrawColor(150, 160, 175);
        $pdf->Line(10, $y + 13, 200, $y + 13);
        $pdf->Line(10, $y + 20, 200, $y + 20);
        $y += 24;
    }
    tr_signatures($pdf, $conn, $conn2, $y, 'exam_marks');
    $pdf->Output();
}

function ms_width($columns, $index)
{
    if ($columns[$index][1] > 0) return $columns[$index][1];
    $fixed = 0;
    foreach ($columns as $column) $fixed += $column[1];
    return 190 - $fixed;
}

function ms_table_head($pdf, $columns, $y)
{
    $pdf->SetDrawColor(150, 160, 175);
    $pdf->SetFillColor(214, 226, 242);
    $pdf->SetTextColor(TR_NAVY[0], TR_NAVY[1], TR_NAVY[2]);
    $pdf->SetFont('Nunito', 'B', 8);
    $pdf->SetXY(10, $y);
    foreach ($columns as $ci => $column) {
        $pdf->Cell(ms_width($columns, $ci), 7, ($column[2] == 'L' ? ' ' : '') . $column[0], 1, 0, $column[2], true);
    }
    $pdf->SetTextColor(0, 0, 0);
    return $y + 7;
}

// Details shared by both sheets: exam, course, level, module and unit.
function ms_details($conn2, $exam_name, $course_level, $course_id, $module_id, $unit_info, $examinees)
{
    return [
        ["EXAM", ucwords(strtolower($exam_name))], ["COURSE", rc_receipt_course($conn2, ['course_done' => $course_id, 'stud_class' => $course_level])],
        ["LEVEL", className_exam($course_level)], ["MODULE", "Module " . $module_id],
        ["UNIT", $unit_info['unit_name'] . (strlen((string) $unit_info['unit_code']) > 0 ? " (" . $unit_info['unit_code'] . ")" : "")], ["EXAMINEES", $examinees],
    ];
}

// "Examined: 5   Mean: 62.4   Highest: 80   Lowest: 37" from the scores entered so far.
function ms_summary($scores, $out_of)
{
    if (count($scores) == 0) return "No scores have been entered yet.";
    $mean = array_sum($scores) / count($scores);
    return "Examined: " . count($scores) . "      Mean: " . number_format($mean, 1) . " / " . $out_of . "      Highest: " . max($scores) . "      Lowest: " . min($scores);
}

function ms_student_name($student)
{
    return ucwords(strtolower(trim($student['first_name'] . " " . $student['second_name'] . " " . $student['surname'])));
}

// Student Exam Performance (ranked best first) or the blank exam marks sheet.
function ms_print_exam_sheet($conn, $conn2, $exam_name, $exam_id, $course_level, $course_id, $module_id, $unit_id, $is_blank, $student_data)
{
    $unit_info = getUnitInfo_report($conn2, $unit_id);
    $stmt = $conn2->prepare("SELECT `max_marks` FROM `table_subject` WHERE `subject_id` = ?");
    $stmt->bind_param("s", $unit_id);
    $stmt->execute();
    $unit_row = $stmt->get_result()->fetch_assoc();
    $out_of = ($unit_row && $unit_row['max_marks'] > 0) ? $unit_row['max_marks'] * 1 : 100;
    $rows = [];
    $scores = [];
    foreach ($student_data as $index => $student) {
        $score = $is_blank ? "" : $student['_display_score'];
        $grade = $is_blank ? "" : $student['_display_grade'];
        if (!$is_blank && $student['_sort_score'] >= 0) $scores[] = $student['_sort_score'];
        $rows[] = [$index + 1, ms_student_name($student), $student['adm_no'], $score, $grade];
    }
    ms_print_sheet($conn, $conn2, [
        'title' => $is_blank ? "EXAM MARKS SHEET" : "STUDENT EXAM PERFORMANCE",
        'file_title' => ucwords(strtolower($exam_name)) . " " . $unit_info['unit_name'] . ($is_blank ? " Exams Sheet" : " Exam Marks Sheet"),
        'details' => ms_details($conn2, $exam_name, $course_level, $course_id, $module_id, $unit_info, count($student_data)),
        'columns' => [['#', 10, 'C'], ['Student Name', 0, 'L'], ['Reg No.', 38, 'L'], ['Score (/' . $out_of . ')', 28, 'C'], ['Grade', 22, 'C']],
        'rows' => $rows, 'blank' => $is_blank,
        'summary' => $is_blank ? "" : ms_summary($scores, $out_of),
        'remarks' => true,
    ]);
}

// Student CAT Performance (ranked best first) or the blank CAT marks sheet.
function ms_print_cat_sheet($conn, $conn2, $exam_name, $course_level, $course_id, $module_id, $unit_id, $cat_id, $is_blank, $student_data)
{
    $unit_info = getUnitInfo_report($conn2, $unit_id);
    $cat_info = getCatInfo_report($conn2, $cat_id);
    $scores = [];
    if (!$is_blank) {
        foreach ($student_data as &$student) {
            $score = getCatScore_report($conn2, $cat_id, $student['examinee_id']);
            $student['_score'] = strlen((string) $score) > 0 ? $score * 1 : null;
            if ($student['_score'] !== null) $scores[] = $student['_score'];
        }
        unset($student);
        usort($student_data, function ($a, $b) {
            return ($b['_score'] ?? -1) <=> ($a['_score'] ?? -1);
        });
    }
    $rows = [];
    foreach ($student_data as $index => $student) {
        $score = $is_blank ? "" : ($student['_score'] === null ? "-" : $student['_score']);
        $percent = (!$is_blank && $student['_score'] !== null && $cat_info['max_marks'] > 0) ? number_format($student['_score'] * 100 / $cat_info['max_marks'], 1) . "%" : ($is_blank ? "" : "-");
        $rows[] = [$index + 1, ms_student_name($student), $student['adm_no'], $score, $percent];
    }
    $details = ms_details($conn2, $exam_name, $course_level, $course_id, $module_id, $unit_info, count($student_data));
    $details[] = ["C.A.T", $cat_info['cat_name']];
    $details[] = ["OUT OF", $cat_info['max_marks'] . " marks"];
    ms_print_sheet($conn, $conn2, [
        'title' => $is_blank ? "C.A.T MARKS SHEET" : "STUDENT C.A.T PERFORMANCE",
        'file_title' => ucwords(strtolower($exam_name)) . " " . $unit_info['unit_name'] . ($is_blank ? " C.A.T Sheet" : " C.A.T Marks Sheet"),
        'details' => $details,
        'columns' => [['#', 10, 'C'], ['Student Name', 0, 'L'], ['Reg No.', 38, 'L'], ['Score (/' . $cat_info['max_marks'] . ')', 28, 'C'], ['Percent', 22, 'C']],
        'rows' => $rows, 'blank' => $is_blank,
        'summary' => $is_blank ? "" : ms_summary($scores, $cat_info['max_marks']),
        'remarks' => false,
    ]);
}
