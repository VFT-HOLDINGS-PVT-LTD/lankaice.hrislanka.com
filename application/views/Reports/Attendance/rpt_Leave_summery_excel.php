<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<table id="leave_summary_table">
    <thead>
        <tr>
            <th>Emp No</th>
            <th>Employee Name</th>
            <th>Year</th>
            <th>Leave Type</th>
            <th>Entitle</th>
            <th>Used</th>
            <th>Balance</th>
            <th>Branch</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data_set as $row): ?>
            <tr>
                <td><?php echo $row->EmpNo; ?></td>
                <td><?php echo $row->Emp_Full_Name; ?></td>
                <td><?php echo $row->Year; ?></td>
                <td><?php echo $row->leave_name; ?></td>
                <td><?php echo $row->Entitle; ?></td>
                <td><?php echo $row->Used; ?></td>
                <td><?php echo $row->Balance; ?></td>
                <td><?php echo $row->B_name; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Include the SheetJS library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

<script type="text/javascript">
    // Ensure the page is fully loaded before triggering the download
    document.addEventListener('DOMContentLoaded', function() {
        // Get the HTML table element
        var table = document.getElementById('leave_summary_table');

        // Convert table to a worksheet
        var ws = XLSX.utils.table_to_sheet(table);

        // Create a new workbook
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Leave Summary');

        // Generate the Excel file and trigger download automatically
        XLSX.writeFile(wb, 'Leave_Summary_Report.xlsx');
    });
</script>

</body>
</html>
