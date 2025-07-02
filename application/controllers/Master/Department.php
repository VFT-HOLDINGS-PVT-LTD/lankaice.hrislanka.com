<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Department extends CI_Controller {

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

        $data['title'] = "Departmrnt | HRM System";
        $data['data_set'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        $this->load->view('Master/Department/index', $data);
    }

    /*
     * Insert Departmrnt
     */

    public function insertDepartment() {

        $data = array(
            'Dep_Name' => $this->input->post('txt_dep_name')
        );

        $result = $this->Db_model->insertData("tbl_departments", $data);


        $this->session->set_flashdata('success_message', 'New Department has been added successfully');

        
        redirect(base_url() . 'Master/Department/');
    }

    /*
     * Get Department data
     */

    public function get_details() {
        $id = $this->input->post('id');
        $whereArray = array('Dep_ID' => $id);

        $this->Db_model->setWhere($whereArray);
        $dataObject = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');

        $array = (array) $dataObject;
        echo json_encode($array);
    }

    /*
     * Edit Data
     */

    public function edit() {
        $ID = $this->input->post("id", TRUE);
        $D_Name = $this->input->post("Dep_Name", TRUE);


        $data = array("Dep_Name" => $D_Name);
        $whereArr = array("Dep_ID" => $ID);
        $result = $this->Db_model->updateData("tbl_departments", $data, $whereArr);
        redirect(base_url() . "Master/Department");
    }

    /*
     * Delete Data
     */

    public function ajax_delete($id) {
        $table = "tbl_departments";
        $where = 'Dep_ID';
        $this->Db_model->delete_by_id($id, $where, $table);
        echo json_encode(array("status" => TRUE));
    }

}
