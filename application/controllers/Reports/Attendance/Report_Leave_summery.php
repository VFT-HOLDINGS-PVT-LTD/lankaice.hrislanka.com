<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Report_Leave_summery extends CI_Controller {

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

        $data['title'] = "Leave Summery Report | HRM System";
        $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        $data['data_desig'] = $this->Db_model->getData('Des_ID,Desig_Name', 'tbl_designations');
        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');
        // $data['data_branch'] = $this->Db_model->getData('B_id,B_name', 'tbl_branches');
        $data['emp_date']= $this->session->userdata('login_user');
        $data['emp_master'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_empmaster where EmpNo = '".$data['emp_date'][0]->EmpNo."'");
        if ($data['emp_master'][0]->user_p_id == "1") {
            $data['data_branch'] = $this->Db_model->getData('B_id,B_name', 'tbl_branches');
        }else{
            $data['data_branch'] = $this->Db_model->getfilteredData("select * from tbl_branches inner join tbl_empmaster on tbl_empmaster.B_id = tbl_branches.B_id WHERE tbl_empmaster.user_p_id = '3' AND tbl_branches.B_id = '".$data['emp_master'][0]->B_id."' AND tbl_empmaster.EmpNo = '".$data['emp_master'][0]->EmpNo."';");
        }
        $this->load->view('Reports/Attendance/Report_Leave_summery', $data);
    }

    /*
     * Insert Departmrnt
     */

    public function Report_department() {

        $Data['data_set'] = $this->Db_model->getData('id,Dep_Name', 'tbl_departments');

        $this->load->view('Reports/Master/rpt_Departments', $Data);
    }

    public function Leave_Summery_Report_By_Cat() {
        
         $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');


        $emp = $this->input->post("txt_emp");
        $emp_name = $this->input->post("txt_emp_name");
        $desig = $this->input->post("cmb_desig");
        $branch = $this->input->post("cmb_branch");
        $dept = $this->input->post("cmb_dep");
        $year = $this->input->post("cmb_year");

        $data['year'] = $year;

        // Filter Data by categories
        $filter = '';

       if (isset($_POST['cmb_year'])){
//        if (($this->input->post("cmb_year"))) {
//            if ($filter) {
                $filter = " where  tbl_leave_allocation.Year= '$year'";
       
//        }
        }
        
//        var_dump($filter);die;
        
        if (($this->input->post("txt_emp"))) {
            if ($filter == null) {
                $filter = " where tbl_empmaster.EmpNo =$emp";
            } else {
                $filter .= " AND tbl_empmaster.EmpNo =$emp";
            }
        }

        if (($this->input->post("txt_emp_name"))) {
            if ($filter == null) {
                $filter = " where tbl_empmaster.Emp_Full_Name ='$emp_name'";
            } else {
                $filter .= " AND tbl_empmaster.Emp_Full_Name ='$emp_name'";
            }
        }
        if (($this->input->post("cmb_desig"))) {
            if ($filter == null) {
                $filter = " where tbl_designations.Des_ID  ='$desig'";
            } else {
                $filter .= " AND tbl_designations.Des_ID  ='$desig'";
            }
        }
        if (($this->input->post("cmb_dep"))) {
            if ($filter == null) {
                $filter = " where tbl_departments.Dep_ID  ='$dept'";
            } else {
                $filter .= " AND tbl_departments.Dep_ID  ='$dept'";
            }
        }
        if (($this->input->post("cmb_branch"))) {
            if ($filter == null) {
                $filter = " where tbl_branches.B_id  ='$branch'";
            } else {
                $filter .= " AND tbl_branches.B_id  ='$branch'";
            }
        }


//        print_r($SS);die;

        $data['data_set'] = $this->Db_model->getfilteredData("SELECT 
                                                                    tbl_leave_allocation.ID,
                                                                    tbl_empmaster.EmpNo,
                                                                    tbl_empmaster.Emp_Full_Name,
                                                                    tbl_leave_allocation.Year,
                                                                    tbl_leave_types.leave_name,
                                                                    tbl_leave_allocation.Entitle,
                                                                    tbl_leave_allocation.Used,
                                                                    tbl_leave_allocation.Balance,
                                                                    tbl_branches.B_name
                                                                    from
                                                                    tbl_leave_allocation
                                                                        INNER JOIN
                                                                    tbl_empmaster ON tbl_empmaster.EmpNo = tbl_leave_allocation.EmpNo
                                                                        INNER JOIN
                                                                    tbl_leave_types ON tbl_leave_types.Lv_T_ID = tbl_leave_allocation.Lv_T_ID
                                                                        INNER JOIN
                                                                    tbl_designations ON tbl_designations.Des_ID = tbl_empmaster.Des_ID
                                                                        INNER JOIN
                                                                    tbl_departments ON tbl_departments.Dep_ID = tbl_empmaster.Dep_ID
                                                                    inner join
                                                                    tbl_branches on tbl_empmaster.B_id = tbl_branches.B_id
    
                                                                    {$filter} and tbl_empmaster.Status='1' AND tbl_empmaster.EmpNo != '00009000' ORDER BY tbl_empmaster.EmpNo ");

//        var_dump($data);die;

        $this->load->view('Reports/Attendance/rpt_Leave_summery', $data);
    }

//     public function Leave_Summery_Report_By_Cat_excel() {
        
//         $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');


//        $emp = $this->input->post("txt_emp");
//        $emp_name = $this->input->post("txt_emp_name");
//        $desig = $this->input->post("cmb_desig");
//        $branch = $this->input->post("cmb_branch");
//        $dept = $this->input->post("cmb_dep");
//        $year = $this->input->post("cmb_year");

//        $data['year'] = $year;

//        // Filter Data by categories
//        $filter = '';

//       if (isset($_POST['cmb_year'])){
// //        if (($this->input->post("cmb_year"))) {
// //            if ($filter) {
//                $filter = " where  tbl_leave_allocation.Year= '$year'";
      
// //        }
//        }
       
// //        var_dump($filter);die;
       
//        if (($this->input->post("txt_emp"))) {
//            if ($filter == null) {
//                $filter = " where tbl_empmaster.EmpNo =$emp";
//            } else {
//                $filter .= " AND tbl_empmaster.EmpNo =$emp";
//            }
//        }

//        if (($this->input->post("txt_emp_name"))) {
//            if ($filter == null) {
//                $filter = " where tbl_empmaster.Emp_Full_Name ='$emp_name'";
//            } else {
//                $filter .= " AND tbl_empmaster.Emp_Full_Name ='$emp_name'";
//            }
//        }
//        if (($this->input->post("cmb_desig"))) {
//            if ($filter == null) {
//                $filter = " where tbl_designations.Des_ID  ='$desig'";
//            } else {
//                $filter .= " AND tbl_designations.Des_ID  ='$desig'";
//            }
//        }
//        if (($this->input->post("cmb_dep"))) {
//            if ($filter == null) {
//                $filter = " where tbl_departments.Dep_ID  ='$dept'";
//            } else {
//                $filter .= " AND tbl_departments.Dep_ID  ='$dept'";
//            }
//        }
//        if (($this->input->post("cmb_branch"))) {
//            if ($filter == null) {
//                $filter = " where tbl_branches.B_id  ='$branch'";
//            } else {
//                $filter .= " AND tbl_branches.B_id  ='$branch'";
//            }
//        }


// //        print_r($SS);die;

//        $data['data_set'] = $this->Db_model->getfilteredData("SELECT 
//                                                                    tbl_leave_allocation.ID,
//                                                                    tbl_empmaster.EmpNo,
//                                                                    tbl_empmaster.Emp_Full_Name,
//                                                                    tbl_leave_allocation.Year,
//                                                                    tbl_leave_types.leave_name,
//                                                                    tbl_leave_allocation.Entitle,
//                                                                    tbl_leave_allocation.Used,
//                                                                    tbl_leave_allocation.Balance,
//                                                                    tbl_branches.B_name
//                                                                    from
//                                                                    tbl_leave_allocation
//                                                                        INNER JOIN
//                                                                    tbl_empmaster ON tbl_empmaster.EmpNo = tbl_leave_allocation.EmpNo
//                                                                        INNER JOIN
//                                                                    tbl_leave_types ON tbl_leave_types.Lv_T_ID = tbl_leave_allocation.Lv_T_ID
//                                                                        INNER JOIN
//                                                                    tbl_designations ON tbl_designations.Des_ID = tbl_empmaster.Des_ID
//                                                                        INNER JOIN
//                                                                    tbl_departments ON tbl_departments.Dep_ID = tbl_empmaster.Dep_ID
//                                                                    inner join
//                                                                    tbl_branches on tbl_empmaster.B_id = tbl_branches.B_id
   
//                                                                    {$filter} and tbl_empmaster.Status='1' AND tbl_empmaster.EmpNo != '00009000' ORDER BY tbl_empmaster.EmpNo ");

// //        var_dump($data);die;

//        $this->load->view(view: 'Reports/Attendance/rpt_Leave_summery_excel', $data);
//    }
public function Leave_Summery_Report_By_Cat_excel() {
    $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');

    $emp = $this->input->post("txt_emp");
    $emp_name = $this->input->post("txt_emp_name");
    $desig = $this->input->post("cmb_desig");
    $branch = $this->input->post("cmb_branch");
    $dept = $this->input->post("cmb_dep");
    $year = $this->input->post("cmb_year");

    $data['year'] = $year;

    // Filter Data by categories
    $filter = '';
    if (isset($year)) {
        $filter = " WHERE tbl_leave_allocation.Year = '$year'";
    }

    if ($emp) {
        $filter .= $filter ? " AND tbl_empmaster.EmpNo = $emp" : " WHERE tbl_empmaster.EmpNo = $emp";
    }

    if ($emp_name) {
        $filter .= $filter ? " AND tbl_empmaster.Emp_Full_Name = '$emp_name'" : " WHERE tbl_empmaster.Emp_Full_Name = '$emp_name'";
    }

    if ($desig) {
        $filter .= $filter ? " AND tbl_designations.Des_ID = '$desig'" : " WHERE tbl_designations.Des_ID = '$desig'";
    }

    if ($dept) {
        $filter .= $filter ? " AND tbl_departments.Dep_ID = '$dept'" : " WHERE tbl_departments.Dep_ID = '$dept'";
    }

    if ($branch) {
        $filter .= $filter ? " AND tbl_branches.B_id = '$branch'" : " WHERE tbl_branches.B_id = '$branch'";
    }

    $data['data_set'] = $this->Db_model->getfilteredData("SELECT 
        tbl_leave_allocation.ID,
        tbl_empmaster.EmpNo,
        tbl_empmaster.Emp_Full_Name,
        tbl_leave_allocation.Year,
        tbl_leave_types.leave_name,
        tbl_leave_allocation.Entitle,
        tbl_leave_allocation.Used,
        tbl_leave_allocation.Balance,
        tbl_branches.B_name
        FROM tbl_leave_allocation
        INNER JOIN tbl_empmaster ON tbl_empmaster.EmpNo = tbl_leave_allocation.EmpNo
        INNER JOIN tbl_leave_types ON tbl_leave_types.Lv_T_ID = tbl_leave_allocation.Lv_T_ID
        INNER JOIN tbl_designations ON tbl_designations.Des_ID = tbl_empmaster.Des_ID
        INNER JOIN tbl_departments ON tbl_departments.Dep_ID = tbl_empmaster.Dep_ID
        INNER JOIN tbl_branches ON tbl_empmaster.B_id = tbl_branches.B_id
        {$filter} AND tbl_empmaster.Status = '1' AND tbl_empmaster.EmpNo != '00009000'
        ORDER BY tbl_empmaster.EmpNo");

    // Load the HTML content with the table
    $html = $this->load->view('Reports/Attendance/rpt_Leave_summery_excel', $data, true);

    // Return the HTML response
    echo json_encode(['html' => $html]);
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
