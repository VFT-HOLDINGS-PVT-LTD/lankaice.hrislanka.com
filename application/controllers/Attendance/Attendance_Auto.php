<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Attendance_Auto extends CI_Controller
{

    // public function __construct()
    // {
    //     parent::__construct();
    //     if (!($this->session->userdata('login_user'))) {
    //         redirect(base_url() . "");
    //     }
    //     /*
    //      * Load Database model
    //      */
    //     $this->load->model('Db_model', '', true);
    // }

    public function autoProcess()
    {

        $this->load->model('Db_model', '', true);

        // 1st
        // update the tbl_autorun_settings table flag (initialize_run 1)
        $data = array("status_flag" => "1");
        $whereArr = array("status_flag_name" => "initialize_run");
        $result = $this->Db_model->updateData('tbl_autorun_settings', $data, $whereArr);

        // check the flage 1 or 0
        $autorunSettings = $this->Db_model->getfilteredData("SELECT * FROM tbl_autorun_settings WHERE status_flag_name='initialize_run'");
        if ($autorunSettings[0]->status_flag == 1) {
            // function call
            $this->initialize();

            // update the tbl_autorun_settings table flag (initialize_run 0)
            $data = array("status_flag" => "0");
            $whereArr = array("status_flag_name" => "initialize_run");
            $result = $this->Db_model->updateData('tbl_autorun_settings', $data, $whereArr);
        }

        // 2nd
        $autorunSettings2 = $this->Db_model->getfilteredData("SELECT * FROM tbl_autorun_settings WHERE status_flag_name='initialize_run'");
        if ($autorunSettings2[0]->status_flag == 0) {
            // update the tbl_autorun_settings table flag (shift_allocation_run 1)
            $data = array("status_flag" => "1");
            $whereArr = array("status_flag_name" => "shift_allocation_run");
            $result = $this->Db_model->updateData('tbl_autorun_settings', $data, $whereArr);

            $autorunSettings3 = $this->Db_model->getfilteredData("SELECT * FROM tbl_autorun_settings WHERE status_flag_name='shift_allocation_run'");
            if ($autorunSettings3[0]->status_flag == 1) {
                // function call
                $this->shift_allocation();

                // update the tbl_autorun_settings table flag (shift_allocation_run 0)
                $data = array("status_flag" => "0");
                $whereArr = array("status_flag_name" => "shift_allocation_run");
                $result = $this->Db_model->updateData('tbl_autorun_settings', $data, $whereArr);
            }
        }
        
         $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'mail.vfthris.com',
            'smtp_user' => 'mail@vfthris.com',
            'smtp_pass' => 'Wlm7?Ux7g[s1',
            'smtp_port' => 587,
            'smtp_crypto' => 'tls', // Add this for security
            'charset' => 'utf-8',
            'mailtype' => 'html',
            'newline' => "\r\n",
            'wordwrap' => TRUE // Optional, but useful to wrap long lines
        );

        $this->load->library('email', $config); // Pass config during load
        $this->email->initialize($config);

        $this->email->from('mail@vfthris.com', 'Your Name');
        $this->email->to('pasinduramesh277@gmail.com');
        $this->email->subject('shift ini & shift all');
        $this->email->message("This is a test email for shift ini & shift all");

        if ($this->email->send()) {
            echo 'Email sent successfully!';
        } else {
            echo 'Failed to send email.';
            echo $this->email->print_debugger(); // For debugging any errors
        }
    }

    public function initialize()
    {
        $this->load->model('Db_model', '', true);

        $cat2 = 1;
        $string = "SELECT EmpNo FROM tbl_empmaster WHERE Cmp_ID='$cat2' and Status = 1";
        $EmpData = $this->Db_model->getfilteredData($string);

        date_default_timezone_set('Asia/Colombo'); // Set time zone to Colombo, Sri Lanka
        $from_date = date('Y-m-01'); // First day of the current month
        $to_date = date('Y-m-t'); // Last day of the current month

        $Count = count($EmpData);

        for ($i = 0; $i < $Count; $i++) {

            $EmpN = $EmpData[$i]->EmpNo;

            $this->Db_model->getfilteredDelete("DELETE FROM tbl_individual_roster WHERE FDate between '$from_date' and '$to_date' and EmpNo= $EmpN");

            $this->Db_model->getfilteredDelete("DELETE FROM tbl_ot_d WHERE OTDate between '$from_date' and '$to_date' and EmpNo= $EmpN");
        }
        $data = array("status_flag" => "0");
        $whereArr = array("status_flag_name" => "initialize_run");
        $result = $this->Db_model->updateData('tbl_autorun_settings', $data, $whereArr);

        // $this->session->set_flashdata('success_message', 'Attendance Initialize successfully');
        // redirect(base_url() . "Attendance/Attendance_Initialize");

    }

    public function shift_allocation()
    {
        $this->load->model('Db_model', '', true);

        $cat2 = 1;
        $string = "SELECT EmpNo FROM tbl_empmaster WHERE Cmp_ID='$cat2' and Status = 1 and Is_Att_process=1";
        $EmpData = $this->Db_model->getfilteredData($string);

        $roster = "RS0001";

        date_default_timezone_set('Asia/Colombo'); // Set time zone to Colombo, Sri Lanka
        $from_date = date('Y-m-01'); // First day of the current month
        $to_date = date('Y-m-t'); // Last day of the current month

        $d1 = new DateTime($from_date);
        $d2 = new DateTime($to_date);

        $interval = $d2->diff($d1)->days;

        for ($x = 0; $x <= $interval; $x++) {

            /*
             * Get Day Type in weekly roster
             */
            $Current_date = "";
            $num = date("N", strtotime($from_date));

            switch ($num) {

                //**********If $Num = 1 Day is Monday
                case 1:
                    $Current_date = "MON";
                    break;
                case 2:
                    $Current_date = "TUE";
                    break;
                case 3:
                    $Current_date = "WED";
                    break;
                case 4:
                    $Current_date = "THU";
                    break;
                case 5:
                    $Current_date = "FRI";
                    break;
                case 6:
                    $Current_date = "SAT";
                    break;
                case 7:
                    $Current_date = "SUN";
                    break;
                default:
                    break;
            }

            /*
             * Get Holiday Days
             */

            $var = $from_date;
            $date = str_replace('/', '-', $var);
            $from_date = date('Y-m-d', strtotime($date));

            $Holiday = $this->Db_model->getfilteredData("select count(Hdate) as HasRow from tbl_holidays where Hdate = '$from_date' ");
            $year = date("Y");

            $ros['i'] = $this->Db_model->getfilteredData("SELECT
                                                                tr.ShiftCode,
                                                                tr.DayName,
                                                                tr.ShiftType,
                                                                ts.FromTime,
                                                                ts.ToTime,
                                                                ts.DayType,
                                                                ts.ShiftGap,
                                                                ts.NextDay
                                                            FROM
                                                                tbl_rosterpatternweeklydtl tr
                                                                    INNER JOIN
                                                                tbl_shifts ts ON ts.ShiftCode = tr.ShiftCode
                                                            WHERE
                                                                tr.RosterCode = '$roster'
                                                                    AND tr.DayName = '$Current_date'");

            $ShiftCode = $ros['i'][0]->ShiftCode;
            //Week Days  MON | TUE
            $DayName = $ros['i'][0]->DayName;
            $FromTime = $ros['i'][0]->FromTime;
            $ToTime = $ros['i'][0]->ToTime;
            //Shift Type DU | EX
            $ShiftType = $ros['i'][0]->ShiftType;
            $ShiftGap = $ros['i'][0]->ShiftGap;
            $DayType = $ros['i'][0]->DayType;
            $Next_Day = $ros['i'][0]->NextDay;

            $DayStatus = 'AB';
            if ($ShiftType == "EX") {
                $NoPay = 0;
                $DayStatus = 'EX';
            } else if ($Holiday[0]->HasRow == 1) {
                $ShiftType = 'EX';
                //**** Day status is Holiday | Late | Early Departure | AB | PR ******
                $DayStatus = 'HD';
                $NoPay = 0;
            } else {
                $NoPay = 1;
            }

            $Count = count($EmpData);

            for ($i = 0; $i < $Count; $i++) {

                $EmpGrp = $EmpData[$i]->EmpNo;

                $Group_Data = $this->Db_model->getfilteredData("SELECT Grp_ID from tbl_empmaster where EmpNo = $EmpGrp");
                $GroupID = $Group_Data[0]->Grp_ID;

                $Group_Grace = $this->Db_model->getfilteredData("SELECT GracePeriod FROM tbl_emp_group where Grp_ID = $GroupID");
                $GracePeriod = $Group_Grace[0]->GracePeriod;

                if ($Next_Day == 1) {
//                    $to_date = strtotime($from_date . '+1 day');
                    $to_date_sh = date('Y-m-d H:i:s', strtotime($from_date . ' +1 day'));
                } else {
                    $to_date_sh = $from_date;
                }

                $Em = $EmpData[$i]->EmpNo;
                $dataArray = array(
                    'RYear' => $year,
                    'EmpNo' => $EmpData[$i]->EmpNo,
                    'ShiftCode' => $ShiftCode,
                    'ShiftDay' => $DayName,
                    'Day_Type' => $DayType,
                    'ShiftIndex' => 1,
                    'FDate' => $from_date,
                    'FTime' => $FromTime,
                    'TDate' => $to_date_sh,
                    'TTime' => $ToTime,
                    'ShType' => $ShiftType,
                    'DayStatus' => $DayStatus,
                    'GapHrs' => $ShiftGap,
                    'GracePrd' => $GracePeriod,
                    'nopay' => $NoPay,
                );

                /*
                 * Check If Allocated Shift in Individual Roster Table
                 */
                $HasR = $this->Db_model->getfilteredData("SELECT
                                                        COUNT(EmpNo) AS HasRow
                                                    FROM
                                                        tbl_individual_roster
                                                    WHERE
                                                        EmpNo = '$Em' AND FDate = '$from_date' ");

                if ($HasR[0]->HasRow == 1) {
                    $this->session->set_flashdata('error_message', 'Already Shift Allocated');
                } else {
                    $this->Db_model->insertData("tbl_individual_roster", $dataArray);
                    $this->session->set_flashdata('success_message', 'Shift Allocation Processed successfully');
                }
            }

            $from_date = date("Y-m-d", strtotime("+1 day", strtotime($from_date)));
        }
        // redirect('/Attendance/Shift_Allocation');
    }
}
