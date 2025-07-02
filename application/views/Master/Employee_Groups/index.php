<!DOCTYPE html>


<!--Description of dashboard page

@author Ashan Rathsara-->


<html lang="en">


    <head>
        <!-- Styles -->
        <?php $this->load->view('template/css.php'); ?>

    </head>

    <body class="infobar-offcanvas">

        <!--header-->

        <?php $this->load->view('template/header.php'); ?>

        <!--end header-->

        <div id="wrapper">
            <div id="layout-static">

                <!--dashboard side-->

                <?php $this->load->view('template/dashboard_side.php'); ?>

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

                                    <li class="active"><a data-toggle="tab" href="#tab1">EMPLOYEE GROUPS</a></li>
                                    <li><a data-toggle="tab" href="#tab2">VIEW EMPLOYEE GROUPS</a></li>


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
                                                            <div class="panel-heading"><h2>ADD EMPLOYEE GROUPS</h2></div>
                                                            <div class="panel-body">
                                                                <form class="form-horizontal" id="frm_emp_group" name="frm_emp_group" action="<?php echo base_url(); ?>Master/Employee_Groups/insert_Data" method="POST">

                                                                    <div class="form-group col-sm-12">
                                                                        <div class="col-sm-8">
                                                                            <img class="imagecss" src="<?php echo base_url(); ?>assets/images/employee_group.png" >
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group col-md-12">

                                                                        <div class="form-group col-sm-6">
                                                                            <label for="focusedinput" class="col-sm-4 control-label">Group Name</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" class="form-control" id="txt_group_name" name="txt_group_name" placeholder="Ex: Office">
                                                                            </div>

                                                                        </div>
                                                                        <div class="form-group col-sm-6">
                                                                            <label for="focusedinput" class="col-sm-4 control-label">Grace Period</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" class="form-control" id="txt_grace_p" name="txt_grace_p" placeholder="Ex: 60">
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-12">
                                                                        <div class="form-group col-sm-6">
                                                                            <label for="focusedinput" class="col-sm-4 control-label">No. Of Short Leave Per Month</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="number" class="form-control" id="txt_sl_per_mth" name="txt_sl_per_mth" placeholder="Ex: 2">
                                                                            </div>

                                                                        </div>

                                                                        <div class="form-group col-sm-6">
                                                                            <label for="focusedinput" class="col-sm-4 control-label">Maximum Short Leave Size</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="number" class="form-control" id="txt_max_l_size" name="txt_max_l_size" placeholder="Ex: 120">
                                                                            </div>

                                                                        </div>

                                                                        <div class="form-group col-sm-6 ">
                                                                            <label for="focusedinput" class="col-sm-4 control-label">Allow First Session</label>
                                                                            <div class="col-sm-8 icheck-flat">
                                                                                <div class="checkbox green icheck">
                                                                                    <label><input type="checkbox" name="chk_1st" id="chk_1st" ></label>
                                                                                </div>
                                                                            </div>

                                                                        </div>

                                                                        <div class="form-group col-sm-6 ">
                                                                            <label for="focusedinput" class="col-sm-4 control-label">Allow Secound Session</label>
                                                                            <div class="col-sm-8 icheck-flat">
                                                                                <div class="checkbox green icheck">
                                                                                    <label><input type="checkbox" name="chk_2nd" id="chk_2nd"></label>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        <div class="form-group col-sm-6">
                                                                            <label for="focusedinput" class="col-sm-4 control-label">OT Pattern</label>
                                                                            <div class="col-sm-8">
                                                                                <select class="form-control" id="cmb_ot_pattern" name="cmb_ot_pattern">

                                                                                    <option value="" default>-- Select --</option>
                                                                                    <?php foreach ($data_ot as $t_data) { ?>
                                                                                        <option value="<?php echo $t_data->OTCode; ?>" ><?php echo $t_data->OTName; ?></option>

                                                                                    <?php }
                                                                                    ?>    



                                                                                </select>
                                                                            </div>

                                                                        </div>
                                                                        
                                                                           <div class="form-group col-sm-6">
                                                                            <label for="focusedinput" class="col-sm-4 control-label">Group Supervisor</label>
                                                                            <div class="col-sm-8">
                                                                                <select class="form-control" id="cmb_Supervisor" name="cmb_Supervisor">

                                                                                    <option value="" default>-- Select --</option>
                                                                                    <?php foreach ($emp_sup as $t_data) { ?>
                                                                                        <option value="<?php echo $t_data->EmpNo; ?>" ><?php echo $t_data->Emp_Full_Name; ?> - <?php echo $t_data->Enroll_No; ?></option>

                                                                                    <?php }
                                                                                    ?>    



                                                                                </select>
                                                                            </div>

                                                                        </div>



                                                                    </div>
                                                                    

                                                                    <!--submit button-->
                                                                    <?php $this->load->view('template/btn_submit.php'); ?>
                                                                    <!--end submit-->


                                                                </form>
                                                                <hr>


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
                                    
                                    
                                     <!--***************************-->
                                    <!-- Grid View -->

                                    <div class="tab-pane" id="tab2">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel panel-primary">
                                                    <div class="col-md-12">
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">
                                                                <h2>USER LEVEL DETAILS</h2>
                                                                <div class="panel-ctrls">
                                                                </div>
                                                            </div>
                                                            <div class="panel-body panel-no-padding">
                                                                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>ID</th>
                                                                            <th>NAME</th>
                                                                            <th>GRACE PERIOD</th>
                                                                            <th>SL PER MONTH</th>
                                                                            <th>MAX SHORT LEAVE SIZE</th>
                                                                            <th>ALLOW 1 SESSION</th>
                                                                            <th>ALLOW 2 SESSION</th>
                                                                            <th>SUPERVISOR NAME</th>
                                                                            
                                                                            <th>EDIT</th>
                                                                            <th>DELETE</th>

                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php foreach ($data_set as $data): ?>
                                                                        <tr class="odd gradeX">
                                                                            <td width="100"><?php echo $data->Grp_ID; ?></td>
                                                                            <td width="100"><?php echo $data->EmpGroupName; ?></td>
                                                                            <td width="100"><?php echo $data->GracePeriod; ?></td>
                                                                            <td width="100"><?php echo $data->NosLeaveForMonth; ?></td>
                                                                            <td width="100"><?php echo $data->MaxSLS; ?>
                                                                            </td>
                                                                            <td width="100"><?php echo $data->Allow1stSession; ?></td>
                                                                            <td width="100"><?php echo $data->Allow2ndSession; ?>
                                                                            </td>
                                                                            <td width="100"><?php echo $data->Emp_Full_Name; ?></td>
                                                                            <td width="15">
                                                                                <?php $url = base_url() . "Master/Employee_Groups/updateAttView?id=$data->Grp_ID"; ?>
                                                                                <a class="edit_data btn btn-green"
                                                                                    href="<?php echo $url; ?>" title="EDIT">
                                                                                    <i class="fa fa-edit"></i> </a>
                                                                            </td>

                                                                            <td width="15">
                                                                            <?php $url = base_url() . "Master/Employee_Groups/Delete?id=$data->Grp_ID"; ?>
                                                                                <a class="edit_data btn btn-danger"
                                                                                    href="<?php echo $url; ?>" title="EDIT">
                                                                                    <i class="fa fa-times-circle"></i></a>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach; ?>
                                                                        <?php
                                                                        // foreach ($data_set as $data) {


                                                                        //     echo "<tr class='odd gradeX'>";


                                                                        //     echo "<td width='100'>" . $data->Grp_ID . "</td>";
                                                                        //     echo "<td width='100'>" . $data->EmpGroupName . "</td>";
                                                                        //     echo "<td width='50'>" . $data->GracePeriod . "</td>";
                                                                        //     echo "<td width='50'>" . $data->NosLeaveForMonth . "</td>";
                                                                        //     echo "<td width='100'>" . $data->MaxSLS . "</td>";
                                                                        //     echo "<td width='50'>" . $data->Allow1stSession . "</td>";
                                                                        //     echo "<td width='50'>" . $data->Allow2ndSession . "</td>";
                                                                        //    echo "<td width='200'>" . $data->Emp_Full_Name . "</td>";

                                                                        //     echo "<td width='15'>";
                                                                        //     echo "<button class='get_data btn btn-green'  data-toggle='modal' data-target='#myModal' title='EDIT' data-id='$data->Grp_ID' href='" . base_url() . "index.php/Master/Employee_Groups/get_details" . $data->Grp_ID . "'><i class='fa fa-edit'></i></button>";
                                                                        //     echo "</td>";

                                                                        //     echo "<td width='15'>";

                                                                        //     echo "<button  class='action_comp btn btn-danger' data-toggle='modal' href='javascript:void()' title='DELETE' onclick='delete_id($data->Grp_ID)'><i class='fa fa-times-circle'></i></a>";


                                                                        //     echo "</td>";

                                                                        //     echo "</tr>";
                                                                        // }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                                <div class="panel-footer"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <!-- End Grid View -->
                                    <!--***************************-->
                                    
                                    
                                    
                                    <!-- Modal -->
                                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h2 class="modal-title">EMPLOYEE GROUP1</h2>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form-horizontal" action="<?php echo base_url(); ?>Master/Employee_Groups/edit" method="post">
                                                    <!-- <div class="form-group col-sm-12">
                                                        <label for="focusedinput" class="col-sm-4 control-label">ID</label>
                                                        <div class="col-sm-8">
                                                            <input value="1" type="text" class="form-control"  name="id" id="id" placeholder="1">
                                                        </div>
                                                    </div> -->

                                                    <div class="form-group col-sm-12">
                                                        <label for="focusedinput" class="col-sm-4 control-label">ID</label>
                                                        <div class="col-sm-8">
                                                            <input value="<?php echo $data->Grp_ID; ?>" type="text" name="id" id="id"  class="form-control m-wrap span6"><br>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-sm-12">
                                                        <label for="focusedinput" class="col-sm-4 control-label">Group Name</label>
                                                        <div class="col-sm-8">
                                                            <input value="<?php echo $data->EmpGroupName; ?>" type="text" name="Group_Name" id="Group_Name"  class="form-control m-wrap span6"><br>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-sm-12">
                                                        <label for="focusedinput" class="col-sm-4 control-label">GRACE PERIOD</label>
                                                        <div class="col-sm-8">
                                                            <input value="<?php echo $data->GracePeriod; ?>" type="text" name="GRACE_PERIOD" id="GRACE_PERIOD"  class="form-control m-wrap span6"><br>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group col-sm-12">
                                                        <label for="focusedinput" class="col-sm-4 control-label">PER MONTH</label>
                                                        <div class="col-sm-8">
                                                            <input value="<?php echo $data->NosLeaveForMonth; ?>" type="text" name="PER_MONTH" id="PER_MONTH"  class="form-control m-wrap span6"><br>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group col-sm-12">
                                                        <label for="focusedinput" class="col-sm-4 control-label">MAX SHORT LEAVE SIZE</label>
                                                        <div class="col-sm-8">
                                                            <input value="<?php echo $data->MaxSLS; ?>" type="text" name="MAX_SHORT_LEAVE" id="MAX_SHORT_LEAVE"  class="form-control m-wrap span6"><br>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group col-sm-12">
                                                        <label for="focusedinput" class="col-sm-4 control-label">ALLOW 1 SESSION</label>
                                                        <div class="col-sm-8">
                                                            <input value="<?php echo $data->Allow1stSession; ?>" type="text" name="1_SESSION" id="1_SESSION"  class="form-control m-wrap span6"><br>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group col-sm-12">
                                                        <label for="focusedinput" class="col-sm-4 control-label">ALLOW 2 SESSION</label>
                                                        <div class="col-sm-8">
                                                            <input value="<?php echo $data->Allow2ndSession; ?>" type="text" name="2_SESSION" id="2_SESSION"  class="form-control m-wrap span6"><br>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-sm-12">
                                                        <label for="focusedinput" class="col-sm-4 control-label">Sup_ID</label>
                                                        <div class="col-sm-8">
                                                        <input value="<?php echo $data->Sup_ID; ?>" type="text" name="Sup_ID" id="Sup_ID"  class="form-control m-wrap span6" readonly><br>

                                                       <!-- <select class="form-control"  name="Sup_ID" id="Sup_ID">
                                                            <option><?php echo $data->Sup_ID; ?></option>
                                                                <?php foreach ($emp_sup as $t_data) { ?>
                                                                    <option value="<?php echo $t_data->Enroll_No; ?>" ><?php echo $t_data->Emp_Full_Name; ?> - <?php echo $t_data->Enroll_No; ?></option>
                                                                <?php }
                                                            ?>        
                                                        </select>     -->
                                                        </div>
                                                    </div>

                                                   


                                            </div>

                                            <br>
                                            <!--<input class="btn green" type="submit" value="submit" id="submit">-->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" id="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                            </form>
                                        </div>

                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                                    
                                    
                                    

                                </div>

                            </div> <!-- .container-fluid -->
                        </div>

                        <!--Footer-->
                        <?php $this->load->view('template/footer.php'); ?>	
                        <!--End Footer-->

                    </div>
                </div>
            </div>




            <!-- Load site level scripts -->

            <?php $this->load->view('template/js.php'); ?>							<!-- Initialize scripts for this page-->

            <!-- End loading page level scripts-->
            
            <!--Ajax-->
            <script src="<?php echo base_url(); ?>system_js/Master/Emp_Group.js"></script>

    </body>


</html>