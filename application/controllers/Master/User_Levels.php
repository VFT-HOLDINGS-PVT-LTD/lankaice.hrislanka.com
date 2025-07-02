<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_Levels extends CI_Controller {

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

        $data['title'] = "User Levels | HRM System";
        $data['data_set'] = $this->Db_model->getData('user_level_id,user_level_name', 'tbl_user_level_master');
        $this->load->view('Master/User_Levels/index', $data);
    }

    /*
     * Insert Departmrnt
     */

    public function insert_data() {

        $data = array(
            'user_level_name' => $this->input->post('txt_user_level')
        );

        $result = $this->Db_model->insertData("tbl_user_level_master", $data);


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

        $whereArray = array('user_level_id' => $id);

        $this->Db_model->setWhere($whereArray);
        $dataObject = $this->Db_model->getData('user_level_id,user_level_name', 'tbl_user_level_master');

        $array = (array) $dataObject;
        echo json_encode($array);
    }

    /*
     * Edit Data
     */

    public function edit() {
        $ID = $this->input->post("id", TRUE);
        $UL = $this->input->post("user_level_name", TRUE);


        $data = array("user_level_name" => $UL);
        $whereArr = array("user_level_id" => $ID);
        $result = $this->Db_model->updateData("tbl_user_level_master", $data, $whereArr);
        redirect(base_url() . "Master/User_Levels");
    }

    /*
     * Delete Data
     */

    public function ajax_delete($id) {
        $table = "tbl_user_level_master";
        $where = 'user_level_id';
        $this->Db_model->delete_by_id($id, $where, $table);
        echo json_encode(array("status" => TRUE));
    }

}
