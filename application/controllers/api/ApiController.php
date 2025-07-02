<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/Format.php';
require_once APPPATH . '../vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

class ApiController extends RestController
{
    public function __construct() {
        parent::__construct();
        $this->load->model('Db_model', '', TRUE);
        // Add this before any output
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
        header('Access-Control-Allow-Headers: X-API-KEY,Content-Type, Authorization,Origin,X-Requested-With,Content-Type,Accept,Access-Control-Request-Method');
        header("HTTP/1.1 200 OK");
        header("Content-Type: application/json");
        
        // Handle preflight requests
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header('HTTP/1.1 200 OK');
            exit();
        }


    }
    public function index_get(){
        $api_key = $this->input->get_request_header('X-API-KEY', TRUE);

        $key = $this->Db_model->getfilteredData("SELECT `key` FROM `tbl_api_keys`");
        
        if($api_key == $key[0]->key){

        // date_default_timezone_set('Asia/Colombo'); // Set time zone to Colombo, Sri Lanka
        // $first_day_of_month = date('Y-m-01'); // First day of the current month
        // $last_day_of_month = date('Y-m-t'); // Last day of the current month
            
        $data['data_set'] = $this->Db_model->getfilteredData("
                SELECT 
                    ir.EmpNo,
                    Emp.Emp_Full_Name,
                    Emp.catCode,
                    SUM(ir.nopay) AS TotalNonPayHours
                FROM
                    tbl_pushed_payroll_data ir
                    LEFT JOIN tbl_empmaster Emp ON Emp.EmpNo = ir.EmpNo
                    LEFT JOIN tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
                    LEFT JOIN tbl_departments dep ON dep.Dep_id = Emp.Dep_id
                    INNER JOIN tbl_branches br ON Emp.B_id = br.B_id  AND Emp.EmpNo != '00009000'
                WHERE Emp.`payment_Type` = 'Payroll' AND Emp.`Status` = '1' GROUP BY ir.EmpNo, Emp.Emp_Full_Name
        ");
        // '".$first_day_of_month."' AND '".$last_day_of_month."'
            
        $jsonArray = [];

        foreach ($data['data_set'] as $row) {
            $data['data_set2'] = $this->Db_model->getfilteredData("SELECT SUM(nopay) AS `leave` FROM tbl_individual_roster ir WHERE ir.EmpNo = '$row->EmpNo' AND ir.FDate BETWEEN '2024-06-01' and '2024-06-25'");
            $jsonArray[] = [
                'EmpNo' => $row->EmpNo,
                'Emp_Full_Name' => $row->Emp_Full_Name,
                'catCode' => $row->catCode,
                'TotalNonPayHours' => 0,
                'Nopay Day' => $row->TotalNonPayHours
            ];
        }
        echo json_encode($jsonArray, JSON_PRETTY_PRINT);
        // $this->update_key_post();
    
        }else{
            $this->response([ 'status' => FALSE, 'message' => 'Invalid API Key' ] );//REST_Controller::HTTP_NOT_FOUND
        }
    }
}

?>