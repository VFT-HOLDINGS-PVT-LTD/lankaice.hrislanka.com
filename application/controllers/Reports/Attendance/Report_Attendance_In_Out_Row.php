<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Report_Attendance_In_Out_Row extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!($this->session->userdata('login_user'))) {
            redirect(base_url() . "");
        }

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

        $data['title'] = "Attendance In Out Report Row | HRM System";
        $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        $data['data_desig'] = $this->Db_model->getData('Des_ID,Desig_Name', 'tbl_designations');
        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');
        $data['data_group'] = $this->Db_model->getData('id,super_gname', 'tbl_super_group');
        // $data['data_branch'] = $this->Db_model->getData('B_id,B_name', 'tbl_branches');
        $data['emp_date']= $this->session->userdata('login_user');
        $data['emp_master'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_empmaster where EmpNo = '".$data['emp_date'][0]->EmpNo."'");
        if ($data['emp_master'][0]->user_p_id == "1") {
            $data['data_branch'] = $this->Db_model->getData('B_id,B_name', 'tbl_branches');
        }else{
            $data['data_branch'] = $this->Db_model->getfilteredData("select * from tbl_branches inner join tbl_empmaster on tbl_empmaster.B_id = tbl_branches.B_id WHERE tbl_empmaster.user_p_id = '3' AND tbl_branches.B_id = '".$data['emp_master'][0]->B_id."' AND tbl_empmaster.EmpNo = '".$data['emp_master'][0]->EmpNo."';");
        }
        $this->load->view('Reports/Attendance/Report_Attendance_In_Out_Row', $data);
        // $this->load->view('Reports/Attendance/commingsoon', $data);

    }

    /*
     * Insert Departmrnt
     */

    public function Report_department() {

        $Data['data_set'] = $this->Db_model->getData('id,Dep_Name', 'tbl_departments');

        $this->load->view('Reports/Master/rpt_Departments', $Data);
    }

    public function Attendance_Report_By_Cat() {
        $this->load->helper('date');
        date_default_timezone_set('Asia/Colombo');
        // Get the current date
        $current_date = date('Y-m-d');
        
        // Get the current time
        $current_time = date('H:i:s');
        
        // You can then pass these variables to your view or use them as needed
        $data['current_date'] = $current_date;
        $data['current_time'] = $current_time;


        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');

        $emp = $this->input->post("txt_emp");
        $emp_name = $this->input->post("txt_emp_name");
        $desig = $this->input->post("cmb_desig");
        $dept = $this->input->post("cmb_dep");
        $from_date = $this->input->post("txt_from_date");
        $to_date = $this->input->post("txt_to_date");
        $branch = $this->input->post("cmb_branch");
        $grop = $this->input->post("cmb_grop");


        $data['f_date'] = $from_date;
        $data['t_date'] = $to_date;
        $data['branch'] = $branch;


        // Filter Data by categories
        $filter = '';

        if (($this->input->post("txt_from_date")) && ($this->input->post("txt_to_date"))) {
            if ($filter == '') {
                $filter = " where  ir.AttDate between '$from_date' and '$to_date'";
            } else {
                $filter .= " AND  ir.AttDate between '$from_date' and '$to_date'";
            }
        }
        if (($this->input->post("txt_emp"))) {
            if ($filter == null) {
                $filter = " where ir.Enroll_No =$emp";
            } else {
                $filter .= " AND ir.Enroll_No =$emp";
            }
        }
        if (($this->input->post("cmb_grop"))) {
            if ($filter == null) {
                $filter = " where Emp.SupGrp_ID =$grop";
            } else {
                $filter .= " AND Emp.SupGrp_ID =$grop";
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

        // echo $filter;
        // die;




        $data['data_set'] = $this->Db_model->getfilteredData("SELECT 
    ir.EventID,
    Emp.Emp_Full_Name,
    ir.Enroll_No,
    ir.AttDate,
    br.B_name,
    ir.AttTime,
    ir.AttPlace,
    MIN(AttTime) AS InTime,
    MAX(AttTime) AS OutTime,
    TIMEDIFF(Max(AttTime),Min(AttTime)) as WorkHour
FROM
    tbl_u_attendancedata ir
        LEFT JOIN
    tbl_empmaster Emp ON Emp.EmpNo = ir.Enroll_No
        LEFT JOIN
    tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
        LEFT JOIN
    tbl_departments dep ON dep.Dep_id = Emp.Dep_id
        INNER JOIN
    tbl_branches br ON Emp.B_id = br.B_id

        
                                                                  {$filter} and verify_type='6' AND Emp.EmpNo != '00009000' GROUP BY ir.EventID,Emp.EmpNo,dsg.Des_ID,dep.Dep_id,Emp.B_id order by ir.AttDate");

//        var_dump($data);die;

        $this->load->view('Reports/Attendance/rpt_In_Out_row', $data);
    }

    function get_auto_emp_name() {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->Db_model->get_auto_emp_name($q);
        }
    }

    function get_auto_emp_no() {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->Db_model->get_auto_emp_no($q);
        }
    }

}
