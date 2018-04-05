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
            $this->Cell(30, 10, 'Initiative Report', 0, 0, 'C');
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

    // First 2 indexes are blank to cover the other 2 row fields from the query
    // Every 2nd and 3rd row is blank to cover the other counters in the query
    $curriculums = array('', '' , 
                         'Biology', '', '',
                         'Chemistry', '', '',
                         'English/Language Arts', '', '',
                         'Technology', '', '',
                         'Career Tech', '', '',
                         'Counseling', '', '',
                         'Climate and Culture', '', '',
                         'Effective Instruction', '', '',
                         'Fine Arts', '', '',
                         'Foreign Language', '', '',
                         'Gifted', '', '',
                         'Interdisciplinary', '', '',
                         'Leadership', '', '', 
                         'Library Media Services', '', '',
                         'Mathematics', '', '',
                         'NBCT', '', '',
                         'Physics', '', '',
                         'Physical Education', '', '',
                         'Science', '', '',
                         'Social Studies', '', '',
                         'Special Education', '', '',
                         'Other', '', '');

    // Instanciation of inherited class
    $pdf = new PDF('P', 'mm', 'A4');

    $pdf->AliasNbPages();

    $sql = "SELECT DISTINCT(i.support_initiative),
                   SUM(i.total_programs),
                   SUM(i.biology),
                   SUM(i.biology_participants),
                   SUM(i.biology_sessions),    
                   SUM(i.chemistry),
                   SUM(i.chemistry_participants),
                   SUM(i.chemistry_sessions),      
                   SUM(i.english_language_arts),
                   SUM(i.english_language_arts_participants),
                   SUM(i.english_language_arts_sessions),      
                   SUM(i.technology),
                   SUM(i.technology_participants),
                   SUM(i.technology_sessions),         
                   SUM(i.career_tech),
                   SUM(i.career_tech_participants),
                   SUM(i.career_tech_sessions),        
                   SUM(i.counseling),
                   SUM(i.counseling_participants),
                   SUM(i.counseling_sessions),         
                   SUM(i.climate_and_culture),
                   SUM(i.climate_and_culture_participants),
                   SUM(i.climate_and_culture_sessions),            
                   SUM(i.effective_instruction),
                   SUM(i.effective_instruction_participants),
                   SUM(i.effective_instruction_sessions),      
                   SUM(i.fine_arts),
                   SUM(i.fine_arts_participants),
                   SUM(i.fine_arts_sessions),      
                   SUM(i.foreign_language),
                   SUM(i.foreign_language_participants),
                   SUM(i.foreign_language_sessions),           
                   SUM(i.gifted),
                   SUM(i.gifted_participants),
                   SUM(i.gifted_sessions),         
                   SUM(i.interdisciplinary),
                   SUM(i.interdisciplinary_participants),
                   SUM(i.interdisciplinary_sessions),              
                   SUM(i.leadership),
                   SUM(i.leadership_participants),
                   SUM(i.leadership_sessions),         
                   SUM(i.library_media_services),
                   SUM(i.library_media_services_participants),
                   SUM(i.library_media_services_sessions),         
                   SUM(i.mathematics),
                   SUM(i.mathematics_participants),
                   SUM(i.mathematics_sessions),            
                   SUM(i.nbct),
                   SUM(i.nbct_participants),
                   SUM(i.nbct_sessions),       
                   SUM(i.physics),
                   SUM(i.physics_participants),
                   SUM(i.physics_sessions),        
                   SUM(i.physical_education),
                   SUM(i.physical_education_participants),
                   SUM(i.physical_education_sessions),     
                   SUM(i.science),
                   SUM(i.science_participants),
                   SUM(i.science_sessions),            
                   SUM(i.social_studies),
                   SUM(i.social_studies_participants),
                   SUM(i.social_studies_sessions),     
                   SUM(i.special_education),
                   SUM(i.special_education_participants),
                   SUM(i.special_education_sessions),      
                   SUM(i.other),
                   SUM(i.other_participants),
                   SUM(i.other_sessions) 
            FROM initiative_report_data i
            WHERE i.report_date BETWEEN '$report_from' AND '$report_to'
            GROUP BY i.support_initiative";

    if ($result = mysqli_query($mysqli, $sql))
    {
        while ($row = mysqli_fetch_row($result))
        {
            $pdf->AddPage();
            
            $pdf->SetFont('Times', 'I', 11);
            $pdf->Cell(80);
            $pdf->Cell(30, 10, $report_from . " - " . $report_to, 0, 0, 'C');
            $pdf->Ln(10);

            $pdf->SetFont('Times', 'B', 12);
            $pdf->Cell(30, 10, "Initiative", 'B', 0);
            $pdf->Cell(0, 10, "Curriculum Area", 'B', 0);
            $pdf->Ln(20);
            
            // Write the initiative
            $pdf->SetFont('Times', 'BI', 13);
            $pdf->Cell(20, 10, $row[0] . ":", 0, 0, 'L');
            $pdf->Ln(8);

            $max = sizeof($row);
            for ($i = 2; $i < $max; $i += 3)
            {
                // // Only list curriculums that have 1 or more programs
                if ($row[$i] > 0)
                {
                    // Write the curriculum area and total programs for that area
                    $pdf->Cell(30, 10, "", 0, 0);
                    $pdf->SetFont('Times', 'B', 12);
                    $pdf->Cell(40, 10, $curriculums[$i]. ":", 0, 0, 'L');
                    $pdf->Ln(8);
                    $pdf->Cell(40, 10, "", 0, 0);
                    $pdf->SetFont('Times', 'B', 11);
                    $pdf->Cell(30, 10, "Total Programs:   ", 0, 0, 'L');
                    $pdf->SetFont('Times', '', 11);
                    $pdf->Cell(10, 10, $row[$i], 0, 0, 'L');
                    $pdf->SetFont('Times', 'B', 11);
                    $pdf->Cell(35, 10, 'Total Participants' . ":   ", 0, 0, 'L');
                    $pdf->SetFont('Times', '', 11);
                    $pdf->Cell(10, 10, $row[$i + 1], 0, 0, 'L');
                    $pdf->SetFont('Times', 'B', 11);
                    $pdf->Cell(30, 10, 'Total Sessions' . ":   ", 0, 0, 'L');
                    $pdf->SetFont('Times', '', 11);
                    $pdf->Cell(10, 10, $row[$i + 2], 0, 0, 'L');
                    $pdf->Ln(8);
                }
            }
            $pdf->Ln(5);
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
    $pdf->Cell(90, 10, "Total Programs Per Initiative", "LTR", 0, "C");
    $pdf->Ln(10);

    $sql = "SELECT DISTINCT(i.support_initiative), SUM(i.total_programs) AS total_programs
            FROM initiative_report_data i
            WHERE i.report_date BETWEEN '$report_from' AND '$report_to'
            GROUP BY i.support_initiative";

    if ($result = mysqli_query($mysqli, $sql))
    {
        while ($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            //Write the total number of programs per initiative
            $pdf->SetFont('Times', 'B', 13);
            $pdf->Cell(50, 10, "", 0, 0);
            $pdf->Cell(40, 10, $row['support_initiative'] . ":", "L", 0, "R");
            $pdf->Cell(50, 10, $row['total_programs'], "R", 0, "C");
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