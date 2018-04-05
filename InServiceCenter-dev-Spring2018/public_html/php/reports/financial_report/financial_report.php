<?php
    session_start();
    require "../../../../resources/config.php";

    # create connection to database
    $mysqli = new mysqli($config['db']['amsti_01']['host']
        , $config['db']['amsti_01']['username']
        , $config['db']['amsti_01']['password']
        , $config['db']['amsti_01']['dbname']);

    /* check connection */
    if ($mysqli->connect_errno) {
        printf("Connect failed: %s\n", $mysqli->connect_error);
        exit();
    }

    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
?>

<!DOCTYPE html>
<html class="no-js" lang="en" dir="ltr">

    <head>
        <link rel="stylesheet" href="../resources/library/DataTables/Buttons/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="css/Reports.css">

        <script src="js/reports/financial_report.js"></script>
        <script src="../resources/library/DataTables/Buttons/js/dataTables.buttons.min.js"></script>
        <script src="../resources/library/DataTables/Buttons/js/buttons.print.min.js"></script>
        <script src="../resources/library/DataTables/Buttons/js/buttons.html5.min.js"></script>
        <script src="../resources/library/DataTables/Buttons/js/pdfmake.min.js"></script>
        <script src="../resources/library/DataTables/Buttons/js/vfs_fonts.js"></script>
        <script src="../resources/library/DataTables/Buttons/js/buttons.colVis.min.js"></script>
        <script src="../resources/library/DataTables/Buttons/js/jszip.min.js"></script>
        <script src="../resources/library/DataTables/Buttons/js/buttons.flash.min.js"></script>

    </head>
    <body>
        <h3>Financial Report</h3>
        <br><br>
<?php
    if (isset ($_SESSION['valid_email']) && ($_SESSION['valid_status']=='Admin'))
    {?>
        <table id="financial_report_table" class="display cell-border table-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Program ID</th>
                    <th>STI PD</th>
                    <th>Program Title</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Sessions</th>
                    <th>Location</th>
                    <th>Initiative</th>
                    <th>Target Audience</th>
                    <th>School System</th>
                    <th>Current Enrollment</th>
                    <th>Maximum Enrollment</th>
                    <th>Total Expenses</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php

                    $sql = "SELECT * 
                            FROM financial_report_data 
                            WHERE report_date BETWEEN '$from_date' AND '$to_date'";

                    if ($result = mysqli_query($mysqli, $sql))
                    {
                        while ($row = $result->fetch_array(MYSQLI_ASSOC))
                        {
                            $total_expenses = number_format((float)$row['total_expenses'], 2, '.', '');

                            echo
                                "<tr>"
                                ."<td>". $row['program_nbr']  ."</td>"             // Program ID
                                ."<td>". $row['request_title']  ."</td>"           // STI ID
                                ."<td>". $row['pd_title']  ."</td>"                // Program Title
                                ."<td>". $row['request_start_date']  ."</td>"      // Start Date
                                ."<td>". $row['request_end_date']  ."</td>"        // End Date
                                ."<td>". $row['request_start_time']  ."</td>"      // Start Time
                                ."<td>". $row['request_end_time']  ."</td>"        // End Time
                                ."<td>". $row['sessions']  ."</td>"                // Number of Sessions
                                ."<td>". $row['request_location'] ."</td>"         // Location
                                ."<td>". $row['support_initiative'] ."</td>"       // Initiative
                                ."<td>". $row['target_group'] ."</td>"             // Target Audience
                                ."<td>". $row['system'] ."</td>"                   // School System
                                ."<td>". $row['enrolled_participants'] ."</td>"    // Current Enrollment
                                ."<td>". $row['target_participants'] ."</td>";     // Maximum Enrollment   

                            if ($row['total_expenses'] == NULL)
                            {
                                echo "<td style='cursor:pointer'>$0.00</td>"       // Total Expenses  
                                     ."<td>". $row['workflow_state'] ."</td>";     // Status
                            } 
                            else 
                            {
                               echo "<td style='cursor:pointer'>". "$" 
                                    . $total_expenses ."</td>"                     // Total Expenses
                                    ."<td>". $row['workflow_state'] ."</td>";      // Status
                                     
                            } 

                            echo "<td>". $row['request_id'] ."</td></tr>";         // Hidden column                             
                        }
                    }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Program ID</th>
                    <th>STI PD</th>
                    <th>Program Title</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Sessions</th>
                    <th>Location</th>
                    <th>Initiative</th>
                    <th>Target Audience</th>
                    <th>School System</th>
                    <th>Current Enrollment</th>
                    <th>Maximum Enrollment</th>
                    <th>Total Expenses</th>
                    <th>Status</th>
                </tr>
            </tfoot>
        </table>
        <br><br><br>
        <h4>Total expenses are a sum of all expenses. 
        To see a detailed breakdown print the PDF report or double click on the expense total.</h4>

    <div id="view_expenses" name="view_expenses"></div>
<?php
    }
    else
    {
        echo "<p><h3>You are not authorized to view this report.</h3></p>";
        echo "<p><a href='../../UserLogin.php'>User Login</a></p>";
        echo "<p><a href='../../UserLogout.php'>User Logout</a></p>";
        echo "<p><a href='../../../Home.html'>Home Page</a></p>";
    }
?>
    </body>
</html>

<?php
    mysqli_free_result($result);
    mysqli_close($mysqli);
?>