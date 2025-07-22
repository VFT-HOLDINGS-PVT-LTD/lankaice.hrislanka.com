<?php

defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Report_Attendance_ATT_Sum extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!($this->session->userdata('login_user'))) {
            redirect(base_url() . "");
        }

        /*
         * Load Database model
         */
        $this->load->library("pdf_library");
        $this->load->model('Db_model', '', true);
    }

    /*
     * Index page in Departmrnt
     */

    public function index()
    {

        $data['title'] = "Attendance In Out Report Summery | HRM System";
        $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        $data['data_desig'] = $this->Db_model->getData('Des_ID,Desig_Name', 'tbl_designations');
        $data['data_group'] = $this->Db_model->getData('id,super_gname', 'tbl_super_group');
        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');
        // $data['data_branch'] = $this->Db_model->getData('B_id,B_name', 'tbl_branches');
        $data['emp_date'] = $this->session->userdata('login_user');
        $data['emp_master'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_empmaster where EmpNo = '" . $data['emp_date'][0]->EmpNo . "'");
        if ($data['emp_master'][0]->user_p_id == "1") {
            $data['data_branch'] = $this->Db_model->getData('B_id,B_name', 'tbl_branches');
        } else {
            $data['data_branch'] = $this->Db_model->getfilteredData("select * from tbl_branches inner join tbl_empmaster on tbl_empmaster.B_id = tbl_branches.B_id WHERE tbl_empmaster.user_p_id = '3' AND tbl_branches.B_id = '" . $data['emp_master'][0]->B_id . "' AND tbl_empmaster.EmpNo = '" . $data['emp_master'][0]->EmpNo . "';");
        }
        $this->load->view('Reports/Attendance/Report_Attendance_In_Out_Sum', $data);
    }

    /*
     * Insert Departmrnt
     */

    public function Report_department()
    {

        $Data['data_set'] = $this->Db_model->getData('id,Dep_Name', 'tbl_departments');

        $this->load->view('Reports/Master/rpt_Departments', $Data);
    }

    //     public function Attendance_Report_By_Cat() {

    //         $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');

    //         $emp = $this->input->post("txt_emp");
//         $emp_name = $this->input->post("txt_emp_name");
//         $desig = $this->input->post("cmb_desig");
//         $dept = $this->input->post("cmb_dep");
//         $grop = $this->input->post("cmb_grop");
//         $from_date = $this->input->post("txt_from_date");
//         $to_date = $this->input->post("txt_to_date");
//         $branch = $this->input->post("cmb_branch");

    //         $data['f_date'] = $from_date;
//         $data['t_date'] = $to_date;

    //         // Filter Data by categories
//         $filter = '';

    //         if (($this->input->post("txt_from_date")) && ($this->input->post("txt_to_date"))) {
//             if ($filter == '') {
//                 $filter = " where  ir.FDate between '$from_date' and '$to_date'";
//             } else {
//                 $filter .= " AND  ir.FDate between '$from_date' and '$to_date'";
//             }
//         }
//         if (($this->input->post("txt_emp"))) {
//             if ($filter == null) {
//                 $filter = " where ir.EmpNo =$emp";
//             } else {
//                 $filter .= " AND ir.EmpNo =$emp";
//             }
//         }

    //         if (($this->input->post("txt_emp_name"))) {
//             if ($filter == null) {
//                 $filter = " where Emp.Emp_Full_Name ='$emp_name'";
//             } else {
//                 $filter .= " AND Emp.Emp_Full_Name ='$emp_name'";
//             }
//         }
//         if (($this->input->post("cmb_grop"))) {
//             if ($filter == null) {
//                 $filter = " where Emp.SupGrp_ID =$grop";
//             } else {
//                 $filter .= " AND Emp.SupGrp_ID =$grop";
//             }
//         }
//         if (($this->input->post("cmb_desig"))) {
//             if ($filter == null) {
//                 $filter = " where dsg.Des_ID  ='$desig'";
//             } else {
//                 $filter .= " AND dsg.Des_ID  ='$desig'";
//             }
//         }
//         if (($this->input->post("cmb_dep"))) {
//             if ($filter == null) {
//                 $filter = " where dep.Dep_id  ='$dept'";
//             } else {
//                 $filter .= " AND dep.Dep_id  ='$dept'";
//             }
//         }

    //         if (($this->input->post("cmb_branch"))) {
//             if ($filter == null) {
//                 $filter = " where br.B_id  ='$branch'";
//             } else {
//                 $filter .= " AND br.B_id  ='$branch'";
//             }
//         }

    //         $data['data_set2'] = $this->Db_model->getfilteredData("SELECT
//                                                                     ir.EmpNo,
//                                                                     Emp.Emp_Full_Name,
//                                                                     ir.FDate,
//                                                                     ir.TDate,
//                                                                     ir.InDate,
//                                                                     ir.OutDate,
//                                                                     ir.ShiftDay,
//                                                                     ir.ShType,
//                                                                     ir.FTime,
//                                                                     ir.TTime,
//                                                                     ir.InTime,
//                                                                     ir.OutTime,
//                                                                     ir.DayStatus,
//                                                                     ir.ApprovedExH,
//                                                                     ir.LateM,
//                                                                     ir.Lv_T_ID,
//                                                                     ir.EarlyDepMin,
//                                                                     br.B_name,
//                                                                     ir.BreackOutTime1,
//                                                                     ir.BreackInTime1,
//                                                                     ir.AfterExH,
//                                                                     ir.NumShift

    //                                                                 FROM
//                                                                     tbl_individual_roster ir
//                                                                         LEFT JOIN
//                                                                     tbl_empmaster Emp ON Emp.EmpNo = ir.EmpNo
//                                                                         LEFT JOIN
//                                                                     tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
//                                                                         LEFT JOIN
//                                                                     tbl_departments dep ON dep.Dep_id = Emp.Dep_id
//                                                                     inner join
//                                                                     tbl_branches br on Emp.B_id = br.B_id

    //                                                                     {$filter} AND STATUS='1' AND Emp.EmpNo != '00009000' order by ir.EmpNo,ir.FDate,ir.InTime;");

    // //        var_dump($data);die;

    //         $this->load->view('Reports/Attendance/rpt_In_Out_Sum', $data);
//     }

    // public function Attendance_Report_By_Cat()
// {
//     $selected_columns = $this->input->post('columns');
//     $f_date = $this->input->post('f_date');
//     $t_date = $this->input->post('t_date');

    //     $filter = "WHERE ir.FDate BETWEEN '$f_date' AND '$t_date'"; // Add more filters if needed

    //     $query = "SELECT
//                 ir.EmpNo,
//                 Emp.Emp_Full_Name,
//                 ir.FDate,
//                 ir.TDate,
//                 ir.InDate,
//                 ir.OutDate,
//                 ir.ShiftDay,
//                 ir.ShType,
//                 ir.FTime,
//                 ir.TTime,
//                 ir.InTime,
//                 ir.OutTime,
//                 ir.DayStatus,
//                 ir.ApprovedExH,
//                 ir.LateM,
//                 ir.Lv_T_ID,
//                 ir.EarlyDepMin,
//                 br.B_name,
//                 ir.BreackOutTime1,
//                 ir.BreackInTime1,
//                 ir.AfterExH,
//                 ir.NumShift
//             FROM tbl_individual_roster ir
//             LEFT JOIN tbl_empmaster Emp ON Emp.EmpNo = ir.EmpNo
//             LEFT JOIN tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
//             LEFT JOIN tbl_departments dep ON dep.Dep_id = Emp.Dep_id
//             INNER JOIN tbl_branches br ON Emp.B_id = br.B_id
//             $filter AND Emp.Status = '1' AND Emp.EmpNo != '00009000'
//             ORDER BY ir.EmpNo, ir.FDate, ir.InTime";

    //     $data['data_set2'] = $this->Db_model->getfilteredData($query);
//     $data['data_cmp'] = $this->Db_model->getfilteredData("SELECT Company_Name FROM tbl_companyprofile"); // For PDF title
//     $data['selected_columns'] = $selected_columns;
//     $data['f_date'] = $f_date;
//     $data['t_date'] = $t_date;

    //     // print_r($data); // For debugging purposes
//     // $this->load->view('Reports/Attendance/rpt_In_Out_Sum', $data);
//     $data['selected_cols'] = $this->input->post('selected_cols') ?? []; // or however you get the selected columns
// $this->load->view('Reports/Attendance/rpt_In_Out_Sum', $data);

    // }

    public function Attendance_Report_By_Cat()
    {
        $rept_type = $this->input->post("cmb_rept_type");

        if ($rept_type == "AttSum") {
            $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');

            // Capture input
            $selected_columns = $this->input->post('columns') ?? []; // array of selected column names
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
            $data['selected_columns'] = $selected_columns;

            // Build filter
            $filter = '';
            if (!empty($from_date) && !empty($to_date)) {
                $filter .= " WHERE ir.FDate BETWEEN '$from_date' AND '$to_date'";
            }

            if (!empty($emp)) {
                $filter .= empty($filter) ? " WHERE ir.EmpNo = '$emp'" : " AND ir.EmpNo = '$emp'";
            }

            if (!empty($emp_name)) {
                $filter .= empty($filter) ? " WHERE Emp.Emp_Full_Name = '$emp_name'" : " AND Emp.Emp_Full_Name = '$emp_name'";
            }

            if (!empty($grop)) {
                $filter .= empty($filter) ? " WHERE Emp.SupGrp_ID = '$grop'" : " AND Emp.SupGrp_ID = '$grop'";
            }

            if (!empty($desig)) {
                $filter .= empty($filter) ? " WHERE dsg.Des_ID = '$desig'" : " AND dsg.Des_ID = '$desig'";
            }

            if (!empty($dept)) {
                $filter .= empty($filter) ? " WHERE dep.Dep_id = '$dept'" : " AND dep.Dep_id = '$dept'";
            }

            if (!empty($branch)) {
                $filter .= empty($filter) ? " WHERE br.B_id = '$branch'" : " AND br.B_id = '$branch'";
            }

            // Always include these
            $filter .= (empty($filter) ? " WHERE " : " AND ") . "Emp.Status = '1' AND Emp.EmpNo != '00009000'";

            // Full query with all columns (selected_columns will control display)
            $query = "SELECT
                ir.EmpNo,
                Emp.Emp_Full_Name,
                ir.FDate,
                ir.TDate,
                ir.InDate,
                ir.OutDate,
                ir.ShiftDay,
                ir.ShType,
                ir.FTime,
                ir.TTime,
                ir.InTime,
                ir.OutTime,
                ir.DayStatus,
                ir.ApprovedExH,
                ir.LateM,
                ir.Lv_T_ID,
                ir.EarlyDepMin,
                br.B_name,
                ir.BreackOutTime1,
                ir.BreackInTime1,
                ir.AfterExH,
                ir.NumShift
            FROM tbl_individual_roster ir
            LEFT JOIN tbl_empmaster Emp ON Emp.EmpNo = ir.EmpNo
            LEFT JOIN tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
            LEFT JOIN tbl_departments dep ON dep.Dep_id = Emp.Dep_id
            INNER JOIN tbl_branches br ON Emp.B_id = br.B_id
            $filter
            ORDER BY ir.EmpNo, ir.FDate, ir.InTime";

            // Fetch data
            $data['data_set2'] = $this->Db_model->getfilteredData($query);

            // Load view
            $this->load->view('Reports/Attendance/rpt_In_Out_Sum', $data);
        } else if ($rept_type == "LateRpt") {
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
                    $filter = " where  ir.FDate between '$from_date' and '$to_date' AND ir.InTime > ADDTIME(tbl_shifts.FromTime, SEC_TO_TIME(tbl_emp_group.GracePeriod * 60)) ";
                } else {
                    $filter .= " AND  ir.FDate between '$from_date' and '$to_date' AND ir.InTime > ADDTIME(tbl_shifts.FromTime, SEC_TO_TIME(tbl_emp_group.GracePeriod * 60)) ";
                }
            }
            if (($this->input->post("txt_emp"))) {
                if ($filter == null) {
                    $filter = " where ir.EmpNo =$emp";
                } else {
                    $filter .= " AND ir.EmpNo =$emp";
                }
            }

            if (($this->input->post("txt_emp_name"))) {
                if ($filter == null) {
                    $filter = " where Emp.Emp_Full_Name ='$emp_name'";
                } else {
                    $filter .= " AND Emp.Emp_Full_Name ='$emp_name'";
                }
            }
            if (($this->input->post("cmb_grop"))) {
                if ($filter == null) {
                    $filter = " where Emp.SupGrp_ID =$grop";
                } else {
                    $filter .= " AND Emp.SupGrp_ID =$grop";
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
                                                                     ir.EmpNo,
                                                                    Emp.Emp_Full_Name,
                                                                    ir.FDate,
                                                                    ir.ShiftDay,
                                                                    ir.ShType,
                                                                    ir.FTime,
                                                                    ir.TTime,
                                                                    ir.InTime,
                                                                    ir.OutTime,
                                                                    ir.DayStatus,
                                                                    ir.LateM,
                                                                    br.B_name,
                                                                    tbl_emp_group.GracePeriod,
                                                                    tbl_shifts.FromTime,
                                                                    ADDTIME(tbl_shifts.FromTime, SEC_TO_TIME(tbl_emp_group.GracePeriod * 60)) AS TotalShiftTime
                                                                FROM
                                                                tbl_individual_roster ir
                                                                        LEFT JOIN
                                                                    tbl_empmaster Emp ON Emp.EmpNo = ir.EmpNo
                                                                        LEFT JOIN
                                                                    tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
                                                                        LEFT JOIN
                                                                    tbl_departments dep ON dep.Dep_id = Emp.Dep_id
                                                                    inner join
                                                                    tbl_branches br on Emp.B_id = br.B_id
                                                                    inner join
                                                                    tbl_shifts on ir.ShiftCode = tbl_shifts.ShiftCode
                                                                    inner join
                                                                    tbl_emp_group on tbl_emp_group.Grp_ID = Emp.Grp_ID


                                                                    {$filter} AND STATUS='1' AND Emp.EmpNo != '00009000' order by Emp.Emp_Full_Name,ir.FDate;");

            //        var_dump($data);die;

            $this->load->view('Reports/Attendance/rpt_In_Out_Late', $data);
        } else if ($rept_type == "MissRpt") {
            $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');

            $emp = $this->input->post("txt_emp");
            $emp_name = $this->input->post("txt_emp_name");
            $desig = $this->input->post("cmb_desig");
            $dept = $this->input->post("cmb_dep");
            $from_date = $this->input->post("txt_from_date");
            $to_date = $this->input->post("txt_to_date");
            $branch = $this->input->post("cmb_branch");

            $data['f_date'] = $from_date;
            $data['t_date'] = $to_date;

            // Filter Data by categories
            $filter = '';

            if (($this->input->post("txt_from_date")) && ($this->input->post("txt_to_date"))) {
                if ($filter == '') {
                    $filter = " where  iro.FDate between '$from_date' and '$to_date'";
                } else {
                    $filter .= " AND  iro.FDate between '$from_date' and '$to_date'";
                }
            }
            if (($this->input->post("txt_emp"))) {
                if ($filter == null) {
                    $filter = " where iro.EmpNo =$emp";
                } else {
                    $filter .= " AND iro.EmpNo =$emp";
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
                Emp.Emp_Full_Name,
                iro.EmpNo,
                iro.InTime,
                iro.OutTime,
                iro.DayStatus,
                iro.FDate,
                iro.TDate,
                br.B_name
            FROM
                tbl_individual_roster iro
                    LEFT JOIN
                tbl_empmaster Emp ON Emp.EmpNo = iro.EmpNo
                    LEFT JOIN
                tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
                    LEFT JOIN
                tbl_departments dep ON dep.Dep_id = Emp.Dep_id
                    INNER JOIN
                tbl_branches br ON Emp.B_id = br.B_id


        {$filter} AND DayStatus='MS'");
            $this->load->view('Reports/Attendance/rpt_In_Out_misspunch_data', $data);

        } else if ($rept_type == "LvRpt") {
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
                    $filter = " where  tbl_leave_entry.Leave_Date between '$from_date' and '$to_date'";
                } else {
                    $filter .= " AND  tbl_leave_entry.Leave_Date between '$from_date' and '$to_date'";
                }
            }
            if (($this->input->post("txt_emp"))) {
                if ($filter == null) {
                    $filter = " where tbl_empmaster.EmpNo =$emp";
                } else {
                    $filter .= " AND tbl_empmaster.EmpNo =$emp";
                }
            }
            if (($this->input->post("cmb_grop"))) {
                if ($filter == null) {
                    $filter = " where tbl_empmaster.SupGrp_ID =$grop";
                } else {
                    $filter .= " AND tbl_empmaster.SupGrp_ID =$grop";
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
                    $filter = " where br.B_id  ='$branch'";
                } else {
                    $filter .= " AND br.B_id  ='$branch'";
                }
            }

            //        print_r($SS);die;

            $data['data_set'] = $this->Db_model->getfilteredData("SELECT
                tbl_leave_entry.LV_ID,
                tbl_empmaster.EmpNo,
                tbl_empmaster.Emp_Full_Name,
                tbl_leave_types.leave_name,
                tbl_leave_entry.Leave_Date,
                tbl_leave_entry.Leave_Count,
                tbl_leave_entry.Approved_by,
                approved_emp.Emp_Full_Name as APP_Emp_Full_Name,
                tbl_leave_entry.Reason,
                br.B_name
            FROM
                tbl_leave_entry
            INNER JOIN
                tbl_leave_types ON tbl_leave_types.Lv_T_ID = tbl_leave_entry.Lv_T_ID
            INNER JOIN
                tbl_empmaster ON tbl_empmaster.EmpNo = tbl_leave_entry.EmpNo
            INNER JOIN
                tbl_designations ON tbl_designations.Des_ID = tbl_empmaster.Des_ID
            INNER JOIN
                tbl_departments ON tbl_departments.Dep_ID = tbl_empmaster.Dep_ID
            INNER JOIN
                tbl_empmaster AS approved_emp ON tbl_leave_entry.Approved_by = approved_emp.EmpNo
                INNER JOIN
                tbl_branches AS br ON tbl_empmaster.B_id = br.B_id

            {$filter} AND tbl_empmaster.Status='1' AND tbl_empmaster.EmpNo != '00009000' AND tbl_leave_entry.Is_Cancel=0 order by tbl_leave_entry.Leave_Date");

            //        var_dump($data);die;

            $this->load->view('Reports/Attendance/rpt_Leave', $data);
        } else if ($rept_type == "SlRpt") {
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
                    $filter = " where  sl.Date between '$from_date' and '$to_date'";
                } else {
                    $filter .= " AND  sl.Date between '$from_date' and '$to_date'";
                }
            }
            if (($this->input->post("txt_emp"))) {
                if ($filter == null) {
                    $filter = " where sl.EmpNo =$emp";
                } else {
                    $filter .= " AND sl.EmpNo =$emp";
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

            $data['data_set'] = $this->Db_model->getfilteredData("SELECT * FROM
        tbl_shortlive sl
            LEFT JOIN
        tbl_empmaster Emp ON Emp.EmpNo = sl.EmpNo
            LEFT JOIN
        tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
            LEFT JOIN
        tbl_departments dep ON dep.Dep_id = Emp.Dep_id
            INNER JOIN
        tbl_branches br ON Emp.B_id = br.B_id

            {$filter} AND Is_Approve = 1 AND Emp.EmpNo != '00009000'");

            //        var_dump($data);die;

            $this->load->view('Reports/Attendance/rpt_Short_Leave', $data);
        } else if ($rept_type == "MonthRpt") {
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
        } else if ($rept_type == "PrRpt") {
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
            if (($this->input->post("cmb_grop"))) {
                if ($filter == null) {
                    $filter = " where Emp.SupGrp_ID =$grop";
                } else {
                    $filter .= " AND Emp.SupGrp_ID =$grop";
                }
            }

            if (($this->input->post("cmb_branch"))) {
                if ($filter == null) {
                    $filter = " where br.B_id  ='$branch'";
                } else {
                    $filter .= " AND br.B_id  ='$branch'";
                }
            }

            $data['data_set2'] = $this->Db_model->getfilteredData("SELECT
                                                                    ir.EmpNo,
                                                                    Emp.Emp_Full_Name,
                                                                    ir.FDate,
                                                                    ir.ShiftDay,
                                                                    ir.ShType,
                                                                    ir.FTime,
                                                                    ir.TTime,
                                                                    ir.InTime,
                                                                    ir.OutTime,
                                                                    ir.DayStatus,
                                                                    ir.ApprovedExH,
                                                                     ir.LateM,
                                                                    br.B_name
                                                                FROM
                                                                    tbl_individual_roster ir
                                                                        LEFT JOIN
                                                                    tbl_empmaster Emp ON Emp.EmpNo = ir.EmpNo
                                                                        LEFT JOIN
                                                                    tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
                                                                        LEFT JOIN
                                                                    tbl_departments dep ON dep.Dep_id = Emp.Dep_id
                                                                    inner join
                                                                    tbl_branches br on Emp.B_id = br.B_id


                                                                    {$filter} AND STATUS='1' AND ir.DayStatus = 'PR' AND Emp.EmpNo != '00009000' order by ir.FDate,ir.InTime;");

            //        var_dump($data);die;

            $this->load->view('Reports/Attendance/rpt_In_Out_Sum_Pr', $data);
        } else if ($rept_type == "AbRpt") {

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

            if (($this->input->post("txt_emp_name"))) {
                if ($filter == null) {
                    $filter = " where Emp.Emp_Full_Name ='$emp_name'";
                } else {
                    $filter .= " AND Emp.Emp_Full_Name ='$emp_name'";
                }
            }
            if (($this->input->post("cmb_grop"))) {
                if ($filter == null) {
                    $filter = " where Emp.SupGrp_ID =$grop";
                } else {
                    $filter .= " AND Emp.SupGrp_ID =$grop";
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

            $data['data_set2'] = $this->Db_model->getfilteredData("SELECT
                                                                    ir.EmpNo,
                                                                    Emp.Emp_Full_Name,
                                                                    ir.FDate,
                                                                    ir.ShiftDay,
                                                                    ir.ShType,
                                                                    ir.FTime,
                                                                    ir.TTime,
                                                                    ir.InTime,
                                                                    ir.OutTime,
                                                                    ir.DayStatus,
                                                                    ir.ApprovedExH,
                                                                     ir.LateM,
                                                                    br.B_name
                                                                FROM
                                                                    tbl_individual_roster ir
                                                                        LEFT JOIN
                                                                    tbl_empmaster Emp ON Emp.EmpNo = ir.EmpNo
                                                                        LEFT JOIN
                                                                    tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
                                                                        LEFT JOIN
                                                                    tbl_departments dep ON dep.Dep_id = Emp.Dep_id
                                                                    inner join
                                                                    tbl_branches br on Emp.B_id = br.B_id


                                                                    {$filter} AND STATUS='1' AND ir.DayStatus = 'AB' AND Emp.EmpNo != '00009000' order by ir.FDate,ir.InTime;");

            //        var_dump($data);die;

            $this->load->view('Reports/Attendance/rpt_In_Out_Sum_Ab', $data);
        } else if ($rept_type == "LvSumRpt") {
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

            if (isset($_POST['cmb_year'])) {
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
        } else if ($rept_type == "BrkRpt") {

            $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');

            $emp = $this->input->post("txt_emp");
            $emp_name = $this->input->post("txt_emp_name");
            $desig = $this->input->post("cmb_desig");
            $dept = $this->input->post("cmb_dep");
            $from_date = $this->input->post("txt_from_date");
            $to_date = $this->input->post("txt_to_date");
            $branch = $this->input->post("cmb_branch");

            $data['f_date'] = $from_date;
            $data['t_date'] = $to_date;

            $currentUser = $this->session->userdata('login_user');
            $login_user_id = $currentUser[0]->EmpNo;
            $login_user_group = $this->Db_model->getfilteredData("SELECT tbl_emp_group.Grp_ID FROM tbl_emp_group WHERE tbl_emp_group.Sup_ID = '$login_user_id'");

            $user_group_id = $login_user_group[0]->Grp_ID;

            if ($user_group_id == 1) {
                $group_setting = "";
            } else if (!empty($login_user_group[1]->Grp_ID)) {
                $user_group_id_2 = $login_user_group[1]->Grp_ID;
                $group_setting = "and (Emp.`Grp_ID` = '$user_group_id' OR Emp.`Grp_ID` = '$user_group_id_2')";
            } else if (!empty($login_user_group[0]->Grp_ID)) {
                $group_setting = "and Emp.`Grp_ID` = '$user_group_id'";
            }

            // Filter Data by categories
            $filter = '';

            if (($this->input->post("txt_from_date")) && ($this->input->post("txt_to_date"))) {
                if ($filter == '') {
                    $filter = " where  ir.AttDate between '$from_date' and '$to_date' and Emp.`Status` = '1' $group_setting ";
                } else {
                    $filter .= " AND  ir.AttDate between '$from_date' and '$to_date' and Emp.`Status` = '1' $group_setting ";
                }
            }
            if (($this->input->post("txt_emp"))) {
                if ($filter == null) {
                    $filter = " where ir.Enroll_No ='$emp'";
                } else {
                    $filter .= " AND ir.Enroll_No ='$emp'";
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
    ir.EventID,
    Emp.Emp_Full_Name,
    Emp.EmpNo,
    ir.Enroll_No,
    dsg.Desig_Name,
    ir.AttDate,
    ir.AttTime,
   ir.AttTime,
   ir.Status
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


                                                                    {$filter}  order by Emp_Full_Name, ir.AttDate, ir.AttTime");

            //        var_dump($data);die;

            $this->load->view('Reports/Attendance/rpt_In_Out_row_Break', $data);
        }

    }

    public function Export_Excel()
    {
        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');
        $rept_type = $this->input->post("cmb_rept_type");

        if ($rept_type == "AttSum") {
            // Input filters
            $emp = $this->input->post("txt_emp");
            $emp_name = $this->input->post("txt_emp_name");
            $desig = $this->input->post("cmb_desig");
            $dept = $this->input->post("cmb_dep");
            $from_date = $this->input->post("txt_from_date");
            $to_date = $this->input->post("txt_to_date");
            $branch = $this->input->post("cmb_branch");

            $data['f_date'] = $from_date;
            $data['t_date'] = $to_date;

            // Build filter
            $filter = '';

            if (!empty($from_date) && !empty($to_date)) {
                $filter = " WHERE ir.FDate BETWEEN '$from_date' AND '$to_date'";
            }

            if (!empty($emp)) {
                $filter .= empty($filter) ? " WHERE ir.EmpNo = '$emp'" : " AND ir.EmpNo = '$emp'";
            }

            if (!empty($emp_name)) {
                $filter .= empty($filter) ? " WHERE Emp.Emp_Full_Name = '$emp_name'" : " AND Emp.Emp_Full_Name = '$emp_name'";
            }

            if (!empty($desig)) {
                $filter .= empty($filter) ? " WHERE dsg.Des_ID = '$desig'" : " AND dsg.Des_ID = '$desig'";
            }

            if (!empty($dept)) {
                $filter .= empty($filter) ? " WHERE dep.Dep_id = '$dept'" : " AND dep.Dep_id = '$dept'";
            }

            if (!empty($branch)) {
                $filter .= empty($filter) ? " WHERE br.B_id = '$branch'" : " AND br.B_id = '$branch'";
            }

            // Load PhpSpreadsheet objects
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Autosize columns A to G
            foreach (range('A', 'G') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }

            // Header row
            $sheet->setCellValue('A1', 'EmpNo');
            $sheet->setCellValue('B1', 'Name');
            $sheet->setCellValue('C1', 'FROM DATE');
            $sheet->setCellValue('D1', 'FROM TIME');
            $sheet->setCellValue('E1', 'TO DATE');
            $sheet->setCellValue('F1', 'TO TIME');
            $sheet->setCellValue('G1', 'IN DATE');
            $sheet->setCellValue('H1', 'IN TIME');
            $sheet->setCellValue('I1', 'OUT DATE');
            $sheet->setCellValue('J1', 'OUT TIME');
            $sheet->setCellValue('K1', 'BREAK IN');
            $sheet->setCellValue('L1', 'BREAK OUT');
            $sheet->setCellValue('M1', 'STATUS');
            $sheet->setCellValue('N1', 'OT');
            $sheet->setCellValue('O1', 'LATE');
            $sheet->setCellValue('P1', 'ED');
            $sheet->setCellValue('Q1', 'SHIFT');

            // Fetch filtered data
            $data_set_query = $this->Db_model->getfilteredData1(
                "SELECT
                ir.EmpNo,
                Emp.Emp_Full_Name,
                ir.FDate,
                ir.TDate,
                ir.InDate,
                ir.OutDate,
                ir.ShiftDay,
                ir.ShType,
                ir.FTime,
                ir.TTime,
                ir.InTime,
                ir.OutTime,
                ir.DayStatus,
                ir.ApprovedExH,
                ir.LateM,
                ir.Lv_T_ID,
                ir.EarlyDepMin,
                br.B_name,
                ir.BreackOutTime1,
                ir.BreackInTime1,
                ir.AfterExH,
                ir.NumShift
            FROM tbl_individual_roster ir
            LEFT JOIN tbl_empmaster Emp ON Emp.EmpNo = ir.EmpNo
            LEFT JOIN tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
            LEFT JOIN tbl_departments dep ON dep.Dep_id = Emp.Dep_id
            INNER JOIN tbl_branches br ON Emp.B_id = br.B_id
            {$filter}
            GROUP BY ir.FDate, Emp.EmpNo
            ORDER BY Emp.Emp_Full_Name, ir.FDate;"
            );

            $data_set = $data_set_query->result_array();

            $rowNum = 2; // Start from second row

            foreach ($data_set as $row) {
                $sheet->setCellValue('A' . $rowNum, $row['EmpNo']);
                $sheet->setCellValue('B' . $rowNum, $row['Emp_Full_Name']);
                $sheet->setCellValue('C' . $rowNum, $row['FDate']);
                $sheet->setCellValue('D' . $rowNum, $row['FTime']);
                $sheet->setCellValue('E' . $rowNum, $row['TDate']);
                $sheet->setCellValue('F' . $rowNum, $row['TTime']);
                $sheet->setCellValue('G' . $rowNum, $row['InDate']);
                $sheet->setCellValue('H' . $rowNum, $row['InTime']);
                $sheet->setCellValue('I' . $rowNum, $row['OutDate']);
                $sheet->setCellValue('J' . $rowNum, $row['OutTime']);
                $sheet->setCellValue('K' . $rowNum, $row['BreackInTime1']);
                $sheet->setCellValue('L' . $rowNum, $row['BreackOutTime1']);
                $sheet->setCellValue('M' . $rowNum, $row['DayStatus']);
                $sheet->setCellValue('N' . $rowNum, $row['AfterExH']);
                $sheet->setCellValue('O' . $rowNum, $row['LateM']);
                $sheet->setCellValue('P' . $rowNum, $row['EarlyDepMin']);
                $sheet->setCellValue('Q' . $rowNum, $row['NumShift']);
                $rowNum++;
            }

            // Prepare file for download
            $fileName = 'Attendance_Report_' . date('YmdHis') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        } elseif ($rept_type == "LateRpt") {
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
                    $filter = " where  ir.FDate between '$from_date' and '$to_date' AND ir.InTime > ADDTIME(tbl_shifts.FromTime, SEC_TO_TIME(tbl_emp_group.GracePeriod * 60)) ";
                } else {
                    $filter .= " AND  ir.FDate between '$from_date' and '$to_date' AND ir.InTime > ADDTIME(tbl_shifts.FromTime, SEC_TO_TIME(tbl_emp_group.GracePeriod * 60)) ";
                }
            }
            if (($this->input->post("txt_emp"))) {
                if ($filter == null) {
                    $filter = " where ir.EmpNo =$emp";
                } else {
                    $filter .= " AND ir.EmpNo =$emp";
                }
            }

            if (($this->input->post("txt_emp_name"))) {
                if ($filter == null) {
                    $filter = " where Emp.Emp_Full_Name ='$emp_name'";
                } else {
                    $filter .= " AND Emp.Emp_Full_Name ='$emp_name'";
                }
            }
            if (($this->input->post("cmb_grop"))) {
                if ($filter == null) {
                    $filter = " where Emp.SupGrp_ID =$grop";
                } else {
                    $filter .= " AND Emp.SupGrp_ID =$grop";
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

            $data_set = $this->Db_model->getfilteredData("SELECT
                                                                     ir.EmpNo,
                                                                    Emp.Emp_Full_Name,
                                                                    ir.FDate,
                                                                    ir.ShiftDay,
                                                                    ir.ShType,
                                                                    ir.FTime,
                                                                    ir.TTime,
                                                                    ir.InTime,
                                                                    ir.OutTime,
                                                                    ir.DayStatus,
                                                                    ir.LateM,
                                                                    br.B_name,
                                                                    tbl_emp_group.GracePeriod,
                                                                    tbl_shifts.FromTime,
                                                                    ADDTIME(tbl_shifts.FromTime, SEC_TO_TIME(tbl_emp_group.GracePeriod * 60)) AS TotalShiftTime
                                                                FROM
                                                                tbl_individual_roster ir
                                                                        LEFT JOIN
                                                                    tbl_empmaster Emp ON Emp.EmpNo = ir.EmpNo
                                                                        LEFT JOIN
                                                                    tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
                                                                        LEFT JOIN
                                                                    tbl_departments dep ON dep.Dep_id = Emp.Dep_id
                                                                    inner join
                                                                    tbl_branches br on Emp.B_id = br.B_id
                                                                    inner join
                                                                    tbl_shifts on ir.ShiftCode = tbl_shifts.ShiftCode
                                                                    inner join
                                                                    tbl_emp_group on tbl_emp_group.Grp_ID = Emp.Grp_ID


                                                                    {$filter} AND STATUS='1' AND Emp.EmpNo != '00009000' order by Emp.Emp_Full_Name,ir.FDate;")->result_array();
            // $data_set = $this->Db_model->getfilteredData1("SELECT ir.EmpNo, Emp.Emp_Full_Name, ir.FDate, ir.InTime, ir.LateM, tbl_shifts.FromTime, tbl_emp_group.GracePeriod, ADDTIME(tbl_shifts.FromTime, SEC_TO_TIME(tbl_emp_group.GracePeriod * 60)) AS TotalShiftTime FROM tbl_individual_roster ir LEFT JOIN tbl_empmaster Emp ON Emp.EmpNo = ir.EmpNo INNER JOIN tbl_shifts ON ir.ShiftCode = tbl_shifts.ShiftCode INNER JOIN tbl_emp_group ON Emp.Grp_ID = tbl_emp_group.Grp_ID WHERE ir.InTime > ADDTIME(tbl_shifts.FromTime, SEC_TO_TIME(tbl_emp_group.GracePeriod * 60))")->result_array();

            $sheetHeaders = ['EmpNo', 'Name', 'Date', 'In Time', 'Late Minutes', 'Grace Time', 'Shift Time'];
            $mapColumns = fn($row) => [
                $row['EmpNo'],
                $row['Emp_Full_Name'],
                $row['FDate'],
                $row['InTime'],
                $row['LateM'],
                $row['GracePeriod'],
                $row['TotalShiftTime']
            ];

        } elseif ($rept_type == "MissRpt") {
            $data_set = $this->Db_model->getfilteredData1("SELECT iro.EmpNo, Emp.Emp_Full_Name, iro.FDate, iro.InTime, iro.OutTime, iro.DayStatus FROM tbl_individual_roster iro LEFT JOIN tbl_empmaster Emp ON Emp.EmpNo = iro.EmpNo WHERE iro.DayStatus = 'MS'")->result_array();

            $sheetHeaders = ['EmpNo', 'Name', 'Date', 'In Time', 'Out Time', 'Status'];
            $mapColumns = fn($row) => [
                $row['EmpNo'],
                $row['Emp_Full_Name'],
                $row['FDate'],
                $row['InTime'],
                $row['OutTime'],
                $row['DayStatus']
            ];

        } elseif ($rept_type == "LvRpt") {
            $data_set = $this->Db_model->getfilteredData1("SELECT tbl_empmaster.EmpNo, tbl_empmaster.Emp_Full_Name, tbl_leave_types.leave_name, tbl_leave_entry.Leave_Date, tbl_leave_entry.Leave_Count, tbl_leave_entry.Reason, approved_emp.Emp_Full_Name AS Approved_By FROM tbl_leave_entry INNER JOIN tbl_leave_types ON tbl_leave_types.Lv_T_ID = tbl_leave_entry.Lv_T_ID INNER JOIN tbl_empmaster ON tbl_empmaster.EmpNo = tbl_leave_entry.EmpNo INNER JOIN tbl_empmaster AS approved_emp ON tbl_leave_entry.Approved_by = approved_emp.EmpNo WHERE tbl_leave_entry.Is_Cancel = 0")->result_array();

            $sheetHeaders = ['EmpNo', 'Name', 'Leave Date', 'Leave Type', 'Count', 'Reason', 'Approved By'];
            $mapColumns = fn($row) => [
                $row['EmpNo'],
                $row['Emp_Full_Name'],
                $row['Leave_Date'],
                $row['leave_name'],
                $row['Leave_Count'],
                $row['Reason'],
                $row['Approved_By']
            ];

        } elseif ($rept_type == "SlRpt") {
            $data_set = $this->Db_model->getfilteredData1("SELECT Emp.EmpNo, Emp.Emp_Full_Name, sl.Date, sl.Reason FROM tbl_shortlive sl LEFT JOIN tbl_empmaster Emp ON Emp.EmpNo = sl.EmpNo WHERE sl.Is_Approve = 1")->result_array();

            $sheetHeaders = ['EmpNo', 'Name', 'Date', 'Reason'];
            $mapColumns = fn($row) => [
                $row['EmpNo'],
                $row['Emp_Full_Name'],
                $row['Date'],
                $row['Reason']
            ];

        } elseif ($rept_type == "MonthRpt") {
            $data_set = $this->Db_model->getfilteredData1("SELECT ir.EmpNo, Emp.Emp_Full_Name, ir.FDate, ir.DayStatus FROM tbl_individual_roster ir LEFT JOIN tbl_empmaster Emp ON Emp.EmpNo = ir.EmpNo GROUP BY ir.FDate, Emp.EmpNo")->result_array();

            $sheetHeaders = ['EmpNo', 'Name', 'Date', 'Status'];
            $mapColumns = fn($row) => [
                $row['EmpNo'],
                $row['Emp_Full_Name'],
                $row['FDate'],
                $row['DayStatus']
            ];

        } elseif ($rept_type == "PrRpt") {
            $data_set = $this->Db_model->getfilteredData1("SELECT ir.EmpNo, Emp.Emp_Full_Name, ir.FDate, ir.InTime, ir.OutTime FROM tbl_individual_roster ir LEFT JOIN tbl_empmaster Emp ON Emp.EmpNo = ir.EmpNo WHERE ir.DayStatus = 'PR'")->result_array();

            $sheetHeaders = ['EmpNo', 'Name', 'Date', 'In Time', 'Out Time'];
            $mapColumns = fn($row) => [
                $row['EmpNo'],
                $row['Emp_Full_Name'],
                $row['FDate'],
                $row['InTime'],
                $row['OutTime']
            ];

        } elseif ($rept_type == "AbRpt") {
            $data_set = $this->Db_model->getfilteredData1("SELECT ir.EmpNo, Emp.Emp_Full_Name, ir.FDate, ir.DayStatus FROM tbl_individual_roster ir LEFT JOIN tbl_empmaster Emp ON Emp.EmpNo = ir.EmpNo WHERE ir.DayStatus = 'AB'")->result_array();

            $sheetHeaders = ['EmpNo', 'Name', 'Date', 'Status'];
            $mapColumns = fn($row) => [
                $row['EmpNo'],
                $row['Emp_Full_Name'],
                $row['FDate'],
                $row['DayStatus']
            ];

        } elseif ($rept_type == "LvSumRpt") {
            $data_set = $this->Db_model->getfilteredData1("SELECT tbl_empmaster.EmpNo, tbl_empmaster.Emp_Full_Name, tbl_leave_types.leave_name, tbl_leave_allocation.Year, tbl_leave_allocation.Entitle, tbl_leave_allocation.Used, tbl_leave_allocation.Balance FROM tbl_leave_allocation INNER JOIN tbl_empmaster ON tbl_empmaster.EmpNo = tbl_leave_allocation.EmpNo INNER JOIN tbl_leave_types ON tbl_leave_types.Lv_T_ID = tbl_leave_allocation.Lv_T_ID")->result_array();

            $sheetHeaders = ['EmpNo', 'Name', 'Year', 'Leave Type', 'Entitled', 'Used', 'Balance'];
            $mapColumns = fn($row) => [
                $row['EmpNo'],
                $row['Emp_Full_Name'],
                $row['Year'],
                $row['leave_name'],
                $row['Entitle'],
                $row['Used'],
                $row['Balance']
            ];

        } elseif ($rept_type == "BrkRpt") {
            $data_set = $this->Db_model->getfilteredData1("SELECT Emp.EmpNo, Emp.Emp_Full_Name, ir.AttDate, ir.AttTime, ir.Status FROM tbl_u_attendancedata ir LEFT JOIN tbl_empmaster Emp ON Emp.EmpNo = ir.Enroll_No")->result_array();

            $sheetHeaders = ['EmpNo', 'Name', 'AttDate', 'AttTime', 'Status'];
            $mapColumns = fn($row) => [
                $row['EmpNo'],
                $row['Emp_Full_Name'],
                $row['AttDate'],
                $row['AttTime'],
                $row['Status']
            ];
        }

        // Generate Excel
        if (!empty($data_set)) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            foreach (range('A', chr(65 + count($sheetHeaders) - 1)) as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Header row
            foreach ($sheetHeaders as $index => $title) {
                $sheet->setCellValueByColumnAndRow($index + 1, 1, $title);
            }

            $rowNum = 2;
            foreach ($data_set as $row) {
                $columns = $mapColumns($row);
                foreach ($columns as $i => $val) {
                    $sheet->setCellValueByColumnAndRow($i + 1, $rowNum, $val);
                }
                $rowNum++;
            }

            $fileName = $rept_type . '_Report_' . date('YmdHis') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"$fileName\"");
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        }

        // redirect('Reports/Attendance/Report_Attendance_In_Out_Sum'); // Redirect after download
    }

    public function get_auto_emp_name()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->Db_model->get_auto_emp_name($q);
        }
    }

    public function get_auto_emp_no()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->Db_model->get_auto_emp_no($q);
        }
    }

    public function exportToExcel1()
    {

        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');

        $emp = $this->input->post("txt_emp");
        $emp_name = $this->input->post("txt_emp_name");
        $desig = $this->input->post("cmb_desig");
        $dept = $this->input->post("cmb_dep");
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

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        foreach (range('A', 'F') as $coulumID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($coulumID)->setAutosize(true);

        }
        $sheet->setCellValue('A1', 'EmpNo');
        $sheet->setCellValue('B1', 'Emp_Full_Name');
        $sheet->setCellValue('C1', 'Date');
        $sheet->setCellValue('D1', 'IN TIME');
        $sheet->setCellValue('E1', 'OUT TIME');
        $sheet->setCellValue('F1', 'ST');
        $sheet->setCellValue('G1', 'LATE');
        // $sheet->setCellValue('H1','Working (H:M:S)');

        $data_set_query = $this->Db_model->getfilteredData1("SELECT
        ir.EmpNo,
        Emp.Emp_Full_Name,
        ir.FDate,
        ir.ShiftDay,
        ir.ShType,
        ir.FTime,
        ir.TTime,
        ir.InTime,
        ir.OutTime,
        ir.DayStatus,
        ir.ApprovedExH,
        ir.NetLateM,
        br.B_name
    FROM
        tbl_individual_roster ir
        LEFT JOIN tbl_empmaster Emp ON Emp.EmpNo = ir.EmpNo
        LEFT JOIN tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
        LEFT JOIN tbl_departments dep ON dep.Dep_id = Emp.Dep_id
        INNER JOIN tbl_branches br ON Emp.B_id = br.B_id
        {$filter} GROUP BY ir.FDate, Emp.EmpNo ORDER BY Emp.Emp_Full_Name, ir.FDate;");

        $data_set = $data_set_query->result_array();
        $x = 2; //start from row 2
        foreach ($data_set as $row)

        // $outTime = strtotime($row['OutTime']);
        // $inTime = strtotime($row['InTime']);

        // // Calculate the difference in seconds
        // $timeDifferenceInSeconds = $outTime - $inTime;

        // // Convert the time difference back to HH:MM:SS format
        // $timeDifferenceFormatted = gmdate("H:i:s", $timeDifferenceInSeconds);
        {
            $sheet->setCellValue('A' . $x, $row['EmpNo']);
            $sheet->setCellValue('B' . $x, $row['Emp_Full_Name']);
            $sheet->setCellValue('C' . $x, $row['FDate']);
            $sheet->setCellValue('D' . $x, $row['InTime']);
            $sheet->setCellValue('E' . $x, $row['OutTime']);
            $sheet->setCellValue('F' . $x, $row['ShType']);
            $sheet->setCellValue('G' . $x, $row['NetLateM']);
            // $sheet->setCellValue('H'.$x, $timeDifferenceFormatted);

            $x++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'users_details_export2022.xlsx';
        //$writer->save($fileName);  //this is for save in folder

        /* for force download */
        header('Content-Type: appliction/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        $writer->save('php://output');
    }

}
