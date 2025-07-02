<!DOCTYPE html>
<!--Description of dashboard page
...
-->

<html lang="en">
<head>
    <title><?php echo $title ?></title>
    <!-- Styles -->
    <?php $this->load->view('template/css.php'); ?>
    <style>
        /* Add this CSS inside the head tag */
        .coming-soon {
            position: relative;
            text-align: center;
            margin-top: 50px;
            font-size: 3em;
            color: #333;
            animation: fadeIn 3s infinite;
        }

        @keyframes fadeIn {
            0%, 100% { opacity: 0; }
            50% { opacity: 1; }
        }
    </style>
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
                    <!-- Add your coming soon animation here -->
                    <div class="coming-soon">
                        Coming Soon...
                    </div>
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
    <!--Clear Text Boxes-->
    <script type="text/javascript">
        $("#cancel").click(function () {
            $("#txt_emp").val("");
            $("#txt_emp_name").val("");
            $("#cmb_desig").val("");
            $("#cmb_dep").val("");
            $("#cmb_comp").val("");
            $("#txt_nic").val("");
            $("#cmb_gender").val("");
            $("#cmb_status").val("");
        });
    </script>

    <!--Date Format-->
    <script>
        $('#dpd1').datepicker({
            format: "dd/mm/yyyy",
            "todayHighlight": true,
            autoclose: true,
            format: 'yyyy/mm/dd'
        }).on('changeDate', function (ev) {
            $(this).datepicker('hide');
        });
        $('#dpd2').datepicker({
            format: "dd/mm/yyyy",
            "todayHighlight": true,
            autoclose: true,
            format: 'yyyy/mm/dd'
        }).on('changeDate', function (ev) {
            $(this).datepicker('hide');
        });
    </script>

    <!--JQuary Validation-->
    <script type="text/javascript">
        $(document).ready(function () {
            $("#frm_in_out_rpt").validate();
            $("#spnmessage").hide("shake", {times: 4}, 1500);
        });
    </script>

    <!--Auto complete-->
    <script type="text/javascript">
        $(function () {
            $("#txt_emp_name").autocomplete({
                source: "<?php echo base_url(); ?>Reports/Attendance/Report_Attendance_In_Out/get_auto_emp_name"
            });
        });

        $(function () {
            $("#txt_emp").autocomplete({
                source: "<?php echo base_url(); ?>Reports/Attendance/Report_Attendance_In_Out/get_auto_emp_no"
            });
        });
    </script>

</body>
</html>
