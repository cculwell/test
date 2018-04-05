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
            $this->Cell(30, 10, 'Curriculum Report', 0, 0, 'C');
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

    // List of initiatives
    $initiatives = array('', 'AMSTI', 'ASIM', 'TIM', 'RIC', 'ALSDE', 'LEA');

    // Instanciation of inherited class
    $pdf = new PDF('P', 'mm', 'A4');

    $pdf->AliasNbPages();
    $pdf->AddPage();

    $pdf->SetFont('Times', 'I', 11);
    $pdf->Cell(80);
    $pdf->Cell(30, 10, $report_from . " - " . $report_to, 0, 0, 'C');
    $pdf->Ln(10);

    $pdf->SetFont('Times', 'B', 12);
    $pdf->Cell(65, 10, "Curriculum/Initiative", 'B', 0);
    $pdf->Cell(0, 10, "Offerings Per Initiative", 'B', 0);
    $pdf->Ln(10);

    $sql = "SELECT DISTINCT(c.curriculum), 
                   SUM(c.amsti), 
                   SUM(c.asim), 
                   SUM(c.tim), 
                   SUM(c.ric), 
                   SUM(c.alsde), 
                   SUM(c.lea) 
            FROM curriculum_report_data c
            WHERE c.report_date BETWEEN '$report_from' AND '$report_to'
            GROUP BY c.curriculum";

    $count = 0;

    // Update dictionary of curriculums with the correct counts per initiative
    if ($result = mysqli_query($mysqli, $sql))
    {
        while ($row = mysqli_fetch_row($result))
        {
            // Create page break that doesn't cut off a group
            if($count == 5)
            {
                $pdf->AddPage();

                $pdf->SetFont('Times', 'I', 11);
                $pdf->Cell(80);
                $pdf->Cell(30, 10, $report_from . " - " . $report_to, 0, 0, 'C');
                $pdf->Ln(10);

                $pdf->SetFont('Times', 'B', 12);
                $pdf->Cell(60, 10, "Curriculum Area", 'B', 0);
                $pdf->Cell(0, 10, "Offerings Per Initiative", 'B', 0);
                $pdf->Ln(10);

                $count = 0;
            }

            // Write curriculum area
            $pdf->SetFont('Times', 'B', 12);
            $pdf->Cell(30, 10, $row[0] . ":", 0, 0, 'L');
            $pdf->Ln(7);

            $max = sizeof($row);
            for ($i = 1; $i < $max; $i++)
            {
                // Only list initiatives that have 1 or more programs
                if ($row[$i] > 0)
                {
                    $pdf->Cell(35, 10, "", 0, 0);
                    $pdf->SetFont('Times', 'B', 11);
                    $pdf->Cell(50, 10, $initiatives[$i], 0, 0, 'L');
                    $pdf->SetFont('Times', '', 11);
                    $pdf->Cell(30, 10, $row[$i], 0, 0, 'L');
                    $pdf->Ln(5);
                }
            }
            $pdf->Ln(10);
            $count++;
         }
    }

    // Put totals on a seperate page
    $pdf->AddPage();

    $pdf->SetFont('Times', 'I', 11);
    $pdf->Cell(80);
    $pdf->Cell(30, 10, $report_from . " - " . $report_to, 0, 0, 'C');
    $pdf->Ln(20);

    $pdf->Ln(10);
    $pdf->SetFont('Times', 'BU', 13);
    $pdf->Cell(50, 10, "", 0, 0);
    $pdf->Cell(90, 10, "Total Programs Per Curriculum", "LTR", 0, "C");
    $pdf->Ln(10);

    $sql = "SELECT DISTINCT(c.curriculum), 
                   SUM(c.amsti + c.asim + c.tim + c.ric + c.alsde + c.lea) AS totals
            FROM curriculum_report_data c
            WHERE c.report_date BETWEEN '$report_from' AND '$report_to'
            GROUP BY c.curriculum";

    if ($result = mysqli_query($mysqli, $sql))
    {
        while ($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            //Write the total number of programs per curriculum
            $pdf->SetFont('Times', 'B', 13);
            $pdf->Cell(50, 10, "", 0, 0);
            $pdf->Cell(40, 10, $row['curriculum'] . ":", "L", 0, "R");
            $pdf->Cell(50, 10, $row['totals'], "R", 0, "C");
            $pdf->Ln(8);
        }
        mysqli_free_result($result);
    }

    $pdf->Cell(50, 10, "", 0, 0);
    $pdf->Cell(90, 10, '', "LBR", 0, 0);
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