<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Report_Attendance_In_Out extends CI_Controller {

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

        $data['title'] = "Attendance In Out Report | HRM System";
        $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        $data['data_desig'] = $this->Db_model->getData('Des_ID,Desig_Name', 'tbl_designations');
        $data['data_group'] = $this->Db_model->getData('Grp_ID,EmpGroupName', 'tbl_emp_group');
        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');
        // $data['data_branch'] = $this->Db_model->getData('B_id,B_name', 'tbl_branches');
        $data['emp_date']= $this->session->userdata('login_user');
        $data['emp_master'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_empmaster where EmpNo = '".$data['emp_date'][0]->EmpNo."'");
        if ($data['emp_master'][0]->user_p_id == "1") {
            $data['data_branch'] = $this->Db_model->getData('B_id,B_name', 'tbl_branches');
        }else{
            $data['data_branch'] = $this->Db_model->getfilteredData("select * from tbl_branches inner join tbl_empmaster on tbl_empmaster.B_id = tbl_branches.B_id WHERE tbl_empmaster.user_p_id = '3' AND tbl_branches.B_id = '".$data['emp_master'][0]->B_id."' AND tbl_empmaster.EmpNo = '".$data['emp_master'][0]->EmpNo."';");
        }
        $this->load->view('Reports/Attendance/Report_Attendance_In_Out', $data);
    }

    /*
     * Insert Departmrnt
     */

    public function Report_department() {

        $Data['data_set'] = $this->Db_model->getData('id,Dep_Name', 'tbl_departments');

        $this->load->view('Reports/Master/rpt_Departments', $Data);
    }

    public function Attendance_Report_By_Cat() {

        
          $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');

        $emp = $this->input->post("txt_emp");
        $emp_name = $this->input->post("txt_emp_name");
        $desig = $this->input->post("cmb_desig");
        $dept = $this->input->post("cmb_dep");
        $grop = $this->input->post("cmb_grop");
        $from_date = $this->input->post("txt_from_date");
        $to_date = $this->input->post("txt_to_date");
        $branch = $this->input->post("cmb_branch");
        

        $data['f_date'] = $from_date;
        $data['t_date'] = $to_date;


        // Filter Data by categories
        $filter = '';

        if (($this->input->post("txt_from_date")) && ($this->input->post("txt_to_date"))) {
            if ($filter == '') {
                $filter = " where  ir.FDate between '$from_date' and '$to_date'";
            } else {
                $filter .= " AND  ir.FDate between '$from_date' and '$to_date'";
            }
        }
        if (($this->input->post("txt_emp"))) {
            if ($filter == null) {
                $filter = " where ir.EmpNo =$emp";
            } else {
                $filter .= " AND ir.EmpNo =$emp";
            }
        }
        if (($this->input->post("cmb_grop"))) {
            if ($filter == null) {
                $filter = " where Emp.Grp_ID =$grop";
            } else {
                $filter .= " AND Emp.Grp_ID =$grop";
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
        
        $data['data_set'] = $this->Db_model->getfilteredData("SELECT 
           *
        FROM
            tbl_individual_roster ir
                LEFT JOIN
            tbl_empmaster Emp ON Emp.EmpNo = ir.EmpNo
                LEFT JOIN
            tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
                LEFT JOIN
            tbl_departments dep ON dep.Dep_id = Emp.Dep_id
                INNER JOIN
            tbl_branches br ON Emp.B_id = br.B_id

            {$filter} AND Emp.EmpNo != '00009000' GROUP BY ir.EmpNo, Emp.Emp_Full_Name");

//        var_dump($data);die;

        $this->load->view('Reports/Attendance/rpt_In_Out', $data);
    }

    // public function Attendance_Report_By_Cat() {

    //     $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');
    
    //     $emp = $this->input->post("txt_emp");
    //     $emp_name = $this->input->post("txt_emp_name");
    //     $desig = $this->input->post("cmb_desig");
    //     $dept = $this->input->post("cmb_dep");
    //     $from_date = $this->input->post("txt_from_date");
    //     $to_date = $this->input->post("txt_to_date");
    //     $branch = $this->input->post("cmb_branch");
    
    //     $data['f_date'] = $from_date;
    //     $data['t_date'] = $to_date;
    
    //     // Filter Data by categories
    //     $filter = '';
    
    //     if (($this->input->post("txt_from_date")) && ($this->input->post("txt_to_date"))) {
    //         if ($filter == '') {
    //             $filter = " where  ir.FDate between '$from_date' and '$to_date' and Emp.`Status` = 1 ";
    //         } else {
    //             $filter .= " AND  ir.FDate between '$from_date' and '$to_date' and Emp.`Status` = 1 ";
    //         }
    //     }
    //     if (($this->input->post("txt_emp"))) {
    //         if ($filter == null) {
    //             $filter = " where ir.EmpNo =$emp";
    //         } else {
    //             $filter .= " AND ir.EmpNo =$emp";
    //         }
    //     }
    
    //     if (($this->input->post("txt_emp_name"))) {
    //         if ($filter == null) {
    //             $filter = " where Emp.Emp_Full_Name ='$emp_name'";
    //         } else {
    //             $filter .= " AND Emp.Emp_Full_Name ='$emp_name'";
    //         }
    //     }
    //     if (($this->input->post("cmb_desig"))) {
    //         if ($filter == null) {
    //             $filter = " where dsg.Des_ID  ='$desig'";
    //         } else {
    //             $filter .= " AND dsg.Des_ID  ='$desig'";
    //         }
    //     }
    //     if (($this->input->post("cmb_dep"))) {
    //         if ($filter == null) {
    //             $filter = " where dep.Dep_id  ='$dept'";
    //         } else {
    //             $filter .= " AND dep.Dep_id  ='$dept'";
    //         }
    //     }
    
    //     if (($this->input->post("cmb_branch"))) {
    //         if ($filter == null) {
    //             $filter = " where br.B_id  ='$branch'";
    //         } else {
    //             $filter .= " AND br.B_id  ='$branch'";
    //         }
    //     }
    
    //     $data_set2 = $this->Db_model->getfilteredData("SELECT 
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
    //     tbl_branches br ON Emp.B_id = br.B_id
    
    //     {$filter} AND Emp.EmpNo != '00009000' GROUP BY ir.EmpNo, Emp.Emp_Full_Name");
    
    //     // Initialize TCPDF
    //     $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    //     $pdf->SetCreator(PDF_CREATOR);
    //     $pdf->SetAuthor('Your Name');
    //     $pdf->SetTitle('Attendance Report by Category');
    //     $pdf->SetSubject('Attendance Report');
    //     $pdf->SetKeywords('TCPDF, PDF, report, attendance, employee');
    
    //     // Set header and footer fonts
    //     $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    //     $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    
    //     // Set default monospaced font
    //     $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    
    //     // Set margins
    //     $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    //     $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    //     $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
    //     // Set auto page breaks
    //     $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    
    //     // Set image scale factor
    //     $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    
    //     // Add a page
    //     $pdf->AddPage();
    
    //     // Set font
    //     $pdf->SetFont('helvetica', '', 10);
    
    //     // Title
    //     $pdf->Cell(0, 10, 'Attendance Report by Category', 0, 1, 'C');
    
    //     // Table header
    //     $pdf->SetFont('helvetica', 'B', 12);
    //     $pdf->Cell(30, 7, 'EMP NO', 1);
    //     $pdf->Cell(40, 7, 'NAME', 1);
    //     $pdf->Cell(50, 7, 'ATTENDED DAYS', 1);
    //     $pdf->Cell(30, 7, 'ABSENT DAYS', 1);
    //     $pdf->Cell(20, 7, 'LEAVE APPLY DAYS', 1);
    //     $pdf->Cell(30, 7, 'NOPAY DAYS', 1);
    //     $pdf->Ln();
    
    //     // Reset font for table content
    //     $pdf->SetFont('helvetica', '', 10);
    
    //     // Table content
    //     foreach ($data_set2 as $row) {
    //         $pdf->Cell(30, 7, '00'.$row->EmpNo, 1);
    //         $pdf->Cell(50, 7, $row->Emp_Full_Name, 1);

    //         $AttDate = $this->Db_model->getfilteredData("SELECT COUNT(EmpNo) AS ATTENDED FROM `tbl_individual_roster` WHERE DayStatus = 'PR' AND EmpNo='".$row->EmpNo."' AND FDate between '".$from_date."' and '".$to_date."' ");
    //         $ATTENDED1 = $AttDate[0]->ATTENDED;
    //         $pdf->Cell(20, 7, $ATTENDED1, 1);
            
    //         $AbDate = $this->Db_model->getfilteredData("SELECT COUNT(EmpNo) AS ABSENT FROM `tbl_individual_roster` WHERE DayStatus = 'AB' AND EmpNo='".$row->EmpNo."' AND FDate between '".$from_date."' and '".$to_date."' ");
    //         $ABSENT1 = $AbDate[0]->ABSENT;
    //         $pdf->Cell(30, 7, $ABSENT1, 1);
            
    //         $LvDate = $this->Db_model->getfilteredData("SELECT COUNT(EmpNo) AS LEAVE1 FROM `tbl_individual_roster` WHERE DayStatus = 'LV' AND EmpNo='".$row->EmpNo."' AND FDate between '".$from_date."' and '".$to_date."' ");
    //         $LEAVE1 = $LvDate[0]->LEAVE1;
    //         $pdf->Cell(30, 7, $LEAVE1, 1);
    //         $pdf->Ln();
    //     }
    
    //     // Output PDF
    //     $pdf->Output('attendance_report_by_category.pdf', 'D');
    // }
    

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
