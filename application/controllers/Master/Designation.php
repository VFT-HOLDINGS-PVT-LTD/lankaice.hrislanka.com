<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Designation extends CI_Controller {

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

        $data['title'] = "Designation | HRM System";
        $data['data_set'] = $this->Db_model->getData('Des_ID,Desig_Name,Desig_Order', 'tbl_designations');
        $this->load->view('Master/Designation/index', $data);
    }

    /*
     * Insert Data
     */

    public function insert_Designation() {

        /*
         * Data array
         */
        $data = array(
            'Desig_Name' => $this->input->post('txt_desig_name'),
            'Desig_Order' => $this->input->post('txt_desig_order')
        );

        //**********Transaction Start
        $this->db->trans_start();

        //Insert Data
        $result = $this->Db_model->insertData("tbl_designations", $data);

        //**********Transaction complate
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {

            $this->db->trans_commit();
            $this->session->set_flashdata('success_message', 'New Designation has been added successfully');
        }

        redirect(base_url() . 'Master/Designation/'); //*********Redirect to designation form
    }

    /*
     * Get data
     */

    public function get_details() {
        $id = $this->input->post('id');

//                    echo "OkM " . $id;

        $whereArray = array('Des_ID' => $id);

        $this->Db_model->setWhere($whereArray);
        $dataObject = $this->Db_model->getData('Des_ID,Desig_Name,Desig_Order', 'tbl_designations');

        $array = (array) $dataObject;
        echo json_encode($array);
    }

    /*
     * Edit Data
     */

    public function edit() {
        $ID = $this->input->post("id", TRUE);
        $D_Name = $this->input->post("Desig_Name", TRUE);
        $D_Order = $this->input->post("Desig_Order", TRUE);

        $data = array("Desig_Name" => $D_Name, 'Desig_Order' => $D_Order);
        $whereArr = array("Des_ID" => $ID);
        $result = $this->Db_model->updateData("tbl_designations", $data, $whereArr);
        redirect(base_url() . "Master/Designation");
    }

    /*
     * Delete Data
     */

    public function ajax_delete($id) {
        $table = "tbl_designations";
        $where = 'Des_ID';
        $this->Db_model->delete_by_id($id, $where, $table);
        echo json_encode(array("status" => TRUE));
    }

}
