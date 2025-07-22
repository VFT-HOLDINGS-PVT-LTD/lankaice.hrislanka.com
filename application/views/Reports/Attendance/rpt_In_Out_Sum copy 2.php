<?php
date_default_timezone_set('Asia/Colombo');
$current_date = date('Y-m-d');
$current_time = date('H:i:s');

$custom_layout = [330, 210];
$pdf = new TCPDF('L', 'mm', $custom_layout, true, 'UTF-8', false);
ini_set('memory_limit', '-1');

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('System');
$pdf->SetTitle('IN OUT Report ' . $f_date . ' to ' . $t_date);
$pdf->SetSubject('Attendance Report');
$pdf->SetKeywords('TCPDF, PDF, report');

$pdf->SetHeaderData('', 0, $data_cmp[0]->Company_Name ?? '', '', [0, 64, 255], [0, 64, 128]);
$pdf->setFooterData([0, 64, 0], [0, 64, 128]);
$pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
$pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(5, 12, 5);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setFontSubsetting(true);
$pdf->SetFont('helvetica', '', 11, '', true);
$pdf->AddPage();

// Title
$pdf->SetFont('', 'B', 14);
$pdf->Cell(0, 10, 'IN OUT SUMMARY', 0, 1, 'C');

// Date info
$pdf->SetFont('', '', 10);
$pdf->Cell(0, 6, "From Date: $f_date  - To Date: $t_date    Date: $current_date  Time: $current_time", 0, 1);

// Add some space
$pdf->Ln(5);

// Columns fallback & check for empty
$selected_columns = $selected_columns ?? ['EmpNo', 'Emp_Full_Name', 'FDate', 'DayStatus', 'InTime', 'OutTime'];
if (empty($selected_columns)) {
    $selected_columns = ['EmpNo', 'Emp_Full_Name', 'FDate', 'DayStatus', 'InTime', 'OutTime'];
}

$col_count = count($selected_columns);
if ($col_count === 0) {
    $col_count = 1; // avoid division by zero
}

$total_width = 320; // total table width in mm
$default_width = floor($total_width / $col_count);

// Assign equal width to each column
$col_widths = [];
foreach ($selected_columns as $col) {
    $col_widths[$col] = $default_width;
}

// Draw table header
$pdf->SetFillColor(220, 220, 220);
$pdf->SetFont('', 'B', 10);

foreach ($selected_columns as $col) {
    $colLabel = match ($col) {
        'EmpNo' => 'EMP NO',
        'Emp_Full_Name' => 'NAME',
        'FDate' => 'FROM DATE',
        'FTime' => 'FROM TIME',
        'TDate' => 'TO DATE',
        'TTime' => 'TO TIME',
        'InDate' => 'IN DATE',
        'InTime' => 'IN TIME',
        'OutDate' => 'OUT DATE',
        'OutTime' => 'OUT TIME',
        'BreackInTime1' => 'BREAK IN',
        'BreackOutTime1' => 'BREAK OUT',
        'DayStatus' => 'STATUS',
        'AfterExH' => 'OT',
        'LateM' => 'LATE',
        'EarlyDepMin' => 'ED',
        'NumShift' => 'SHIFT',
        default => strtoupper(str_replace('_', ' ', $col))
    };
    $pdf->Cell($col_widths[$col], 7, $colLabel, 1, 0, 'C', 1);
}
$pdf->Ln();

// Table data rows
$pdf->SetFont('', '', 9);
$pdf->SetFillColor(255, 255, 255);

foreach ($data_set2 as $data) {
    foreach ($selected_columns as $colid) {
        $value = '';

        if (in_array($colid, ['LateM', 'EarlyDepMin', 'AfterExH'])) {
            $minutes = $data->$colid ?? 0;
            $hours = floor($minutes / 60);
            $min = $minutes % 60;
            $value = $hours . ':' . str_pad($min, 2, '0', STR_PAD_LEFT);
        } elseif ($colid === 'Lv_T_ID' && !empty($data->Lv_T_ID)) {
            $leave = $this->Db_model->getfilteredData("SELECT leave_name FROM tbl_leave_types WHERE Lv_T_ID = '{$data->Lv_T_ID}'");
            $value = $leave[0]->leave_name ?? '';
        } else {
            $value = $data->$colid ?? '';
        }

        $value = htmlspecialchars($value);

        $pdf->MultiCell($col_widths[$colid], 6, $value, 1, 'L', 0, 0, '', '', true);
    }
    $pdf->Ln();
}

$pdf->Output('IN_OUT_Report_' . $f_date . '_to_' . $t_date . '.pdf', 'I');
