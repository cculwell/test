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
            $this->Cell(30, 10, 'School and System Report', 0, 0, 'C');
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

    $field = "s.system";
    $table = "school_and_system_report_data s";
    $where = "s.report_date BETWEEN '$report_from' AND '$report_to'";
    $systems = "SELECT DISTINCT($field) FROM $table WHERE $where";

    // Instanciation of inherited class
    $pdf = new PDF('P', 'mm', 'A4');

    $pdf->AliasNbPages();
    $pdf->AddPage();

    $pdf->SetFont('Times', 'I', 11);
    $pdf->Cell(80);
    $pdf->Cell(30, 10, $report_from . " - " . $report_to, 0, 0, 'C');
    $pdf->Ln(10);

    $pdf->SetFont('Times', 'B', 12);
    $pdf->Cell(40, 10, "School System", 'B', 0);
    $pdf->Cell(0, 10, "     School System Information", 'B', 0);
    $pdf->Ln(10);

    // Get school systems
    if ($system_result = mysqli_query($mysqli, $systems))
    {
        while ($system = $system_result->fetch_array(MYSQLI_ASSOC))
        {
            // Write the system name
            $school_system = $system['system'];
            $system_text = $school_system . ":";
            $pdf->SetFont('Times', 'B', 14);
            $pdf->Cell(30, 10, $system_text, 0, 0, 'L');
            $pdf->Ln(8);

            $field = "s.curriculum";
            $table = "school_and_system_report_data s";
            $match = "s.system='$school_system'";
            $system_curriculums = "SELECT DISTINCT($field) FROM $table WHERE $match";

            // Get each curriculum area from the school system
            if ($system_curriculums_result = mysqli_query($mysqli, $system_curriculums))
            {
                while ($curriculum = $system_curriculums_result->fetch_array(MYSQLI_ASSOC))
                {
                    $the_curriculum = $curriculum['curriculum'];
                    $pdf->SetFont('Times', 'B', 12);
                    $pdf->Cell(45, 10, "", 0, 0);
                    $pdf->Cell(30, 10, $the_curriculum . ":", 0, 0, 'L');
                    $pdf->Ln(8);

                    $fields = "s.program_nbr, s.pd_title, s.school, s.support_initiative, s.actual_participants";
                    $table = "school_and_system_report_data s";
                    $match1 = "s.system='$school_system'";
                    $match2 = "s.curriculum='$the_curriculum'";
                    $system_programs = "SELECT $fields FROM $table WHERE $match1 AND $match2";

                    // Get the data from each program in the systems's curriculum
                    if ($system_programs_result = mysqli_query($mysqli, $system_programs))
                    {
                        while ($program = $system_programs_result->fetch_array(MYSQLI_ASSOC))
                        {
                            // Write the total PD's for the system
                            $pdf->SetFont('Times', '', 12);
                            $pdf->Cell(55, 5, "", 0, 0);
                            $pdf->Cell(30, 5, $program['program_nbr'], 0, 0, 'L');
                            $pdf->MultiCell(0, 5, $program['pd_title'], 0, 'L');
                            $pdf->Cell(85, 6, "", 0, 0);
                            $pdf->Cell(0, 6, $program['school'], 0, 'L');
                            $pdf->Ln(5);
                            $pdf->Cell(85, 6, "", 0, 0);
                            $pdf->Cell(0, 6, $program['support_initiative'], 0, 'L');
                            $pdf->Ln(5);
                            $pdf->Cell(85, 6, "", 0, 0);
                            $pdf->Cell(30, 6, "Attendance:", 0, 0, 'L');
                            $pdf->Cell(0, 6, $program['actual_participants'], 0, 'L');
                            $pdf->Ln(10);
                        }
                        mysqli_free_result($system_programs_result);
                    }
                }
                mysqli_free_result($system_curriculums_result);
            }
        }
        mysqli_free_result($system_result);

        // Create totals page
        $pdf->AddPage();

        $pdf->SetFont('Times', 'I', 11);
        $pdf->Cell(80);
        $pdf->Cell(30, 10, $report_from . " - " . $report_to, 0, 0, 'C');
        $pdf->Ln(20);

        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(60, 10, "School System Totals", 'B', 0);
        $pdf->Cell(0, 10, "Curriculum/Initiative Totals", 'B', 0);
        $pdf->Ln(10);

        $field = "s.system, COUNT(s.system) AS system_count, 
                  SUM(s.actual_participants) AS actual_participants_count";
        $table = "school_and_system_report_data s";
        $match1 = "report_date BETWEEN '$report_from' AND '$report_to'";
        $group = "s.system";

        $systems = "SELECT $field FROM $table WHERE $match1 GROUP BY $group";

        if ($systems_result = mysqli_query($mysqli, $systems))
        {
            while ($system = $systems_result->fetch_array(MYSQLI_ASSOC))
            {
                $system_name = $system['system'];
                $system_count = $system['system_count'];
                $total_attendance = $system['actual_participants_count'];

                $pdf->SetFont('Times', 'B', 12);
                $pdf->Cell(50, 10, $system_name . ": ", 0, 0, 'L');
                $pdf->SetFont('Times', '', 12);
                $pdf->Cell(20, 10, $system_count, 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->SetFont('Times', 'B', 12);
                $pdf->Cell(50, 10, "Total Attendance: ", 0, 0, 'L');
                $pdf->SetFont('Times', '', 12);
                $pdf->Cell(30, 10, $total_attendance, 0, 0, 'L');
                $pdf->Ln(5);

                $field = "s.curriculum, COUNT(s.curriculum) AS curriculum_count";
                $table = "school_and_system_report_data s";
                $match1 = "(s.report_date BETWEEN '$report_from' AND '$report_to')";
                $match2 = "s.system='$system_name'";
                $group = "s.curriculum";

                $system_curriculums = "SELECT $field FROM $table WHERE $match1 AND $match2 GROUP BY $group";

                if ($system_curriculums_result = mysqli_query($mysqli, $system_curriculums))
                {
                    while ($curriculum = $system_curriculums_result->fetch_array(MYSQLI_ASSOC))
                    {
                        $curriculum_name = $curriculum['curriculum'];
                        $curriculum_count = $curriculum['curriculum_count'];

                        $pdf->Cell(60, 10, "", 0, 0); 
                        $pdf->SetFont('Times', 'B', 12);
                        $pdf->Cell(40, 10, $curriculum_name . ": ", 0, 0, 'L');
                        $pdf->SetFont('Times', '', 12);
                        $pdf->Cell(30, 10, $curriculum_count, 0, 0, 'L');
                        $pdf->Ln(5);                           

                        if($curriculum_count > 0)
                        {
                            $field = "s.support_initiative, COUNT(s.support_initiative) AS initiative_count";
                            $table = "school_and_system_report_data s";
                            $match1 = "(s.report_date BETWEEN '$report_from' AND '$report_to')";
                            $match2 = "s.system='$system_name'";
                            $match3 = "s.curriculum='$curriculum_name'";
                            $group = "s.support_initiative";

                            $curriculum_initiatives = "SELECT $field FROM $table 
                                                       WHERE $match1 AND $match2 AND $match3 GROUP BY $group";

                            if ($curriculum_initiatives_result = mysqli_query($mysqli, $curriculum_initiatives))
                            {
                                while ($initiative = $curriculum_initiatives_result->fetch_array(MYSQLI_ASSOC))
                                {
                                    $initiative_name = $initiative['support_initiative'];
                                    $initiative_count = $initiative['initiative_count'];

                                    $pdf->SetFont('Times', '', 12);
                                    $pdf->Cell(70, 10, "", 0, 0);
                                    $pdf->Cell(30, 10, $initiative_name . ": ", 0, 0, 'L');
                                    $pdf->Cell(0, 10, $initiative_count, 0, 0, 'L');
                                    $pdf->Ln(5);
                                }
                                mysqli_free_result($curriculum_initiatives_result);
                            }
                            $pdf->Ln(3);
                        }
                    }
                    mysqli_free_result($system_curriculums_result);
                }
                $pdf->Ln(5);
            }
            mysqli_free_result($systems_result);
        }

        $totals_count = 0;

        // Calculate the total spent for each system
        $pdf->AddPage();

        $pdf->SetFont('Times', 'I', 11);
        $pdf->Cell(80);
        $pdf->Cell(30, 10, $report_from . " - " . $report_to, 0, 0, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(50, 10, "School System", 'B', 0);
        $pdf->Cell(0, 10, "Totals Spent Per System", 'B', 0);
        $pdf->Ln(10);

        $sql = "SELECT DISTINCT s.system 
                FROM school_and_system_report_data s 
                WHERE s.report_date BETWEEN '$report_from' AND '$report_to'";

        if ($system_totals_result = mysqli_query($mysqli, $sql))
        {
            while ($system = $system_totals_result->fetch_array(MYSQLI_ASSOC))
            {
                // Create page break that doesn't cut off a group
                if($totals_count == 4)
                {
                    $pdf->AddPage();
                    $pdf->Ln(1);

                    $pdf->SetFont('Times', 'I', 11);
                    $pdf->Cell(80);
                    $pdf->Cell(30, 10, $report_from . " - " . $report_to, 0, 0, 'C');
                    $pdf->Ln(10);

                    $pdf->SetFont('Times', 'B', 12);
                    $pdf->Cell(50, 10, "School System", 'B', 0);
                    $pdf->Cell(0, 10, "Totals Spent Per System", 'B', 0);
                    $pdf->Ln(10);

                    $totals_count = 0;
                }

                $system_name = $system['system'];

                $pdf->SetFont('Times', 'B', 12);
                $pdf->Cell(50, 10, $system_name . ": ", "", 0, "L");
                $pdf->Ln(5);

                $sql = "SELECT e.expense_type, SUM(e.expense_amount) AS expense_total 
                        FROM expenses e 
                        INNER JOIN school_and_system_report_data s
                        ON e.request_id = s.request_id
                        WHERE (report_date BETWEEN '$report_from' AND '$report_to') 
                        AND s.system = '$system_name'
                        GROUP BY e.expense_type";

                $total = 0.0;

                if ($system_expenses_result = mysqli_query($mysqli, $sql))
                {
                    while ($expense = $system_expenses_result->fetch_array(MYSQLI_ASSOC))
                    {
                        $pdf->Cell(50, 10, "", 0, 0);
                        $pdf->SetFont('Times', '', 12);
                        $pdf->Cell(50, 10, $expense['expense_type'] . ": ", 0, 0, "L");
                        $pdf->Cell(30, 10, "$" . number_format((float)$expense['expense_total'], 2, '.', ''), 0, 0, "L");
                        $total += $expense['expense_total'];
                        $pdf->Ln(5);
                    }
                    mysqli_free_result($system_expenses_result);

                    $pdf->Cell(50, 10, "", 0, 0);
                    $pdf->SetFont('Times', 'B', 12);
                    $pdf->Cell(50, 10, "TOTAL:", 0, 0, "L");
                    $pdf->Cell(30, 10, "$" . number_format($total, 2, '.', ''), 0, 0, "L");
                    $total = 0.0;
                    $pdf->Ln(5);
                }
                $pdf->Ln(5);
                $totals_count++;
            }
            mysqli_free_result($system_totals_result);
        }
    }

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