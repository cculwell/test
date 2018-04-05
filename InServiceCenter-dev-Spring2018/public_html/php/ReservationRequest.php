<?php

require "../php/Common.php";
require "../php/captcha/get_captcha_hash.php";
require "../../resources/library/PHPMailer/src/PHPMailer.php";
require "../../resources/library/PHPMailer/src/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


//Connect to the database
$conn = new mysqli($config['db']['amsti_01']['host']
    , $config['db']['amsti_01']['username']
    , $config['db']['amsti_01']['password']
    , $config['db']['amsti_01']['dbname']);
//Get Error
if ($conn -> connect_error) { die("Connection failed: " . $conn->connect_error); }


if(isset($_POST['program']) && isset($_POST['responsible']) && isset($_POST['sponsor'])&& isset($_POST['evntdesc'])
    && isset($_POST['room']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['attend'])
    && isset($_POST['accept']) && isset($_POST['captcha'])&&isset($_POST['captchaHash']))
{
    $Captcha_User = $_POST['captcha'];
    $Captcha_HASH = $_POST['captchaHash'];
    //Check if hash matches the users hash
    if(rpHash($Captcha_User) == $Captcha_HASH)
    {
        $Program = SanitizePostString($conn, $_POST['program']);
        $InCharge = SanitizePostString($conn, $_POST['responsible']);
        $groupSponsor = SanitizePostString($conn, $_POST['sponsor']);
        $Description = SanitizePostString($conn, $_POST['evntdesc']);
        $RoomReservation = SanitizePostString($conn, $_POST['room']);
        $Email = SanitizePostString($conn, $_POST['email']);
        $PhoneNumber = SanitizePostString($conn, $_POST['phone']);
        $BookedStatus = 'pending';
        $smartBoard = (isset($_POST['Smartboard']) ? 'Yes': 'No');
        $Projector = (isset($_POST['projector']) ? 'Yes': 'No');
        $ExtensionCord = (isset($_POST['extensioncords']) ? 'Yes': 'No');
        $DocumentCamera = (isset($_POST['documentcamera']) ? 'Yes' : 'No');
        $AV_Need = (isset($_POST['avsetup']) ? 'Yes': 'No');
        $NumberEvents = SanitizePostString($conn, $_POST['attend']);

        //Create Query to insert information for the event
        $statement = $conn->prepare('Insert into reservations (programName, programPerson,
                            programGroup, programDescription, room, email,
                            phone, bookedStatus, sm_board, ex_cord, projector,
                            document_camera, av_needs, num_events)VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)');

        $statement->bind_param('ssssssssssssss', $Program, $InCharge, $groupSponsor, $Description, $RoomReservation, $Email, $PhoneNumber,
            $BookedStatus, $smartBoard, $ExtensionCord, $Projector,  $DocumentCamera, $AV_Need, $NumberEvents);


        $statement->execute();
        $statement->close();

        //Get The primary key
        $ReservationID = $conn->insert_id;
        //Using Rick's way of putting dynamic date and time in MySQL
        $index = 0;
        $reservationDatesAndTime = array();
        $Email_Subject = $Program;
        foreach ($_POST as $key)
        {
            if(preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $key))
            {
                //For PHPMail Summary
                $mail_date = SanitizePostString($conn, $_POST['requesteddatefrom' . $index]);
                $mail_startTime = SanitizePostString($conn, $_POST['starttime' . $index]);
                $mail_endTime =  SanitizePostString($conn, $_POST['endtime' . $index]);
                $mail_preTime = SanitizePostString($conn, $_POST['preeventsetup' . $index]);
                $Email_Subject .= ' Date: ' . $mail_date . '), Start: ' . $mail_startTime . '; End: ' . $mail_endTime . '; Pre: '. $mail_preTime;

                //Put dates in reservationDate_Time table
                $startDate = FormatDate4Db($mail_date);
                $startTime = FormatTime4Db($mail_startTime);
                $endTime = FormatTime4Db($mail_endTime);
                $preTime = FormatTime4Db($mail_preTime);
                $statement = $conn->prepare("INSERT INTO reservationDate_Time (reservationID, StartDate, startTime,
                                    endTime, preTime) VALUES (?,?,?,?,?)");
                $statement->bind_param('sssss', $ReservationID, $startDate, $startTime, $endTime, $preTime);
                $statement->execute();
                $index++;
            }
        }
        //The Automated reservation email to users confirming that they have a pending reservation.
        $mail = new PHPMailer();
        $Mail_Body = "Thank You for submitting your date request through our
                      automated system. Our staff will review your request. Once
                      reviewed you will receive an email informing you if the request
                      is approved or denied.
                      Please remember our building hours are Monday-Friday, 8:00-4:30";
        //From email address and name
        $mail->From = "inserviceathens@gmail.com";
        $mail->FromName =  'Athens State Regional In-Service Center';
        //To address and name
        $mail->AddAddress($Email);
        $mail->Subject = $Email_Subject;
        $mail->Body = $Mail_Body;


        if(!$mail->Send())
        {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
        else
        {
            echo "Message has been sent successfully";
        }
        //Mail to the Administrator to alert them of a pending request
        $Mail_Alert = new PHPMailer();
        $Mail_Alert->From = "inserviceathens@gmail.com";
        $Mail_Alert->FromName =  'Athens State Regional In-Service Center';

        $Mail_Alert->AddAddress('inserviceathens@gmail.com');//Change when done testing
        $Mail_Alert->Subject = $Email_Subject ;
        $Mail_Alert->Body = "Hello, there is a new pending reservation request awaiting for your approval";

        if(!$Mail_Alert->Send())
        {
            echo "Mailer Error: " . $Mail_Alert->ErrorInfo;
        }
        else
        {
            echo "Message has been sent successfully";
        }




    }
    elseif(rpHash($Captcha_User) !== $Captcha_HASH) {
        echo "captcha failed";
        exit();
    }
    else
    {
        echo"All fields are not filled";
    }
}
else
{
    echo "SQL Error";
}
function mysql_fixstring($conn, $string)
{
    if(get_magic_quotes_gpc()) $string=stripslashes($string);
    return $conn->real_escape_string($string);
}
function SanitizePostString($conn, $string)
{
    return htmlentities(mysql_fixstring($conn, $string));
}

$conn->close();
?>