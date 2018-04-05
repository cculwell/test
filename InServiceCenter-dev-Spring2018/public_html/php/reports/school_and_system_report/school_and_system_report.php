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

        <script src="js/reports/school_and_system.js"></script>
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
        <h3>School and System Report</h3>
        <br><br>
<?php
    if (isset ($_SESSION['valid_email']) && ($_SESSION['valid_status']=='Admin'))
    {?>
        <table id="school_and_system_report_table" class="display cell-border table-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>System Name</th>
                    <th>Curriculum</th>
                    <th>Program Number</th>
                    <th>Program Title</th>
                    <th>School</th>
                    <th>Initiative</th>
                    <th>Attendance</th>
                    <th>Total Expenses</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                    $sql = "SELECT * 
                            FROM school_and_system_report_data 
                            WHERE report_date BETWEEN '$from_date' AND '$to_date'";

                    if ($result = mysqli_query($mysqli, $sql))
                    {
                        while ($row = $result->fetch_array(MYSQLI_ASSOC))
                        {
                            echo
                                "<tr>"
                                ."<td>". $row['system'] ."</td>"                // System
                                ."<td>". $row['curriculum'] ."</td>"            // Curriculum
                                ."<td>". $row['program_nbr'] ."</td>"           // Program Number                                   
                                ."<td>". $row['pd_title'] ."</td>"              // Program Title
                                ."<td>". $row['school'] ."</td>"                // School
                                ."<td>". $row['support_initiative'] ."</td>"    // Initiative
                                ."<td>". $row['actual_participants'] ."</td>";  // Actual Attendance


                            if ($row['total_expenses'] == NULL)
                            {
                                echo "<td style='cursor:pointer'>$0.00</td>";  //Total Expenses   
                            } 
                            else 
                            {
                                $total_expenses = number_format((float)$row['total_expenses'], 2, '.', '');
                                echo "<td style='cursor:pointer'>$" 
                                     . $total_expenses . "</td>";              // Total Expenses
                            } 

                            echo "<td>". $row['request_id'] ."</td></tr>";                // Hidden column  
                        }
                    }
                ?>
            </tbody>
            <tfoot>
                    <th>System Name</th>
                    <th>Curriculum</th>
                    <th>Program Number</th>
                    <th>Program Title</th>
                    <th>School</th>
                    <th>Initiative</th>
                    <th>Attendance</th>
                    <th>Total Expenses</th>
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