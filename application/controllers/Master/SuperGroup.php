<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SuperGroup extends CI_Controller {

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

        $data['title'] = "Super Group | HRM System";
        $data['data_set'] = $this->Db_model->getData('id,super_gname', 'tbl_super_group');
        $this->load->view('Master/SuperGroup/index', $data);
    }

    /*
     * Insert Departmrnt
     */

    public function insertSuperGroup() {

        $data = array(
            'super_gname' => $this->input->post('txt_sgroup_name')
        );

        $result = $this->Db_model->insertData("tbl_super_group", $data);


        $this->session->set_flashdata('success_message', 'New Super Group has been added successfully');

        
        redirect(base_url() . 'Master/SuperGroup/');
    }

    /*
     * Get Department data
     */

    public function get_details() {
        $id = $this->input->post('id');
        $whereArray = array('id' => $id);

        $this->Db_model->setWhere($whereArray);
        $dataObject = $this->Db_model->getData('id,super_gname', 'tbl_super_group');

        $array = (array) $dataObject;
        echo json_encode($array);
    }

    /*
     * Edit Data
     */

    public function edit() {
        $ID = $this->input->post("id", TRUE);
        $D_Name = $this->input->post("super_gname", TRUE);


        $data = array("super_gname" => $D_Name);
        $whereArr = array("id" => $ID);
        $result = $this->Db_model->updateData("tbl_super_group", $data, $whereArr);
        redirect(base_url() . "Master/SuperGroup");
    }

    /*
     * Delete Data
     */

    public function ajax_delete($id) {
        $table = "tbl_super_group";
        $where = 'id';
        $this->Db_model->delete_by_id($id, $where, $table);
        echo json_encode(array("status" => TRUE));
    }

}
