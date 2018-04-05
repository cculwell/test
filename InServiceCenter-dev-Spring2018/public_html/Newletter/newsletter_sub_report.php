<?php
//require "Common.php";
    

$host = 'myathensricorg.ipowermysql.com';
$user = 'accounts';
$pass = 'password';
$db = 'accounts';
$mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);
$result = $mysqli->query("SELECT * FROM newsletter_emails");


//Return every row in the table

//Output the html
if ($result->num_rows > 0)
{
   echo "<html>\r\n";
   echo "   <head>\r\n";
   echo "      <title>Newsletter Emails Report</title>\r\n";
   echo "      <link rel='stylesheet' href='http://myathensric.org/Report.css' />\r\n";
   echo "   </head>\r\n"; 
   echo "   <body>\r\n";
   echo "      <div id = 'toplogo'>\r\n";
   echo "         <img src='http://myathensric.org/Home_Data/Logo.jpg' alt ='AthensLogo'>\r\n";      
   echo "      </div>\r\n";  
   echo "      <table id='tblReport'>\r\n";
   echo "         <tr>\r\n";
   echo "               <th>ID</th>\r\n";
   echo "               <th>Email</th>\r\n";
   echo "               <th>Subscribed</th>\r\n";
   echo "               <th>Need Area</th>\r\n";
   echo "         </tr>\r\n";
   echo "         <tbody>\r\n";

   while($row = $result->fetch_assoc()) 
   {      
         echo "            <tr>\r\n";
         echo "               <td>" . $row["id"] . "</td>\r\n";
         echo "               <td>" . $row["email"] . "</td>\r\n";
         echo "               <td>" . $row["subscribe"] . "</td>\r\n";
         echo "            </tr>\r\n";
    }

   echo "         </tbody>\r\n";
   echo "      </table>\r\n";
   echo "   </body>\r\n";
   echo "</html>";
}
else
{
   echo "No records found.";
}

$result->close();
?> 