<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Attendance_Auto_Process_New extends CI_Controller
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
    //     $this->load->model('Db_model', '', TRUE);
    // }

    // /*
    //  * Index page
    //  */

    // public function index()
    // {

    //     $data['title'] = "Attendance Process | HRM System";
    //     $data['data_set'] = $this->Db_model->getData('EmpNo,Emp_Full_Name', 'tbl_empmaster');
    //     $data['data_shift'] = $this->Db_model->getData('ShiftCode,ShiftName', 'tbl_shifts');
    //     $data['data_roster'] = $this->Db_model->getData('RosterCode,RosterName', 'tbl_rosterpatternweeklyhd');



    //     $data['sh_employees'] = $this->Db_model->getfilteredData("SELECT 
    //                                                                 tbl_empmaster.EmpNo
    //                                                             FROM
    //                                                                 tbl_empmaster
    //                                                                     LEFT JOIN
    //                                                                 tbl_individual_roster ON tbl_individual_roster.EmpNo = tbl_empmaster.EmpNo
    //                                                                 where tbl_individual_roster.EmpNo is null AND tbl_empmaster.status=1 and Active_process=1");


    //     $this->load->view('Attendance/Attendance_Process/index', $data);
    // }

    /*
     * Insert Data
     */
    // public function Test(){
    //     date_default_timezone_set('Asia/Colombo');

    //     $from_date = date('Y-m-01'); // First day of the current month
    //     $to_date = date('Y-m-t'); // Last day of the current month

    //     $query = "UPDATE tbl_individual_roster SET Is_processed = 0 WHERE FDate BETWEEN '".$from_date."' AND '".$to_date."';";

    //     // Run the custom query
    //     $result = $this->Db_model->getUpdateData($query);

    //     if ($result) {
    //         echo "Update successful!";
    //     } else {
    //         echo "Update failed!";
    //     }
    // }

    public function emp_attendance_process()
    {
        $this->load->model('Db_model', '', TRUE);

        date_default_timezone_set('Asia/Colombo');

        $from_date = date('Y-m-01'); // First day of the current month
        $to_date = date('Y-m-t'); // Last day of the current month

        $query = "UPDATE tbl_individual_roster SET Is_processed = 0 WHERE FDate BETWEEN '".$from_date."' AND '".$to_date."';";

        // Run the custom query
        $result = $this->Db_model->getUpdateData($query);

        $autorunSettings = $this->Db_model->getfilteredData("SELECT * FROM tbl_autorun_settings WHERE status_flag_name='initialize_run'");
        $autorunSettings2 = $this->Db_model->getfilteredData("SELECT * FROM tbl_autorun_settings WHERE status_flag_name='shift_allocation_run'");
        if ($autorunSettings[0]->status_flag == 0 && $autorunSettings2[0]->status_flag == 0) {
            $data = array("status_flag" => "1");
            $whereArr = array("status_flag_name" => "attendance_process_run");
            $result = $this->Db_model->updateData('tbl_autorun_settings', $data, $whereArr);
            
            // attendance_process_code_start
            /*
            * Get Employee Data
            * Emp no , EPF No, Roster Type, Roster Pattern Code, Status
            */
            //        $dtEmp['EmpData'] = $this->Db_model->getfilteredData("SELECT EmpNo,Enroll_No, EPFNO,RosterCode, Status  FROM  tbl_empmaster where status=1");
            $dtEmp['EmpData'] = $this->Db_model->getfilteredData("select * from tbl_individual_roster where Is_processed = 0");

            $AfterShift = 0;
            //        var_dump($dtEmp);die;
            if (!empty($dtEmp['EmpData'])) {
                /*
                * For Loop untill all employee and where employee status = 1
                */
                for ($x = 0; $x < count($dtEmp['EmpData']); $x++) {
                    $EmpNo = $dtEmp['EmpData'][$x]->EmpNo;
                    $FromDate = $dtEmp['EmpData'][$x]->FDate;
                    $ToDate = $dtEmp['EmpData'][$x]->TDate;
                    $OutTime = 0;
                    $OutDate = 0;
                    $SHFT = 0;
                    $SHTT = 0;
                    $InTime = 0;
                    $AfterShiftWH = 0;
                    $leave_type = 0;

                    if ($FromDate <= $ToDate) {
                        // Get the CheckIN
                        $dt_in_Records['dt_Records'] = $this->Db_model->getfilteredData("select min(AttTime) as INTime,Enroll_No,AttDate from tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='" . $FromDate . "' ");
                        //**** In Date
                        $InDate = $dt_in_Records['dt_Records'][0]->AttDate;
                        //**** In Time
                        $InTime = $dt_in_Records['dt_Records'][0]->INTime;

                        // tbl_individual_roster eke OFF dwas ganne
                        $OFFDAY['OFF'] = $this->Db_model->getfilteredData("select `ShType` from tbl_individual_roster where FDate = '$FromDate'");
                        $Day = $OFFDAY['OFF'][0]->ShType;

                        if ($Day != "OFF") {
                            // Get the CheckOut
                            $dt_out_Records['dt_out_Records'] = $this->Db_model->getfilteredData("select max(AttTime) as OutTime,Enroll_No,AttDate from tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='" . $FromDate . "'");

                            //**** Out Date
                            $OutDate = $dt_out_Records['dt_out_Records'][0]->AttDate;
                            //**** Out Time
                            $OutTime = $dt_out_Records['dt_out_Records'][0]->OutTime;

                            /*
                        * ***** Get Shift Code
                        */
                            $SH['SH'] = $this->Db_model->getfilteredData("select ID_roster,EmpNo,ShiftCode,ShType,ShiftDay,Day_Type,FDate,FTime,TDate,TTime,ShType,GracePrd from tbl_individual_roster where Is_processed=0 and EmpNo='$EmpNo' and FDate='$FromDate' ");
                            $SH_Code = $SH['SH'][0]->ShiftCode;
                            $Shift_Day = $SH['SH'][0]->ShiftDay;
                            //****Shift Type DU| EX
                            $ShiftType = $SH['SH'][0]->ShType;
                            //****Individual Roster ID
                            $ID_Roster = $SH['SH'][0]->ID_roster;
                            //****Shift from time
                            $SHFT = $SH['SH'][0]->FTime;
                            //****Shift to time
                            $SHTT = $SH['SH'][0]->TTime;

                            //****Day Type Full day or Half day (1)or 0.5
                            $DayType = $SH['SH'][0]->Day_Type;

                            $GracePrd = $SH['SH'][0]->GracePrd;

                            $OT['OT'] = $this->Db_model->getfilteredData("SELECT tbl_ot_pattern_dtl.DayCode,tbl_ot_pattern_dtl.OTCode,tbl_empmaster.EmpNo,tbl_ot_pattern_dtl.OTPatternName,tbl_ot_pattern_dtl.DUEX,tbl_ot_pattern_dtl.BeforeShift,tbl_ot_pattern_dtl.MinBS,tbl_ot_pattern_dtl.AfterShift,tbl_ot_pattern_dtl.MinAS,tbl_ot_pattern_dtl.RoundUp,tbl_ot_pattern_dtl.Rate,tbl_ot_pattern_dtl.Deduct_LNC FROM tbl_ot_pattern_dtl RIGHT JOIN tbl_empmaster ON tbl_ot_pattern_dtl.OTCode = tbl_empmaster.OTCode WHERE tbl_ot_pattern_dtl.DayCode ='$Shift_Day' and tbl_ot_pattern_dtl.DUEX='$ShiftType'");

                            $AfterShift = $OT['OT'][0]->AfterShift;

                            $MinAS = $OT['OT'][0]->MinAS;


                            $lateM = 0;
                            // $BeforeShift = 0;
                            $Late_Status = 0;
                            $NetLateM = 0;
                            $ED = 0;
                            $EDF = 0;
                            $Att_Allowance = 1;
                            $Nopay = 0;

                            if ($InTime == $OutTime || $OutTime == null || $OutTime == '') {
                                $DayStatus = 'MS';
                                $Late_Status = 0;
                                $Nopay = 0;
                                $Nopay_Hrs = 0;
                            }

                            /*
                        * If In Available & Out Missing
                        */
                            if ($InTime != '' && $InTime == $OutTime) {
                                $DayStatus = 'MS';
                                $Late_Status = 0;
                                $Nopay = 0;
                                $Nopay_Hrs = 0;
                            }

                            // If Out Available & In Missing
                            if ($OutTime != '' && $InTime == $OutTime) {
                                $DayStatus = 'MS';
                                $Late_Status = 0;
                                $Nopay = 0;
                                $Nopay_Hrs = 0;
                            }

                            // If In Available & Out Missing
                            if ($InTime != '' && $OutTime == '') {
                                $DayStatus = 'MS';
                                $Late_Status = 0;
                                $Nopay = 0;
                                $Nopay_Hrs = 0;
                            }

                            // If Out Available & In Missing
                            if ($OutTime != '' && $InTime == '') {
                                $DayStatus = 'MS';
                                $Late_Status = 0;
                                $Nopay = 0;
                                $Nopay_Hrs = 0;
                            }
                            // **************************************************************************************//

                            if ($InTime != '' && $InTime != $OutTime && $OutTime != '') {
                                $Nopay = 0;
                                $DayStatus = 'PR';
                                $Nopay_Hrs = 0;
                            }

                            $Nopay_Hrs = 0;
                            // Nopay
                            if ($InTime == '' && $OutTime == '' && $Day == 'DU') {
                                $DayStatus = 'AB';
                                $Nopay = 1;
                                $Nopay_Hrs = (((strtotime($SHTT) - strtotime($SHFT))) / 60);



                                if ($InTime == '' && $OutTime == '' && $Day == 'EX') {
                                    $Nopay = 0;
                                    $Nopay_Hrs = 0;
                                    $DayStatus = 'EX';
                                }
                                $appointdate = $this->Db_model->getfilteredData("SELECT tbl_empmaster.ApointDate 
                                FROM tbl_empmaster WHERE tbl_empmaster.EmpNo = '$EmpNo'");
                                if ($appointdate[0]->ApointDate > $FromDate) {
                                    $DayStatus = '';
                                    $Nopay = 0;
                                    $Nopay_Hrs = 0;
                                }
                            }
                            $AfterShiftWH = 0;
                            //OT
                            $icalData = 0;
                            $ApprovedExH = 0;
                            if ($OutTime != '' && $InTime != $OutTime && $InTime != '' && $Day == 'DU' && $OutTime != "00:00:00") {
                                if ($ToDate == $OutDate) {
                                    if ($AfterShift == 1) {

                                        $OutTimeSrt = strtotime($OutTime);
                                        $SHEndTime = strtotime($SHTT);

                                        //*******Get Minutes
                                        $iCalcOut = (($OutTimeSrt - $SHEndTime) / 60);
                                        $icalData = $iCalcOut - $MinAS; //windi 30kin pase OT hedenne(tbl_ot_pattern_dtl eken balanna)

                                    } else if ($AfterShift == 0) {

                                        $OutTimeSrt = strtotime($OutTime);
                                        $SHEndTime = strtotime($SHTT);

                                        //*******Get Minutes
                                        $iCalcOut = (($OutTimeSrt - $SHEndTime) / 60);
                                        $icalData = $iCalcOut;
                                    }
                                }
                            }
                            if ($icalData >= 0 && $AfterShift == 1) {
                                $AfterShiftWH = $icalData;
                            }
                            $lateM = 0; //late minutes
                            $ED = 0; //ED minutes
                            $iCalcHaffT = 0;
                            //ED
                            if ($ToDate == $OutDate) {
                                if ($Day == 'DU') {
                                    if ($OutTime < $SHTT) {
                                        $OutTimeSrt = strtotime($OutTime);
                                        $SHEndTime = strtotime($SHTT);
                                        $EDF = ($SHEndTime - $OutTimeSrt) / 60;

                                        // kalin gihhilanm haff day ekak thiynwda balanna
                                        $HaffDayaLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry where EmpNo = $EmpNo and Leave_Date = '$FromDate' AND Leave_Count='0.5' ");
                                        if (!empty($HaffDayaLeave[0]->Is_Approve)) {
                                            $SHstarttime = "14:00:00";

                                            $OutTimeSrt = strtotime($OutTime);
                                            $SHstartimeSrt = strtotime($SHstarttime);

                                            $iCalcHaffED = ($SHstartimeSrt - $OutTimeSrt) / 60;

                                            if ($iCalcHaffED >= 0) {
                                                //ED thiywa
                                                $ED = $iCalcHaffED;
                                                // $ED = $EDF + $iCalcHaffED;

                                            } else if ($iCalcHaffED <= 0) {
                                                //ED nee
                                                $ED = 0;
                                            }
                                        } else {

                                            $HDFDateSrt = strtotime($InDate);
                                            $OutDateSrt = strtotime($OutDate);

                                            if ($HDFDateSrt == $OutDateSrt) {

                                                $ED = $EDF;
                                            } else {
                                                $ED = 0;
                                            }
                                        }
                                    }

                                    // $ED = 0 - $icalData;
                                    // if ($ED <= 0) {
                                    //     $ED = 0;
                                    // }
                                }
                            }
                            // HaffDay walata kalin gihin nethnm (ED)
                            if ($InTime != '' && $InTime != $OutTime && $Day == 'DU' || $OutTime != '' && $Day == 'DU') {

                                $HaffDayaLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry where EmpNo = $EmpNo and Leave_Date = '$FromDate' AND Leave_Count='0.5' ");
                                if (!empty($HaffDayaLeave[0]->Is_Approve)) {
                                    $SHstarttime = "14:00:00";

                                    $OutTimeSrt = strtotime($OutTime);
                                    $SHstartimeSrt = strtotime($SHstarttime);

                                    $iCalcHaffED = ($SHstartimeSrt - $OutTimeSrt) / 60;

                                    if ($iCalcHaffED <= 0) {
                                        //ED nee

                                        $ED = 0;
                                    } else if ($iCalcHaffED >= 0) {

                                        $HDFDateSrt = strtotime($InDate);
                                        $OutDateSrt = strtotime($OutDate);

                                        if ($HDFDateSrt == $OutDateSrt) {

                                            $ED = $iCalcHaffED;
                                        } else {
                                            $ED = 0;
                                        }
                                    }
                                }
                            }
                            if ($InTime != '' && $InTime != $OutTime && $Day == 'DU' || $OutTime != '' && $Day == 'DU') {

                                $SHStartTime = strtotime($SHFT);
                                $InTimeSrt = strtotime($InTime);

                                $iCalc = ($InTimeSrt - $SHStartTime) / 60; //minutes

                                $HaffDayaLeave = $this->Db_model->getfilteredData("SELECT count(EmpNo) as HasRow FROM tbl_leave_entry where EmpNo = $EmpNo and Leave_Date = '$FromDate' AND Leave_Count='0.5' ");

                                // if ($HaffDayaLeave[0]->HasRow == 0) {
                                //     $lateM = $iCalc - $GracePrd;
                                // }else{
                                //     $lateM = 0;
                                // }
                                $lateM = $iCalc - $GracePrd;


                                $HaffDayaLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry where EmpNo = $EmpNo and Leave_Date = '$FromDate' AND Leave_Count='0.5' ");
                                // echo $HaffDayaLeave[0]->Is_Approve.'********';
                                // haffDay thiywam (only) Morning
                                if (!empty($HaffDayaLeave[0]->Is_Approve)) {
                                    $SHTtime = "14:00:00";

                                    $InTimeSrt = strtotime($InTime);
                                    $SHToTimeSrt = strtotime($SHTtime);

                                    $iCalcHaffT = ($InTimeSrt - $SHToTimeSrt) / 60;

                                    if ($InTime <= "11:00:00") {
                                        $lateM;
                                    } else {
                                        if ($iCalcHaffT <= 0) {
                                            // welawta ewilla
                                            $lateM = 0;
                                            $Late_Status = 0;
                                            $DayStatus = 'HFD';
                                        } else if ($iCalcHaffT >= 0) {
                                            $lateM = $iCalcHaffT;

                                            $DayStatus = 'HFD';
                                        }
                                    }
                                }


                                $SHEndTime = strtotime($SHTT);
                                $OutTimeSrt = strtotime($OutTime);

                                $iCalcED = ($SHEndTime - $OutTimeSrt) / 60; //minutes

                                if ($ED >= 0) {
                                    $ED = $iCalcED;
                                }

                                // haffDay thiywam (only) Evening
                                if (!empty($HaffDayaLeave[0]->Is_Approve)) {
                                    $HDFTtime = "14:00:00";

                                    $HDFTimeSrt = strtotime($HDFTtime);
                                    $OutTimeSrt = strtotime($OutTime);

                                    $iCalcHaffT = ($HDFTimeSrt - $OutTimeSrt) / 60;

                                    if ($InTime <= "13:00:00") {
                                        if ($iCalcHaffT <= 0) {
                                            // welawta ewilla
                                            $ED = 0;

                                            // $Late_Status = 0;
                                            $DayStatus = 'HFD';
                                            $lateM;
                                        } else if ($iCalcHaffT >= 0) {
                                            $DayStatus = 'HFD';

                                            $HDFDateSrt = strtotime($InDate);
                                            $OutDateSrt = strtotime($OutDate);

                                            if ($HDFDateSrt == $OutDateSrt) {

                                                $ED = $iCalcHaffT;
                                                $lateM;
                                            } else {
                                                $ED = 0;
                                            }
                                        }
                                    }
                                }
                            }
                            $HaffDayaLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry where EmpNo = $EmpNo and Leave_Date = '$FromDate' AND Leave_Count='0.5' ");

                            if (!empty($HaffDayaLeave[0]->Is_Approve) && $InTime == '' && $OutTime == '' || !empty($HaffDayaLeave[0]->Is_Approve) && $InTime == null && $OutTime == null) {
                                $DayStatus = 'HFDAB';
                                $Nopay = 0.5;
                                $Nopay_Hrs = 0;
                            }
                            // Hawasa ShortLeave thiynwam
                            $ShortLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_shortlive WHERE EmpNo = $EmpNo AND tbl_shortlive.Date = '$FromDate' ");
                            if (!empty($ShortLeave[0]->Is_Approve)) {
                                if ($ShortLeave[0]->from_time >= '17:30:00') {

                                    $SHFtime = $ShortLeave[0]->from_time;
                                    $SHTtime = $ShortLeave[0]->to_time;

                                    if (($OutTime > '17:30:00') || ($OutTime < '18:00:00')) {
                                        // echo $InTime . '-' . $OutTime . ' || ' . $EmpNo;
                                        // echo "<br>";
                                        // echo $FromDate;
                                        // echo "<br>";
                                        $OutTimeSrt = strtotime($OutTime);
                                        $SHFromTimeSrt = strtotime($SHFtime);

                                        $iCalcShortLTED = ($SHFromTimeSrt - $OutTimeSrt) / 60;
                                        // echo $iCalcShortLTED;
                                        if ($iCalcShortLTED > 0) {
                                            $ED = 0;
                                            $ED = $iCalcShortLTED;
                                            $DayStatus = 'SL';
                                        } else {
                                            $ED = 0;
                                            $DayStatus = 'SL';
                                            // echo "2";
                                        }
                                    } else {
                                    }
                                }
                            }

                            // Morning In Time ekata kalin short leave thiywam
                            $ShortLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_shortlive WHERE EmpNo = $EmpNo AND tbl_shortlive.Date = '$FromDate' ");
                            if (!empty($ShortLeave[0]->Is_Approve)) {
                                $SHFtime = $ShortLeave[0]->from_time;
                                $SHTtime = $ShortLeave[0]->to_time;

                                $InTimeSrt = strtotime($InTime);
                                $SHToTimeSrt = strtotime($SHTtime);

                                $iCalcShortLT = ($InTimeSrt - $SHToTimeSrt) / 60;

                                if ($SHFtime <= "10:00:00") {
                                    if ($iCalcShortLT <= 0) {
                                        // welawta ewilla
                                        $lateM = 0;
                                        $Late_Status = 0;
                                        $DayStatus = 'SL';
                                    } else {
                                        // welatwa ewilla ne(short leave ektath passe late /haffDay ne )
                                        $lateM = $iCalcShortLT;
                                        $DayStatus = 'SL';

                                        // echo "2gg";
                                    }
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
                            $Holiday = $this->Db_model->getfilteredData("select count(Hdate) as HasRow from tbl_holidays where Hdate = '$FromDate' ");
                            if ($Holiday[0]->HasRow == 1) {
                                $DayStatus = 'HD';
                                $Nopay = 0;
                                $Nopay_Hrs = 0;
                                $Att_Allowance = 0;
                            }
                            $Leave = $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry where EmpNo = $EmpNo and Leave_Date = '$FromDate' AND Leave_Count='1' AND Is_Cancel = '0'");
                            if (!empty($Leave[0]->Is_Approve)) {
                                $Nopay = 0;
                                $DayStatus = 'LV';
                                $leave_type = $Leave[0]->Lv_T_ID;
                                $Nopay_Hrs = 0;
                                $Att_Allowance = 0;
                            }
                        }
                        if ($Day == "OFF" || $Day == "EX") {
                            $Nopay = 0;
                            $OutTime = 0;
                            $OutDate = 0;
                            $Late_Status = 0;
                            $NetLateM = 0;
                            $ApprovedExH = 0;
                            $Nopay_Hrs = 0;
                            $Att_Allowance = 1;
                            $Late_Status = 0;
                            $leave_type = 0;
                            // $SHFT = 0;
                            // $SHTT = 0;
                            $InTime = 0;
                            $ID_Roster = 0;
                            $Shift_Day = 0;
                            $AfterShiftWH = 0;
                            $Allnomalotmin = 0;
                            $ED = 0;
                            $DayStatus = 'OFF';
                            $SH['SH'] = $this->Db_model->getfilteredData("select ID_roster,EmpNo,ShiftCode,ShType,ShiftDay,Day_Type,FDate,FTime,TDate,TTime,ShType,GracePrd from tbl_individual_roster where Is_processed=0 and EmpNo='$EmpNo' and FDate='$FromDate' ");
                            $Shift_Day = $SH['SH'][0]->ShiftDay;

                            //****Shift Type DU| EX
                            $ShiftType = $SH['SH'][0]->ShType;
                            //****Individual Roster ID
                            $ID_Roster = $SH['SH'][0]->ID_roster;
                            $dt_in_Records['dt_in_Records'] = $this->Db_model->getfilteredData("select min(AttTime) as INTime,Enroll_No,AttDate,EventID from tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='" . $FromDate . "'  ");
                            $dt_in_Records['dt_out_Records'] = $this->Db_model->getfilteredData("select max(AttTime) as OUTTime,Enroll_No,AttDate,EventID from tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='" . $FromDate . "'  ");
                            $InsunIN = $dt_in_Records['dt_in_Records'][0]->INTime;
                            $OutsunOUT = $dt_in_Records['dt_out_Records'][0]->OUTTime;
                            if (!empty($OutsunOUT)) {
                                if (empty($InsunIN)) {
                                    $InsunIN = '08:30:00';
                                    // $DaysunOUT = date('Y-m-d', strtotime($FromDate . ' +1 day'));
                                }
                                $OutTimeSrt = strtotime($OutsunOUT);
                                $SHEndTime = strtotime($InsunIN);
                                $iCalcOut = round(($OutTimeSrt - $SHEndTime) / 60);
                                $AfterShiftWH = $iCalcOut;
                                $DayStatus = 'EX';
                                $InTime = $InsunIN;
                                $OutTime = $OutsunOUT;
                                $InDate = $FromDate;
                                $OutDate = $FromDate;
                            }

                            // if (empty($OutsunOUT)) {
                            //     $OutDate2 = date('Y-m-d', strtotime($FromDate . ' +1 day'));
                            //     $dt_in_Records['dt_out_sun_next_Records'] = $this->Db_model->getfilteredData("select min(AttTime) as OUTTime,Enroll_No,AttDate,EventID from tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='" . $OutDate2 . "' and AttTime BETWEEN '00:00:01' AND '06:59:00' AND Status='1' ");
                            //     $OutsunOUT2 = $dt_in_Records['dt_out_sun_next_Records'][0]->OUTTime;
                            //     if (empty($InsunIN)) {
                            //         $InsunIN = '08:00:00';
                            //         // $DaysunOUT = date('Y-m-d', strtotime($FromDate . ' +1 day'));
                            //     }
                            //     $dayconcattoday = $FromDate . " " . $InsunIN;
                            //     $dayconcatprday = $OutDate2 . " " . $OutsunOUT2;
                            //     $OutTimeSrt = strtotime($dayconcatprday);
                            //     $InndTime = strtotime($dayconcattoday);
                            //     $iCalcOut = round(($OutTimeSrt - $InndTime) / 60);
                            //     $Alldoubleotmin = $iCalcOut;
                            //     $DayStatus = 'EX';
                            //     $InTime = $InsunIN;
                            //     $OutTime = $OutsunOUT2;
                            //     $InDate = $FromDate;
                            //     $OutDate = $OutDate2;
                            // }
                            if (empty($OutTime)) {
                                $Nopay = 0;
                                $OutTime = 0;
                                $OutDate = 0;
                                $lateM = 0;
                                $InTime = 0;
                                $ED = 0;
                                $DayStatus = 'OFF';
                                $AfterShiftWH = 0;
                                $Allnomalotmin = 0;
                            }
                            $appointdate = $this->Db_model->getfilteredData("SELECT tbl_empmaster.ApointDate 
                                FROM tbl_empmaster WHERE tbl_empmaster.EmpNo = '$EmpNo'");
                            if ($appointdate[0]->ApointDate > $FromDate) {
                                $DayStatus = '';
                                $Nopay = 0;
                                $Nopay_Hrs = 0;
                            }
                        }
                        if ($InTime != '' && $InTime == $OutTime) {
                            $DayStatus = 'MS';
                            $Late_Status = 0;
                            $Nopay = 0;
                            $OutTime = '00:00:00';


                            $Nopay_Hrs = 0;
                        }
                        if ($InTime != '' && $OutTime == '') {
                            $DayStatus = 'MS';
                            $Late_Status = 0;
                            $Nopay = 0;
                            $OutTime = '00:00:00';


                            $Nopay_Hrs = 0;
                        }
                        // echo "emp-no-" . $EmpNo;
                        // echo "<br/>";
                        // echo $ID_Roster;
                        // echo "<br/>";
                        // echo "indate" . $InDate;
                        // echo "<br/>";
                        // echo $InTime;
                        // echo "<br/>";
                        // echo "outdate" . $OutDate;
                        // echo "<br/>";
                        // echo $OutTime;
                        // echo "<br/>";
                        // echo $ToDate;
                        // echo "<br/>";
                        // echo $Shift_Day;
                        // echo "<br/>";
                        // echo $ShiftType;
                        // echo "<br/>";
                        // echo "ot" . $AfterShiftWH;
                        // echo "<br/>";
                        // echo "ed" . $ED;
                        // echo "<br/>";
                        // echo "late" . $lateM;
                        // echo "<br/>";
                        //  echo "late" . $leave_type;
                        // echo "<br/>";
                        // echo "<br/>";
                        // echo "<br/>";
                        $data_arr = array("InRec" => 1, "InDate" => $FromDate, "InTime" => $InTime, "OutRec" => 1, "OutDate" => $OutDate, "OutTime" => $OutTime, "nopay" => $Nopay, "Is_processed" => 1, "DayStatus" => $DayStatus, "AfterExH" => $AfterShiftWH, "LateSt" => $Late_Status, "LateM" => $lateM, "Lv_T_ID" => $leave_type, "EarlyDepMin" => $ED, "NetLateM" => $NetLateM, "ApprovedExH" => $ApprovedExH, "nopay_hrs" => $Nopay_Hrs, "Att_Allow" => $Att_Allowance);
                        $whereArray = array("ID_roster" => $ID_Roster);
                        $result = $this->Db_model->updateData("tbl_individual_roster", $data_arr, $whereArray);
                    }
                }
                // $this->session->set_flashdata('success_message', 'Attendance Process successfully');
                // redirect('/Attendance/Attendance_Process_New');
            } else {
                // $this->session->set_flashdata('success_message', 'Attendance Process successfully');
                // redirect('/Attendance/Attendance_Process_New');
            }
                // $this->session->set_flashdata('success_message', 'Attendance Process successfully');
                // redirect('/Attendance/Attendance_Process_New');

                // update the tbl_autorun_settings table flag (shift_allocation_run 0)
                $data = array("status_flag" => "0");
                $whereArr = array("status_flag_name" => "attendance_process_run");
                $result = $this->Db_model->updateData('tbl_autorun_settings', $data, $whereArr);
            }
        // else{
        //     echo "0";
        // }

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
        $this->email->subject('attendance_process');
        $this->email->message("This is a test email for attendance_process");

        if ($this->email->send()) {
            echo 'Email sent successfully!';
        } else {
            echo 'Failed to send email.';
            echo $this->email->print_debugger(); // For debugging any errors
        }

    }
}
