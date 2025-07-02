<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_Groups extends CI_Controller {

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
     * Index page in Departmrnt
     */

    public function index() {

        $data['title'] = "Employee Groups | HRM System";
        // $data['data_set'] = $this->Db_model->getData('Grp_ID,EmpGroupName,GracePeriod,NosLeaveForMonth,MaxSLS,Allow1stSession,Allow2ndSession,OTPattern,Sup_ID', 'tbl_emp_group');
        $data['data_set'] = $this->Db_model->getfilteredData('SELECT tbl_emp_group.Grp_ID,tbl_emp_group.EmpGroupName,tbl_emp_group.GracePeriod,tbl_emp_group.NosLeaveForMonth,tbl_emp_group.MaxSLS,tbl_emp_group.Allow1stSession,tbl_emp_group.Allow2ndSession,tbl_emp_group.OTPattern,tbl_emp_group.Sup_ID,tbl_empmaster.Emp_Full_Name FROM tbl_emp_group INNER JOIN tbl_empmaster ON tbl_emp_group.Sup_ID = tbl_empmaster.Enroll_No ');
        $data['data_ot'] = $this->Db_model->getData('OTCode,OTName', 'tbl_ot_pattern_hd');
        $data['emp_sup'] = $this->Db_model->getfilteredData("select EmpNo,Emp_Full_Name,Enroll_No from tbl_empmaster where Status=1");
        $this->load->view('Master/Employee_Groups/index', $data);
    }

    

    /*
     * Insert Departmrnt
     */

    public function insert_data() {

        $FSt = $this->input->post('chk_1st');
        if ($FSt == null) {
            $FSt = 0;
        } elseif ($FSt == 'on') {
            $FSt = 1;
        }

        $Snd = $this->input->post('chk_2nd');
        if ($Snd == null) {
            $Snd = 0;
        } elseif ($Snd == 'on') {
            $Snd = 1;
        }

        $sup = $this->input->post('cmb_Supervisor');
        if ($sup == null) {
            $sup = 9000;
        }

        $data = array(
            'EmpGroupName' => $this->input->post('txt_group_name'),
            'GracePeriod' => $this->input->post('txt_grace_p'),
            'Sup_ID' => $sup,
            'NosLeaveForMonth' => $this->input->post('txt_sl_per_mth'),
            'MaxSLS' => $this->input->post('txt_max_l_size'),
            'Allow1stSession' => $FSt,
            'Allow2ndSession' => $Snd,
            'OTPattern' => $this->input->post('cmb_ot_pattern')
        );

        $result = $this->Db_model->insertData("tbl_emp_group", $data);


        if ($result) {
            $condition = 1;
        } else {
            
        }

        $info[] = array('a' => $condition);
        echo json_encode($info);
    }

    /*
     * Get Department data
     */

    public function get_details() {
        $id = $this->input->post('id');

        // echo $id;

        $whereArray = array('Grp_ID' => $id);

        $this->Db_model->setWhere($whereArray);
        $dataObject = $this->Db_model->getData('Grp_ID,EmpGroupName,GracePeriod,NosLeaveForMonth,MaxSLS,Allow1stSession,Allow2ndSession,Sup_ID', 'tbl_emp_group');
        // $dataObject = $this->Db_model->getfilteredData('SELECT tbl_emp_group.Grp_ID,tbl_emp_group.EmpGroupName,tbl_emp_group.GracePeriod,tbl_emp_group.NosLeaveForMonth,tbl_emp_group.MaxSLS,tbl_emp_group.Allow1stSession,tbl_emp_group.Allow2ndSession,tbl_emp_group.OTPattern,tbl_emp_group.Sup_ID FROM tbl_emp_group INNER JOIN tbl_empmaster ON tbl_emp_group.Sup_ID = tbl_empmaster.Enroll_No ');
        $array = (array) $dataObject;
        echo json_encode($array);
    }

    public function updateAttView()
    {
        $id = $this->input->get('id');

        //    echo "OkM " . $id;

        $whereArray = array('ID' => $id);


        $this->Db_model->setWhere($whereArray);
        $data['data_set'] = $this->Db_model->getfilteredData("SELECT tbl_emp_group.Grp_ID,tbl_emp_group.EmpGroupName,tbl_emp_group.GracePeriod,tbl_emp_group.NosLeaveForMonth,tbl_emp_group.MaxSLS,tbl_emp_group.Allow1stSession,tbl_emp_group.Allow2ndSession,tbl_emp_group.OTPattern,tbl_emp_group.Sup_ID,tbl_empmaster.Emp_Full_Name FROM tbl_emp_group INNER JOIN tbl_empmaster ON tbl_emp_group.Sup_ID = tbl_empmaster.Enroll_No WHERE tbl_emp_group.Grp_ID = '$id';");
        $data['emp_sup'] = $this->Db_model->getfilteredData("select EmpNo,Emp_Full_Name,Enroll_No from tbl_empmaster where Status=1");
        $data['title'] = "Employee Group | HRM System";


        $this->load->view('Master/Employee_Groups/update', $data);
    }

    /*
     * Edit Data
     */

    public function edit() {
        // $ID = $this->input->post("id", TRUE);
        // $UL = $this->input->post("user_level_name", TRUE);


        // $data = array("user_level_name" => $UL);
        // $whereArr = array("Grp_ID" => $ID);
        // $result = $this->Db_model->updateData("tbl_emp_group", $data, $whereArr);
        // redirect(base_url() . "Master/User_Levels");


        $ID = $this->input->post("id", TRUE);
        // echo $ID;
        $Group_Name = $this->input->post("Group_Name", TRUE);
        $GRACE_PERIOD = $this->input->post("GRACE_PERIOD", TRUE);
        $PER_MONTH = $this->input->post("PER_MONTH", TRUE);
        $MAX_SHORT_LEAVE = $this->input->post("MAX_SHORT_LEAVE", TRUE);
        $SESSION1 = $this->input->post("1_SESSION", TRUE);
        $SESSION2 = $this->input->post("2_SESSION", TRUE);   
        $Sup_ID = $this->input->post("Sup_ID", TRUE);        
     
        $data = array("EmpGroupName" => $Group_Name,'GracePeriod'=>$GRACE_PERIOD,"NosLeaveForMonth" => $PER_MONTH,"MaxSLS" => $MAX_SHORT_LEAVE,"Allow1stSession" => $SESSION1,"Allow2ndSession" => $SESSION2,"Sup_ID" => $Sup_ID);
        
        $whereArr = array("Grp_ID" => $ID);
        $result = $this->Db_model->updateData("tbl_emp_group", $data, $whereArr);
        redirect(base_url() . "Master/Employee_Groups");
    }

    /*
     * Delete Data
     */

    public function ajax_delete($id) {
        $table = "tbl_emp_group";
        $where = 'Grp_ID';
        $this->Db_model->delete_by_id($id, $where, $table);
        echo json_encode(array("status" => TRUE));
    }

}
