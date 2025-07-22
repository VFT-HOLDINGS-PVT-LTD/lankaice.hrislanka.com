<?php

$date = date("Y/m/d");
$this->load->helper('date');
date_default_timezone_set('Asia/Colombo');
$current_date = date('Y-m-d');
$current_time = date('H:i:s');

// Custom wider layout: 330mm wide × 210mm height (like extended A4 Landscape)
$custom_layout = array(330, 210);

// Create new PDF document with custom size
$pdf = new TCPDF('L', 'mm', $custom_layout, true, 'UTF-8', false);

ini_set('memory_limit', '-1');

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('IN OUT Report' . $f_date . ' to ' . $t_date . '.pdf');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// Header details
$PDF_HEADER_TITLE = $data_cmp[0]->Company_Name;
$PDF_HEADER_LOGO_WIDTH = '0';
$PDF_HEADER_LOGO = '';
$PDF_HEADER_STRING = '';

$pdf->SetHeaderData($PDF_HEADER_LOGO, $PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE, $PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

// Fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Use reduced margins to utilize more width
$pdf->SetMargins(5, 12, 5);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Load language strings if available
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

$pdf->setFontSubsetting(true);
$pdf->SetFont('helvetica', '', 14, '', true);
$pdf->AddPage();
$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.0, 'depth_h' => 0.0, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));

$html = '
    <div style="text-align:center; font-size:13px;">IN OUT SUMMERY</div>
    <div style="font-size: 11px; float: left; border-bottom: solid #000 1px;">
        From Date: ' . $f_date . ' - To Date : ' . $t_date . '
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Date: ' . $current_date . ' Time: ' . $current_time . '
    </div><br><br>

     <table cellpadding="3">
                <thead style="border-bottom: #000 solid 1px;">
                    <tr style="border-bottom: 1px solid black;"> 
                        <th style="font-size:11px;border-bottom: 1px solid black; width:60px;">EMP NO</th>
                        <th style="font-size:11px;border-bottom: 1px solid black; width:120px;">NAME</th>
                        <th style="font-size:11px;border-bottom: 1px solid black;width:80px;">FROM DATE</th>
                        <th style="font-size:11px;border-bottom: 1px solid black; width:70px;">FROM TIME</th>
                        <th style="font-size:11px;border-bottom: 1px solid black;width:70px;">TO DATE</th>
                        <th style="font-size:11px;border-bottom: 1px solid black; width:70px;">TO TIME</th>
                        <th style="font-size:11px;border-bottom: 1px solid black;width:70px;">IN DATE</th>
                        <th style="font-size:11px;border-bottom: 1px solid black; width:70px;">IN TIME</th>
                        <th style="font-size:11px;border-bottom: 1px solid black;width:70px;">OUT DATE</th>
                        <th style="font-size:11px;border-bottom: 1px solid black; width:70px;">OUT TIME</th>
                        <th style="font-size:11px;border-bottom: 1px solid black; width:70px;">BREAK IN</th>
                        <th style="font-size:11px;border-bottom: 1px solid black; width:70px;">BREAK OUT</th>
                        <th style="font-size:11px;border-bottom: 1px solid black; width:60px;">STATUS</th>    
                        <th style="font-size:11px;border-bottom: 1px solid black; width:40px;">OT</th>
                        <th style="font-size:11px;border-bottom: 1px solid black; width:40px;">LATE</th>
                        <th style="font-size:11px;border-bottom: 1px solid black; width:40px;">ED</th>
                        <th style="font-size:11px;border-bottom: 1px solid black; width:40px;">Shifts</th>
                    </tr>
                </thead>
             <tbody>';

foreach ($data_set2 as $data) {
    $Mint = $data->LateM;
    $hours = floor($Mint / 60);
    $min = $Mint - ($hours * 60);

    $EDMint = $data->EarlyDepMin;
    $EDhours = floor($EDMint / 60);
    $EDmin = $EDMint - ($EDhours * 60);

    $OT = $data->AfterExH;
    $OThours = floor($OT / 60);
    $OTmin = $OT - ($OThours * 60);
    
    $leave_type = " ";
    if ($data->Lv_T_ID != 0) {
        $leavename = $this->Db_model->getfilteredData("SELECT tbl_leave_types.leave_name FROM tbl_leave_types WHERE tbl_leave_types.Lv_T_ID = '$data->Lv_T_ID'");
        $leave_type = $leavename[0]->leave_name;
    }

    $html .= '
        <tr>
            <td style="font-size:10px; width:60px;">' . $data->EmpNo . '</td>
            <td style="font-size:10px; width:120px;">' . $data->Emp_Full_Name . '</td>
            <td style="font-size:10px; width:80px;">' . $data->FDate . '</td>
            <td style="font-size:10px; width:70px;">' . $data->FTime . '</td>
            <td style="font-size:10px; width:70px;">' . $data->TDate . '</td>
            <td style="font-size:10px; width:70px;">' . $data->TTime . '</td>
            <td style="font-size:10px; width:70px;">' . $data->InDate . '</td>
            <td style="font-size:10px; width:70px;">' . $data->InTime . '</td>
            <td style="font-size:10px; width:70px;">' . $data->OutDate . '</td>
            <td style="font-size:10px; width:70px;">' . $data->OutTime . '</td>
            <td style="font-size:10px; width:70px;">' . $data->BreackInTime1 . '</td>
            <td style="font-size:10px; width:70px;">' . $data->BreackOutTime1 . '</td>
            <td style="font-size:10px; width:60px;">' . $data->DayStatus . '</td>
            <td style="font-size:10px; width:40px;">' . $OThours . ':' . $OTmin . '</td>
            <td style="font-size:10px; width:40px;">' . $hours . ':' . $min . '</td>
            <td style="font-size:10px; width:40px;">' . $EDhours . ':' . $EDmin . '</td>
            <td style="font-size:10px; width:60px;">' . $data->NumShift . '</td>
        </tr>';
}

$html .= '
        </tbody>
    </table><br>';

// Write HTML to PDF
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// Output PDF to browser
$pdf->Output('IN OUT Report' . $f_date . ' to ' . $t_date . '.pdf', 'I');

?>
