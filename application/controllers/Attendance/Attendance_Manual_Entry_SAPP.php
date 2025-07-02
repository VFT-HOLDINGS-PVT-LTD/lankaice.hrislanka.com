<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance_Manual_Entry_SAPP extends CI_Controller {

    public function __construct() {
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

    public function index() {

        $data['title'] = "Attendance Manual Entry | HRM System";
        $data['data_set'] = $this->Db_model->getData('EmpNo,Emp_Full_Name', 'tbl_empmaster');
        $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        $data['data_desig'] = $this->Db_model->getData('Des_ID,Desig_Name', 'tbl_designations');
        $data['data_grp'] = $this->Db_model->getData('Grp_ID,EmpGroupName', 'tbl_emp_group');
        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');
        // new code
        $data['emp_date']= $this->session->userdata('login_user');
        $data['emp_master'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_empmaster where EmpNo = '".$data['emp_date'][0]->EmpNo."'");
        if ($data['emp_master'][0]->user_p_id == "1") {
            $data['data_branch'] = $this->Db_model->getData('B_id,B_name', 'tbl_branches');
        }else{
            $data['data_branch'] = $this->Db_model->getfilteredData("select * from tbl_branches inner join tbl_empmaster on tbl_empmaster.B_id = tbl_branches.B_id WHERE tbl_empmaster.user_p_id = '3' AND tbl_branches.B_id = '".$data['emp_master'][0]->B_id."' AND tbl_empmaster.EmpNo = '".$data['emp_master'][0]->EmpNo."';");
        }
        $this->load->view('Attendance/Attendance_Manual_View_SUP/index', $data);
    }

    public function search_employee() {


        $emp = $this->input->post("txt_emp");
        $emp_name = $this->input->post("txt_emp_name");
        $desig = $this->input->post("cmb_desig");
        $dept = $this->input->post("cmb_dep");
        $from_date = $this->input->post("txt_from_date");
        $to_date = $this->input->post("txt_to_date");
        $branch = $this->input->post("cmb_branch");

    if ($branch != null) {
            # code...
        

        // Filter Data by categories
        $filter = '';


        if (($this->input->post("txt_from_date")) && ($this->input->post("txt_to_date"))) {
            if ($filter == '') {
                $filter = " AND  le.Leave_Date between '$from_date' and '$to_date'";
            } else {
                $filter .= " AND  le.Leave_Date  between '$from_date' and '$to_date'";
            }
        }

        if (($this->input->post("txt_emp"))) {
            if ($filter == null) {
                $filter = " AND em.EmpNo = '$emp'";
            } else {
                $filter .= " AND em.EmpNo = '$emp'";
            }
        }

        if (($this->input->post("txt_emp_name"))) {
            if ($filter == null) {
                $filter = " AND em.Emp_Full_Name= '$emp_name'";
            } else {
                $filter .= " AND em.Emp_Full_Name = '$emp_name'";
            }
        }

        if (($this->input->post("cmb_branch"))) {
            if ($filter == null) {
                $filter = " AND em.B_id= '$branch'";
            } else {
                $filter .= " AND em.B_id = '$branch'";
            }
        }

    // echo    $filter;

        $currentUser = $this->session->userdata('login_user');
        $Emp = $currentUser[0]->EmpNo;

        $data['data_set'] = $this->Db_model->getfilteredData("select * from tbl_manual_entry INNER JOIN tbl_empmaster em ON em.Enroll_No = tbl_manual_entry.Enroll_No 	
INNER JOIN tbl_branches ON tbl_branches.B_id = em.B_id where Is_App_Sup_User =0 and Is_Admin_App_ID=0 {$filter}");


        $this->load->view('Attendance/Attendance_Manual_View_SUP/search_data', $data);
    }else{
        echo "Please select the region";
    }
    }
    
    
    public function approve($ID) {

        $currentUser = $this->session->userdata('login_user');
        $Emp = $currentUser[0]->EmpNo;

        $data = array(
            'Is_App_Sup_User' => 1,
            'App_Sup_User' => $Emp
        );
        
        
         $whereArr = array("M_ID" => $ID);
        $result = $this->Db_model->updateData("tbl_manual_entry", $data, $whereArr);
        
        
        
        $this->session->set_flashdata('success_message', 'Leave Approved successfully');
        redirect(base_url() . "Attendance/Attendance_Manual_Entry_SAPP");
        
    }

    public function dropdown() {

        $cat = $this->input->post('cmb_cat');

        if ($cat == "Employee") {
            $query = $this->Db_model->get_dropdown();
            echo '<option value="" default>-- Select --</option>';
            foreach ($query->result() as $row) {

                echo "<option value='" . $row->EmpNo . "'>" . $row->Emp_Full_Name . "</option>";
            }
        }

        if ($cat == "Department") {
            $query = $this->Db_model->get_dropdown_dep();
            echo '<option value="" default>-- Select --</option>';
            foreach ($query->result() as $row) {
                echo "<option value='" . $row->Dep_ID . "'>" . $row->Dep_Name . "</option>";
            }
        }
        if ($cat == "Designation") {
            $query = $this->Db_model->get_dropdown_des();
            echo '<option value="" default>-- Select --</option>';
            foreach ($query->result() as $row) {
                echo "<option value='" . $row->Des_ID . "'>" . $row->Desig_Name . "</option>";
            }
        }
        if ($cat == "Employee_Group") {
            $query = $this->Db_model->get_dropdown_group();
            echo '<option value="" default>-- Select --</option>';
            foreach ($query->result() as $row) {
                echo "<option value='" . $row->Grp_ID . "'>" . $row->EmpGroupName . "</option>";
            }
        }

        if ($cat == "Company") {
            $query = $this->Db_model->get_dropdown_comp();
            echo '<option value="" default>-- Select --</option>';
            foreach ($query->result() as $row) {
                echo "<option value='" . $row->Cmp_ID . "'>" . $row->Company_Name . "</option>";
            }
        }
    }

    /*
     * Search Employee Manual Attendance Entry
     */

    public function emp_manual_entry() {


        $emp = $this->input->post("txt_emp");
        $emp_name = $this->input->post("txt_emp_name");
        $desig = $this->input->post("cmb_desig");
        $dept = $this->input->post("cmb_dep");
        $comp = $this->input->post("cmb_comp");

        $att_date = $this->input->post("att_date");
        $in_time = $this->input->post("in_time");
        $out_time = $this->input->post("out_time");
        $reason = $this->input->post("txt_reason");


        $EmpData = $this->Db_model->getfilteredData("select EmpNo,Enroll_No from tbl_empmaster where EmpNo ='$emp' or Emp_Full_Name='$emp_name' ");



        $EnrollNo = $EmpData[0]->Enroll_No;





        $data = array(
            'Att_Date' => $att_date,
            'In_Time' => $in_time,
            'Out_Time' => $out_time,
            'Enroll_No' => $EnrollNo,
            'Reason' => $reason
        );

        $this->Db_model->insertData('tbl_manual_entry', $data);
        $this->session->set_flashdata('success_message', 'Manual Entry added successfully');

        redirect(base_url() . "Attendance/Attendance_Manual_Entry");
    }
    public function ajax_StatusReject($id)
    {
        // echo $id;
        $currentUser = $this->session->userdata('login_user');
        $Emp = $currentUser[0]->EmpNo;

        $EmpDataLeave = $this->Db_model->getfilteredDelete("DELETE FROM tbl_manual_entry WHERE M_ID = '$id';");

        // $data = array(
        //     'Is_pending' => 0,
        //     'Is_Approve' => 0,
        //     'Is_Cancel' => 1,
        //     'Approved_by' => $Emp,
        // );

        // $whereArr = array("LV_ID" => $id);
        // $result = $this->Db_model->updateData("tbl_leave_entry", $data, $whereArr);

        $this->session->set_flashdata('success_message', 'Manual Entry Reject successfully');
        redirect(base_url() . "Attendance/Attendance_Manual_Entry_SAPP");
    }

    public function approveAll() {
        $ids = $this->input->post('ids');

        echo json_encode($ids);
        
        if (!empty($ids)) {
            foreach ($ids as $ID) {
                // Approve the leave request with the given ID
                // Your code to approve the leave request
                $currentUser = $this->session->userdata('login_user');
                $Emp = $currentUser[0]->EmpNo;
        
                $data = array(
                    'Is_App_Sup_User' => 1,
                    'App_Sup_User' => $Emp,
                );
        
        
                // $Emp_Data = $this->Db_model->getfilteredData("select * from tbl_manual_entry where M_ID=$ID");
                // $Emp_No = $Emp_Data[0]->EmpNo;
                
                // //Get Employee Contact Details
               
                // $Emp_cont_Data = $this->Db_model->getfilteredData(" select EmpNo,Emp_Full_Name,Tel_mobile from tbl_empmaster where EmpNo=$Emp_No");
                // $Tel = $Emp_cont_Data[0]->Tel_mobile;
                // $Emp_Fullname = $Emp_cont_Data[0]->Emp_Full_Name;
                        
        
                //***Get leave date by Leave ID 
                // $Leave_data = $this->Db_model->getfilteredData("select * from tbl_manual_entry where M_ID=$ID and EmpNo=$Emp_No");
                $whereArr = array("M_ID" => $ID);
                $result = $this->Db_model->updateData("tbl_manual_entry", $data, $whereArr);
            }
            // Redirect or give a success message
            $this->session->set_flashdata('success_message', 'Leave Approved successfully');
            redirect(base_url() . "Attendance/Attendance_Manual_Entry_SAPP");        } 
        else {
            // Handle the case where no IDs are provided
            // Redirect or give an error message
            redirect('path/to/error/page');
        }
    }

}
