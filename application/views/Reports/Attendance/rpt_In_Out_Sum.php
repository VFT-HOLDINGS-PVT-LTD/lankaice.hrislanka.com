<?php

$date = date("Y/m/d");
$this->load->helper('date');
date_default_timezone_set('Asia/Colombo');
$current_date = date('Y-m-d');
$current_time = date('H:i:s');

// Custom wider layout: 330mm wide × 210mm height
$custom_layout = array(330, 210);

// Create new PDF document
$pdf = new TCPDF('L', 'mm', $custom_layout, true, 'UTF-8', false);
ini_set('memory_limit', '-1');

// Document info
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('System');
$pdf->SetTitle('IN OUT Report' . $f_date . ' to ' . $t_date . '.pdf');
$pdf->SetSubject('Attendance Report');
$pdf->SetKeywords('TCPDF, PDF, report');

$pdf->SetHeaderData('', 0, $data_cmp[0]->Company_Name, '', array(0, 64, 255), array(0, 64, 128));
$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetMargins(5, 12, 5);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->setFontSubsetting(true);
$pdf->SetFont('helvetica', '', 11, '', true);
$pdf->AddPage();

$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.0, 'depth_h' => 0.0, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));

$selected_columns = $selected_columns ?? ['EmpNo', 'Name', 'FDate', 'DayType', 'InTime', 'OutTime'];

$html = '
    <div style="text-align:center; font-size:13px;">IN OUT SUMMARY</div><br>
    <table cellpadding="2" cellspacing="0" style="font-size:11px; width:100%;">
        <tr>
            <td style="border-bottom:1px solid #000;">
                From Date: ' . $f_date . ' - To Date : ' . $t_date . '
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Date: ' . $current_date . ' Time: ' . $current_time . '
            </td>
        </tr>
    </table><br><br><br>

    <table cellpadding="3" cellspacing="0" border="0" style="border-collapse: collapse;">
        <thead>
            <tr>';

foreach ($selected_columns as $col) {
    $colLabel = strtoupper(str_replace('_', '', $col));
    if ($col === 'EmpNo') {
        $colLabel = 'EMP NO';
    }elseif ($col === 'Emp_Full_Name') {
        $colLabel = 'NAME';
    }elseif ($col === 'FDate') {
        $colLabel = 'FROM DATE';
    }elseif ($col === 'FTime') {
        $colLabel = 'FROM TIME';
    }elseif ($col === 'TDate') {
        $colLabel = 'TO DATE';
    }elseif ($col === 'TTime') {
        $colLabel = 'TO TIME';
    }elseif ($col === 'InDate') {
        $colLabel = 'IN DATE';
    }elseif ($col === 'InTime') {
        $colLabel = 'IN TIME';
    }elseif ($col === 'OutDate') {
        $colLabel = 'OUT DATE';
    }elseif ($col === 'OutTime') {
        $colLabel = 'OUT TIME';
    }elseif ($col === 'BreackInTime1') {
        $colLabel = 'BREAK IN';
    }elseif ($col === 'BreackOutTime1') {
        $colLabel = 'BREAK OUT';
    }elseif ($col === 'DayStatus') {
        $colLabel = 'STATUS';
    }elseif ($col === 'AfterExH') {
        $colLabel = 'OT';
    }elseif ($col === 'LateM') {
        $colLabel = 'LATE';
    }elseif ($col === 'EarlyDepMin') {
        $colLabel = 'ED';
    }elseif ($col === 'NumShift') {
        $colLabel = 'SHIFT';
    }
    $html .= '<th style="font-size:10px; border-bottom:1px solid black;">' . $colLabel . '</th>';
}


$html .= '</tr></thead>';
$html .= '<tbody style="background-color: #f2f2f2;">';

// BODY
foreach ($data_set2 as $data) {
    $html .= '<tr>';
    foreach ($selected_columns as $colid) {
        $value = '';

        if ($colid == 'LateM') {
            $hours = floor($data->LateM / 60);
            $min = $data->LateM % 60;
            $value = $hours . ':' . str_pad($min, 2, '0', STR_PAD_LEFT);
        } elseif ($colid == 'EarlyDepMin') {
            $hours = floor($data->EarlyDepMin / 60);
            $min = $data->EarlyDepMin % 60;
            $value = $hours . ':' . str_pad($min, 2, '0', STR_PAD_LEFT);
        } elseif ($colid == 'AfterExH') {
            $hours = floor($data->AfterExH / 60);
            $min = $data->AfterExH % 60;
            $value = $hours . ':' . str_pad($min, 2, '0', STR_PAD_LEFT);
        } elseif ($colid == 'Lv_T_ID') {
            if ($data->Lv_T_ID != 0) {
                $leave = $this->Db_model->getfilteredData("SELECT leave_name FROM tbl_leave_types WHERE Lv_T_ID = '{$data->Lv_T_ID}'");
                $value = $leave[0]->leave_name ?? '';
            }
        } else {
            $value = $data->$colid ?? '';
        }

        $html .= '<td style="font-size:10px;">' . htmlspecialchars($value) . '</td>';
    }
    $html .= '</tr>';
}

$html .= '</tbody></table><br>';

// Render PDF
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('IN_OUT_Report_' . $f_date . '_to_' . $t_date . '.pdf', 'I');
