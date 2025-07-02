<!DOCTYPE html>


<!--Description of dashboard page

@author Ashan Rathsara-->


<html lang="en">


<head>
    <!-- Styles -->
    <?php $this->load->view('template/css.php');?>

</head>

<body class="infobar-offcanvas">

    <!--header-->

    <?php $this->load->view('template/header.php');?>

    <!--end header-->

    <div id="wrapper">
        <div id="layout-static">

            <!--dashboard side-->

            <?php $this->load->view('template/dashboard_side.php');?>

            <!--dashboard side end-->

            <div class="static-content-wrapper">
                <div class="static-content">
                    <div class="page-content">
                        <ol class="breadcrumb">

                            <li class=""><a href="index.html">HOME</a></li>
                            <li class="active"><a href="index.html">EMPLOYEE GROUPS</a></li>

                        </ol>


                        <div class="page-tabs">
                                <ul class="nav nav-tabs">

                                    <li><a data-toggle="tab" href="#tab1">EMPLOYEE GROUPS</a></li>
                                    <li class="active"><a data-toggle="tab" href="#tab2">VIEW EMPLOYEE GROUPS</a></li>


                                </ul>
                            </div>
                        <div class="container-fluid">


                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">

                                    <div class="row">
                                        <div class="col-xs-12">


                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="panel panel-info">
                                                        <div class="panel-heading">
                                                            <h2>UPDATE LEAVE ALLOCATION EDIT</h2>
                                                        </div>
                                                        <div class="panel-body">
                                                            <center>
                                                                <div class="modal-body">
                                                                    <form class="form-horizontal"
                                                                        action="<?php echo base_url(); ?>Master/Employee_Groups/edit"
                                                                        method="post">

                                                                        <div class="form-group col-sm-12">
                                                                            <span class="col-sm-3">Group ID</span>
                                                                            <div class="col-sm-6">
                                                                                <input
                                                                                    value="<?php echo $data_set[0]->Grp_ID; ?>"
                                                                                    type="text" class="form-control"
                                                                                    readonly="readonly" name="id"
                                                                                    id="id" class="m-wrap span3">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group col-sm-12">
                                                                            <span class="col-sm-3">Group Name</span>
                                                                            <div class="col-sm-6">
                                                                                <input
                                                                                    value="<?php echo $data_set[0]->EmpGroupName; ?>"
                                                                                    type="text" name="Group_Name" id="Group_Name"
                                                                                    class="form-control m-wrap span6"><br>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group col-sm-12">
                                                                            <span class="col-sm-3">GracePeriod</span>
                                                                            <div class="col-sm-6">
                                                                                <input
                                                                                    value="<?php echo $data_set[0]->GracePeriod; ?>"
                                                                                    type="text" name="GRACE_PERIOD" id="GRACE_PERIOD"
                                                                                    class="form-control m-wrap span6"><br>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group col-sm-12">
                                                                            <span class="col-sm-3">Short LEAVE PER MONTH</span>
                                                                            <div class="col-sm-6">
                                                                                <input
                                                                                    value="<?php echo $data_set[0]->NosLeaveForMonth; ?>"
                                                                                    type="text" name="PER_MONTH" id="PER_MONTH"
                                                                                    class="form-control m-wrap span6"><br>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group col-sm-12">
                                                                            <span class="col-sm-3">MAX SHORT LEAVE SIZE</span>
                                                                            <div class="col-sm-6">
                                                                                <?php if ($data_set[0]->MaxSLS == 1) {?>
                                                                                    <input type="checkbox" name="" id="">
                                                                                <?php
                                                                                }?>
                                                                                <input
                                                                                    value="<?php echo $data_set[0]->MaxSLS; ?>"
                                                                                    type="text" name="MAX_SHORT_LEAVE" id="MAX_SHORT_LEAVE"
                                                                                    class="form-control m-wrap span6"><br>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group col-sm-12">
                                                                            <span class="col-sm-3">ALLOW 1 SESSION</span>
                                                                            <div class="col-sm-6">
                                                                                <input
                                                                                    value="<?php echo $data_set[0]->Allow1stSession; ?>"
                                                                                    type="text" name="1_SESSION"
                                                                                    id="1_SESSION"
                                                                                    class="form-control m-wrap span6"><br>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group col-sm-12">
                                                                            <span class="col-sm-3">ALLOW 2 SESSION</span>
                                                                            <div class="col-sm-6">
                                                                                <input
                                                                                    value="<?php echo $data_set[0]->Allow2ndSession; ?>"
                                                                                    type="text" name="2_SESSION" id="2_SESSION"
                                                                                    class="form-control m-wrap span6"><br>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group col-sm-12">
                                                                            <span class="col-sm-3">SUPERVISOR NAME</span>
                                                                            <div class="col-sm-6">
                                                                            <select class="form-control"  name="Sup_ID" id="Sup_ID">
                                                                                    <option><?php echo $data_set[0]->Emp_Full_Name; ?></option>
                                                                                        <?php foreach ($emp_sup as $t_data) {?>
                                                                                            <option value="<?php echo $t_data->Enroll_No; ?>" ><?php echo $t_data->Emp_Full_Name; ?> - <?php echo $t_data->Enroll_No; ?></option>
                                                                                        <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <!-- <div class="form-group col-sm-12">
                                                                            <label for="focusedinput"
                                                                                class="col-sm-4 control-label">Sup_ID</label>
                                                                            <div class="col-sm-8">
                                                                                <input
                                                                                    value="<?php echo $data->Sup_ID; ?>"
                                                                                    type="text" name="Sup_ID"
                                                                                    id="Sup_ID"
                                                                                    class="form-control m-wrap span6"
                                                                                    readonly><br>

                                                                                <select class="form-control"  name="Sup_ID" id="Sup_ID">
                                                                                    <option><?php echo $data_set[0]->Emp_Full_Name; ?></option>
                                                                                        <?php foreach ($emp_sup as $t_data) {?>
                                                                                            <option value="<?php echo $t_data->Enroll_No; ?>" ><?php echo $t_data->Emp_Full_Name; ?> - <?php echo $t_data->Enroll_No; ?></option>
                                                                                        <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div> -->

                                                                        <?php $this->load->view('template/btn_submit.php');?>

                                                                    </form>
                                                                </div>

                                                            </center>

                                                            <div id="divmessage" class="">

                                                                <div id="spnmessage"> </div>
                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                </div>




                            </div>






                        </div> <!-- .container-fluid -->
                    </div>

                    <!--Footer-->
                    <?php $this->load->view('template/footer.php');?>
                    <!--End Footer-->

                </div>
            </div>
        </div>




        <!-- Load site level scripts -->

        <?php $this->load->view('template/js.php');?> <!-- Initialize scripts for this page-->

        <!-- End loading page level scripts-->

        <!--Ajax-->
        <script src="<?php echo base_url(); ?>system_js/Master/OT_Pattern.js"></script>
        <script>
            function createOTPatternArr2() {
                myData = [];
                $("[id^='Day'").each(function () {

                    elementIndex = this.id.replace("Day", "");

                    myData.push({
                        "Day": $("#Day" + elementIndex).val(),
                        "Type": $("#Type" + elementIndex).val(),
                        "chkBSH": $("#chkBSH" + elementIndex).val(),
                        "MinTw": $("#MinTw" + elementIndex).val(),
                        "ChkASH": $("#chkASH" + elementIndex).val(),
                        "ASH_MinTw": $("#ASH_MinTw" + elementIndex).val(),
                        "RoundUp": $("#RoundUp" + elementIndex).val()


                    });

                });

                $("#hdntext2").val(JSON.stringify(myData));
                console.log(JSON.stringify(myData));
            }
        </script>

</body>


</html>