<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll_Data_Push extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!($this->session->userdata('login_user'))) {
            redirect(base_url() . "");
        }
//        if (!($this->session->userdata('login_user'))) {
//            redirect(base_url() . "");
//        }
        $this->load->model('Db_model', '', TRUE);
    }

    public function index() {

        $this->load->helper('url');
        $data['title'] = 'Payroll Data Push | HRM System.';
        // $data['data_array'] = $this->Db_model->getData('Bnk_ID,bank_name', 'tbl_banks');

        $this->load->view('Payroll_Data_Push/index', $data);
    }

    public function pushed_payroll_data() {
        $month = $this->input->post('cmb_month'); // Get the selected month
        $year = $this->input->post('cmb_year'); // Get the selected month
        // $year = date('Y'); // Get the current year

        if ($month) {
            // Convert the month name to a timestamp
            $start_date = date('Y-m-01', strtotime("$month $year"));
            $end_date = date('Y-m-t', strtotime("$month $year"));

            // Delete all existing data in tbl_pushed_payroll_data to avoid duplicates
            $this->Db_model->getfilteredDelete("DELETE FROM tbl_pushed_payroll_data");

            // Fetch filtered data
            $dtEmp['EmpData'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_individual_roster WHERE FDate BETWEEN '".$start_date."' AND '".$end_date."'");

            // Loop through each record in EmpData
            foreach ($dtEmp['EmpData'] as $emp) {
                // Prepare the data array for insertion
                $dataArray = array(
                    'RYear' => $year,
                    'RMonth' => date('n', strtotime($emp->FDate)), // Extract month from FDate
                    'EmpNo' => $emp->EmpNo,
                    'ShiftCode' => $emp->ShiftCode,
                    // 'ShiftDay' => date('D', strtotime($emp->FDate)), // Get day abbreviation from FDate
                    'ShiftDay' => $emp->ShiftDay, // Get day abbreviation from FDate
                    'ShiftIndex' => 1, // Example index, adjust as needed
                    'FDate' => $emp->FDate,
                    'FTime' => $emp->FTime,
                    'TDate' => $emp->TDate,
                    'TTime' => $emp->TTime,
                    'ShType' => $emp->ShType,
                    'Day_Type' => $emp->Day_Type,
                    'DayStatus' => $emp->DayStatus,
                    'InRec' => $emp->InRec,
                    'InRecID' => $emp->InRecID,
                    'InDate' => $emp->InDate,
                    'InTime' => $emp->InTime,
                    'OutRec' => $emp->OutRec,
                    'OutRecID' => $emp->OutRecID,
                    'OutDate' => $emp->OutDate,
                    'OutTime' => $emp->OutTime,
                    'LateSt' => $emp->LateSt,
                    'LateM' => $emp->LateM,
                    'Lv_T_ID' => $emp->Lv_T_ID,
                    'Is_Leave' => $emp->Is_Leave,
                    'LV_ID' => $emp->LV_ID,
                    'LeaveM' => $emp->LeaveM,
                    'HDSession' => $emp->HDSession,
                    'BeforeExH' => $emp->BeforeExH,
                    'AfterExH' => $emp->AfterExH,
                    'EarlyDepMin' => $emp->EarlyDepMin,
                    'CloseState' => $emp->CloseState,
                    'NetLateM' => $emp->NetLateM,
                    'NetExtraM' => $emp->NetExtraM,
                    'InType' => $emp->InType,
                    'OutType' => $emp->OutType,
                    'GracePrd' => $emp->GracePrd,
                    'GapHrs' => $emp->GapHrs,
                    'LCHD' => $emp->LCHD,
                    'LCHDDate' => $emp->LCHDDate,
                    'LCHDShift' => $emp->LCHDShift,
                    'LULV' => $emp->LULV,
                    'LULVDate' => $emp->LULVDate,
                    'LULVShift' => $emp->LULVShift,
                    'LULVHrs' => $emp->LULVHrs,
                    'ApprovedExH' => $emp->ApprovedExH,
                    'MODExtra' => $emp->MODExtra,
                    'nopay_hrs' => $emp->nopay_hrs,
                    'nopay' => $emp->nopay,
                    'Att_Allow' => $emp->Att_Allow,
                    'Is_processed' => $emp->Is_processed,
                );

                // Insert the prepared data into the database
                $this->Db_model->insertData('tbl_pushed_payroll_data', $dataArray);
            }

            redirect(base_url() . 'Payroll_Data_Push/Payroll_Data_Push');


            // Output the dates (you can return them to a view or process further)
            // echo "Selected Month: " . ucfirst($month) . "<br>";
            // echo "First Day: " . $start_date . "<br>";
            // echo "Last Day: " . $end_date . "<br>";
        } else {
            echo "Please select a month.";
        }
    }

    

}
