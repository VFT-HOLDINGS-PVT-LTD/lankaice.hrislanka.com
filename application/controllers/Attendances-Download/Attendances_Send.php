<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Attendances_Send extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // if (!($this->session->userdata('login_user'))) {
        //     redirect(base_url() . "");
        // }

        /*
         * Load Database model
         */
        $this->load->library("pdf_library");
        $this->load->model('Db_model', '', TRUE);
    }

    /*
     * Index page in Departmrnt
     */

    public function index() {

        // $data['title'] = "Leave Summery Report | HRM System";
        // $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        // $data['data_desig'] = $this->Db_model->getData('Des_ID,Desig_Name', 'tbl_designations');
        // $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');
        // // $data['data_branch'] = $this->Db_model->getData('B_id,B_name', 'tbl_branches');
        // $data['emp_date']= $this->session->userdata('login_user');
        // $data['emp_master'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_empmaster where EmpNo = '".$data['emp_date'][0]->EmpNo."'");
        // if ($data['emp_master'][0]->user_p_id == "1") {
        //     $data['data_branch'] = $this->Db_model->getData('B_id,B_name', 'tbl_branches');
        // }else{
        //     $data['data_branch'] = $this->Db_model->getfilteredData("select * from tbl_branches inner join tbl_empmaster on tbl_empmaster.B_id = tbl_branches.B_id WHERE tbl_empmaster.user_p_id = '3' AND tbl_branches.B_id = '".$data['emp_master'][0]->B_id."' AND tbl_empmaster.EmpNo = '".$data['emp_master'][0]->EmpNo."';");
        // }

    //     $data['data_set'] = $this->Db_model->getfilteredData("SELECT 
    //     ir.EmpNo,
    //     Emp.Emp_Full_Name,
    //     Emp.catCode,
    //     SUM(ir.nopay) AS TotalNonPayHours
    // FROM
    //     tbl_individual_roster ir
    //         LEFT JOIN
    //     tbl_empmaster Emp ON Emp.EmpNo = ir.EmpNo
    //         LEFT JOIN
    //     tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
    //         LEFT JOIN
    //     tbl_departments dep ON dep.Dep_id = Emp.Dep_id
    //         INNER JOIN
    //     tbl_branches br ON Emp.B_id = br.B_id  AND Emp.EmpNo != '00009000'
    // GROUP BY ir.EmpNo, Emp.Emp_Full_Name");

    //     foreach ( $data['data_set'] as $row) {
    //         echo "EmpNo:". $row->EmpNo . '<br>'; 
    //         echo "Emp_Full_Name:". $row->Emp_Full_Name . '<br>'; 
    //         echo "catCode:". $row->catCode . '<br>'; 
    //         echo "TotalNonPayHours:". $row->TotalNonPayHours . '<br>'; 
    //         $data['data_set2'] = $this->Db_model->getfilteredData("SELECT SUM(nopay) AS `leave` FROM tbl_individual_roster ir WHERE ir.EmpNo = '$row->EmpNo' AND ir.FDate BETWEEN '2024-06-01' and '2024-06-25'");
    //         echo "leave:". $data['data_set2'][0]->leave;
    //         echo "<br>";
    //         echo "<br>";
    //     }

        $data['data_set'] = $this->Db_model->getfilteredData("SELECT 
            ir.EmpNo,
            Emp.Emp_Full_Name,
            Emp.catCode,
            SUM(ir.nopay) AS TotalNonPayHours
        FROM
            tbl_individual_roster ir
            LEFT JOIN tbl_empmaster Emp ON Emp.EmpNo = ir.EmpNo
            LEFT JOIN tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
            LEFT JOIN tbl_departments dep ON dep.Dep_id = Emp.Dep_id
            INNER JOIN tbl_branches br ON Emp.B_id = br.B_id  AND Emp.EmpNo != '00009000'
        GROUP BY ir.EmpNo, Emp.Emp_Full_Name");

        $jsonArray = [];

        foreach ($data['data_set'] as $row) {
            $data['data_set2'] = $this->Db_model->getfilteredData("SELECT SUM(nopay) AS `leave` FROM tbl_individual_roster ir WHERE ir.EmpNo = '$row->EmpNo' AND ir.FDate BETWEEN '2024-06-01' and '2024-06-25'");
            $jsonArray[] = [
                'EmpNo' => $row->EmpNo,
                'Emp_Full_Name' => $row->Emp_Full_Name,
                'catCode' => $row->catCode,
                'TotalNonPayHours' => $row->TotalNonPayHours,
                'Nopay Day' => $data['data_set2'][0]->leave
            ];
        }

        echo json_encode($jsonArray, JSON_PRETTY_PRINT);


      
        //         // var_dump($data_set2);
        // echo $data['data'][0]->EmpNo;

        // echo json_decoad($data_set2->EmpNo);
        // echo json_decode($data_set2->EmpNo);
        // referto the property of an object of stdClass as an array.

        // $this->load->view('Attendances-Download/Attendances_Send', $data);
    }

    public function Attendance_Report_By_Cat() {

        
        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');

        $emp = $this->input->post("txt_emp");
        $emp_name = $this->input->post("txt_emp_name");
        $desig = $this->input->post("cmb_desig");
        $dept = $this->input->post("cmb_dep");
        $from_date = $this->input->post("txt_from_date");
        $to_date = $this->input->post("txt_to_date");
        $branch = $this->input->post("cmb_branch");


        $data['f_date'] = $from_date;
        $data['t_date'] = $to_date;


        // Filter Data by categories
        $filter = '';

        if (($this->input->post("txt_from_date")) && ($this->input->post("txt_to_date"))) {
            if ($filter == '') {
                $filter = " where  ir.FDate between '$from_date' and '$to_date' and Emp.`Status` = 1 ";
            } else {
                $filter .= " AND  ir.FDate between '$from_date' and '$to_date' and Emp.`Status` = 1 ";
            }
        }
        if (($this->input->post("txt_emp"))) {
            if ($filter == null) {
                $filter = " where ir.EmpNo =$emp";
            } else {
                $filter .= " AND ir.EmpNo =$emp";
            }
        }

        if (($this->input->post("txt_emp_name"))) {
            if ($filter == null) {
                $filter = " where Emp.Emp_Full_Name ='$emp_name'";
            } else {
                $filter .= " AND Emp.Emp_Full_Name ='$emp_name'";
            }
        }
        if (($this->input->post("cmb_desig"))) {
            if ($filter == null) {
                $filter = " where dsg.Des_ID  ='$desig'";
            } else {
                $filter .= " AND dsg.Des_ID  ='$desig'";
            }
        }
        if (($this->input->post("cmb_dep"))) {
            if ($filter == null) {
                $filter = " where dep.Dep_id  ='$dept'";
            } else {
                $filter .= " AND dep.Dep_id  ='$dept'";
            }
        }

        if (($this->input->post("cmb_branch"))) {
            if ($filter == null) {
                $filter = " where br.B_id  ='$branch'";
            } else {
                $filter .= " AND br.B_id  ='$branch'";
            }
        }



       

        // $spreadsheet = new Spreadsheet();
        // $sheet = $spreadsheet->getActiveSheet();

        // foreach (range('A', 'H') as $columID) {
        //     $spreadsheet->getActiveSheet()->getColumnDimension($columID)->setAutoSize(true);
        // }
        // $sheet->setCellValue('A1', 'Company Code'); // ID
        // $sheet->setCellValue('B1', 'Employee Category'); // Full Name
        // $sheet->setCellValue('C1', 'Employee Name'); // Full Name
        // $sheet->setCellValue('D1', 'Employee Number'); // Desig_Name
        // $sheet->setCellValue('E1', 'Nopay Day'); // Dep_Name
        // $sheet->setCellValue('F1', 'OT 1.5 Hours'); // AttDate
        // $sheet->setCellValue('G1', 'Salary Arrears Days'); // InTime
        // // $sheet->setCellValue('G1', 'DOT'); // OutTime

        // $sheet->getStyle('A1:G1')->getFont()->setBold(true);


        // $x = 2;
        // foreach ($data_set2 as $row) {
        //     $sheet->setCellValue('A' . $x, 0);
        //     $sheet->setCellValue('B' . $x, $row->catCode);
        //     $sheet->setCellValue('C' . $x, $row->Emp_Full_Name);
        //     $sheet->setCellValue('D' . $x, '00'.$row->EmpNo);
        //     $leave = $this->Db_model->getfilteredData("SELECT SUM(nopay) AS `leave` FROM tbl_individual_roster ir WHERE ir.EmpNo = '$row->EmpNo' and ir.FDate between '$from_date' and '$to_date'");
        //     $sheet->setCellValue('E' . $x, $leave[0]->leave);
        //     $sheet->setCellValue('F' . $x, 0);
        //     $sheet->setCellValue('G' . $x, 0);

        //     $x++;
        // }
        // $writer = new Xlsx($spreadsheet);
        // $filename = 'userattendencedetails.xlsx';
        // // $writer->save($filename);
        // header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="' . $filename . '"');
        // $writer->save('php://output');
    }


}
