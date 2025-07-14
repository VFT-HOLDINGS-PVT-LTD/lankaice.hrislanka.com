<!DOCTYPE html>


<!--Description of dashboard page

@authorAshanRathsara-->


<html lang="en">


<head>
    <!-- Styles -->
<?php $this->load->view('template/css.php'); ?>
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"> -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />


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

                            <li class=""><a href="<?php echo base_url(); ?>Dashboard/">HOME</a></li>
                            <li class=""><a href="<?php echo base_url(); ?>Master/Shift_config/">ASSIGNED SHIFTS</a>
                            </li>

                        </ol>


                        <div class="page-tabs">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#tab1">ASSIGNED SHIFTS</a></li>
                                <li><a data-toggle="tab" href="#tab2">VIEW SHIFTS</a></li>
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
                                                            <h2>ASSIGNED SHIFTS</h2>
                                                        </div>
                                                        <div class="panel-body">
                                                            <form class="form-horizontal" id="frm_shifts"
                                                                name="frm_shifts"
                                                                action="<?php echo base_url(); ?>Master/Shifts/insert_Data"
                                                                method="POST">

                                                                <!--success Message-->
                                                                <?php if (isset($_SESSION['success_message']) && $_SESSION['success_message'] != '') { ?>
                                                                <div id="spnmessage"
                                                                    class="alert alert-dismissable alert-success">
                                                                    <strong>Success !</strong>
                                                                    <?php echo $_SESSION['success_message'] ?>
                                                                </div>
                                                                <?php } ?>

                                                                <div class="form-group col-sm-12">
                                                                    <div class="col-sm-8">
                                                                        <img class="imagecss"
                                                                            src="<?php echo base_url(); ?>assets/images/shifts.png">
                                                                    </div>
                                                                </div>

                                                                <form id="assignShiftsForm">
                                                                    <div class="row">
                                                                        <div class="form-group col-sm-8">
                                                                            <div id="dynamic-fields"></div>

                                                                            <!-- <label for="focusedinput"
                                                                            class="col-sm-4 control-label">Category</label> -->
                                                                            <div class="col-sm-1"
                                                                                style="display: none;">
                                                                                <select class="form-control" required
                                                                                    id="cmb_cat" name="cmb_cat"
                                                                                    onchange="selctcity()">
                                                                                    <option value="" default>-- Select
                                                                                        --
                                                                                    </option>
                                                                                    <option value="Employee">Employee
                                                                                    </option>
                                                                                    <option value="Department">
                                                                                        Department
                                                                                    </option>
                                                                                    <option value="Designation">
                                                                                        Designation
                                                                                    </option>
                                                                                    <option value="Employee_Group">
                                                                                        Employee_Group</option>
                                                                                    <option value="Company">Company
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                        </div>


                                                                        <div class="mb-3 col-sm-4">
                                                                            <label for="shiftSelect"
                                                                                class="form-label col-sm-3">Select
                                                                                Shifts:</label>
                                                                            <select id="shiftSelect"
                                                                                class="form-select col-sm-9 multi-select"
                                                                                multiple required size="10">
                                                                                <?php foreach ($data_set as $shifts): ?>
                                                                                <option
                                                                                    value="<?php echo $shifts->ShiftCode ?>">
                                                                                    <?php echo $shifts->ShiftName . " " . $shifts->FromTime . "-" . $shifts->ToTime ?>
                                                                                </option>
                                                                                <?php endforeach; ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <button type="button" class="btn btn-primary"
                                                                        onclick="assignShifts()">Assign Shifts</button>
                                                                </form>
                                                                <!-- <hr> -->
                                                                <!-- <h3>Assigned Shifts</h3> -->



                                                                <!--submit button-->
                                                                <!-- <?php $this->load->view('template/btn_submit.php'); ?> -->
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
                                                            <h2>VIEW SHIFT DETAILS</h2>
                                                            <div class="panel-ctrls">
                                                            </div>
                                                        </div>
                                                        <div class="panel-body panel-no-padding">
                                                            <table id="example"
                                                                class="table table-striped table-bordered"
                                                                cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>EMPLOYEE NO.</th>
                                                                        <th>EMP NAME</th>
                                                                        <th>SHIFT CODE</th>
                                                                        <th>NAME</th>
                                                                        <th>FROM TIME</th>
                                                                        <th>TO TIME</th>
                                                                        <th>DAY TYPE</th>
                                                                        <th>NEXT DAY</th>
                                                                        <th>DELETE</th>

                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    foreach ($data_set2 as $data) {

                                                                        if ($data->NextDay == 1) {
                                                                            $day = "Next Day";
                                                                        } elseif ($data->NextDay == 0) {
                                                                            $day = "Same Day";
                                                                        }

                                                                        if ($data->DayType == 1) {
                                                                            $daytype = "Full Day ";
                                                                        } elseif ($data->DayType == 0.5) {
                                                                            $daytype = "Half Day";
                                                                        }

                                                                        echo "<tr class='odd gradeX'>";

                                                                        echo "<td width='100'>" . $data->EmpNo . "</td>";
                                                                        echo "<td width='100'>" . $data->Emp_Full_Name . "</td>";
                                                                        echo "<td width='100'>" . $data->ShiftCode . "</td>";
                                                                        echo "<td width='100'>" . $data->ShiftName . "</td>";
                                                                        echo "<td width='100'>" . $data->FromTime . "</td>";
                                                                        echo "<td width='100'>" . $data->ToTime . "</td>";
                                                                        echo "<td width='100'>" . $day . "</td>";
                                                                        echo "<td width='100'>" . $daytype . "</td>";


                                                                        

                                                                        echo "<td width='15'>";

                                                                        echo "<button  class='action_comp btn btn-danger' data-toggle='modal' href='javascript:void()' title='DELETE' onclick='delete_id($data->ID)'><i class='fa fa-times-circle'></i></a>";


                                                                        echo "</td>";

                                                                        echo "</tr>";
                                                                    }
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

                            </div>


                            <!-- Modal -->
                            <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">&times;</button>
                                            <h2 class="modal-title">SHIFTS</h2>
                                        </div>
                                        <div class="modal-body">
                                            <form class="form-horizontal"
                                                action="<?php echo base_url(); ?>Master/Shifts/edit" method="post">
                                                <div class="form-group col-sm-12">
                                                    <label for="focusedinput" class="col-sm-4 control-label">SHIFT
                                                        CODE</label>
                                                    <div class="col-sm-8">
                                                        <input value="<?php echo $data->ShiftCode; ?>" type="text"
                                                            class="form-control" readonly="readonly" name="ShiftCode"
                                                            id="ShiftCode" class="m-wrap span3">
                                                    </div>
                                                </div>

                                                <div class="form-group col-sm-12">
                                                    <label for="focusedinput"
                                                        class="col-sm-4 control-label">NAME</label>
                                                    <div class="col-sm-8">
                                                        <input value="<?php echo $data->ShiftName; ?>" type="text"
                                                            name="ShiftName" id="ShiftName"
                                                            class="form-control m-wrap span6"><br>
                                                    </div>
                                                </div>

                                                <div class="form-group col-sm-12">
                                                    <label for="focusedinput" class="col-sm-4 control-label">FROM
                                                        TIME</label>
                                                    <div class="col-sm-8">
                                                        <input value="<?php echo $data->FromTime; ?>" type="time"
                                                            name="FromTime" id="FromTime"
                                                            class="form-control m-wrap span6"><br>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="focusedinput" class="col-sm-4 control-label">TO
                                                        TIME</label>
                                                    <div class="col-sm-8">
                                                        <input value="<?php echo $data->ToTime; ?>" type="time"
                                                            name="ToTime" id="ToTime"
                                                            class="form-control m-wrap span6"><br>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="focusedinput" class="col-sm-4 control-label">SHIFT
                                                        GAP</label>
                                                    <div class="col-sm-8">
                                                        <input value="<?php echo $data->ShiftGap; ?>" type="text"
                                                            name="ShiftGap" id="ShiftGap"
                                                            class="form-control m-wrap span6"><br>
                                                    </div>
                                                </div>


                                        </div>

                                        <br>
                                        <!--<input class="btn green" type="submit" value="submit" id="submit">-->
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" id="submit" class="btn btn-primary">Save
                                                changes</button>
                                        </div>
                                        </form>
                                    </div>

                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->



                        </div> <!-- .container-fluid -->
                    </div>

                    <!--Footer-->
                    <?php $this->load->view('template/footer.php'); ?>
                    <!--End Footer-->

                </div>
            </div>
        </div>




        <!-- Load site level scripts -->

        <?php $this->load->view('template/js.php'); ?>
        <!-- Initialize scripts for this page-->

        <!-- End loading page level scripts-->

        <!--Ajax-->
        <!-- <script src="<?php echo base_url(); ?>system_js/Master/Shifts_Config.js"></script> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('.itemName').select2({
                    placeholder: '--- Find ---',
                    ajax: {
                        url: "<?php echo base_url(); ?>Leave_Transaction/Leave_Entry/search",
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    }
                });

                $('#txt_nic').on('change', function () {
                    var empNo = $(this).val();
                    if (empNo) {
                        $.ajax({
                            url: '<?php echo base_url(); ?>Leave_Transaction/Leave_Entry/get_mem_data/' +
                                empNo,
                            type: "GET",
                            dataType: "json",
                            success: function (data) {
                                if (data.length > 0) {
                                    $('#txt_emp_name').val(data[0].Emp_Full_Name);
                                }
                            }
                        });
                    }
                });

                $('#cmb_cat').on('change', function () {
                    var selectedValue = $(this).val();
                    var dynamicFields = $('#dynamic-fields');
                    dynamicFields.empty();

                    // if (selectedValue === 'Employee') {
                    dynamicFields.html(`
                        <div class="form-group col-sm-6">
                            <label for="" class="col-sm-4 control-label">Emp Number</label>
                            <div class="col-sm-8">
                                <select type="text" required="required" autocomplete="off" class="form-control txt_nic itemName" name="txt_nic" id="txt_nic" placeholder="">
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="txt_emp_name" class="col-sm-4 control-label">Selected Emp Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="txt_emp_name" name="txt_emp_name" placeholder="Selected Emp Name" readonly>
                            </div>
                        </div>
                    `);

                    $('.itemName').select2({
                        placeholder: '--- Find ---',
                        ajax: {
                            url: "<?php echo base_url(); ?>Leave_Transaction/Leave_Entry/search",
                            dataType: 'json',
                            delay: 250,
                            processResults: function (data) {
                                return {
                                    results: data
                                };
                            },
                            cache: true
                        }
                    });

                    $('#txt_nic').on('change', function () {
                        var empNo = $(this).val();
                        if (empNo) {
                            $.ajax({
                                url: '<?php echo base_url(); ?>Leave_Transaction/Leave_Entry/get_mem_data/' +
                                    empNo,
                                type: "GET",
                                dataType: "json",
                                success: function (data) {
                                    if (data.length > 0) {
                                        $('#txt_emp_name').val(data[0]
                                            .Emp_Full_Name);
                                    }
                                }
                            });
                        }
                    });
                    // } else {
                    //     dynamicFields.html(`
                    //         <div class="form-group col-sm-6">
                    //             <label for="" class="col-sm-4 control-label">Select</label>
                    //             <div class="col-sm-8" id="cat_div">
                    //                 <select class="form-control" required id="cmb_cat2" name="cmb_cat2">
                    //                 </select>
                    //             </div>
                    //         </div>
                    //     `);

                    //     $.post('<?php echo base_url(); ?>index.php/Pay/Allowance/dropdown/', { cmb_cat: selectedValue }, function (data) {
                    //         $('#cmb_cat2').html(data);
                    //     });
                    // }
                });

                $("#cmb_cat").trigger("change");
            });
        </script>

        <!--JQuary Validation-->
        <script type="text/javascript">
            $(document).ready(function () {
                $("#frm_shifts").validate();
                $("#spnmessage").hide("shake", {
                    times: 4
                }, 1500);
            });
        </script>
        <script>
            const assignShifts = () => {
                const employeeSelect = document.getElementById('txt_nic');
                const shiftSelect = document.getElementById('shiftSelect');

                // Collect selected shift values into an array
                const selectedShifts = Array.from(shiftSelect.selectedOptions).map(option => option.value);

                $.ajax({
                    url: '<?php echo base_url(); ?>Master/Shift_config/insert_data',
                    type: "POST",
                    contentType: 'application/json',
                    data: JSON.stringify({
                        employeeName: employeeSelect.value,
                        shiftSelect: selectedShifts // Send as an array
                    }),
                    success: function (response) {
                        if (response == 1) {
                            alert("Data Added");
                            location.reload();
                        } else if (response == 2) {
                            alert("Please Select Employee or Shift");
                        } else if (response == 3) {
                            alert("Shift Already Assigned");
                        }
                    },
                    error: function (error) {
                        console.error('Error in request:', error);
                    }
                });
            };

            // Newly created shift config
            function delete_id(id) {
                swal({
                        title: "Are you sure?",
                        text: "You will not be able to recover this data!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, Delete This!",
                        cancelButtonText: "No, Cancel This!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function (isConfirm) {
                        if (isConfirm) {

                            $.ajax({
                                url: baseurl + "index.php/Master/Shift_config/ajax_delete/" + id,
                                type: "POST",
                                dataType: "JSON",
                                success: function (data) {

                                    // if success reload ajax table
                                    $('#modal_form').modal('hide');
                                    location.reload();
                                }

                            });

                            swal("Deleted!", "Selected data has been deleted.", "success");

                            $(document).ready(function () {
                                setTimeout(function () {
                                    window.location.replace(baseurl + "Master/Shift_Config/");
                                }, 1000);
                            });

                        } else {
                            swal("Cancelled", "Selected data Cancelled", "error");
                        }
                    });
            }
        </script>




</body>


</html>