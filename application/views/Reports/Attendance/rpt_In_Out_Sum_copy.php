<!-- application/views/attendance_report_view.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        /* Add your custom styles for the printable page here */
        body {
            font-family: Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        @media print {
            button.print-button {
                display: none;
            }
        }
    </style>
</head>

<body>
    <button class="print-button btn btn-primary mt-3 offset-lg-10 col-2" onclick="printPage()">Print</button>
    <span style="color:blue; font-weight: bold;">
        <?= $data_cmp[0]->Company_Name; ?>
    </span>
    <hr />
    <center> <span>ATTENDANCE SUMMERY</span>
    </center>
    <!-- <div style="font-size: 11px; float: left; border-bottom: solid #000 1px;">From Date:
        <?php echo $f_date ?> &nbsp;- To Date :
        <?= $t_date ?>
    </div> -->
    <hr>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Emp No</th>
                <th>Name</th>
                <th>Date</th>
                <th>IN TIME</th>
                <th>OUT TIME</th>
                <th>ST</th>
                <th>LATE</th>
                <th>OTH</th>
                <th>Working (H:M:S)</th>

                <!-- Add more table headers as needed -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data_set2 as $row):
                $Mint = $row->ApprovedExH;
                $hours = floor($Mint / 60);
                $min = $Mint - ($hours * 60);

                $outTime = strtotime($row->OutTime);
                $inTime = strtotime($row->InTime);

                // Calculate the difference in seconds
                $timeDifferenceInSeconds = $outTime - $inTime;

                // Convert the time difference back to HH:MM:SS format
                $timeDifferenceFormatted = gmdate("H:i:s", $timeDifferenceInSeconds);

                ?>

                <tr>
                    <td>
                        <?= $row->EmpNo ?>
                    </td>
                    <td>
                        <?= $row->Emp_Full_Name; ?>
                    </td>
                    <td>
                        <?= $row->FDate; ?>
                    </td>
                    <td>
                        <?= $row->InTime; ?>
                    </td>
                    <td>
                        <?= $row->OutTime; ?>
                    </td>
                    <td>
                        <?= $row->DayStatus; ?>
                    </td>
                    <td>
                        <?= $row->NetLateM; ?>
                    </td>
                    <td>
                        <?= $hours . ':' . $min; ?>
                    </td>
                    <td>
                        <?= $timeDifferenceFormatted ?>
                    </td>


                    <!-- Add more table cells as needed -->
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Add a button to trigger printing -->

    <!-- JavaScript function to trigger printing -->
    <script>
        function printPage() {
            window.print();
        }
    </script>
</body>

</html>