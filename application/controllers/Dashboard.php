<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!($this->session->userdata('login_user'))) {
            redirect(base_url() . "");
        }
        $this->load->model('Db_model', '', TRUE);
    }

    public function index() {

        date_default_timezone_set('Asia/Colombo');
        $now = new DateTime();
        $Date = $now->format('Y-m-d'); 
        $month_day = $now->format('m-d');


        $data['title'] = "Dashboard | HRM System";
        $data['count'] = $this->Db_model->getfilteredData('select count(EmpNo) as count_emp from tbl_empmaster');
        $data['Bdays'] = $this->Db_model->getfilteredData("SELECT Emp_Full_Name, Tel_mobile, tbl_branches.B_name FROM tbl_empmaster INNER JOIN tbl_branches ON tbl_branches.B_id = tbl_empmaster.B_id WHERE DATE_FORMAT(DOB, '%m-%d') = '$month_day'");
        // $data['Bdays_count'] = $this->Db_model->getfilteredData("SELECT COUNT(Emp_Full_Name) as count FROM tbl_empmaster WHERE DATE_FORMAT(DOB, '%m-%d') = '$month_day'");
        // $data['Bdays_count'] = $data['Bdays_count'][0]->count;

        //**** Employee department chart data
        $data['sdata'] = $this->Db_model->getfilteredData("SELECT 
                                                            COUNT(EmpNo)as EmpCount , Dep_Name
                                                        FROM
                                                            tbl_empmaster
                                                                INNER JOIN
                                                            tbl_departments ON tbl_empmaster.Dep_ID = tbl_departments.Dep_ID
                                                        group by tbl_departments.Dep_ID");

$date1 = new DateTime();
$timestamp1 = date_format($date1, 'Y-m-d');

//**** Employee day present (PR) count
$data['data3']  = $this->Db_model->getfilteredData("select count(EmpNo) as allcountemp from tbl_empmaster ");

// $data['data3']; die;
$data['data2'] = $this->Db_model->getfilteredData("SELECT COUNT(DISTINCT Enroll_No) AS count FROM tbl_u_attendancedata WHERE AttDate = '$timestamp1'");
$data['data1'] =  intval($data['data3'][0]->allcountemp)-intval($data['data2'][0]->count);
// $data['data4']='';
foreach ($data['data2'] as $sales) { 
    $data['data4'] = intval($sales->count);

} 
        
//         $data['sdata_gender'] = $this->Db_model->getfilteredData("SELECT
//             COUNT(*) AS total_count,
//     COUNT(CASE WHEN Gender = 'Male' THEN 1 END) AS male_count,
//     COUNT(CASE WHEN Gender = 'Female' THEN 1 END) AS female_count
// FROM
//     tbl_empmaster where Status=1");
        
     

        //**** Employee day present (PR) count
        $data['today_c'] = $this->Db_model->getfilteredData("select count(ID_Roster) as TodayCount from tbl_individual_roster where FDate = curdate() and DayStatus='PR' ");



        $currentUser = $this->session->userdata('login_user');
        $Emp = $currentUser[0]->EmpNo;
        
         $Year = date('Y');

        $data['data_leave'] = $this->Db_model->getfilteredData("SELECT 
                                                                        lv_typ.Lv_T_ID,
                                                                        lv_typ.leave_name,
                                                                        lv_al.Balance
                                                                    FROM
                                                                        tbl_leave_allocation lv_al
                                                                        right join
                                                                        tbl_leave_types lv_typ on lv_al.Lv_T_ID = lv_typ.Lv_T_ID
                                                                        where EmpNo='$Emp' and lv_al.Year = '$Year';
                                                                    ");
        
//        var_dump($data['data_leave'] );die;



        $this->load->view('Dashboard/index', $data);
    }

    public function get_attendance_data(){
        $department = $this->input->post('department');
        $date = date('Y-m-d');

        if ($department === 'all') {
            // All departments
            $total = $this->Db_model->getfilteredData("SELECT COUNT(EmpNo) as total FROM tbl_empmaster WHERE Status = 1");
            $present = $this->Db_model->getfilteredData("SELECT COUNT(DISTINCT Enroll_No) as count
                FROM tbl_u_attendancedata
                WHERE AttDate = '$date'");
        } else {
            // Specific department
            $total = $this->Db_model->getfilteredData("SELECT COUNT(EmpNo) as total 
                FROM tbl_empmaster em
                INNER JOIN tbl_departments d ON em.Dep_ID = d.Dep_ID
                WHERE d.Dep_Name = '$department'");

            $present = $this->Db_model->getfilteredData("SELECT COUNT(DISTINCT ua.Enroll_No) as count 
                FROM tbl_u_attendancedata ua
                INNER JOIN tbl_empmaster em ON ua.Enroll_No = em.Enroll_No
                INNER JOIN tbl_departments d ON em.Dep_ID = d.Dep_ID
                WHERE em.Status = 1 AND ua.AttDate = '$date' AND d.Dep_Name = '$department'");
        }

        $attended = intval($present[0]->count);
        $absent = intval($total[0]->total) - $attended;

        echo json_encode([
            'attended' => $attended,
            'absent' => $absent,
            'title' => ucfirst($department) . ' Department Attendance'
        ]);
    }

    public function get_employee_data() {
        $department = $this->input->post('department');
        log_message('error', 'Department selected: ' . $department);
    
        if ($department === 'all') {
            // Query for all departments
            $result = $this->Db_model->getfilteredData("
                SELECT
                    COUNT(*) AS total_count,
                    COUNT(CASE WHEN Gender = 'Male' THEN 1 END) AS male_count,
                    COUNT(CASE WHEN Gender = 'Female' THEN 1 END) AS female_count
                FROM tbl_empmaster
                WHERE Status = 1
            ");
        } else {
            // Escape the input to prevent SQL injection
            $escaped_dep = $this->db->escape($department);
    
            // Query for specific department
            $sql = "
                SELECT
                    COUNT(*) AS total_count,
                    COUNT(CASE WHEN Gender = 'Male' THEN 1 END) AS male_count,
                    COUNT(CASE WHEN Gender = 'Female' THEN 1 END) AS female_count
                FROM tbl_empmaster em
                INNER JOIN tbl_departments d ON em.Dep_ID = d.Dep_ID
                WHERE d.Dep_Name = $escaped_dep AND em.Status = 1
            ";
    
            $result = $this->Db_model->getfilteredData($sql);
        }
    
        echo json_encode([
            'total' => intval($result[0]->total_count),
            'male' => intval($result[0]->male_count),
            'female' => intval($result[0]->female_count)
        ]);
    }
    
    
}
