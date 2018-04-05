<?php
require "Common.php";

$date02 = "0000-00-00";

$parseDate = explode(",", $_GET['dates']);
$date01 = FormatDate4Db($parseDate[0]);

if (count($parseDate) > 1)
{
   $date02 =  FormatDate4Db($parseDate[1]);
}

$time02 = "00:00 AM";

$parseTime = explode(",", $_GET['times']);
$time01 = FormatTime4Db($parseTime[0]);

if (count($parseTime) > 1)
{
   $time02 =  FormatTime4Db($parseTime[1]);
}

$conn = new mysqli($config['db']['amsti_01']['host']
    , $config['db']['amsti_01']['username']
    , $config['db']['amsti_01']['password']
    , $config['db']['amsti_01']['dbname']);


if ($conn -> connect_error) { die("Connection failed: " . $conn->connect_error); } 

$sql = "INSERT INTO tblGeneralRequest(vcSchool
                                    , vcSystem
                                    , vcRequest
                                    , vcJustification
                                    , dtDate01
                                    , dtDate02
                                    , dtTime01
                                    , dtTime02
                                    , vcLocation
                                    , iTotalHours
                                    , vcTargetPartic
                                    , iNumPartic
                                    , vcEvalMethod
                                    , vcCompany
                                    , vcContactInfo
                                    , fAmount
                                    , vcContact
                                    , vcPhone
                                    ,  vcEmail
) VALUES ('".
            $_GET['school'] . "', '" .
            $_GET['system'] . "', '" .
            $_GET['request'] . "', '" .
            $_GET['needarea'] . "', '" .
            $date01 . "', '" .
            $date02 . "', '" .
            $time01 . "', '" .
            $time02 . "', '" .
            $_GET['location'] . "', " .
            $_GET['totalhours'] . ", '" .
            $_GET['targetparticipant'] . "', " .
            $_GET['numberpart'] . ", '" .
            $_GET['evalmethod'] . "', '" .
            $_GET['copublisher'] . "', '" .
            $_GET['contact'] . "', " .
            $_GET['amtrequested'] . ", '" .
            $_GET['contactperson'] . "', '" .
            $_GET['Phone'] . "', '" .
            $_GET['email'] . "')";

if ($conn->query($sql) === TRUE) {
   $to = "Holly.Wood@athens.edu";
   $subject = "General Request";
   $txt = "School: " . $_GET['school'] . "\r\n" . 
      "System: " . $_GET['system'] . "\r\n" . 
      "Request: " . $_GET['request'] . "\r\n" . 
      "Need Area: " . $_GET['needarea'] . "\r\n" .
      "Dates: " . $_GET['dates'] . "\r\n" .
      "Times: " . $_GET['times'] . "\r\n" .
      "Location: " . $_GET['location'] . "\r\n" .
      "Total Hours: " . $_GET['totalhours'] . "\r\n" .
      "Target Participant: " . $_GET['targetparticipant'] . "\r\n" .
      "# Participating: " . $_GET['numberpart'] . "\r\n" .
      "Method of Evaluation: " . $_GET['evalmethod'] . "\r\n" .
      "Company: " . $_GET['copublisher'] . "\r\n" .
      "Contact Info: " . $_GET['contact'] . "\r\n" .
      "Amt Requested: " . $_GET['amtrequested'] . "\r\n" .
      "Contact Person: " . $_GET['contactperson'] . "\r\n" .
      "Phone #: " . $_GET['Phone'] . "\r\n" .
      "Email: " . $_GET['email'];
   $headers = "From: webmaster@myathensric.org";

   mail($to,$subject,$txt,$headers);

    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>