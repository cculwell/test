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

        <script src="js/reports/initiative_report.js"></script>
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
        <h3>Initiative Report</h3>
        <br><br>
<?php
    if (isset ($_SESSION['valid_email']) && ($_SESSION['valid_status']=='Admin'))
    {?>
        <table id="initiative_report_table" class="display cell-border table-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Initiative</th>
                    <th>Total Programs</th>
                    <th>Biology Programs</th>
                    <th>Biology Participants</th>
                    <th>Biology Sessions</th>
                    <th>Chemistry Programs</th>
                    <th>Chemistry Participants</th>
                    <th>Chemistry Sessions</th>
                    <th>English/Language Arts Programs</th>
                    <th>English/Language Participants</th>
                    <th>English/Language Sessions</th>
                    <th>Technology Programs</th>
                    <th>Technology Participants</th>
                    <th>Technology Sessions</th>
                    <th>Career Tech Programs</th>
                    <th>Career Tech Participants</th>
                    <th>Career Tech Sessions</th>
                    <th>Counseling Programs</th>
                    <th>Counseling Participants</th>
                    <th>Counseling Sessions</th>                        
                    <th>Climate and Culture Programs</th>
                    <th>Climate and Culture Participants</th>
                    <th>Climate and Culture Sessions</th>                          
                    <th>Effective Instruction Programs</th>
                    <th>Effective Instruction Participants</th>
                    <th>Effective Instruction Sessions</th> 
                    <th>Fine Arts Programs</th>
                    <th>Fine Arts Participants</th>
                    <th>Fine Arts Sessions</th>
                    <th>Foreign Language Programs</th>
                    <th>Foreign Language Participants</th>
                    <th>Foreign Language Sessions</th>
                    <th>Gifted Programs</th>
                    <th>Gifted Participants</th>
                    <th>Gifted Sessions</th>
                    <th>Interdisciplinary Programs</th>
                    <th>Interdisciplinary Participants</th>
                    <th>Interdisciplinary Sessions</th>
                    <th>Leadership Programs</th>
                    <th>Leadership Participants</th>
                    <th>Leadership Sessions</th>
                    <th>Library Media Services Programs</th>
                    <th>Library Media Services Participants</th>
                    <th>Library Media Services Sessions</th>
                    <th>Mathematics Programs</th>
                    <th>Mathematics Participants</th>
                    <th>Mathematics Sessions</th>
                    <th>NBCT Programs</th>
                    <th>NBCT Participants</th>
                    <th>NBCT Sessions</th>
                    <th>Physics Programs</th>
                    <th>Physics Participants</th>
                    <th>Physics Sessions</th>
                    <th>Physical Education Programs</th>
                    <th>Physical Education Participants</th>
                    <th>Physical Education Sessions</th>
                    <th>Science Programs</th>
                    <th>Science Participants</th>
                    <th>Science Sessions</th>
                    <th>Social Studies Programs</th>
                    <th>Social Studies Participants</th>
                    <th>Social Studies Sessions</th>
                    <th>Special Education Programs</th>
                    <th>Special Education Participants</th>
                    <th>Special Education Sessions</th>
                    <th>Other Programs</th>
                    <th>Other Participants</th>
                    <th>Other Sessions</th>
                </tr>
            </thead>
            <tbody>
                <?php

                    $sql = "SELECT DISTINCT(i.support_initiative),
                                   SUM(i.total_programs) AS total_programs,
                                   SUM(i.biology) AS biology,
                                   SUM(i.biology_participants) AS biology_participants,
                                   SUM(i.biology_sessions) AS biology_sessions,    
                                   SUM(i.chemistry) AS chemistry,
                                   SUM(i.chemistry_participants) AS chemistry_participants,
                                   SUM(i.chemistry_sessions) AS chemistry_sessions,      
                                   SUM(i.english_language_arts) AS english_language_arts,
                                   SUM(i.english_language_arts_participants) AS english_language_arts_participants,
                                   SUM(i.english_language_arts_sessions) AS english_language_arts_sessions,      
                                   SUM(i.technology) AS technology,
                                   SUM(i.technology_participants) AS technology_participants,
                                   SUM(i.technology_sessions) AS technology_sessions,         
                                   SUM(i.career_tech) AS career_tech,
                                   SUM(i.career_tech_participants) AS career_tech_participants,
                                   SUM(i.career_tech_sessions) AS career_tech_sessions,        
                                   SUM(i.counseling) AS counseling,
                                   SUM(i.counseling_participants) AS counseling_participants,
                                   SUM(i.counseling_sessions) AS counseling_sessions,         
                                   SUM(i.climate_and_culture) AS climate_and_culture,
                                   SUM(i.climate_and_culture_participants) AS climate_and_culture_participants,
                                   SUM(i.climate_and_culture_sessions) AS climate_and_culture_sessions,            
                                   SUM(i.effective_instruction) AS effective_instruction,
                                   SUM(i.effective_instruction_participants) AS effective_instruction_participants,
                                   SUM(i.effective_instruction_sessions) AS effective_instruction_sessions,      
                                   SUM(i.fine_arts) AS fine_arts,
                                   SUM(i.fine_arts_participants) AS fine_arts_participants,
                                   SUM(i.fine_arts_sessions) AS fine_arts_sessions,      
                                   SUM(i.foreign_language) AS foreign_language,
                                   SUM(i.foreign_language_participants) AS foreign_language_participants,
                                   SUM(i.foreign_language_sessions) AS foreign_language_sessions,           
                                   SUM(i.gifted) AS gifted,
                                   SUM(i.gifted_participants) AS gifted_participants,
                                   SUM(i.gifted_sessions) AS gifted_sessions,         
                                   SUM(i.interdisciplinary) AS interdisciplinary,
                                   SUM(i.interdisciplinary_participants) AS interdisciplinary_participants,
                                   SUM(i.interdisciplinary_sessions) AS interdisciplinary_sessions,              
                                   SUM(i.leadership) AS leadership,
                                   SUM(i.leadership_participants) AS leadership_participants,
                                   SUM(i.leadership_sessions) AS leadership_sessions,         
                                   SUM(i.library_media_services) AS library_media_services,
                                   SUM(i.library_media_services_participants) AS library_media_services_participants,
                                   SUM(i.library_media_services_sessions) AS library_media_services_sessions,         
                                   SUM(i.mathematics) AS mathematics,
                                   SUM(i.mathematics_participants) AS mathematics_participants,
                                   SUM(i.mathematics_sessions) AS mathematics_sessions,            
                                   SUM(i.nbct) AS nbct,
                                   SUM(i.nbct_participants) AS nbct_participants,
                                   SUM(i.nbct_sessions) AS nbct_sessions,       
                                   SUM(i.physics) AS physics,
                                   SUM(i.physics_participants) AS physics_participants,
                                   SUM(i.physics_sessions) AS physics_sessions,        
                                   SUM(i.physical_education) AS physical_education,
                                   SUM(i.physical_education_participants) AS physical_education_participants,
                                   SUM(i.physical_education_sessions) AS physical_education_sessions,     
                                   SUM(i.science) AS science,
                                   SUM(i.science_participants) AS science_participants,
                                   SUM(i.science_sessions) AS science_sessions,            
                                   SUM(i.social_studies) AS social_studies,
                                   SUM(i.social_studies_participants) AS social_studies_participants,
                                   SUM(i.social_studies_sessions) AS social_studies_sessions,     
                                   SUM(i.special_education) AS special_education,
                                   SUM(i.special_education_participants) AS special_education_participants,
                                   SUM(i.special_education_sessions) AS special_education_sessions,      
                                   SUM(i.other) AS other,
                                   SUM(i.other_participants) AS other_participants,
                                   SUM(i.other_sessions) AS other_sessions 
                            FROM initiative_report_data i
                            WHERE i.report_date BETWEEN '$from_date' AND '$to_date'
                            GROUP BY i.support_initiative";
                    
                    if ($result = mysqli_query($mysqli, $sql))
                    {
                        while ($row = $result->fetch_array(MYSQLI_ASSOC))
                        {
                            echo
                                "<tr>"
                                ."<td>". $row['support_initiative']."</td>"                    // Initiative
                                ."<td>". $row['total_programs']."</td>"                        // Total Programs
                                ."<td>". $row['biology'] ."</td>"                              // Biology
                                ."<td>". $row['biology_participants'] ."</td>"                 // Biology Participants
                                ."<td>". $row['biology_sessions'] ."</td>"                     // Biology Sessions
                                ."<td>". $row['chemistry'] ."</td>"                            // Chemistry
                                ."<td>". $row['chemistry_participants'] ."</td>"               // Chemistry Participants
                                ."<td>". $row['chemistry_sessions'] ."</td>"                   // Chemistry Sessions
                                ."<td>". $row['english_language_arts'] ."</td>"                // English/Language Arts
                                ."<td>". $row['english_language_arts_participants'] ."</td>"   // English/Language Participants
                                ."<td>". $row['english_language_arts_sessions'] ."</td>"       // English/Language Sessions
                                ."<td>". $row['technology'] ."</td>"                           // Technology
                                ."<td>". $row['technology_participants'] ."</td>"              // Technology Participants
                                ."<td>". $row['technology_sessions'] ."</td>"                  // Technology Sessions
                                ."<td>". $row['career_tech'] ."</td>"                          // Career Tech
                                ."<td>". $row['career_tech_participants'] ."</td>"             // Career Tech Participants
                                ."<td>". $row['career_tech_sessions'] ."</td>"                 // Career Tech Sessions
                                ."<td>". $row['counseling'] ."</td>"                           // Counseling
                                ."<td>". $row['counseling_participants'] ."</td>"              // Counseling Participants
                                ."<td>". $row['counseling_sessions'] ."</td>"                  // Counseling Sessions
                                ."<td>". $row['climate_and_culture'] ."</td>"                  // Climate and Culture
                                ."<td>". $row['climate_and_culture_participants'] ."</td>"     // Climate and Culture Participants
                                ."<td>". $row['climate_and_culture_sessions'] ."</td>"         // Climate and Culture Sessions
                                ."<td>". $row['effective_instruction'] ."</td>"                // Effective Instruction
                                ."<td>". $row['effective_instruction_participants'] ."</td>"   // Effective Instruction Participants
                                ."<td>". $row['effective_instruction_sessions'] ."</td>"       // Effective Instruction Sessions
                                ."<td>". $row['fine_arts'] ."</td>"                            // Fine Arts
                                ."<td>". $row['fine_arts_participants'] ."</td>"               // Fine Arts Participants
                                ."<td>". $row['fine_arts_sessions'] ."</td>"                   // Fine Arts Sessions
                                ."<td>". $row['foreign_language'] ."</td>"                     // Foreign Language
                                ."<td>". $row['foreign_language_participants'] ."</td>"        // Foreign Language Participants
                                ."<td>". $row['foreign_language_sessions'] ."</td>"            // Foreign Language Sessions
                                ."<td>". $row['gifted'] ."</td>"                               // Gifted
                                ."<td>". $row['gifted_participants'] ."</td>"                  // Gifted Participants
                                ."<td>". $row['gifted_sessions'] ."</td>"                      // Gifted Sessions
                                ."<td>". $row['interdisciplinary'] ."</td>"                    // Interdisciplinary
                                ."<td>". $row['interdisciplinary_participants'] ."</td>"       // Interdisciplinary Participants
                                ."<td>". $row['interdisciplinary_sessions'] ."</td>"           // Interdisciplinary Sessions
                                ."<td>". $row['leadership'] ."</td>"                           // Leadership
                                ."<td>". $row['leadership_participants'] ."</td>"              // Leadership Participants
                                ."<td>". $row['leadership_sessions'] ."</td>"                  // Leadership Sessions
                                ."<td>". $row['library_media_services'] ."</td>"               // Library Media Services
                                ."<td>". $row['library_media_services_participants'] ."</td>"  // Library Media Services Participants
                                ."<td>". $row['library_media_services_sessions'] ."</td>"      // Library Media Services Sessions
                                ."<td>". $row['mathematics'] ."</td>"                          // Mathematics
                                ."<td>". $row['mathematics_participants'] ."</td>"             // Mathematics Participants
                                ."<td>". $row['mathematics_sessions'] ."</td>"                 // Mathematics Sessions
                                ."<td>". $row['nbct'] ."</td>"                                 // NBCT
                                ."<td>". $row['nbct_participants'] ."</td>"                    // NBCT Participants
                                ."<td>". $row['nbct_sessions'] ."</td>"                        // NBCT Sessions
                                ."<td>". $row['physics'] ."</td>"                              // Physics
                                ."<td>". $row['physics_participants'] ."</td>"                 // Physics Participants
                                ."<td>". $row['physics_sessions'] ."</td>"                     // Physics Sessions
                                ."<td>". $row['physical_education'] ."</td>"                   // Physical Education
                                ."<td>". $row['physical_education_participants'] ."</td>"      // Physical Education Participants
                                ."<td>". $row['physics_sessions'] ."</td>"                     // Physical Education Sessions
                                ."<td>". $row['science'] ."</td>"                              // Science
                                ."<td>". $row['science_participants'] ."</td>"                 // Science Participants
                                ."<td>". $row['science_sessions'] ."</td>"                     // Science Sessions
                                ."<td>". $row['social_studies'] ."</td>"                       // Social Studies
                                ."<td>". $row['social_studies_participants'] ."</td>"          // Social Studies Participants
                                ."<td>". $row['social_studies_sessions'] ."</td>"              // Social Studies Sessions
                                ."<td>". $row['special_education'] ."</td>"                    // Special Education
                                ."<td>". $row['special_education_participants'] ."</td>"       // Special Education Participants
                                ."<td>". $row['special_education_sessions'] ."</td>"           // Special Education Sessions
                                ."<td>". $row['other'] ."</td>"                                // Other
                                ."<td>". $row['other_participants'] ."</td>"                   // Other Participants
                                ."<td>". $row['other_sessions'] ."</td>"                       // Other Sessions
                                ."</tr>";
                        }
                    }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Initiative</th>
                    <th>Total Programs</th>
                    <th>Biology Programs</th>
                    <th>Biology Participants</th>
                    <th>Biology Sessions</th>
                    <th>Chemistry Programs</th>
                    <th>Chemistry Participants</th>
                    <th>Chemistry Sessions</th>
                    <th>English/Language Arts Programs</th>
                    <th>English/Language Participants</th>
                    <th>English/Language Sessions</th>
                    <th>Technology Programs</th>
                    <th>Technology Participants</th>
                    <th>Technology Sessions</th>
                    <th>Career Tech Programs</th>
                    <th>Career Tech Participants</th>
                    <th>Career Tech Sessions</th>
                    <th>Counseling Programs</th>
                    <th>Counseling Participants</th>
                    <th>Counseling Sessions</th>                        
                    <th>Climate and Culture Programs</th>
                    <th>Climate and Culture Participants</th>
                    <th>Climate and Culture Sessions</th>                          
                    <th>Effective Instruction Programs</th>
                    <th>Effective Instruction Participants</th>
                    <th>Effective Instruction Sessions</th> 
                    <th>Fine Arts Programs</th>
                    <th>Fine Arts Participants</th>
                    <th>Fine Arts Sessions</th>
                    <th>Foreign Language Programs</th>
                    <th>Foreign Language Participants</th>
                    <th>Foreign Language Sessions</th>
                    <th>Gifted Programs</th>
                    <th>Gifted Participants</th>
                    <th>Gifted Sessions</th>
                    <th>Interdisciplinary Programs</th>
                    <th>Interdisciplinary Participants</th>
                    <th>Interdisciplinary Sessions</th>
                    <th>Leadership Programs</th>
                    <th>Leadership Participants</th>
                    <th>Leadership Sessions</th>
                    <th>Library Media Services Programs</th>
                    <th>Library Media Services Participants</th>
                    <th>Library Media Services Sessions</th>
                    <th>Mathematics Programs</th>
                    <th>Mathematics Participants</th>
                    <th>Mathematics Sessions</th>
                    <th>NBCT Programs</th>
                    <th>NBCT Participants</th>
                    <th>NBCT Sessions</th>
                    <th>Physics Programs</th>
                    <th>Physics Participants</th>
                    <th>Physics Sessions</th>
                    <th>Physical Education Programs</th>
                    <th>Physical Education Participants</th>
                    <th>Physical Education Sessions</th>
                    <th>Science Programs</th>
                    <th>Science Participants</th>
                    <th>Science Sessions</th>
                    <th>Social Studies Programs</th>
                    <th>Social Studies Participants</th>
                    <th>Social Studies Sessions</th>
                    <th>Special Education Programs</th>
                    <th>Special Education Participants</th>
                    <th>Special Education Sessions</th>
                    <th>Other Programs</th>
                    <th>Other Participants</th>
                    <th>Other Sessions</th>
                </tr>
            </tfoot>
        </table>
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