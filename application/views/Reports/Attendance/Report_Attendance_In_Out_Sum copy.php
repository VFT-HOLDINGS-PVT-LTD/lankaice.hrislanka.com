<!DOCTYPE html>
<!--Description of dashboard page

@author Ashan Rathsara-->

<html lang="en">



<head>
    <title>
        <?php echo $title ?>
    </title>
    <!-- Styles -->
    <?php $this->load->view('template/css.php'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #4f46e5;
            --primary-dark: #3730a3;
            --secondary-color: #06b6d4;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
            --border-color: #e2e8f0;
            --text-primary: #1a202c;
            --text-secondary: #64748b;
            --gradient-primary: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --gradient-secondary: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            --gradient-success: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --gradient-danger: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px rgba(0, 0, 0, 0.1);
            --border-radius-sm: 8px;
            --border-radius-md: 12px;
            --border-radius-lg: 16px;
            --border-radius-xl: 24px;
        }

        /* body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            line-height: 1.6;
        } */

        /* .container {
            max-width: 1400px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius-xl);
            box-shadow: var(--shadow-xl);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        } */

        /* Header Section */
        .form-header {
            background: var(--gradient-primary);
            padding: 32px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .form-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            pointer-events: none;
        }

        .header-content {
            position: relative;
            z-index: 1;
        }

        .header-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 16px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
        }

        .header-title {
            color: white;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .header-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            font-weight: 400;
        }

        /* Form Body */
        .form-body {
            padding: 32px;
        }

        .form-section {
            margin-bottom: 32px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 20px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gradient-primary);
            border-radius: 6px;
            color: white;
            font-size: 14px;
        }

        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .form-grid-2 {
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        }

        .form-grid-4 {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }

        /* Form Group */
        .form-group {
            position: relative;
        }

        .form-label {
            display: block;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-label .required {
            color: var(--danger-color);
        }

        /* Input Styles */
        .form-input, .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius-md);
            font-size: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            color: var(--text-primary);
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            transform: translateY(-1px);
        }

        .form-input:hover, .form-select:hover {
            border-color: var(--primary-color);
        }

        .form-input::placeholder {
            color: var(--text-secondary);
        }

        /* Date Input Styles */
        .date-input-wrapper {
            position: relative;
        }

        .date-input-wrapper::after {
            content: '\f073';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            pointer-events: none;
        }

        /* Select Styles */
        .form-select {
            cursor: pointer;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            padding-right: 40px;
            appearance: none;
        }

        /* Column Selection */
        .columns-section {
            background: var(--light-color);
            border-radius: var(--border-radius-lg);
            padding: 24px;
            border: 1px solid var(--border-color);
        }

        .select-all-section {
            background: var(--gradient-primary);
            border-radius: var(--border-radius-md);
            padding: 20px;
            margin-bottom: 24px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .select-all-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            pointer-events: none;
        }

        .select-all-label {
            color: white;
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            cursor: pointer;
            position: relative;
            z-index: 1;
        }

        .columns-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
        }

        .column-item {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius-md);
            padding: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .column-item:hover {
            border-color: var(--primary-color);
            background: #f1f5f9;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .column-item.checked {
            background: var(--gradient-primary);
            border-color: var(--primary-color);
            color: white;
        }

        .column-item.checked::after {
            content: '✓';
            position: absolute;
            top: 8px;
            right: 8px;
            width: 24px;
            height: 24px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: bold;
        }

        .column-label {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            font-weight: 500;
            font-size: 15px;
        }

        .column-item.checked .column-label {
            color: white;
        }

        /* Custom Checkbox */
        .checkbox-custom {
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid var(--border-color);
            border-radius: 6px;
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            flex-shrink: 0;
        }

        .checkbox-custom:checked {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .checkbox-custom:checked::before {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
        }

        .column-item.checked .checkbox-custom {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .column-item.checked .checkbox-custom:checked {
            background: white;
            border-color: white;
        }

        .column-item.checked .checkbox-custom:checked::before {
            color: var(--primary-color);
        }

        /* Stats Bar */
        .stats-bar {
            background: white;
            border-radius: var(--border-radius-md);
            padding: 16px;
            margin-top: 24px;
            text-align: center;
            border: 1px solid var(--border-color);
        }

        .stats-text {
            color: var(--text-secondary);
            font-weight: 500;
        }

        .selected-count {
            color: var(--primary-color);
            font-weight: 700;
        }

        /* Button Styles */
        .form-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
        }

        .btn {
            padding: 14px 32px;
            border: none;
            border-radius: var(--border-radius-md);
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            position: relative;
            overflow: hidden;
            min-width: 160px;
            justify-content: center;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-success {
            background: var(--gradient-success);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-danger {
            background: var(--gradient-danger);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Animation Classes */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .pulse-animation {
            animation: pulse 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: var(--border-radius-lg);
            }
            
            .form-header {
                padding: 24px;
            }
            
            .header-title {
                font-size: 24px;
            }
            
            .form-body {
                padding: 24px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
                align-items: stretch;
            }
            
            .btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .form-header {
                padding: 20px;
            }
            
            .header-icon {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }
            
            .header-title {
                font-size: 20px;
            }
            
            .form-body {
                padding: 20px;
            }
            
            .columns-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Loading States */
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
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
                    <div class="page-content">
                        <ol class="breadcrumb">
                            <li class=""><a href="">HOME</a></li>
                            <li class="active"><a href="">ATTENDANCE IN OUT REPORT</a></li>
                        </ol>

                        <div class="container-fluid">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">

                                    <div class="row">
                                        <div class="col-xs-12">


                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="panel panel-primary">
                                                        <div class="panel-heading">
                                                            <h2>ATTENDANCE IN OUT REPORT</h2>
                                                        </div>

                                                        <div class="panel-body">
                                                            <div style="font-size: 15px; font-weight: bold;"
                                                                class="bg-bright">REPORT BY CATEGORIES</div>

                                                            <!-- <form action="<?php echo base_url(); ?>Reports/Attendance/Report_Attendance_ATT_Sum/Attendance_Report_By_Cat" class="form-horizontal" id="frm_in_out_rpt" name="frm_in_out_rpt" method="POST">

                                                                <div class="form-group col-sm-12">
                                                                    <div class="col-sm-6">
                                                                        <img class="imagecss1" src="<?php echo base_url(); ?>assets/images/attendance_inout.png">
                                                                    </div>

                                                                </div>

                                                                <div class="form-group col-md-12">
                                                                    <div class="form-group col-sm-3">
                                                                        <label for="focusedinput" class="col-sm-4 control-label">Emp
                                                                            No</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" name="txt_emp" id="txt_emp" placeholder="Ex: 0001">
                                                                        </div>

                                                                    </div>
                                                                    <div class="form-group col-sm-3">
                                                                        <label for="focusedinput" class="col-sm-4 control-label">Emp
                                                                            Name</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" name="txt_emp_name" id="txt_emp_name" placeholder="Ex: Ashan">
                                                                        </div>

                                                                    </div>
                                                                    <div class="form-group col-sm-3">
                                                                        <label for="focusedinput" class="col-sm-4 control-label">Designation</label>
                                                                        <div class="col-sm-8">
                                                                            <select class="form-control" id="cmb_desig" name="cmb_desig">

                                                                                <option value="" default>-- Select --
                                                                                </option>
                                                                                <?php foreach ($data_desig as $t_data) { ?>
                                                                                    <option value="<?php echo $t_data->Des_ID; ?>">
                                                                                        <?php echo $t_data->Desig_Name; ?>
                                                                                    </option>

                                                                                <?php }
                                                                                ?>

                                                                            </select>
                                                                        </div>

                                                                    </div>
                                                                    <div class="form-group col-sm-3">
                                                                        <label for="focusedinput" class="col-sm-4 control-label">Department</label>
                                                                        <div class="col-sm-8">
                                                                            <select class="form-control" id="cmb_dep" name="cmb_dep">


                                                                                <option value="" default>-- Select --
                                                                                </option>
                                                                                <?php foreach ($data_dep as $t_data) { ?>
                                                                                    <option value="<?php echo $t_data->Dep_ID; ?>">
                                                                                        <?php echo $t_data->Dep_Name; ?>
                                                                                    </option>

                                                                                <?php }
                                                                                ?>

                                                                            </select>
                                                                        </div>

                                                                    </div>


                                                                    <div class="form-group col-sm-3">
                                                                        <label for="focusedinput" class="col-sm-4 control-label">Region</label>
                                                                        <div class="col-sm-8">
                                                                            <select class="form-control" id="cmb_branch" name="cmb_branch">


                                                                                <option value="" default>-- Select --
                                                                                </option>
                                                                                <?php foreach ($data_branch as $t_data) { ?>
                                                                                    <option value="<?php echo $t_data->B_id; ?>">
                                                                                        <?php echo $t_data->B_name; ?>
                                                                                    </option>

                                                                                <?php }
                                                                                ?>

                                                                            </select>
                                                                        </div>

                                                                    </div>


                                                                    <div class="form-group col-sm-3">
                                                                        <label for="focusedinput" class="col-sm-4 control-label">Company</label>
                                                                        <div class="col-sm-8">
                                                                            <select class="form-control" id="cmb_comp" name="cmb_comp">


                                                                                <option value="" default>-- Select --
                                                                                </option>
                                                                                <?php foreach ($data_cmp as $t_data) { ?>
                                                                                    <option value="<?php echo $t_data->Cmp_ID; ?>">
                                                                                        <?php echo $t_data->Company_Name; ?>
                                                                                    </option>

                                                                                <?php }
                                                                                ?>

                                                                            </select>
                                                                        </div>

                                                                    </div>
                                                                    <div class="form-group col-sm-3">
                                                                        <label for="focusedinput" class="col-sm-4 control-label">Super EmpGroup</label>
                                                                        <div class="col-sm-8">
                                                                            <select class="form-control" id="cmb_grop" name="cmb_grop">


                                                                                <option value="" default>-- Select --</option>
                                                                                <?php foreach ($data_group as $t_data) { ?>
                                                                                    <option value="<?php echo $t_data->id; ?>"><?php echo $t_data->super_gname; ?></option>

                                                                                <?php }
                                                                                ?>

                                                                            </select>
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-md-6">
                                                                    <div class="form-group col-sm-6">
                                                                        <label for="focusedinput" class="col-sm-4 control-label">From
                                                                            Date</label>
                                                                        <div class="col-sm-8">


                                                                            <input type="text" class="form-control" required="" id="dpd1" name="txt_from_date" placeholder="Select Date">


                                                                        </div>

                                                                    </div>

                                                                    <div class="form-group col-sm-6" style="">
                                                                        <label for="focusedinput" class="col-sm-4 control-label">To
                                                                            Date</label>
                                                                        <div class="col-sm-8">


                                                                            <input type="text" class="form-control" required="" id="dpd2" name="txt_to_date" placeholder="Select Date">


                                                                        </div>

                                                                    </div>
                                                                </div>


                                                                <div class="col-sm-6">
                                                                    <input type="submit" id="search" name="search" formtarget="_new" class="btn-green btn fa fa-check" value="&nbsp;&nbsp;VIEW&nbsp;PDF REPORT">

                                                                    <input type="button" id="cancel" name="cancel" class="btn-danger-alt btn fa fa-check" value="&nbsp;&nbsp;CLEAR">
                                                                </div>
                                                            </form> -->

                                                            <!-- <form action="<?php echo base_url('Reports/Attendance/Report_Attendance_ATT_Sum/Attendance_Report_By_Cat') ?>" method="POST" target="_blank">
    <label><input type="checkbox" name="columns[]" value="EmpNo" checked> EMP NO</label>
    <label><input type="checkbox" name="columns[]" value="Emp_Full_Name" checked> NAME</label>
    <label><input type="checkbox" name="columns[]" value="FDate"> FROM DATE</label>
    <label><input type="checkbox" name="columns[]" value="FTime"> FROM TIME</label>
    <label><input type="checkbox" name="columns[]" value="TDate"> TO DATE</label>
    <label><input type="checkbox" name="columns[]" value="TTime"> TO TIME</label>
    <label><input type="checkbox" name="columns[]" value="InTime"> IN TIME</label>
    <label><input type="checkbox" name="columns[]" value="OutTime"> OUT TIME</label>
    <label><input type="checkbox" name="columns[]" value="BreackInTime1"> BREAK IN</label>
    <label><input type="checkbox" name="columns[]" value="BreackOutTime1"> BREAK OUT</label>
    <label><input type="checkbox" name="columns[]" value="DayStatus"> STATUS</label>
    <label><input type="checkbox" name="columns[]" value="AfterExH"> OT</label>
    <label><input type="checkbox" name="columns[]" value="LateM"> LATE</label>
    <label><input type="checkbox" name="columns[]" value="EarlyDepMin"> ED</label>
    <label><input type="checkbox" name="columns[]" value="NumShift"> SHIFTS</label>

    <input type="date" name="f_date" required>
    <input type="date" name="t_date" required>

    <button type="submit">Generate PDF</button>
</form> -->

                                                            <div class="container fade-in">
        <!-- Header -->
        <div class="form-header">
            <div class="header-content">
                <div class="header-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h1 class="header-title">Attendance Report Generator</h1>
                <p class="header-subtitle">Generate comprehensive attendance reports with customizable filters and columns</p>
            </div>
        </div>

        <!-- Form Body -->
        <div class="form-body">
            <form action="<?php echo base_url('Reports/Attendance/Report_Attendance_ATT_Sum/Attendance_Report_By_Cat') ?>" method="POST" target="_blank">
                
                <!-- Employee Filters Section -->
                <div class="form-section">
                    <h3 class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        Employee Filters
                    </h3>
                    
                    <div class="form-grid form-grid-4">
                        <div class="form-group">
                            <label for="txt_emp" class="form-label">Employee Number</label>
                            <input type="text" class="form-input" name="txt_emp" id="txt_emp" placeholder="Ex: 0001">
                        </div>

                        <div class="form-group">
                            <label for="txt_emp_name" class="form-label">Employee Name</label>
                            <input type="text" class="form-input" name="txt_emp_name" id="txt_emp_name" placeholder="Ex: Ashan">
                        </div>

                        <div class="form-group">
                            <label for="cmb_desig" class="form-label">Designation</label>
                            <select class="form-select" id="cmb_desig" name="cmb_desig">
                                <option value="">-- Select Designation --</option>
                                <!-- PHP options will be populated here -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="cmb_dep" class="form-label">Department</label>
                            <select class="form-select" id="cmb_dep" name="cmb_dep">
                                <option value="">-- Select Department --</option>
                                <!-- PHP options will be populated here -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="cmb_branch" class="form-label">Region</label>
                            <select class="form-select" id="cmb_branch" name="cmb_branch">
                                <option value="">-- Select Region --</option>
                                <!-- PHP options will be populated here -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="cmb_comp" class="form-label">Company</label>
                            <select class="form-select" id="cmb_comp" name="cmb_comp">
                                <option value="">-- Select Company --</option>
                                <!-- PHP options will be populated here -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="cmb_grop" class="form-label">Super Employee Group</label>
                            <select class="form-select" id="cmb_grop" name="cmb_grop">
                                <option value="">-- Select Group --</option>
                                <!-- PHP options will be populated here -->
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Date Range Section -->
                <div class="form-section">
                    <h3 class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        Date Range
                    </h3>
                    
                    <div class="form-grid form-grid-2">
                        <div class="form-group">
                            <label for="dpd1" class="form-label">From Date <span class="required">*</span></label>
                            <div class="date-input-wrapper">
                                <input type="date" class="form-input" required id="dpd1" name="txt_from_date">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="dpd2" class="form-label">To Date <span class="required">*</span></label>
                            <div class="date-input-wrapper">
                                <input type="date" class="form-input" required id="dpd2" name="txt_to_date">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Column Selection Section -->
                <div class="form-section">
                    <h3 class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-columns"></i>
                        </div>
                        Column Selection
                    </h3>
                    
                    <div class="columns-section">
                        <div class="select-all-section">
                            <label class="select-all-label">
                                <input type="checkbox" id="selectAllColumns" class="checkbox-custom">
                                <span>Select All Columns</span>
                            </label>
                        </div>

                        <div class="columns-grid">
                            <div class="column-item checked" data-value="EmpNo">
                                <label class="column-label">
                                    <input type="checkbox" name="columns[]" value="EmpNo" checked class="checkbox-custom">
                                    <span>Employee Number</span>
                                </label>
                            </div>

                            <div class="column-item checked" data-value="Emp_Full_Name">
                                <label class="column-label">
                                    <input type="checkbox" name="columns[]" value="Emp_Full_Name" checked class="checkbox-custom">
                                    <span>Employee Name</span>
                                </label>
                            </div>

                            <div class="column-item" data-value="FDate">
                                <label class="column-label">
                                    <input type="checkbox" name="columns[]" value="FDate" class="checkbox-custom">
                                    <span>From Date</span>
                                </label>
                            </div>

                            <div class="column-item" data-value="FTime">
                                <label class="column-label">
                                    <input type="checkbox" name="columns[]" value="FTime" class="checkbox-custom">
                                    <span>From Time</span>
                                </label>
                            </div>

                            <div class="column-item" data-value="TDate">
                                <label class="column-label">
                                    <input type="checkbox" name="columns[]" value="TDate" class="checkbox-custom">
                                    <span>To Date</span>
                                </label>
                            </div>

                            <div class="column-item" data-value="TTime">
                                <label class="column-label">
                                    <input type="checkbox" name="columns[]" value="TTime" class="checkbox-custom">
                                    <span>To Time</span>
                                </label>
                            </div>

                            <div class="column-item" data-value="InDate">
                                <label class="column-label">
                                    <input type="checkbox" name="columns[]" value="InDate" class="checkbox-custom">
                                    <span>Check In Date</span>
                                </label>
                            </div>

                            <div class="column-item" data-value="InTime">
                                <label class="column-label">
                                    <input type="checkbox" name="columns[]" value="InTime" class="checkbox-custom">
                                    <span>Check In Time</span>
                                </label>
                            </div>

                            <div class="column-item" data-value="OutDate">
                                <label class="column-label">
                                    <input type="checkbox" name="columns[]" value="OutDate" class="checkbox-custom">
                                    <span>Check Out Date</span>
                                </label>
                            </div>

                            <div class="column-item" data-value="OutTime">
                                <label class="column-label">
                                    <input type="checkbox" name="columns[]" value="OutTime" class="checkbox-custom">
                                    <span>Check Out Time</span>
                                </label>
                            </div>

                            <div class="column-item" data-value="BreackInTime1">
                                <label class="column-label">
                                    <input type="checkbox" name="columns[]" value="BreackInTime1" class="checkbox-custom">
                                    <span>Break In Time</span>
                                </label>
                            </div>

                            <div class="column-item" data-value="BreackOutTime1">
                                <label class="column-label">
                                    <input type="checkbox" name="columns[]" value="BreackOutTime1" class="checkbox-custom">
                                    <span>Break Out Time</span>
                                </label>
                            </div>

                            <div class="column-item" data-value="DayStatus">
                                <label class="column-label">
                                    <input type="checkbox" name="columns[]" value="DayStatus" class="checkbox-custom">
                                    <span>Day Status</span>
                                </label>
                            </div>

                            <div class="column-item" data-value="AfterExH">
                                <label class="column-label">
                                    <input type="checkbox" name="columns[]" value="AfterExH" class="checkbox-custom">
                                    <span>Overtime Hours</span>
                                </label>
                            </div>

                            <div class="column-item" data-value="LateM">
                                <label class="column-label">
                                    <input type="checkbox" name="columns[]" value="LateM" class="checkbox-custom">
                                    <span>Late Minutes</span>
                                </label>
                            </div>

                            <div class="column-item" data-value="EarlyDepMin">
                                <label class="column-label">
                                    <input type="checkbox" name="columns[]" value="EarlyDepMin" class="checkbox-custom">
                                    <span>Early Departure</span>
                                </label>
                            </div>

                            <div class="column-item" data-value="NumShift">
                                <label class="column-label">
                                    <input type="checkbox" name="columns[]" value="NumShift" class="checkbox-custom">
                                    <span>Number of Shifts</span>
                                </label>
                            </div>
                        </div>

                        <div class="stats-bar">
                            <span class="stats-text">
                                <span class="selected-count">2</span> of 17 columns selected
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-success" id="generateBtn">
                        <i class="fas fa-file-pdf"></i>
                        Generate PDF Report
                    </button>
                    
                    <button type="button" class="btn btn-danger" id="clearBtn">
                        <i class="fas fa-eraser"></i>
                        Clear Form
                    </button>
                </div>
            </form>
        </div>
    </div>



                                                            <center>
                                                                <div id="loadingContainer" style="width: 30%;"></div>
                                                            </center>
                                                            <hr>


                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>



                                    </div>
                                </div>
                            </div> <!-- .container-fluid -->
                        </div>
                    </div>
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
            $("#spnmessage").hide("shake", {
                times: 4
            }, 1500);
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

    <script>
        // function getPDF() {
        //     var emp = document.getElementById("txt_emp").value;
        //     var empName = document.getElementById("txt_emp_name").value;
        //     var desig = document.getElementById("cmb_desig").value;
        //     var dep = document.getElementById("cmb_dep").value;
        //     var branch = document.getElementById("cmb_branch").value;
        //     var comp = document.getElementById("cmb_comp").value;
        //     var dp1 = document.getElementById("dpd1").value;
        //     var dp2 = document.getElementById("dpd2").value;

        //     var form = new FormData();
        //     form.append("txt_emp", emp);
        //     form.append("txt_emp_name", empName);
        //     form.append("cmb_desig", empName);
        //     form.append("cmb_dep", empName);
        //     form.append("cmb_branch", empName);
        //     form.append("cmb_comp", empName);
        //     form.append("dpd1", empName);
        //     form.append("dpd2", empName);
        //     var r = new XMLHttpRequest();
        //     r.onreadystatechange = function () {
        //         if (r.readyState == 4) {
        //             var text = r.responseText;
        //             // alert(text);
        //             console.log(text);
        //             // Optionally redirect to the generated Excel file link
        //             window.location.href = '<?php echo base_url(); ?>Reports/Attendance/Report_Attendance_ATT_Sum/Attendance_Report_By_Cat';
        //         }
        //     }

        //     r.open("POST", "<?php echo base_url(); ?>Reports/Attendance/Report_Attendance_ATT_Sum/Attendance_Report_By_Cat", true);
        //     r.send(form);
        // }

        function getPDF() {
            // Create a div element to contain the loading indicator
            var loadingContainer = document.getElementById("loadingContainer");
            loadingContainer.innerHTML = ""; // Clear any previous content

            // Show loading indicator inside the div
            var loadingImg = document.createElement("img");
            loadingImg.src = "https://nerp.vft.lk/assets/images/icon-loading.gif";
            loadingImg.id = "loadingIndicator";
            loadingContainer.appendChild(loadingImg);

            var emp = document.getElementById("txt_emp").value;
            var empName = document.getElementById("txt_emp_name").value;
            var desig = document.getElementById("cmb_desig").value;
            var dep = document.getElementById("cmb_dep").value;
            var branch = document.getElementById("cmb_branch").value;
            var comp = document.getElementById("cmb_comp").value;
            var dp1 = document.getElementById("dpd1").value;
            var dp2 = document.getElementById("dpd2").value;

            var form = new FormData();
            form.append("txt_emp", emp);
            form.append("txt_emp_name", empName);
            form.append("cmb_desig", desig);
            form.append("cmb_dep", dep);
            form.append("cmb_branch", branch);
            form.append("cmb_comp", comp);
            form.append("dpd1", dp1);
            form.append("dpd2", dp2);

            var r = new XMLHttpRequest();
            r.onreadystatechange = function () {
                if (r.readyState == 4) {
                    // Remove loading indicator
                    // var loadingIndicator = document.getElementById("loadingIndicator");
                    // if (loadingIndicator) {
                    //     loadingIndicator.parentNode.removeChild(loadingIndicator);
                    // }

                    // Remove loading indicator
                    loadingContainer.innerHTML = "";

                    var text = r.responseText;
                    console.log(text);
                    // Optionally redirect to the generated Excel file link
                    window.location.href = '<?php echo base_url(); ?>Reports/Attendance/Report_Attendance_ATT_Sum/Attendance_Report_By_Cat';
                }
            }

            r.open("POST", "<?php echo base_url(); ?>Reports/Attendance/Report_Attendance_ATT_Sum/Attendance_Report_By_Cat", true);
            r.send(form);
        }

        function get() {
            // Create a div element to contain the loading indicator
            var loadingContainer = document.getElementById("loadingContainer");
            loadingContainer.innerHTML = ""; // Clear any previous content

            // Show loading indicator inside the div
            var loadingImg = document.createElement("img");
            loadingImg.src = "https://nerp.vft.lk/assets/images/icon-loading.gif";
            loadingImg.id = "loadingIndicator";
            loadingContainer.appendChild(loadingImg);

            var emp = document.getElementById("txt_emp").value;
            var empName = document.getElementById("txt_emp_name").value;
            var desig = document.getElementById("cmb_desig").value;
            var dep = document.getElementById("cmb_dep").value;
            var branch = document.getElementById("cmb_branch").value;
            var comp = document.getElementById("cmb_comp").value;
            var dp1 = document.getElementById("dpd1").value;
            var dp2 = document.getElementById("dpd2").value;

            var form = new FormData();
            form.append("txt_emp", emp);
            form.append("txt_emp_name", empName);
            form.append("cmb_desig", desig);
            form.append("cmb_dep", dep);
            form.append("cmb_branch", branch);
            form.append("cmb_comp", comp);
            form.append("dpd1", dp1);
            form.append("dpd2", dp2);

            var r = new XMLHttpRequest();
            r.onreadystatechange = function () {
                if (r.readyState == 4) {
                    // Remove loading indicator
                    // var loadingIndicator = document.getElementById("loadingIndicator");
                    // if (loadingIndicator) {
                    //     loadingIndicator.parentNode.removeChild(loadingIndicator);
                    // }

                    // Remove loading indicator
                    loadingContainer.innerHTML = "";

                    var text = r.responseText;
                    console.log(text);
                    // Optionally redirect to the generated Excel file link
                    window.location.href = '<?php echo base_url(); ?>Reports/Attendance/Report_Attendance_ATT_Sum/exportToExcel';
                }
            }

            r.open("POST", "<?php echo base_url(); ?>Reports/Attendance/Report_Attendance_ATT_Sum/exportToExcel", true);
            r.send(form);
        }
    </script>

    <!-- <script>
        function get() {
            var htmlContent = $(".form-group.col-md-12").html();

            // Send AJAX request
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>Reports/Attendance/Report_Attendance_ATT_Sum/exportToExcel",
                data: { htmlContent: htmlContent },
                dataType: 'json',
                success: function (response) {
                    console.log("AJAX request successful");
                    // Run the exportToExcel function after the AJAX request is successful
                    exportToExcel();
                },
                error: function (error) {
                    console.error("AJAX request failed", error);
                }
            });
        }
    </script> -->
    <script>
        document.getElementById('selectAllColumns').addEventListener('change', function () {
            const allCheckboxes = document.querySelectorAll('input[name="columns[]"]');
            allCheckboxes.forEach(function (checkbox) {
                if (checkbox.value !== 'EmpNo' && checkbox.value !== 'Emp_Full_Name') {
                    checkbox.checked = document.getElementById('selectAllColumns').checked;
                }
            });
        });
    </script>


</body>


</html>