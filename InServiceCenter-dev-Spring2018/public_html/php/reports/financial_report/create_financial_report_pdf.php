<?php
	session_start();
    require '../../../../resources/library/Reports/fpdf181/fpdf.php';
    require "../../../../resources/config.php";

    $report_from = $_POST['report_from'];
    $report_to = $_POST['report_to'];

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
	
if (isset ($_SESSION['valid_email']) && ($_SESSION['valid_status']=='Admin'))
	{
    class PDF extends FPDF
    {
        // Page header
        function Header()
        {
            // Create the title
            $this->SetFont('Times','B', 14);
            $this->Cell(80);
            $this->Cell(30, 10, 'Athens State University', 0, 0, 'C');
            $this->Ln(5);
            $this->Cell(80);
            $this->Cell(30, 10, 'Regional In-Service Center', 0, 0, 'C');
            $this->Ln(10);
            $this->Cell(80);
            $this->SetFont('Times', 'B', 12);
            $this->Cell(30, 10, 'Financial Report', 0, 0, 'C');
            $this->Ln(10);
        }

        // Page footer
        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial','I',8);

            // Page number
            $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');

            // Printed on 
            $this->SetFont('Arial', '', 8);
            $this->Cell(0, 10, "Printed on " . date('n/j/Y'), 0, 0, 'R');
        }
    }

    $sql = "SELECT * 
            FROM financial_report_data 
            WHERE report_date BETWEEN '$report_from' AND '$report_to'";

    //Instanciation of inherited class
    $pdf = new PDF('P', 'mm', 'A4');

    $pdf->AliasNbPages();
    $pdf->AddPage();

    $pdf->SetFont('Times', 'I', 11);
    $pdf->Cell(80);
    $pdf->Cell(30, 10, $report_from . " - " . $report_to, 0, 0, 'C');
    $pdf->Ln(10);

    $pdf->SetFont('Times', 'B', 12);
    $pdf->Cell(30, 10, "Program ID#", 'B', 0);
    $pdf->Cell(0, 10, "     Program Title", 'B', 0);
    $pdf->Ln(10);

    $count = 0;

    if ($result = mysqli_query($mysqli, $sql))
    {
        while ($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            // Create page break that doesn't cut off a group
            if($count == 2)
            {
                $pdf->AddPage();

                $pdf->SetFont('Times', 'I', 11);
                $pdf->Cell(80);
                $pdf->Cell(30, 10, $report_from . " - " . $report_to, 0, 0, 'C');
                $pdf->Ln(10);

                $pdf->SetFont('Times', 'B', 12);
                $pdf->Cell(30, 10, "Program ID#", 'B', 0);
                $pdf->Cell(0, 10, "     Program Title", 'B', 0);
                $pdf->Ln(10);

                $count = 0;
            }

            // Write program ID
            $id = $row['program_nbr'];
            $pdf->SetFont('Times', 'B', 12);
            $pdf->Cell(30, 10, $id, 0, 0, 'C');

            // Write the program title
            $title = "     " . $row['pd_title'];
            $pdf->SetFont('Times', 'BI', 12);
            $pdf->Cell(30, 10, $title, 0, 0);

            if ($row['workflow_state'] == 'Canceled')
            {
                // Write the canceled notification
                $canceled = "***** CANCELED *****";
                $pdf->SetFont('Times', 'B', 12);
                $pdf->Cell(30, 10, "", 0, 0);
                $pdf->Cell(0, 10, $canceled, 0, 0, 'R');
            }

            $pdf->Ln(5);

            // Write STI PD
            $pdf->SetFont('Times', '', 10);
            $sti_pd = "     " . $row['request_title'];
            $pdf->Cell(30, 10, "", 0, 0);
            $pdf->Cell(30, 10, $sti_pd, 0, 0);
            $pdf->Ln(8);

            // Write dates of the program
            $date_range = $row['request_start_date'] . " to " . $row['request_end_date'];
            $pdf->Cell(40, 10, "", 0, 0);
            $pdf->SetFont('Times', 'B', 10);
            $pdf->Cell(40, 10, "Date: ", 0, 0);
            $pdf->SetFont('Times', '', 10);
            $pdf->Cell(30, 10, $date_range, 0, 0, 'L');

            $pdf->Ln(5);

            // Write times of the program
            $time_range = $row['request_start_time'] . " to " . $row['request_end_time'];
            $pdf->Cell(40, 10, "", 0, 0);
            $pdf->SetFont('Times', 'B', 10);
            $pdf->Cell(40, 10, "Times: ", 0, 0);
            $pdf->SetFont('Times', '', 10);
            $pdf->Cell(30, 10, $time_range, 0, 0, 'L');
            $pdf->Ln(5);

            // Write number of sessions for the program
            $pdf->Cell(40, 10, "", 0, 0);
            $pdf->SetFont('Times', 'B', 10);
            $pdf->Cell(40, 10, "Number of Sessions: ", 0, 0);
            $pdf->SetFont('Times', '', 10);
            $pdf->Cell(30, 10, $row['sessions'], 0, 0, 'L');
            $pdf->Ln(5);

            // Write location of the program
            $pdf->Cell(40, 10, "", 0, 0);
            $pdf->SetFont('Times', 'B', 10);
            $pdf->Cell(40, 10, "Location: ", 0, 0);
            $pdf->SetFont('Times', '', 10);
            $pdf->Cell(30, 10, $row['request_location'], 0, 0, 'L');
            $pdf->Ln(5);

            // Write the initiative providing support
            $pdf->Cell(40, 10, "", 0, 0);
            $pdf->SetFont('Times', 'B', 10);
            $pdf->Cell(40, 10, "Initiative: ", 0, 0);
            $pdf->SetFont('Times', '', 10);
            $pdf->Cell(30, 10, $row['support_initiative'], 0, 0, 'L');
            $pdf->Ln(5);

            // Write the target audience
            $pdf->Cell(40, 10, "", 0, 0);
            $pdf->SetFont('Times', 'B', 10);
            $pdf->Cell(40, 10, "Target Audience: ", 0, 0);
            $pdf->SetFont('Times', '', 10);
            $pdf->Cell(30, 10, $row['target_group'], 0, 0, 'L');
            $pdf->Ln(5);

            // Write current enrollment
            $pdf->Cell(40, 10, "", 0, 0);
            $pdf->SetFont('Times', 'B', 10);
            $pdf->Cell(40, 10, "Current Enrollment: ", 0, 0);
            $pdf->SetFont('Times', '', 10);
            $pdf->Cell(30, 10, $row['enrolled_participants'], 0, 0, 'L');
            $pdf->Ln(5);

            // Write max enrollment
            $pdf->Cell(40, 10, "", 0, 0);
            $pdf->SetFont('Times', 'B', 10);
            $pdf->Cell(40, 10, "Maximum Enrollment: ", 0, 0);
            $pdf->SetFont('Times', '', 10);
            $pdf->Cell(30, 10, $row['target_participants'], 0, 0, 'L');
            $pdf->Ln(5);

            // Write the school system
            $pdf->Cell(40, 10, "", 0, 0);
            $pdf->SetFont('Times', 'B', 10);
            $pdf->Cell(40, 10, "School System: ", 0, 0);
            $pdf->SetFont('Times', '', 10);
            $pdf->Cell(30, 10, $row['system'], 0, 0, 'L');
            $pdf->Ln(7);

            // Begin writing the expenses for the program
            $pdf->SetFont('Times', 'B', 10);
            $pdf->Cell(34, 10, "", 0, 0);
            $pdf->Cell(30, 10, "Program Expenses:", 0, 0);
            $pdf->Ln(5);

            $total = 0.0;

            $request_id = $row['request_id'];
            $expense_sql = "SELECT DISTINCT(e.expense_type), SUM(e.expense_amount) as expense_amount
                            FROM expenses e
                            WHERE e.request_id='$request_id'
                            GROUP BY e.expense_type";

            if ($expense_result = mysqli_query($mysqli, $expense_sql))
            {
                while ($expense = $expense_result->fetch_array(MYSQLI_ASSOC))
                {
                    // Write expense name and amount
                    $pdf->Cell(45, 10, "", 0, 0);
                    $pdf->SetFont('Times', 'B', 10);
                    $pdf->Cell(45, 10, $expense['expense_type'], 0, 0);
                    $pdf->SetFont('Times', '', 10);
                    $pdf->Cell(10, 10, "$" . number_format((float)$expense['expense_amount'], 2, '.', ''), 0, 0, 'L');
                    $pdf->Ln(5);

                    $total = $total + (float)$expense['expense_amount'];
                }
                mysqli_free_result($expense_result);
            }

            // Write total expenses for the program
            $pdf->Ln(2);
            $pdf->Cell(45, 10, "", 0, 0);
            $pdf->SetFont('Times', 'B', 10);
            $pdf->Cell(45, 10, "Total Expenses: ", 0, 0);
            $pdf->SetFont('Times', 'B', 10);
            $pdf->Cell(10, 10, "$" . number_format((float)$total, 2, '.', ''), 0, 0, 'L"');
            $pdf->Ln(15);

            $count++;
        }
        mysqli_free_result($result);
    }

    // Put totals on a seperate page
    $pdf->AddPage();

    $pdf->SetFont('Times', 'I', 11);
    $pdf->Cell(80);
    $pdf->Cell(30, 10, $report_from . " - " . $report_to, 0, 0, 'C');
    $pdf->Ln(20);

    $pdf->Ln(10);
    $pdf->SetFont('Times', 'BU', 13);
    $pdf->Cell(40, 10, "", 0, 0);
    $pdf->Cell(110, 10, "Totals By Expense", "LTR", 0, "C");
    $pdf->SetFont('Times', 'B', 13);
    $pdf->Ln(8);

    // Get total expenses by expense type
    // $sql = "SELECT DISTINCT(e.expense_type), SUM(e.expense_amount) AS expense_amount
    //         FROM expenses e 
    //         JOIN (SELECT request_id 
    //               FROM financial_report_data 
    //               WHERE report_date BETWEEN '$report_from' AND '$report_to') AS rq 
    //         ON e.request_id = rq.request_id
    //         GROUP BY e.expense_type";
    $sql = "SELECT DISTINCT(e.expense_type), SUM(e.expense_amount) AS expense_amount
            FROM expenses e 
            INNER JOIN financial_report_data f e.request_id = f.request_id 
            WHERE report_date BETWEEN '$report_from' AND '$report_to'
            GROUP BY e.expense_type";

    if ($result = mysqli_query($mysqli, $sql))
    {
        while ($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $pdf->Cell(40, 10, "", 0, 0);
            $pdf->Cell(10, 10, "", "L", 0);
            $pdf->Cell(70, 10, $row['expense_type'] . ": ", "", 0, "L");
            $pdf->Cell(30, 10, "$" . number_format((float)$row['expense_amount'], 2, '.', ''), "R", 0, "L");
            $pdf->Ln(7);
        }
        mysqli_free_result($result);
    }

    // Get total expenses
    $sql = "SELECT SUM(f.total_expenses) AS total_expenses
            FROM financial_report_data f
            WHERE f.report_date BETWEEN '$report_from' AND '$report_to'";

    $result = mysqli_query($mysqli, $sql); 
    $row = $result->fetch_array(MYSQLI_ASSOC); 

    $pdf->Cell(40, 10, "", 0, 0);
    $pdf->Cell(10, 10, "", "L", 0);
    $pdf->Cell(70, 10, "TOTAL EXPENSES: ", "", 0, "L");
    $pdf->Cell(30, 10, "$" . number_format((float)$row['total_expenses'], 2, '.', ''), "R", 0);
    $pdf->Ln(10);

    mysqli_free_result($result);

    $pdf->SetFont('Times', 'BU', 13);
    $pdf->Cell(40, 10, "", 0, 0);
    $pdf->Cell(110, 10, "Totals Per System", "LR", 0, "C");
    $pdf->SetFont('Times', 'B', 13);
    $pdf->Ln(8);

    // $sql = "SELECT rq.system, SUM(e.expense_amount) AS expense_amount
    //         FROM expenses e 
    //         JOIN (SELECT f.request_id, f.system
    //               FROM financial_report_data f 
    //               WHERE report_date BETWEEN '$report_from' AND '$report_to') AS rq 
    //         ON e.request_id = rq.request_id
    //         GROUP BY rq.system";
    $sql = "SELECT f.system, SUM(e.expense_amount) AS expense_amount
            FROM expenses e 
            INNER JOIN financial_report_data f 
            ON e.request_id = f.request_id
            WHERE report_date BETWEEN '$report_from' AND '$report_to'
            GROUP BY f.system";

    if ($result = mysqli_query($mysqli, $sql))
    {
        while ($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $pdf->Cell(40, 10, "", 0, 0);
            $pdf->Cell(10, 10, "", "L", 0);
            $pdf->Cell(70, 10, $row['system'] . ": ", "", 0, "L");
            $pdf->Cell(30, 10, "$" . number_format((float)$row['expense_amount'], 2, '.', ''), "R", 0, "L");
            $pdf->Ln(7);
        }
        mysqli_free_result($result);
    }

    $pdf->Cell(40, 10, "", 0, 0);
    $pdf->Cell(110, 10, "", "LBR", 0, 0);

    $pdf->Output();
}
else
{
	echo "<p><h3>You are not authorized to view this report.</h3></p>";
	echo "<p><a href='../../UserLogin.php'>User Login</a></p>";
	echo "<p><a href='../../UserLogout.php'>User Logout</a></p>";
	echo "<p><a href='../../../Home.html'>Home Page</a></p>";
}
    mysqli_close($mysqli);
?>