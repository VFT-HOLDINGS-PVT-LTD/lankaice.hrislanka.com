<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Attendance_Process_New extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (! ($this->session->userdata('login_user'))) {
            redirect(base_url() . "");
        }
        /*
         * Load Database model
         */
        $this->load->model('Db_model', '', true);
    }

    /*
     * Index page
     */

    public function index()
    {

        $data['title']             = "Attendance Process | HRM System";
        $data['data_set']          = $this->Db_model->getData('EmpNo,Emp_Full_Name', 'tbl_empmaster');
        $data['data_shift']        = $this->Db_model->getData('ShiftCode,ShiftName', 'tbl_shifts');
        $data['data_roster']       = $this->Db_model->getData('RosterCode,RosterName', 'tbl_rosterpatternweeklyhd');
        $data['autorun_settings']  = $this->Db_model->getfilteredData("SELECT * FROM tbl_autorun_settings WHERE status_flag_name='attendance_process_run'");
        $data['autorun_settings2'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_autorun_settings WHERE status_flag_name='shift_allocation_run'");
        $data['autorun_settings3'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_autorun_settings WHERE status_flag_name='initialize_run'");

        $data['sh_employees'] = $this->Db_model->getfilteredData("SELECT
                                                                    tbl_empmaster.EmpNo
                                                                FROM
                                                                    tbl_empmaster
                                                                        LEFT JOIN
                                                                    tbl_individual_roster ON tbl_individual_roster.EmpNo = tbl_empmaster.EmpNo
                                                                    where tbl_individual_roster.EmpNo is null AND tbl_empmaster.status=1 and Active_process=1");

        $this->load->view('Attendance/Attendance_Process/index', $data);
    }

    public function re_process()
    {
        $data['title']       = "Attendance Process | HRM System";
        $data['data_set']    = $this->Db_model->getData('EmpNo,Emp_Full_Name', 'tbl_empmaster');
        $data['data_shift']  = $this->Db_model->getData('ShiftCode,ShiftName', 'tbl_shifts');
        $data['data_roster'] = $this->Db_model->getData('RosterCode,RosterName', 'tbl_rosterpatternweeklyhd');

        $data['sh_employees'] = $this->Db_model->getfilteredData("SELECT
                                                                    tbl_empmaster.EmpNo
                                                                FROM
                                                                    tbl_empmaster
                                                                        LEFT JOIN
                                                                    tbl_individual_roster ON tbl_individual_roster.EmpNo = tbl_empmaster.EmpNo
                                                                    where tbl_individual_roster.EmpNo is null AND tbl_empmaster.status=1 and Active_process=1");

        $this->load->view('Attendance/Attendance_REProcess/index', $data);
    }

    /*
     * Insert Data
     */

    public function emp_attendance_process()
    {

        date_default_timezone_set('Asia/Colombo');
        /*
         * Get Employee Data
         * Emp no , EPF No, Roster Type, Roster Pattern Code, Status
         */
        //        $dtEmp['EmpData'] = $this->Db_model->getfilteredData("SELECT EmpNo,Enroll_No, EPFNO,RosterCode, Status  FROM  tbl_empmaster where status=1");
        $dtEmp['EmpData'] = $this->Db_model->getfilteredData("select * from tbl_individual_roster where Is_processed = 0");

        $AfterShift = 0;

        if (! empty($dtEmp['EmpData'])) {

            for ($x = 0; $x < count($dtEmp['EmpData']); $x++) {
                $EmpNo = $dtEmp['EmpData'][$x]->EmpNo;

                $FromDate = $dtEmp['EmpData'][$x]->FDate;
                $ToDate   = $dtEmp['EmpData'][$x]->TDate;
                //Check If From date less than to Date
                if ($FromDate <= $ToDate) {
                    $settings = $this->Db_model->getfilteredData("SELECT tbl_setting.Group_id,tbl_setting.Ot_m,tbl_setting.Ot_e,tbl_setting.Ot_d_Late,
                    tbl_setting.Late,tbl_setting.Ed,tbl_setting.Min_time_t_ot_m,tbl_setting.Min_time_t_ot_e,tbl_setting.ST,
                    tbl_setting.late_Grs_prd,tbl_setting.`Round`,tbl_setting.Hd_d_from,tbl_setting.Dot_f_holyday,tbl_setting.Dot_f_offday
                     FROM tbl_setting INNER JOIN tbl_emp_group ON tbl_setting.Group_id = tbl_emp_group.Grp_ID
                     INNER JOIN tbl_empmaster ON tbl_empmaster.Grp_ID = tbl_emp_group.Grp_ID WHERE tbl_empmaster.EmpNo = '$EmpNo'");

                    $emp_shifts = $this->Db_model->getfilteredData("SELECT tbl_shift_config.Emp_No,tbl_shifts.ShiftCode,tbl_shifts.ShiftName,tbl_shifts.FromTime,tbl_shifts.ToTime,
                    tbl_shifts.NextDay,tbl_shifts.DayType,tbl_shifts.FHDSessionEndTime
                    FROM tbl_shift_config INNER JOIN tbl_empmaster ON tbl_shift_config.Emp_No = tbl_empmaster.EmpNo
                    INNER JOIN tbl_shifts ON tbl_shift_config.Shift_Id = tbl_shifts.ShiftCode WHERE tbl_shift_config.Emp_No = '$EmpNo' ORDER BY tbl_shifts.ShiftCode");

                    $query_length = count($emp_shifts);

                    $ApprovedExH = 0;
                    $DayStatus   = "not";
                    $ID_Roster   = '';
                    $InDate      = '';
                    $InTime      = '';
                    $OutDate     = '';
                    $OutTime     = '';

                    $from_date     = '';
                    $from_time     = '';
                    $to_date       = '';
                    $to_time       = '';
                    $Day_Type      = 0;
                    $lateM         = '';
                    $ED            = '';
                    $DayStatus     = '';
                    $AfterShiftWH  = '';
                    $BeforeShift   = '';
                    $DOT           = '';
                    $Late_Status   = 0;
                    $NetLateM      = 0;
                    $Nopay         = 0;
                    $Nopay_Hrs     = 0;
                    $Att_Allowance = 0;

                    $shift_code = null;
                    $found      = true;
                    for ($i = 0; $i < $query_length; $i++) {
                        if ($found) {
                            //shift code eka
                            $shift_code = $emp_shifts[$i]->ShiftCode;
                            //date time ekathukarala hada gannawa
                            $shift_time          = $emp_shifts[$i]->FromTime;
                            $check_time_withdate = $FromDate . " " . $shift_time;
                            //date format ekkt gannawa
                            $check_time_withdate_dateformat = new DateTime($check_time_withdate);
                            //paya 2k adu karanawa
                            $dateTimeMinus2Hours = clone $check_time_withdate_dateformat;
                            $dateTimeMinus2Hours->modify('-2 hours');
                            $check_minus_date = $dateTimeMinus2Hours->format('Y-m-d');
                            $check_minus_time = $dateTimeMinus2Hours->format('H:i:s');
                                                                                        //payak ekathu karanawa
                            $dateTimePlus1Hour = clone $check_time_withdate_dateformat; // Clone to keep original
                            $dateTimePlus1Hour->modify('+4.5 hour');
                            $check_plus_date = $dateTimePlus1Hour->format('Y-m-d');
                            $check_plus_time = $dateTimePlus1Hour->format('H:i:s');

                            // echo $check_minus_date . " " . $check_minus_time . "    /    " . $FromDate . " " . $shift_time . "    /   " . $check_plus_date . " " . $check_plus_time;
                            // echo "<br/>";

                            // Get the CheckIN
                            $dt_in_Records['dt_Records'] = $this->Db_model->getfilteredData("select min(AttTime) as INTime,Enroll_No,AttDate from
                            tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='" . $check_minus_date . "' AND (AttTime BETWEEN '" . $check_minus_time . "' AND '" . $check_plus_time . "') AND Status='0'");
                            $InDate = $dt_in_Records['dt_Records'][0]->AttDate;
                            $InTime = $dt_in_Records['dt_Records'][0]->INTime;
                            if (! empty($InDate)) {
                                $found = false;
                            }
                            if (empty($InDate)) {
                                $shift_code = null;
                            }
                        }
                    }
                    // tbl_individual_roster eken shift details tika gannawa
                    $rosterDetails['shift'] = $this->Db_model->getfilteredData("select `ID_Roster`,`ShType`,`ShiftDay`,`FDate`,`FTime`,`TDate`,`TTime`,`GracePrd`,`HDSession` from tbl_individual_roster where FDate = '$FromDate' AND EmpNo = '$EmpNo' ");
                    $ID_Roster              = $rosterDetails['shift'][0]->ID_Roster;
                    $shift_day              = $rosterDetails['shift'][0]->ShiftDay;
                    $shift_type             = $rosterDetails['shift'][0]->ShType;
                    $from_date              = $rosterDetails['shift'][0]->FDate;
                    //shift code eka hamunata passe ithuru process eka kalana hari//////////////////////////////////ex:- ot / late /ed mekata passe
                    if (! empty($shift_code) || $shift_code != null || $shift_code != '') {
                        //shift eke details gannaww
                        $ShiftDetails['shift'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_shifts WHERE tbl_shifts.ShiftCode = '$shift_code'");
                        $from_time             = $ShiftDetails['shift'][0]->FromTime;
                        $to_time               = $ShiftDetails['shift'][0]->ToTime;
                        $cutofftime            = $ShiftDetails['shift'][0]->FHDSessionEndTime;
                        // $GracePrd = $ShiftDetails['shift'][0]->GracePrd;
                        //shift eke next day thibboth todate eka wenas karanaw
                        if (! empty($ShiftDetails['shift'][0]->NextDay) || $ShiftDetails['shift'][0]->NextDay != 0) {
                            $to_date = date('Y-m-d', strtotime($from_date . ' +1 day'));
                            //out time eka hoyanawa
                            $dt_out_Records['dt_out_Records'] = $this->Db_model->getfilteredData("select max(AttTime) as OutTime,Enroll_No,AttDate from
                            tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='" . $to_date . "' AND Status='1'"); //danata 7 damu passe wenas karamu
                            $OutDate = $dt_out_Records['dt_out_Records'][0]->AttDate;
                            $OutTime = $dt_out_Records['dt_out_Records'][0]->OutTime;
                            $to_date = $dt_out_Records['dt_out_Records'][0]->AttDate;
                        } else {
                            //out time eka hoyanawa
                            $out_time_eka_over_one_hour = new DateTime($InTime);
                            $out_time_eka_over_one_hour->modify('+1 hour');
                            $updated_inTime                   = $out_time_eka_over_one_hour->format('H:i:s');
                            $dt_out_Records['dt_out_Records'] = $this->Db_model->getfilteredData("select max(AttTime) as OutTime,Enroll_No,AttDate from
                            tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='" . $FromDate . "' AND AttTime > '" . $updated_inTime . "' AND Status='1' ");
                            $OutDate = $dt_out_Records['dt_out_Records'][0]->AttDate;
                            $OutTime = $dt_out_Records['dt_out_Records'][0]->OutTime;
                            $to_date = $from_date;
                        }

                        $two_shift_code = '';
                        //out time eka null unoth me shift from time ekatama next day yana shift ekk balanaw
                        if ($OutTime == null || empty($OutTime) || $OutTime == '') {

                            //next day yana shift tika load krnw
                            $next_day_yana_wena_shift = $this->Db_model->getfilteredData("SELECT tbl_shift_config.Emp_No,tbl_shifts.ShiftCode,tbl_shifts.ShiftName,tbl_shifts.FromTime,tbl_shifts.ToTime,
                            tbl_shifts.NextDay,tbl_shifts.DayType,tbl_shifts.FHDSessionEndTime
                            FROM tbl_shift_config INNER JOIN tbl_empmaster ON tbl_shift_config.Emp_No = tbl_empmaster.EmpNo
                            INNER JOIN tbl_shifts ON tbl_shift_config.Shift_Id = tbl_shifts.ShiftCode WHERE tbl_shift_config.Emp_No = '$EmpNo' AND tbl_shifts.NextDay = '1' ORDER BY tbl_shifts.ShiftCode");

                            $two_found        = true;
                            $two_query_length = count($next_day_yana_wena_shift);

                            for ($i = 0; $i < $two_query_length; $i++) {
                                if ($two_found) {
                                    //date time ekathukarala hada gannawa
                                    $two_shift_time          = $next_day_yana_wena_shift[$i]->FromTime;
                                    $two_shift_code          = $next_day_yana_wena_shift[$i]->ShiftCode;
                                    $two_check_time_withdate = $FromDate . " " . $two_shift_time;
                                    //date format ekkt gannawa
                                    $two_check_time_withdate_dateformat = new DateTime($two_check_time_withdate);
                                    //paya 2k adu karanawa
                                    $two_dateTimeMinus2Hours = clone $two_check_time_withdate_dateformat;
                                    $two_dateTimeMinus2Hours->modify('-2 hours');
                                    $two_check_minus_date = $two_dateTimeMinus2Hours->format('Y-m-d');
                                    $two_check_minus_time = $two_dateTimeMinus2Hours->format('H:i:s');
                                                                                                        //payak ekathu karanawa
                                    $two_dateTimePlus1Hour = clone $two_check_time_withdate_dateformat; // Clone to keep original
                                    $two_dateTimePlus1Hour->modify('+4.5 hour');
                                    $two_check_plus_date = $two_dateTimePlus1Hour->format('Y-m-d');
                                    $two_check_plus_time = $two_dateTimePlus1Hour->format('H:i:s');
                                    //two shift eka intime eka athara ekkda balanaw
                                    $timeToCheck = strtotime($InTime);
                                    $startTime   = strtotime($two_check_minus_time);
                                    $endTime     = strtotime($two_check_plus_time);
                                    // if($two_check_minus_time < $InTime && $InTime < $two_check_plus_time){}
                                    if ($timeToCheck >= $startTime && $timeToCheck <= $endTime) {
                                        //out time eka hoyanawa
                                        $two_new_date                     = date('Y-m-d', strtotime($from_date . ' +1 day'));
                                        $dt_out_Records['dt_out_Records'] = $this->Db_model->getfilteredData("select max(AttTime) as OutTime,Enroll_No,AttDate from
                                        tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='" . $two_new_date . "' AND Status='1' ");
                                        $OutDate = $dt_out_Records['dt_out_Records'][0]->AttDate;
                                        $OutTime = $dt_out_Records['dt_out_Records'][0]->OutTime;
                                        $to_date = $dt_out_Records['dt_out_Records'][0]->AttDate;
                                        //shift code eka
                                        $two_found = false;
                                        //dewaniyata hambuna next day yana shift eke out time ekk thiyenawann from time to time maru karanw
                                        if ($two_shift_code != null && $two_shift_code != $shift_code && ! empty($InTime) && ! empty($OutTime)) {
                                            //shift eke details gannaww
                                            $ShiftDetails['shift'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_shifts WHERE tbl_shifts.ShiftCode = '$two_shift_code'");
                                            $from_time             = $ShiftDetails['shift'][0]->FromTime;
                                            $to_time               = $ShiftDetails['shift'][0]->ToTime;
                                            $cutofftime            = $ShiftDetails['shift'][0]->FHDSessionEndTime;
                                        }
                                    }
                                }
                            }
                        }
                        if ($InTime != '' && (($InTime != $OutTime && $InDate == $OutDate) || ($InDate != $OutDate)) && $OutTime != '') {
                            $Nopay     = 0;
                            $DayStatus = 'PR';
                            $Nopay_Hrs = 0;
                            $Day_Type  = 1;
                        }

                        $lateM = 0;
                        // $BeforeShift = 0;
                        $Late_Status   = 0;
                        $NetLateM      = 0;
                        $ED            = 0;
                        $EDF           = 0;
                        $Att_Allowance = 1;
                        $Nopay         = 0;
                        $AfterShiftWH  = 0;
                        $lateM         = 0; //late minutes
                                            // $ED = 0; //ED minutes

                        $iCalcHaffT = 0;

                        // BreakIN/Out ganna thena
                        //  Break IN/OUT
                        $breakIn  = $this->Db_model->getfilteredData("SELECT AttTime AS BreakINTime, AttDate FROM tbl_u_attendancedata WHERE Enroll_No='$EmpNo' AND AttDate='$FromDate' AND Status='3' LIMIT 1");
                        $breakOut = $this->Db_model->getfilteredData("SELECT AttTime AS BreakOutTime, AttDate FROM tbl_u_attendancedata WHERE Enroll_No='$EmpNo' AND AttDate='$FromDate' AND Status='4' LIMIT 1");

                        $BreakInTime  = ! empty($breakIn[0]->BreakINTime) ? $breakIn[0]->BreakINTime : '';
                        $BreakOutTime = ! empty($breakOut[0]->BreakOutTime) ? $breakOut[0]->BreakOutTime : '';

                        // $Day       = $shift->ShType;
                        // $SHFT      = $shift->FTime;
                        // $SHTT      = $shift->TTime;
                        // $Shift_Day = $shift->ShiftDay;
                        // $ShiftType = $shift->ShType;
                        // $ID_Roster = $shift->ID_roster;
                        // $DayType   = $shift->Day_Type;
                        // $GracePrd  = $shift->GracePrd;

                        //ot hadana thana ********
                        $ApprovedExH = 0;
                        $SH_EX_OT    = 0;
                        $icalData    = 0;
                        if ($settings[0]->ST == 1) {
                            if ($settings[0]->Ot_e == 1) {
                                if ($OutTime != '' && $InTime != $OutTime && $InTime != '' && $OutTime != "00:00:00") {

                                    $from_Data = $from_date . " " . $from_time;
                                    $to_Data   = $to_date . " " . $to_time;

                                    // 8-8 labor set eka - start
                                    if ($from_time == '08:00:00' && $to_time == '08:00:00' && $from_date != $to_date) {
                                        $OT_OUT = '00:00:01';
                                        $OT_IN  = '17:00:00';

                                        $date_out = new DateTime("today $OT_OUT");
                                        $date_in  = new DateTime("today $OT_IN");

                                        // Handle case where OT_OUT is after midnight (next day)
                                        if ($date_out < $date_in) {
                                            $date_out->modify('+1 day');
                                        }

                                        $interval = $date_in->diff($date_out);

                                        // Format as hours and minutes only
                                        $OT = $interval->format('%H:%I');

                                        if ($OT >= 0) {
                                            $AfterShiftWH = $OT;
                                        } else {
                                            $AfterShiftWH = 0;
                                        }

                                        // labor set eka next date eke 8n passe work karalanam
                                        if ($to_time < $OutTime) {
                                            if ($to_date == $OutDate) { // dawas =na wenna one
                                                                            // Calculate OT
                                                $from = new DateTime("today $to_time");
                                                $to   = new DateTime("today $OutTime");

                                                $interval = $from->diff($to);
                                                $OT       = $interval->format('%H:%I');

                                                // Add OT to AfterShiftWH
                                                list($h1, $m1) = explode(':', $AfterShiftWH);
                                                list($h2, $m2) = explode(':', $OT);

                                                $totalMinutes = ($h1 * 60 + $m1) + ($h2 * 60 + $m2);

                                                $hours   = floor($totalMinutes / 60);
                                                $minutes = $totalMinutes % 60;

                                                $AfterShiftWH = sprintf('%02d:%02d', $hours, $minutes);
                                            }
                                        }

                                    } // 8-8 labor set eka - end

                                    // 9-9 supervisor set eka - start
                                    if ($from_time == '09:00:00' && $to_time == '09:00:00' && $from_date != $to_date) {
                                        $OT_OUT = '00:00:01';
                                        $OT_IN  = '18:00:00';

                                        $date_out = new DateTime("today $OT_OUT");
                                        $date_in  = new DateTime("today $OT_IN");

                                        // Handle case where OT_OUT is after midnight (next day)
                                        if ($date_out < $date_in) {
                                            $date_out->modify('+1 day');
                                        }

                                        $interval = $date_in->diff($date_out);

                                        // Format as hours and minutes only
                                        $OT = $interval->format('%H:%I');

                                        if ($OT >= 0) {
                                            $AfterShiftWH = $OT;
                                        } else {
                                            $AfterShiftWH = 0;
                                        }

                                        // labor set eka next date eke 8n passe work karalanam
                                        if ($to_time < $OutTime) {
                                            if ($to_date == $OutDate) { // dawas =na wenna one
                                                                            // Calculate OT
                                                $from = new DateTime("today $to_time");
                                                $to   = new DateTime("today $OutTime");

                                                $interval = $from->diff($to);
                                                $OT       = $interval->format('%H:%I');

                                                // Add OT to AfterShiftWH
                                                list($h1, $m1) = explode(':', $AfterShiftWH);
                                                list($h2, $m2) = explode(':', $OT);

                                                $totalMinutes = ($h1 * 60 + $m1) + ($h2 * 60 + $m2);

                                                $hours   = floor($totalMinutes / 60);
                                                $minutes = $totalMinutes % 60;

                                                $AfterShiftWH = sprintf('%02d:%02d', $hours, $minutes);
                                            }
                                        }

                                    } // 9-9 supervisor set eka - end

                                    //min time to ot eka hada gannawa group setting table eken
                                    // $min_time_to_ot = $settings[0]->Min_time_t_ot_e;
                                    // $dateTime       = new DateTime($to_time);
                                    // $dateTime->add(new DateInterval('PT' . $min_time_to_ot . 'M'));
                                    // $shift_evning = $dateTime->format('H:i:s');

                                    // if ($shift_evning < $OutTime) {
                                    //     $fromtime                = $to_date . " " . $to_time;
                                    //     $totime                  = $OutDate . " " . $OutTime;
                                    //     $timestamp1              = strtotime($fromtime);
                                    //     $timestamp2              = strtotime($totime);
                                    //     $time_difference_seconds = ($timestamp2 - $timestamp1);
                                    //     $time_difference_minutes = $time_difference_seconds / 60;
                                    //     $icalData                = round($time_difference_minutes, 2);
                                    // }

                                    // // Out wunma passe OT
                                    // if ($icalData >= 0) {
                                    //     $AfterShiftWH = $icalData;
                                    // } else {
                                    //     $AfterShiftWH = 0;
                                    // }
                                }
                            }
                        } else {
                            if ($OutTime != '' && $InTime != $OutTime && $InTime != '' && $OutTime != "00:00:00") {
                                //shift time eka hada gannawa
                                $SHFT                    = $from_date . " " . $from_time;
                                $SHTT                    = $to_date . " " . $to_time;
                                $timestamp1              = strtotime($SHFT);
                                $timestamp2              = strtotime($SHTT);
                                $Shift_duration_time     = ($timestamp2 - $timestamp1);
                                $time_difference_minutes = $Shift_duration_time / 60;
                                $shift_munites           = round($time_difference_minutes);

                                //shortleave thibboth e time eka adu karala danawa
                                $ShortLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_shortlive WHERE EmpNo = $EmpNo AND tbl_shortlive.Date = '$FromDate' ");
                                if (! empty($ShortLeave[0]->Is_Approve)) {
                                    $shortleave_ftime                   = $ShortLeave[0]->from_time;
                                    $shortleave_ttime                   = $ShortLeave[0]->to_time;
                                    $shortleavetimestamp1               = strtotime($shortleave_ftime);
                                    $shortleavetimestamp2               = strtotime($shortleave_ttime);
                                    $shortleave_duration_time           = ($shortleavetimestamp2 - $shortleavetimestamp1);
                                    $shortleave_time_difference_minutes = $shortleave_duration_time / 60;
                                    $shortleave_munites                 = round($shortleave_time_difference_minutes);
                                    $shift_munites                      = ($shift_munites - $shortleave_munites);
                                }
                                //half day e time eka adu karala danawa
                                $HaffDayaLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry where EmpNo = $EmpNo and Leave_Date = '$FromDate' AND Leave_Count='0.5' AND Is_Approve = '1' ");
                                if (! empty($HaffDayaLeave[0]->Is_Approve)) {

                                    $halfday_fromtime                = $from_date . " " . $cutofftime;
                                    $halfday_totime                  = $from_date . " " . $from_time;
                                    $halfday_timestamp1              = strtotime($halfday_fromtime);
                                    $halfday_timestamp2              = strtotime($halfday_totime);
                                    $halfday_time_difference_seconds = ($halfday_timestamp1 - $halfday_timestamp2);
                                    $halfday_time_difference_minutes = $halfday_time_difference_seconds / 60;
                                    $halfd_late                      = round($halfday_time_difference_minutes);
                                    $DayStatus                       = 'HFD';
                                    $shift_munites                   = ($shift_munites - $halfd_late);
                                }
                                //in out time hada gannawa
                                $In_time_date_config               = $InDate . " " . $InTime;
                                $Out_time_date_config              = $OutDate . " " . $OutTime;
                                $timepunchstamp1                   = strtotime($In_time_date_config);
                                $timepunchstamp2                   = strtotime($Out_time_date_config);
                                $In_out_time_duration              = ($timepunchstamp2 - $timepunchstamp1);
                                $time_difference_inouttime_minutes = $In_out_time_duration / 60;
                                $inout_munit                       = round($time_difference_inouttime_minutes);
                                $check_ot_or_late                  = ($inout_munit - $shift_munites);
                                if ($check_ot_or_late > 0) {
                                    $AfterShiftWH = $check_ot_or_late;
                                }
                            }
                        }

                        //Late hadana thana ********
                        $iCalclate = 0;
                        $iCalc     = 0;
                        if ($settings[0]->ST == 1) {
                            if ($settings[0]->Late == 1) {
                                if ($InTime != '' && $InTime != $OutTime || $OutTime != '') {

                                    $late_grass_period = $settings[0]->late_Grs_prd;
                                    $fromData          = $from_date . " " . $from_time;
                                    $toData            = $InDate . " " . $InTime;

                                    $timestamp1 = strtotime($fromData);
                                    $timestamp2 = strtotime($toData);

                                    $diff_seconds = $timestamp2 - $timestamp1;

                                    $minutes = floor($diff_seconds / 60);
                                    $seconds = $diff_seconds % 60;

                                                                       // Convert seconds to decimal (e.g., 34 seconds becomes 0.34)
                                    $decimal_seconds = $seconds / 100; // Note: this just appends the seconds after decimal point

                                    $late      = $minutes + $decimal_seconds;
                                    $iCalclate = number_format($late, 2);
                                    $lateM     = $iCalclate;
                                    // kalin gihhilanm haff day ekak thiynwda balanna
                                    $HaffDayaLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry where EmpNo = $EmpNo and Leave_Date = '$FromDate' AND Leave_Count='0.5' AND Is_Approve = '1' ");
                                    if (! empty($HaffDayaLeave[0]->Is_Approve)) {
                                        if ($cutofftime != '00:00:00') {
                                            $fromtime                = $from_date . " " . $cutofftime;
                                            $totime                  = $InDate . " " . $InTime;
                                            $timestamp1              = strtotime($fromtime);
                                            $timestamp2              = strtotime($totime);
                                            $time_difference_seconds = ($timestamp2 - $timestamp1);
                                            $time_difference_minutes = $time_difference_seconds / 60;
                                            $iCalc                   = round($time_difference_minutes);
                                            $DayStatus               = 'HFD';
                                        }
                                        if ($iCalc <= 0) {
                                            // $iCalcHaff = 0;
                                            $lateM = 0;
                                        } else {
                                            $lateM = $iCalc;
                                        }
                                    }
                                    $ShortLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_shortlive WHERE EmpNo = $EmpNo AND tbl_shortlive.Date = '$FromDate' ");
                                    if (! empty($ShortLeave[0]->Is_Approve)) {
                                        // Morning In Time ekata kalin short leave thiywam
                                        $SHFtime      = $ShortLeave[0]->from_time;
                                        $SHTtime      = $ShortLeave[0]->to_time;
                                        $InTimeSrt    = strtotime($InTime);
                                        $SHToTimeSrt  = strtotime($SHTtime);
                                        $iCalcShortLT = round(($InTimeSrt - $SHToTimeSrt) / 60);
                                        if ($SHFtime <= $fromtime) {
                                            if ($iCalcShortLT <= 0) {
                                                // welawta ewilla
                                                $lateM       = 0;
                                                $Late_Status = 0;
                                                $DayStatus   = 'SL';
                                            } else {
                                                // welatwa ewilla ne(short leave ektath passe late /haffDay ne )
                                                $lateM     = $iCalcShortLT;
                                                $DayStatus = 'SL';
                                            }
                                        }
                                        // Get the BreakkIN
                                        $dt_Breakin_Records['dt_Records'] = $this->Db_model->getfilteredData("select min(AttTime) as INTime,Enroll_No,AttDate from tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='" . $FromDate . "' AND Status='3' ");
                                        $BreakInRecords                   = $dt_Breakin_Records['dt_Records'][0]->AttDate;
                                        $BreakInDate                      = $dt_Breakin_Records['dt_Records'][0]->AttDate;
                                        $BreakInTime                      = $dt_Breakin_Records['dt_Records'][0]->INTime;
                                        $BreakInRec                       = 1;

                                        // Get the BreakOut
                                        $dt_Breakout_Records['dt_out_Records'] = $this->Db_model->getfilteredData("select max(AttTime) as OutTime,Enroll_No,AttDate from tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='" . $FromDate . "' AND Status='4' ");
                                        $BreakOutDate                          = $dt_Breakout_Records['dt_out_Records'][0]->AttDate;
                                        $BreakOutTime                          = $dt_Breakout_Records['dt_out_Records'][0]->OutTime;
                                        $BreakOutRec                           = 0;
                                        $BreakOutRecords                       = $dt_Breakout_Records['dt_out_Records'][0]->AttDate;
                                        // // ShortLeave thani eka [(After)atharameda]
                                        if ($BreakInTime != null && $BreakOutTime != null) {
                                            $BreakInTime  = $dt_Breakin_Records['dt_Records'][0]->INTime;
                                            $BreakOutTime = $dt_Breakout_Records['dt_out_Records'][0]->OutTime;
                                            //Late(Short)
                                            $SHFtime          = $ShortLeave[0]->from_time;
                                            $SHTtime          = $ShortLeave[0]->to_time;
                                            $BreakOutTimeSrt  = strtotime($BreakOutTime);
                                            $SHToTimeSrt      = strtotime($SHTtime);
                                            $iCalcShortLTIntv = round(($BreakOutTimeSrt - $SHToTimeSrt) / 60);
                                            $DayStatus        = 'SL';
                                            if ($iCalcShortLTIntv <= 0) {
                                                // welawta ewilla
                                            } else if ($iCalcShortLTIntv >= 0) {
                                                // welatwa ewilla ne(short leave & haffDay ektath passe late)
                                                $lateM = $iCalcHaffT + $iCalcShortLTIntv;
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($OutTime != '' && $InTime != $OutTime && $InTime != '' && $OutTime != "00:00:00") {
                                //shift time eka hada gannawa

                                $SHFT                    = $from_date . " " . $from_time;
                                $SHTT                    = $to_date . " " . $to_time;
                                $timestamp1              = strtotime($SHFT);
                                $timestamp2              = strtotime($SHTT);
                                $Shift_duration_time     = ($timestamp2 - $timestamp1);
                                $time_difference_minutes = $Shift_duration_time / 60;
                                $shift_munites           = round($time_difference_minutes);

                                //shortleave thibboth e time eka adu karala danawa
                                $ShortLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_shortlive WHERE EmpNo = $EmpNo AND tbl_shortlive.Date = '$FromDate' ");
                                if (! empty($ShortLeave[0]->Is_Approve)) {
                                    $shortleave_ftime                   = $ShortLeave[0]->from_time;
                                    $shortleave_ttime                   = $ShortLeave[0]->to_time;
                                    $shortleavetimestamp1               = strtotime($shortleave_ftime);
                                    $shortleavetimestamp2               = strtotime($shortleave_ttime);
                                    $shortleave_duration_time           = ($shortleavetimestamp2 - $shortleavetimestamp1);
                                    $shortleave_time_difference_minutes = $shortleave_duration_time / 60;
                                    $shortleave_munites                 = round($shortleave_time_difference_minutes);
                                    $shift_munites                      = ($shift_munites - $shortleave_munites);
                                }
                                //half day e time eka adu karala danawa
                                $HaffDayaLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry where EmpNo = $EmpNo and Leave_Date = '$FromDate' AND Leave_Count='0.5' AND Is_Approve = '1' ");
                                if (! empty($HaffDayaLeave[0]->Is_Approve)) {
                                    // echo "okkkk";
                                    $halfday_fromtime                = $from_date . " " . $cutofftime;
                                    $halfday_totime                  = $from_date . " " . $from_time;
                                    $halfday_timestamp1              = strtotime($halfday_fromtime);
                                    $halfday_timestamp2              = strtotime($halfday_totime);
                                    $halfday_time_difference_seconds = ($halfday_timestamp1 - $halfday_timestamp2);
                                    $halfday_time_difference_minutes = $halfday_time_difference_seconds / 60;
                                    $halfd_late                      = round($halfday_time_difference_minutes);
                                    $DayStatus                       = 'HFD';
                                    $shift_munites;
                                    $shift_munites = ($shift_munites - $halfd_late);
                                }

                                //in out time hada gannawa
                                $In_time_date_config               = $InDate . " " . $InTime;
                                $Out_time_date_config              = $OutDate . " " . $OutTime;
                                $timepunchstamp1                   = strtotime($In_time_date_config);
                                $timepunchstamp2                   = strtotime($Out_time_date_config);
                                $In_out_time_duration              = ($timepunchstamp2 - $timepunchstamp1);
                                $time_difference_inouttime_minutes = $In_out_time_duration / 60;
                                $inout_munit                       = round($time_difference_inouttime_minutes);
                                $check_ot_or_late                  = ($shift_munites - $inout_munit);
                                if ($check_ot_or_late > 0) {
                                    $lateM = $check_ot_or_late;
                                }
                            }
                        }

                        //ED hadana thana ********
                        if ($settings[0]->ST == 1) {
                            if ($settings[0]->Ed == 1) {
                                if ($InTime != $OutTime && $OutTime != '') {
                                    $fromtime                = $to_date . " " . $to_time;
                                    $totime                  = $OutDate . " " . $OutTime;
                                    $timestamp1              = strtotime($totime);
                                    $timestamp2              = strtotime($fromtime);
                                    $time_difference_seconds = ($timestamp2 - $timestamp1);
                                    $time_difference_minutes = $time_difference_seconds / 60;
                                    $iCalcHaffED             = round($time_difference_minutes, 2);
                                    if ($iCalcHaffED > 0) {
                                        $ED = $iCalcHaffED;
                                    }
                                    // kalin gihhilanm haff day ekak thiynwda balanna
                                    $HaffDayaLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry where EmpNo = $EmpNo and Leave_Date = '$FromDate' AND Leave_Count='0.5' AND Is_Approve = '1' ");
                                    if (! empty($HaffDayaLeave[0]->Is_Approve)) {
                                        if ($cutofftime != '00:00:00') {
                                            $fromtime                = $from_date . " " . $cutofftime;
                                            $totime                  = $OutDate . " " . $OutTime;
                                            $timestamp1              = strtotime($totime);
                                            $timestamp2              = strtotime($fromtime);
                                            $time_difference_seconds = ($timestamp2 - $timestamp1);
                                            $time_difference_minutes = $time_difference_seconds / 60;
                                            $iCalcHaff               = round($time_difference_minutes, 2);
                                            $DayStatus               = 'HFD';
                                        }
                                        if ($iCalcHaff <= 0) {
                                            $ED = 0;
                                        } else {
                                            $ED = $iCalcHaff;
                                        }
                                    }
                                }
                            }
                        } else {
                            // echo "okkkkk";
                            // if ($OutTime != '' && $InTime != $OutTime && $InTime != '' && $OutTime != "00:00:00") {
                            //     //shift time eka hada gannawa
                            //     $SHFT = $from_date . " " . $from_time;
                            //     $SHTT = $to_date . " " . $to_time;
                            //     $timestamp1 = strtotime($SHFT);
                            //     $timestamp2 = strtotime($SHTT);
                            //     $Shift_duration_time = ($timestamp2 - $timestamp1);
                            //     $time_difference_minutes = $Shift_duration_time / 60;
                            //     $shift_munites = round($time_difference_minutes);

                            //     //shortleave thibboth e time eka adu karala danawa
                            //     $ShortLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_shortlive WHERE EmpNo = $EmpNo AND tbl_shortlive.Date = '$FromDate' ");
                            //     if (!empty($ShortLeave[0]->Is_Approve)) {
                            //         $shortleave_ftime = $ShortLeave[0]->from_time;
                            //         $shortleave_ttime = $ShortLeave[0]->to_time;
                            //         $shortleavetimestamp1 = strtotime($shortleave_ftime);
                            //         $shortleavetimestamp2 = strtotime($shortleave_ttime);
                            //         $shortleave_duration_time = ($shortleavetimestamp2 - $shortleavetimestamp1);
                            //         $shortleave_time_difference_minutes = $shortleave_duration_time / 60;
                            //         $shortleave_munites = round($shortleave_time_difference_minutes);
                            //         $shift_munites = ($shift_munites - $shortleave_munites);
                            //     }
                            //     //half day e time eka adu karala danawa
                            //     $HaffDayaLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry where EmpNo = $EmpNo and Leave_Date = '$FromDate' AND Leave_Count='0.5' AND Is_Approve = '1' ");
                            //     if (!empty($HaffDayaLeave[0]->Is_Approve)) {
                            //         // echo "okkkk";
                            //         $halfday_fromtime = $from_date . " " . $cutofftime;
                            //         $halfday_totime = $from_date . " " . $from_time;
                            //         $halfday_timestamp1 = strtotime($halfday_fromtime);
                            //         $halfday_timestamp2 = strtotime($halfday_totime);
                            //         $halfday_time_difference_seconds = ($halfday_timestamp1 - $halfday_timestamp2);
                            //         $halfday_time_difference_minutes = $halfday_time_difference_seconds / 60;
                            //         $halfd_late = round($halfday_time_difference_minutes);
                            //         $DayStatus = 'HFD';
                            //         $shift_munites;
                            //         $shift_munites = ($shift_munites - $halfd_late);
                            //     }

                            //     //in out time hada gannawa
                            //     $In_time_date_config = $InDate . " " . $InTime;
                            //     $Out_time_date_config = $OutDate . " " . $OutTime;
                            //     $timepunchstamp1 = strtotime($In_time_date_config);
                            //     $timepunchstamp2 = strtotime($Out_time_date_config);
                            //     $In_out_time_duration = ($timepunchstamp2 - $timepunchstamp1);
                            //     $time_difference_inouttime_minutes = $In_out_time_duration / 60;
                            //     $inout_munit = round($time_difference_inouttime_minutes);
                            //     $check_ot_or_late = ($shift_munites - $inout_munit);
                            //     if ($check_ot_or_late > 0) {
                            //         $ED = $check_ot_or_late;
                            //     }
                            // }
                        }

                        if (($InDate == $OutDate && $InTime == $OutTime) || $OutTime == null || $OutTime == '') {
                            $DayStatus      = 'MS';
                            $Late_Status    = 0;
                            $Nopay          = 0;
                            $Nopay_Hrs      = 0;
                            $Allnomalotmin  = 0;
                            $Alldoubleotmin = 0;
                            $Day_Type       = 0.5;
                        }

                        /*
                         * If In Available & Out Missing
                         */
                        if ($InTime != '' && ($InDate == $OutDate && $InTime == $OutTime)) {
                            $DayStatus      = 'MS';
                            $Late_Status    = 0;
                            $Nopay          = 0;
                            $Nopay_Hrs      = 0;
                            $Allnomalotmin  = 0;
                            $Alldoubleotmin = 0;
                            $Day_Type       = 0.5;
                        }

                        // If Out Available & In Missing
                        if ($OutTime != '' && ($InDate == $OutDate && $InTime == $OutTime)) {
                            $DayStatus      = 'MS';
                            $Late_Status    = 0;
                            $Nopay          = 0;
                            $Nopay_Hrs      = 0;
                            $Allnomalotmin  = 0;
                            $Alldoubleotmin = 0;
                            $Day_Type       = 0.5;
                        }

                        // If In Available & Out Missing
                        if ($InTime != '' && $OutTime == '') {
                            $DayStatus      = 'MS';
                            $Late_Status    = 0;
                            $Nopay          = 0;
                            $Nopay_Hrs      = 0;
                            $Allnomalotmin  = 0;
                            $Alldoubleotmin = 0;
                            $Day_Type       = 0.5;
                        }

                        // If Out Available & In Missing
                        if ($OutTime != '' && $InTime == '') {
                            $DayStatus      = 'MS';
                            $Late_Status    = 0;
                            $Nopay          = 0;
                            $Nopay_Hrs      = 0;
                            $Allnomalotmin  = 0;
                            $Alldoubleotmin = 0;
                            $Day_Type       = 0.5;
                        }
                    } elseif (empty($shift_code)) {
                        //shift code eka na kiyanne eda absent dawasak hari off dawasak hari//////////////////////////////////

                        $DayStatus   = 'CHECK';
                        $ApprovedExH = 0;
                        $InDate      = '';
                        $InTime      = '';
                        $OutDate     = '';
                        $OutTime     = '';

                        $from_date     = $FromDate;
                        $from_time     = '';
                        $to_date       = '';
                        $to_time       = '';
                        $Day_Type      = 0;
                        $lateM         = '';
                        $ED            = '';
                        $AfterShiftWH  = '';
                        $BeforeShift   = '';
                        $DOT           = '';
                        $Late_Status   = 0;
                        $NetLateM      = 0;
                        $Nopay         = 0;
                        $Nopay_Hrs     = 0;
                        $Att_Allowance = 0;
                        if ($shift_type == 'OFF') {
                            $DayStatus = 'OFF';
                        }
                    }

                    $Holiday = $this->Db_model->getfilteredData("select count(Hdate) as HasRow from tbl_holidays where Hdate = '$FromDate' ");
                    if ($Holiday[0]->HasRow == 1) {
                        if ($InTime != '' && (($InTime != $OutTime && $InDate == $OutDate) || ($InDate != $OutDate)) && $OutTime != '') {
                            $Nopay     = 0;
                            $DayStatus = 'HDPR';
                            $Nopay_Hrs = 0;
                            $Day_Type  = 1;
                        } else {
                            $DayStatus     = 'HD';
                            $Nopay         = 0;
                            $Nopay_Hrs     = 0;
                            $Att_Allowance = 0;
                            $Day_Type      = 0;
                        }
                    }
                    $Leave = $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry where EmpNo = $EmpNo and Leave_Date = '$FromDate' AND Leave_Count='1' AND Is_Approve = '1' ");
                    if (! empty($Leave[0]->Is_Approve)) {
                        $Nopay         = 0;
                        $DayStatus     = 'LV';
                        $Nopay_Hrs     = 0;
                        $Att_Allowance = 0;
                        $Day_Type      = 1;
                        if ($InTime != '' && $InTime != $OutTime && $OutTime != '') {
                            $Nopay     = 0;
                            $DayStatus = 'LV-PR';
                            $Nopay_Hrs = 0;
                            $Day_Type  = 1;
                        }
                    }
                    $halfd_late    = 0;
                    $HaffDayaLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry where EmpNo = $EmpNo and Leave_Date = '$FromDate' AND Leave_Count='0.5' AND Is_Approve = '1' ");
                    if (! empty($HaffDayaLeave[0]->Is_Approve)) {

                        if ($InTime == '' && $OutTime == '') {

                            $fromtime                = $from_date . " " . $cutofftime;
                            $totime                  = $from_date . " " . $from_time;
                            $timestamp1              = strtotime($totime);
                            $timestamp2              = strtotime($fromtime);
                            $time_difference_seconds = ($timestamp2 - $timestamp1);
                            $time_difference_minutes = $time_difference_seconds / 60;
                            $halfd_late              = round($time_difference_minutes, 2);
                            $DayStatus               = 'HFD-AB';
                            $lateM                   = $halfd_late;
                        }
                    }

                    if ($lateM >= 0) {
                        $lateM;
                    } else {
                        $lateM = 0;
                    }

                    if ($ED >= 0) {
                        $ED;
                    } else {
                        $ED = 0;
                    }
                    echo $ID_Roster;
                    echo "<br/>";
                    echo $EmpNo;
                    echo "<br/>";
                    echo $FromDate;
                    echo "<br/>";
                    echo "from date-" . $from_date;
                    echo "<br/>";
                    echo "from time-" . $from_time;
                    echo "<br/>";
                    echo "in date-" . $InDate;
                    echo "<br/>";
                    echo "in time-" . $InTime;
                    echo "<br/>";
                    echo "<br/>";
                    echo "to date-" . $to_date;
                    echo "<br/>";
                    echo "to time-" . $to_time;
                    echo "<br/>";
                    echo "out date-" . $OutDate;
                    echo "<br/>";
                    echo "out time-" . $OutTime;
                    echo "<br/>";
                    echo "Late " . $lateM;
                    echo "<br/>";
                    echo "ED " . $ED;
                    echo "<br/>";
                    echo "DayStatus " . $DayStatus;
                    echo "<br/>";
                    echo "OT " . $AfterShiftWH;
                    echo "<br/>";
                    echo "dot" . $DOT;
                    echo "<br/>";
                    // // echo "in 3-" . $InmoTime3;
                    // // echo "<br/>";
                    // // echo "out 3-" . $OutDate3;
                    // // echo "<br/>";
                    // // echo "out 3-" . $OutTime3;
                    // // echo "<br/>";
                    // // echo "workhours1-" . $workhours1;
                    // // echo "<br/>";
                    // // echo "workhours2-" . $workhours2;
                    // // echo "<br/>";
                    // // echo "workhours3-" . $workhours3;
                    // // echo "<br/>";
                    // // echo "workhours3-" . $workhours;
                    // // echo "<br/>";
                    // // echo "dot1-" . $DOT1;
                    // // echo "<br/>";
                    // // echo "dot2-" . $DOT2;
                    // // echo "<br/>";
                    // // echo "dot3-" . $DOT3;
                    // // echo "<br/>";
                    // // echo "dot-" . $DOT;
                    // // echo "<br/>";
                    // // echo "out" . $OutTime;
                    // // echo "<br/>";
                    // // echo "outd-" . $OutDate;
                    echo "<br/>";
                    echo "<br/>";
                    echo "<br/>";
                    echo "<br/>";
                    // die;
                    // $data_arr   = ["InRec" => 1, "InDate" => $FromDate, "InTime" => $InTime, "TDate" => $to_date, "TTime" => $to_time, "OutRec" => 1, "Day_Type" => $Day_Type, "OutDate" => $OutDate, "OutTime" => $OutTime, "nopay" => $Nopay, "Is_processed" => 1, "DayStatus" => $DayStatus, "BeforeExH" => $BeforeShift, "AfterExH" => $AfterShiftWH, "LateSt" => $Late_Status, "LateM" => $lateM, "EarlyDepMin" => $ED, "NetLateM" => $NetLateM, "ApprovedExH" => $ApprovedExH, "nopay_hrs" => $Nopay_Hrs, "Att_Allow" => $Att_Allowance, "DOT" => $DOT,BreackInTime1" => $BreakInTime, "BreackOutTime1" => $BreakOutTime];
                    // $whereArray = ["ID_roster" => $ID_Roster];
                    // $result     = $this->Db_model->updateData("tbl_individual_roster", $data_arr, $whereArray);
                }
            }
            // }
            // $this->session->set_flashdata('success_message', 'Attendance Process successfully');
            // redirect('/Attendance/Attendance_Process_New');
        }
        // else {
        //     $this->session->set_flashdata('success_message', 'Attendance Process successfully');
        //     redirect('/Attendance/Attendance_Process_New');
        // }
        // $this->session->set_flashdata('success_message', 'Attendance Process successfully');
        // redirect('/Attendance/Attendance_Process_New');
    }

}
