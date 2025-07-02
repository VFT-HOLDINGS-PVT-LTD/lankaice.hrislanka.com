<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Shift_config extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!($this->session->userdata('login_user'))) {
            redirect(base_url() . "");
        }
        /*
         * Load Database model
         */
        $this->load->model('Db_model', '', TRUE);
    }

    /*
     * Index page
     */

    public function index()
    {

        $data['title'] = "Shifts | HRM System";
        $data['data_set'] = $this->Db_model->getData('ShiftCode,ShiftName,FromTime,ToTime,NextDay,DayType,FHDSessionEndTime,SHDSessionStartTime,ShiftGap', 'tbl_shifts');
        $data['data_set2'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_shift_config INNER JOIN tbl_empmaster ON tbl_shift_config.Emp_No = tbl_empmaster.EmpNo 
                            INNER JOIN tbl_shifts ON tbl_shift_config.Shift_Id = tbl_shifts.ShiftCode");

        $this->load->view('Master/Shift_Config/index', $data);
    }

    /*
     * Insert Data
     */

    public function insert_data()
    {
        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        if (!$data) {
            echo "Invalid JSON received.";
            return;
        }

        $employeeName = isset($data['employeeName']) ? $data['employeeName'] : null;
        $shiftSelect = isset($data['shiftSelect']) ? $data['shiftSelect'] : []; // Expecting an array

        if (!empty($employeeName) && !empty($shiftSelect)) {
            foreach ($shiftSelect as $shift) {
                // Check if shift is already assigned
                $exist_shift = $this->Db_model->getfilteredData(
                    "SELECT * FROM tbl_shift_config WHERE Emp_No = '$employeeName' AND Shift_Id = '$shift'"
                );

                if (!empty($exist_shift)) {
                    echo "3"; // Shift already assigned
                    return; // Exit if even one shift already exists
                }

                // Insert shift
                $dataArr = [
                    'Emp_No' => $employeeName,
                    'Shift_Id' => $shift
                ];
                $this->Db_model->insertData("tbl_shift_config", $dataArr);
            }

            echo "1"; // All shifts added successfully
        } else {
            echo "2"; // Missing employee or shifts
        }
    }

    public function get_data()
    {
        $exist_shift = $this->Db_model->getfilteredData("SELECT * FROM tbl_shift_config INNER JOIN tbl_empmaster ON tbl_shift_config.Emp_No = tbl_empmaster.EmpNo 
                            INNER JOIN tbl_shifts ON tbl_shift_config.Shift_Id = tbl_shifts.ShiftCode");
    }

    /*
     * Get data
     */

    public function get_details()
    {
        $ShiftCode = $this->input->post('ShiftCode');

        $whereArray = array('ShiftCode' => $ShiftCode);

        $this->Db_model->setWhere($whereArray);
        $dataObject = $this->Db_model->getData('ShiftCode,ShiftName,FromTime,ToTime,ShiftGap', 'tbl_shifts');

        $array = (array) $dataObject;
        echo json_encode($array);
    }

    /*
     * Edit Data
     */

    public function edit()
    {
        $ShiftCode = $this->input->post("ShiftCode", TRUE);
        $ShiftName = $this->input->post("ShiftName", TRUE);
        $FromTime = $this->input->post("FromTime", TRUE);
        $ToTime = $this->input->post("ToTime", TRUE);
        $ShiftGap = $this->input->post("ShiftGap", TRUE);



        $data = array("ShiftName" => $ShiftName, "FromTime" => $FromTime, "ToTime" => $ToTime, "ShiftGap" => $ShiftGap,);
        $whereArr = array("ShiftCode" => $ShiftCode);
        $result = $this->Db_model->updateData("tbl_shifts", $data, $whereArr);
        redirect(base_url() . "Master/Shifts");
    }

    /*
     * Delete Data
     */

    public function ajax_delete($id)
    {
        $table = "tbl_shift_config";
        $where = 'ID';
        $this->Db_model->delete_by_id($id, $where, $table);
        echo json_encode(array("status" => TRUE));
    }
}
