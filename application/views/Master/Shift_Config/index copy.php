<!DOCTYPE html>


<!--Description of dashboard page

@author Ashan Rathsara-->


<html lang="en">


<head>
    <!-- Styles -->
    <?php $this->load->view('template/css.php'); ?>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"> -->

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
                            <li class="active"><a href="index.html">SHIFTS</a></li>

                        </ol>


                        <div class="page-tabs">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#tab1">SHIFTS</a></li>
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
                                                            <h2>ADD SHIFTS</h2>
                                                        </div>
                                                        <div class="panel-body">
                                                            <form class="form-horizontal" id="frm_shifts" name="frm_shifts" action="<?php echo base_url(); ?>Master/Shifts/insert_Data" method="POST">

                                                                <!--success Message-->
                                                                <?php if (isset($_SESSION['success_message']) && $_SESSION['success_message'] != '') { ?>
                                                                    <div id="spnmessage" class="alert alert-dismissable alert-success">
                                                                        <strong>Success !</strong> <?php echo $_SESSION['success_message'] ?>
                                                                    </div>
                                                                <?php } ?>

                                                                <div class="form-group col-sm-12">
                                                                    <div class="col-sm-8">
                                                                        <img class="imagecss" src="<?php echo base_url(); ?>assets/images/shifts.png">
                                                                    </div>
                                                                </div>

                                                                <form id="assignShiftsForm">
                                                                    <div class="mb-3">
                                                                        <label for="employeeSelect" class="form-label">Select Employee:</label>
                                                                        <select id="employeeSelect" class="form-select" required>
                                                                            <option value="" disabled selected>Choose an employee</option>
                                                                            <option value="1">John Doe</option>
                                                                            <option value="2">Jane Smith</option>
                                                                            <option value="3">Michael Brown</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="container mt-5">
                                                                        <div class="form-group">
                                                                            <label for="searchInput">Search Employee</label>
                                                                            <div class="dropdown-container">
                                                                                <input type="text" id="searchInput" class="form-control" placeholder="Type or click to search">
                                                                                <ul class="dropdown-list" id="dropdownList">
                                                                                    <!-- Dynamic content will appear here -->
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="shiftSelect" class="form-label">Select Shifts:</label>
                                                                        <select id="shiftSelect" class="form-select multi-select" multiple required>
                                                                            <option value="Morning Shift">Morning Shift (8 AM - 4 PM)</option>
                                                                            <option value="Evening Shift">Evening Shift (4 PM - 12 AM)</option>
                                                                            <option value="Night Shift">Night Shift (12 AM - 8 AM)</option>
                                                                        </select>
                                                                    </div>
                                                                    <button type="button" class="btn btn-primary" onclick="assignShifts()">Assign Shifts</button>
                                                                </form>
                                                                <hr>
                                                                <h3>Assigned Shifts</h3>
                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Employee Name</th>
                                                                            <th>Assigned Shifts</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="assignedShiftsTable">
                                                                        <!-- Assigned shifts will appear here -->
                                                                    </tbody>
                                                                </table>


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
                                                            <h2>VIEW SHIFT DETAILS</h2>
                                                            <div class="panel-ctrls">
                                                            </div>
                                                        </div>
                                                        <div class="panel-body panel-no-padding">
                                                            <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>SHIFT CODE</th>
                                                                        <th>NAME</th>
                                                                        <th>FROM TIME</th>
                                                                        <th>TO TIME</th>
                                                                        <th>DAY TYPE</th>
                                                                        <th>NEXT DAY</th>
                                                                        <th>SHIFT GAP</th>


                                                                        <th>EDIT</th>
                                                                        <th>DELETE</th>

                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    foreach ($data_set as $data) {

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


                                                                        echo "<td width='100'>" . $data->ShiftCode . "</td>";
                                                                        echo "<td width='100'>" . $data->ShiftName . "</td>";
                                                                        echo "<td width='100'>" . $data->FromTime . "</td>";
                                                                        echo "<td width='100'>" . $data->ToTime . "</td>";
                                                                        echo "<td width='100'>" . $day . "</td>";
                                                                        echo "<td width='100'>" . $daytype . "</td>";
                                                                        echo "<td width='100'>" . $data->ShiftGap . "</td>";


                                                                        echo "<td width='15'>";
                                                                        echo "<button class='get_data btn btn-green'  data-toggle='modal' data-target='#myModal' title='EDIT' data-id='$data->ShiftCode' href='" . base_url() . "index.php/Master/Department/get_details" . $data->ShiftCode . "'><i class='fa fa-edit'></i></button>";
                                                                        echo "</td>";

                                                                        echo "<td width='15'>";

                                                                        echo "<button  class='action_comp btn btn-danger' data-toggle='modal' href='javascript:void()' title='DELETE' onclick='delete_id($data->ShiftCode)'><i class='fa fa-times-circle'></i></a>";


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
                            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h2 class="modal-title">SHIFTS</h2>
                                        </div>
                                        <div class="modal-body">
                                            <form class="form-horizontal" action="<?php echo base_url(); ?>Master/Shifts/edit" method="post">
                                                <div class="form-group col-sm-12">
                                                    <label for="focusedinput" class="col-sm-4 control-label">SHIFT CODE</label>
                                                    <div class="col-sm-8">
                                                        <input value="<?php echo $data->ShiftCode; ?>" type="text" class="form-control" readonly="readonly" name="ShiftCode" id="ShiftCode" class="m-wrap span3">
                                                    </div>
                                                </div>

                                                <div class="form-group col-sm-12">
                                                    <label for="focusedinput" class="col-sm-4 control-label">NAME</label>
                                                    <div class="col-sm-8">
                                                        <input value="<?php echo $data->ShiftName; ?>" type="text" name="ShiftName" id="ShiftName" class="form-control m-wrap span6"><br>
                                                    </div>
                                                </div>

                                                <div class="form-group col-sm-12">
                                                    <label for="focusedinput" class="col-sm-4 control-label">FROM TIME</label>
                                                    <div class="col-sm-8">
                                                        <input value="<?php echo $data->FromTime; ?>" type="time" name="FromTime" id="FromTime" class="form-control m-wrap span6"><br>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="focusedinput" class="col-sm-4 control-label">TO TIME</label>
                                                    <div class="col-sm-8">
                                                        <input value="<?php echo $data->ToTime; ?>" type="time" name="ToTime" id="ToTime" class="form-control m-wrap span6"><br>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="focusedinput" class="col-sm-4 control-label">SHIFT GAP</label>
                                                    <div class="col-sm-8">
                                                        <input value="<?php echo $data->ShiftGap; ?>" type="text" name="ShiftGap" id="ShiftGap" class="form-control m-wrap span6"><br>
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



                        </div> <!-- .container-fluid -->
                    </div>

                    <!--Footer-->
                    <?php $this->load->view('template/footer.php'); ?>
                    <!--End Footer-->

                </div>
            </div>
        </div>




        <!-- Load site level scripts -->

        <?php $this->load->view('template/js.php'); ?> <!-- Initialize scripts for this page-->

        <!-- End loading page level scripts-->

        <!--Ajax-->
        <script src="<?php echo base_url(); ?>system_js/Master/Shifts.js"></script>
        <script>
        $(document).ready(function () {
            // Sample data
            const employees = [
                "John Doe",
                "Jane Smith",
                "Michael Brown",
                "Sarah Connor",
                "James Bond",
                "Tony Stark",
                "Bruce Wayne",
                "Diana Prince",
                "Clark Kent",
                "Natasha Romanoff"
            ];

            const $input = $('#searchInput');
            const $dropdown = $('#dropdownList');

            // Function to display the dropdown with filtered results
            const showDropdown = (filter = "") => {
                const filteredEmployees = employees.filter(emp => 
                    emp.toLowerCase().includes(filter.toLowerCase())
                );

                // Populate the dropdown
                $dropdown.empty();
                if (filteredEmployees.length > 0) {
                    filteredEmployees.forEach(emp => {
                        $dropdown.append(`<li>${emp}</li>`);
                    });
                } else {
                    $dropdown.append('<li>No results found</li>');
                }
                $dropdown.show();
            };

            // Event to show all results when input is focused
            $input.on('focus', () => {
                showDropdown();
            });

            // Event to filter results as user types
            $input.on('input', function () {
                const query = $(this).val();
                showDropdown(query);
            });

            // Event to hide dropdown when clicking outside
            $(document).on('click', function (e) {
                if (!$(e.target).closest('.dropdown-container').length) {
                    $dropdown.hide();
                }
            });

            // Event to set input value when a dropdown item is clicked
            $dropdown.on('click', 'li', function () {
                const selectedValue = $(this).text();
                if (selectedValue !== 'No results found') {
                    $input.val(selectedValue);
                }
                $dropdown.hide();
            });
        });
    </script>
        <script>
            const assignShifts = () => {
                const employeeSelect = document.getElementById('employeeSelect');
                const shiftSelect = document.getElementById('shiftSelect');
                const assignedShiftsTable = document.getElementById('assignedShiftsTable');

                const employeeName = employeeSelect.options[employeeSelect.selectedIndex]?.text;
                const selectedShifts = Array.from(shiftSelect.selectedOptions).map(option => option.text);

                if (!employeeName || selectedShifts.length === 0) {
                    alert('Please select an employee and at least one shift.');
                    return;
                }

                const row = document.createElement('tr');
                const employeeCell = document.createElement('td');
                employeeCell.textContent = employeeName;
                const shiftsCell = document.createElement('td');
                shiftsCell.textContent = selectedShifts.join(', ');

                row.appendChild(employeeCell);
                row.appendChild(shiftsCell);
                assignedShiftsTable.appendChild(row);

                // Reset form
                employeeSelect.value = '';
                shiftSelect.value = '';
            };
        </script>

</body>


</html>