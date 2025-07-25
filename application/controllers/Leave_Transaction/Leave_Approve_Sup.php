<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Leave_Approve_Sup extends CI_Controller {

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
        $currentUser = $this->session->userdata('login_user');
        $Emp = $currentUser[0]->EmpNo;
        $data['title'] = "Leave Apply | HRM System";
        $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        $data['data_desig'] = $this->Db_model->getData('Des_ID,Desig_Name', 'tbl_designations');
        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');
        $data['data_set'] = $this->Db_model->getData('EmpNo,Emp_Full_Name', 'tbl_empmaster');
        $data['data_leave'] = $this->Db_model->getfilteredData("SELECT 
                                                                        lv_typ.Lv_T_ID,
                                                                        lv_typ.leave_name
                                                                    FROM
                                                                        tbl_leave_allocation lv_al
                                                                        right join
                                                                        tbl_leave_types lv_typ on lv_al.Lv_T_ID = lv_typ.Lv_T_ID
                                                                        where EmpNo='$Emp'
                                                                    ");
                                                                    // new code
        $data['emp_date']= $this->session->userdata('login_user');
        $data['emp_master'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_empmaster where EmpNo = '".$data['emp_date'][0]->EmpNo."'");
        if ($data['emp_master'][0]->user_p_id == "1") {
            $data['data_branch'] = $this->Db_model->getData('B_id,B_name', 'tbl_branches');
        }else{
            $data['data_branch'] = $this->Db_model->getfilteredData("select * from tbl_branches inner join tbl_empmaster on tbl_empmaster.B_id = tbl_branches.B_id WHERE tbl_empmaster.user_p_id = '3' AND tbl_branches.B_id = '".$data['emp_master'][0]->B_id."' AND tbl_empmaster.EmpNo = '".$data['emp_master'][0]->EmpNo."';");
        }
        $this->load->view('Leave_Transaction/Leave_Approve_Sup/index', $data);
    }

    /*
     * Check Leave Balance
     */

    public function check_Leave() {


        $cat = $this->input->post('cmb_cat2');

        $query = $this->Db_model->getfilteredData("select Used, Balance from tbl_leave_allocation where EmpNo='" . $cat . "' ");

        $query;
    }

    /*
     * Dependent Dropdown
     */

    public function dropdown() {

        $cat = $this->input->post('cmb_cat');

        if ($cat == "Employee") {
            $query = $this->Db_model->get_dropdown();
            echo '<option value="" default>-- Select --</option>';
            foreach ($query->result() as $row) {

                echo "<option value='" . $row->EmpNo . "'>" . $row->Emp_Full_Name . "</option>";
            }
        }
    }

    /*
     * Search Employees by cat
     */

    public function search_employee() {


        $emp = $this->input->post("txt_emp");
        $emp_name = $this->input->post("txt_emp_name");
        $desig = $this->input->post("cmb_desig");
        $dept = $this->input->post("cmb_dep");
        $from_date = $this->input->post("txt_from_date");
        $to_date = $this->input->post("txt_to_date");
        $branch = $this->input->post("cmb_branch");
        // if ($branch != null) {

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

        $currentUser = $this->session->userdata('login_user');
         $Emp = $currentUser[0]->EmpNo;
        
        $data['data_set'] = $this->Db_model->getfilteredData("SELECT
    le.LV_ID,
    le.EmpNo,
    em.Emp_Full_Name,
    lt.leave_name,
    le.Apply_Date,
    le.month,
    le.Year,
    le.Sup_AD_APP,
    le.Is_Sup_AD_APP,
    le.Is_pending,
    le.Leave_Date,
    le.Reason,
    le.Leave_Count,
    le.Attach,
    em.B_id,
    tbl_branches.B_name

FROM
    tbl_leave_entry le
    INNER JOIN tbl_empmaster em ON em.EmpNo = le.EmpNo
    INNER JOIN tbl_leave_types lt ON lt.Lv_T_ID = le.Lv_T_ID
    INNER JOIN tbl_branches ON tbl_branches.B_id = em.B_id
    left JOIN tbl_emp_group ON tbl_emp_group.Sup_ID = em.Emp_Full_Name
WHERE
    le.Is_pending = 1 AND le.Is_Cancel = 0 and Is_Sup_AD_APP =0 and le.Sup_AD_APP = $Emp
    -- and le.Sup_AD_APP = $Emp
  {$filter}");

        $this->load->view('Leave_Transaction/Leave_Approve_Sup/search_data', $data);
    // }else{
    //     echo "Please select the region";
    // }
    }

    /*
     * Approve Leave request
     */

     public function approveAll() {
        $ids = $this->input->post('ids');

        // echo json_encode($ids);
        
        if (!empty($ids)) {
            foreach ($ids as $ID) {
                // Approve the leave request with the given ID
                // Your code to approve the leave request
                $currentUser = $this->session->userdata('login_user');
                $Emp = $currentUser[0]->EmpNo;
        
                $data = array(
                    'Is_pending' => 1,
                    'Is_Sup_AD_APP' => 1,
                    'Sup_AD_APP' => $Emp,
                );
        
        
                $Emp_Data = $this->Db_model->getfilteredData("select * from tbl_leave_entry where LV_ID=$ID");
                $Emp_No = $Emp_Data[0]->EmpNo;
                
                //Get Employee Contact Details
               
                $Emp_cont_Data = $this->Db_model->getfilteredData(" select EmpNo,Emp_Full_Name,Tel_mobile from tbl_empmaster where EmpNo=$Emp_No");
                $Tel = $Emp_cont_Data[0]->Tel_mobile;
                $Emp_Fullname = $Emp_cont_Data[0]->Emp_Full_Name;
                        
        
                //***Get leave date by Leave ID 
                $Leave_data = $this->Db_model->getfilteredData("select * from tbl_leave_entry where LV_ID=$ID and EmpNo=$Emp_No");
                $whereArr = array("LV_ID" => $ID);
                $result = $this->Db_model->updateData("tbl_leave_entry", $data, $whereArr);
            }
            // Redirect or give a success message
            $this->session->set_flashdata('success_message', 'Leave Approved successfully');
            redirect(base_url() . "Leave_Transaction/Leave_Approve_Sup");        } 
        // else {
        //     // Handle the case where no IDs are provided
        //     // Redirect or give an error message
        //     redirect('path/to/error/page');
        // }
    }
    


    public function approve($ID) {

        $currentUser = $this->session->userdata('login_user');
        $Emp = $currentUser[0]->EmpNo;

        $data = array(
            'Is_pending' => 1,
            'Is_Sup_AD_APP' => 1,
            'Sup_AD_APP' => $Emp,
        );


        $Emp_Data = $this->Db_model->getfilteredData("select * from tbl_leave_entry where LV_ID=$ID");
        $Emp_No = $Emp_Data[0]->EmpNo;
        
        //Get Employee Contact Details
       
        $Emp_cont_Data = $this->Db_model->getfilteredData(" select EmpNo,Emp_Full_Name,Tel_mobile from tbl_empmaster where EmpNo=$Emp_No");
        $Tel = $Emp_cont_Data[0]->Tel_mobile;
        $Emp_Fullname = $Emp_cont_Data[0]->Emp_Full_Name;
                

        //***Get leave date by Leave ID 
        $Leave_data = $this->Db_model->getfilteredData("select * from tbl_leave_entry where LV_ID=$ID and EmpNo=$Emp_No");

//        $from_date = $Leave_data[0]->Leave_Date;

        /*
         * Update Individual Roster Table Is Leave status and Leave Type
         */
        //Start
//        $Roster_ID = $this->Db_model->getfilteredData("select ID_Roster from tbl_individual_roster where EmpNo ='$Emp_No' and Fdate = '$from_date' ");
//        $DayStatus = 'LV'; //****** IF Apply Leave Update Individual Roster DayStatus As 'LV'
//        $data_RS = array("Lv_T_ID" => $leave_type, "Is_Leave" => 1, "nopay" => 0, "DayStatus" => $DayStatus, 'Is_processed' => 1, "Att_Allow" =>0);
//        $whereArray = array("ID_Roster" => $Roster_ID[0]->ID_Roster);
//        $results = $this->Db_model->updateData("tbl_individual_roster", $data_RS, $whereArray);

        $whereArr = array("LV_ID" => $ID);
        $result = $this->Db_model->updateData("tbl_leave_entry", $data, $whereArr);
        //End

        //****** Send message to leave request employee
        /*
         * SMS Server configuration
         */
      



        $this->session->set_flashdata('success_message', 'Leave Approved successfully');
        redirect(base_url() . "Leave_Transaction/Leave_Approve_Sup");
    }
    
    
    
    
    
    
    
    public function edit_lv($ID) {
        
         $data['title'] = "Leave Apply | HRM System";
        
        $this->load->view('Leave_Transaction/Leave_Edit/index', $data);
        
        

//        $currentUser = $this->session->userdata('login_user');
//        $Emp = $currentUser[0]->EmpNo;
//
//        $data = array(
//            'Is_pending' => 0,
//            'Is_Approve' => 1,
//            'Approved_by' => $Emp,
//        );
//
//
//        $Emp_Data = $this->Db_model->getfilteredData("select * from tbl_leave_entry where LV_ID=$ID");
//        $Emp_No = $Emp_Data[0]->EmpNo;
//        
//        //Get Employee Contact Details
//       
//        $Emp_cont_Data = $this->Db_model->getfilteredData(" select EmpNo,Emp_Full_Name,Tel_mobile from tbl_empmaster where EmpNo=$Emp_No");
//        $Tel = $Emp_cont_Data[0]->Tel_mobile;
//        $Emp_Fullname = $Emp_cont_Data[0]->Emp_Full_Name;
//                
//
//        //***Get leave date by Leave ID 
//        $Leave_data = $this->Db_model->getfilteredData("select * from tbl_leave_entry where LV_ID=$ID and EmpNo=$Emp_No");
//
//        $from_date = $Leave_data[0]->Leave_Date;
//
//        /*
//         * Update Individual Roster Table Is Leave status and Leave Type
//         */
//        //Start
//        $Roster_ID = $this->Db_model->getfilteredData("select ID_Roster from tbl_individual_roster where EmpNo ='$Emp_No' and Fdate = '$from_date' ");
//        $DayStatus = 'LV'; //****** IF Apply Leave Update Individual Roster DayStatus As 'LV'
//        $data_RS = array("Lv_T_ID" => $leave_type, "Is_Leave" => 1, "nopay" => 0, "DayStatus" => $DayStatus, 'Is_processed' => 1);
//        $whereArray = array("ID_Roster" => $Roster_ID[0]->ID_Roster);
//        $results = $this->Db_model->updateData("tbl_individual_roster", $data_RS, $whereArray);
//
//        $whereArr = array("LV_ID" => $ID);
//        $result = $this->Db_model->updateData("tbl_leave_entry", $data, $whereArr);
//        //End
//
//        //****** Send message to leave request employee
//        /*
//         * SMS Server configuration
//         */
//        $sender = "HRM SYSTEM";
//        $recipient = $Tel;
//        $message = 'System Response : ' . $Emp_Fullname .' '. 'Your Leave Request on'. ' '.$from_date.' '. 'is Approved';
//
//        $url = 'http://127.0.0.1:9333/ozeki?';
//        $url .= "action=sendMessage";
//        $url .= "&login=admin";
//        $url .= "&password=abc123";
//        $url .= "&recepient=" . urlencode($recipient);
//        $url .= "&messageData=" . urlencode($message);
//        $url .= "&sender=" . urlencode($sender);
//        file($url);
//
//
//
//
//        $this->session->set_flashdata('success_message', 'Leave Approved successfully');
//        redirect(base_url() . "Leave_Transaction/Leave_Approve");
    }

    //sms
    // public function sms() {

    //     $sender = "Name";
    //     $recipient = $user_details[$x]->contact_no;
    //     $message = 'Dear Customer';

    //     $url = 'http://127.0.0.1:9333/ozeki?';
    //     $url .= "action=sendMessage";
    //     $url .= "&login=admin";
    //     $url .= "&password=abc123";
    //     $url .= "&recepient=" . urlencode($recipient);
    //     $url .= "&messageData=" . urlencode($message);
    //     $url .= "&sender=" . urlencode($sender);
    //     file($url);
    // }

    /*
     * Reject Leave request
     */

    public function reject($ID) {


        $currentUser = $this->session->userdata('login_user');
        $Emp = $currentUser[0]->EmpNo;

        $data = array(
            'Is_pending' => 0,
            'Is_Approve' => 0,
            'Is_Cancel' => 1,
            'Approved_by' => $Emp,
        );
        
        
        //        -------- Leave Allocation Update
        date_default_timezone_set('Asia/Colombo');
        $date = date_create();
        $year = date("Y");

        $LTYpe = $this->Db_model->getfilteredData("select * from tbl_leave_entry where LV_ID = $ID");
        $Emp_LV = $LTYpe[0]->EmpNo;
        $LeaveType = $LTYpe[0]->Lv_T_ID;
        $tbl_leave_count = $LTYpe[0]->Leave_Count;

        $Balance_Usd = $this->Db_model->getfilteredData("select Balance,Used,Lv_T_ID from tbl_leave_allocation where EmpNo=$Emp_LV and Year=$year and Lv_T_ID=$LeaveType ");
        //                    var_dump($Balance_Usd);die;
        $Day_type = $tbl_leave_count;
        $Balance = $Balance_Usd[0]->Balance + $Day_type;



        $Used = $Balance_Usd[0]->Used - $Day_type;
        if($Used < 0){
            $Used = 0;  
        }
        $Lv_T_ID = $Balance_Usd[0]->Lv_T_ID;

        $data_arr = array("Balance" => $Balance, "Used" => $Used);

        $whereArray = array("EmpNo" => $Emp_LV, "Lv_T_ID" => $Lv_T_ID);
        $result = $this->Db_model->updateData("tbl_leave_allocation", $data_arr, $whereArray);
        
        

        $whereArr = array("LV_ID" => $ID);
        $result = $this->Db_model->updateData("tbl_leave_entry", $data, $whereArr);

        $this->session->set_flashdata('success_message', 'Leave Reject successfully');
        redirect(base_url() . "Leave_Transaction/Leave_Approve_Sup");
    }

}
