<?php
// require_once APPPATH . 'application/libraries/ExcelExport.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelExport
{
    public function export($data, $filename)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add header row
        $header = array_keys($data[0]);
        $sheet->fromArray([$header], null, 'A1');

        // Add data rows
        $rowData = [];
        foreach ($data as $row) {
            $rowData[] = array_values($row);
        }
        $sheet->fromArray($rowData, null, 'A2');

        // Save to Excel file
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);
    }
}
