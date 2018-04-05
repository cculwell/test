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
            $this->Cell(30, 10, 'Detailed Report', 0, 0, 'C');
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
            FROM detailed_report_data 
            WHERE report_date BETWEEN '$report_from' AND '$report_to'";

    // Instanciation of inherited class
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

            // Write school system
            $pdf->Cell(40, 10, "", 0, 0);
            $pdf->SetFont('Times', 'B', 10);
            $pdf->Cell(40, 10, "School System: ", 0, 0);
            $pdf->SetFont('Times', '', 10);
            $pdf->Cell(30, 10, $row['system'], 0, 0, 'L');
            $pdf->Ln(5);

            // Write school name
            $pdf->Cell(40, 10, "", 0, 0);
            $pdf->SetFont('Times', 'B', 10);
            $pdf->Cell(40, 10, "School: ", 0, 0);
            $pdf->SetFont('Times', '', 10);
            $pdf->Cell(30, 10, $row['school'], 0, 0, 'L');
            $pdf->Ln(5);

            // Write curriculum area
            $pdf->Cell(40, 10, "", 0, 0);
            $pdf->SetFont('Times', 'B', 10);
            $pdf->Cell(40, 10, "Curriculum Area: ", 0, 0);
            $pdf->SetFont('Times', '', 10);
            $pdf->Cell(30, 10, $row['curriculum'], 0, 0, 'L');
            $pdf->Ln(5);

           // Write consultant name
            $pdf->Cell(40, 10, "", 0, 0);
            $pdf->SetFont('Times', 'B', 10);
            $pdf->Cell(40, 10, "Consultant: ", 0, 0);
            $pdf->SetFont('Times', '', 10);
            $pdf->Cell(30, 10, $row['contact_name'], 0, 0, 'L');
            $pdf->Ln(15);

            $count++;
        }

    }

    // Put totals on a seperate page
    $pdf->AddPage();

    $pdf->SetFont('Times', 'I', 11);
    $pdf->Cell(80);
    $pdf->Cell(30, 10, $report_from . " - " . $report_to, 0, 0, 'C');
    $pdf->Ln(20);

    // Get the total number of programs
    $total_programs = mysqli_num_rows($result);

    // Get total number of canceled programs
    $where = "workflow_state='Canceled' AND (report_date BETWEEN '$report_from' AND '$report_to')";
    $sql = "SELECT workflow_state FROM detailed_report_data WHERE $where";
    $total_canceled = mysqli_num_rows(mysqli_query($mysqli, $sql));

    // Get the total enrollment over all programs
    $where = "report_date BETWEEN '$report_from' AND '$report_to'";
    $sql = "SELECT SUM(enrolled_participants) AS total_enrollment FROM detailed_report_data WHERE $where";
    
    $enrollment_result = mysqli_query($mysqli, $sql); 
    $row = mysqli_fetch_assoc($enrollment_result); 
    $total_enrollment = $row['total_enrollment'];

    // Write total programs, total canceled programs, total enrollment, and total fees
    $pdf->Ln(10);
    $pdf->SetFont('Times', 'B', 13);
    $pdf->Cell(50, 10, "", 0, 0);
    $pdf->Cell(90, 10, "Enrollment Grand Totals", "LTR", 0, "L");
    $pdf->Ln(8);
    $pdf->Cell(50, 10, "", 0, 0);
    $pdf->SetFont('Times', 'B', 11);
    $pdf->Cell(30, 10, "", "L", 0);
    $pdf->Cell(30, 10, "Total Programs: ", 0, 0, "R");
    $pdf->Cell(30, 10, $total_programs, "R", 0, "C");
    $pdf->Ln(5);
    $pdf->Cell(50, 10, "", 0, 0);
    $pdf->Cell(30, 10, "", "L", 0);
    $pdf->Cell(30, 10, "Canceled Programs: ", 0, 0, "R");
    $pdf->Cell(30, 10, $total_canceled, "R", 0, "C");
    $pdf->Ln(5);
    $pdf->Cell(50, 10, "", 0, 0);
    $pdf->Cell(30, 10, "", "BL", 0);
    $pdf->Cell(30, 10, "Enrollment: ", "B", 0, "R");
    $pdf->Cell(30, 10, $total_enrollment, "BR", 0, "C");

    $pdf->Output();
	
}
else
{
	echo "<p><h3>You are not authorized to view this report.</h3></p>";
	echo "<p><a href='../../UserLogin.php'>User Login</a></p>";
	echo "<p><a href='../../UserLogout.php'>User Logout</a></p>";
	echo "<p><a href='../../../Home.html'>Home Page</a></p>";
}

    mysqli_free_result($result);
    mysqli_close($mysqli);
?>