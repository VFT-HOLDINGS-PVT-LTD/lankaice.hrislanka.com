<!DOCTYPE html>


<!--Description of dashboard page-->


<div class="panel panel-primary">
    <div class="panel panel-default">
        <div class="panel-body panel-no-padding" >
        <button type="button" class='get_data btn btn-primary' onclick="handleApproveAll()">Approve All Selected</button>
            <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>REGION ID</th>
                        <th>EMP NO</th>
                        <th>NAME</th>
                        <th>DATE</th>
                        <th>TIME</th>
                        <!-- <th>OUT TIME</th> -->
                        <th>REASON</th>
                       


                        <th>STATUS</th>
                        <!--<th>EDIT</th>-->
                        <th>APPROVE</th>
                        <th>REJECT</th>

                    </tr>
                </thead>
                <tbody>
                <?php foreach ($data_set as $data) {?>
                        <tr class='odd gradeX'>
                            <td width='15'>
                                <input type='checkbox' class='select-item' value="<?php echo $data->M_ID; ?>">
                            </td>
                            <td width='120'><?php echo $data->B_name; ?></td>
                            <td width='100'><?php echo $data->EmpNo; ?></td>
                            <td width='100'><?php echo $data->Emp_Full_Name; ?></td>
                            <td width='100'><?php echo $data->Att_Date; ?></td>
                            <td width='150'><?php echo $data->In_Time; ?></td>
                            <td width='100'><?php echo $data->Reason; ?></td>
                            <td width='15'><span class='get_data label label-warning'>Pending<i class='fa fa-eye'></i></span></td>
                            <td width='15'><a class='get_data btn btn-primary' href="<?php echo base_url() . 'Attendance/Attendance_Manual_Entry_ADMIN/approve/' . $data->M_ID; ?>">APPROVE<i class=''></i></a></td>
                            <td width='15'><a class='get_data btn btn-danger' href="<?php echo base_url() . 'Attendance/Attendance_Manual_Entry_ADMIN/ajax_StatusReject/' . $data->M_ID; ?>">REJECT<i class=''></i></a></td>
                        </tr>
                    <?php }?>
                    <?php
//                     foreach ($data_set as $data) {



//                         echo "<tr class='odd gradeX'>";
//                         echo "<td width='100'>" . $data->M_ID . "</td>";
//                         echo "<td width='100'>" . $data->EmpNo . "</td>";
//                         echo "<td width='100'>" . $data->Emp_Full_Name . "</td>";
                        
//                         echo "<td width='100'>" . $data->Att_Date . "</td>";
//                         echo "<td width='100'>" . $data->In_Time . "</td>";
//                         // echo "<td width='100'>" . $data->Out_Time . "</td>";
//                         echo "<td width='100'>" . $data->Reason . "</td>";
                     


//                         echo "<td width='15'>";

//                         echo "<span class='get_data label label-warning'>Pending<i class='fa fa-eye'></i> </span>";
//                         echo "</td>";

// //                        echo "<td width='15'>";
// //                        echo "<a class='get_data btn btn-green' href='" . base_url() . "Leave_Transaction/Leave_Approve/edit_lv/" . $data->LV_ID . "'>EDIT<i class=''></i> </a>";
// //                        echo "</td>";

//                         echo "<td width='15'>";
//                         echo "<a class='get_data btn btn-primary' href='" . base_url() . "Attendance/Attendance_Manual_Entry_ADMIN/approve/" . $data->M_ID . "'>APPROVE<i class=''></i> </a>";
//                         echo "</td>";

//                       echo "<td width='15'>";
//                        echo "<a class='get_data btn btn-danger' href='" . base_url() . "Attendance/Attendance_Manual_Entry_ADMIN/ajax_StatusReject/" . $data->M_ID . "'>REJECT<i class=''></i> </a>";
//                        echo "</td>";

//                         echo "</tr>";
//                     }
                    ?>
                </tbody>
            </table>
            <div class="panel-footer"></div>
        </div>
    </div>
</div>
<script>
    document.getElementById('select-all').addEventListener('click', function() {
        var checkboxes = document.querySelectorAll('.select-item');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    });

    function handleApproveAll() {
        var selected = [];
        var checkboxes = document.querySelectorAll('.select-item:checked');
        for (var checkbox of checkboxes) {
            selected.push(checkbox.value);
        }

        if (selected.length > 0) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo base_url(); ?>Attendance/Attendance_Manual_Entry_ADMIN/approveAll';

            for (var id of selected) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                form.appendChild(input);
            }

            document.body.appendChild(form);
            form.submit();
        } else {
            alert('No leave requests selected');
        }
    }
</script>