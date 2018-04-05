<?php
    require "../../../resources/config.php";
    require "../../../resources/library/PHPMailer/src/PHPMailer.php";
    require "../../../resources/library/PHPMailer/src/Exception.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    $display_block = "";

    # create connection to database
    $mysqli = new mysqli($config['db']['amsti_01']['host']
        , $config['db']['amsti_01']['username']
        , $config['db']['amsti_01']['password']
        , $config['db']['amsti_01']['dbname']);

    /* check connection */
    if ($mysqli->connect_errno) 
    {
        printf("Connect failed: %s\n", $mysqli->connect_error);
        exit();
    }

    // Get subscribers emails
    $sql = "SELECT email FROM subscribers";

    $sent_emails = "";
    $error_sending = "";
    $no_newsletter = "";
    $no_subscribers = "";

    if ($email_results = mysqli_query($mysqli, $sql))
    {
        if (mysqli_num_rows($email_results) > 0)
        {
            // Get the current newsletter
            $sql = "SELECT file_path, name FROM newsletters WHERE current='yes'";

            if ($newsletter_results = mysqli_query($mysqli, $sql))
            {
                if (mysqli_num_rows($newsletter_results) != 0) 
                {
                    $newsletter_row = mysqli_fetch_row($newsletter_results);
                    $newsletter_path = $newsletter_row[0];   // file_path
                    $newsletter_name = $newsletter_row[1];   // name
                    $subject = stripslashes($_POST['subject']);
                    $message = stripslashes($_POST['message']);

                    $file = $newsletter_path;
                    $fp = @fopen($file, "rb");
                    $pdf_data = @fread($fp, filesize($file));
                    @fclose($fp);

                    while ($email_row = mysqli_fetch_row($email_results)) 
                    {
                        $email_message = new PHPMailer();
                        // $email_message->From = "inserviceathens@gmail.com";
                        $email_message->From = "inserviceathens@myathensric.org";
                        $email_message->FromName = 'Athens State Regional Inservice Center';
                        $email_message->Subject = $subject;
                        $email_message->Body = $message;
                        $email_message->AddStringAttachment($pdf_data, $newsletter_name, "base64", "application/pdf");

                        $email_address = $email_row[0];   // email address

                        $email_message->AddAddress($email_address);

                        set_time_limit(0);

                        if ($email_message->Send()) 
                        {
                            $sent_emails .= $email_address . "<br>";   
                        }
                        else 
                        {
                            $error_sending .= $email_address . " => " . $email_message->ErrorInfo . "<br>";
                        }
                        $email_message->ClearAddresses();
                    }
                }
                else 
                {
                    $no_newsletter = "no_newsletter";
                }
            }
            else
            {
                echo "ERROR:  " . mysqli_error($mysqli);
            }
        }
        else
        {
            $no_subscribers = "no_subscribers";
        } 
    }
    else
    {
        echo "ERROR:  " . mysqli_error($mysqli);
    }

    if ($sent_emails == "")
    {
        $sent_emails = "No E-mails were sent.";
    }

    if ($error_sending == "")
    {
        $error_sending = "There were no errors sending E-mails";
    }

    if (($no_newsletter == "") && ($no_subscribers == ""))
    {
        echo "<label>Successfully Sent E-mails To:</label><br>";
        echo $sent_emails;
        echo "<br>";
        echo "<label>There Were Errors Sending E-mails To:</label><br>";
        echo $error_sending;        
    }
    elseif ($no_newsletter != "")
    {
        echo "<label>No current newsletter is selected</label><br>";
        echo "Please select a current newsletter in <br>";
        echo "the 'Manage Newsletters' section <br>";
    }
    elseif ($no_subscribers != "")
    {
        echo "<label>No subscribers</label><br>";
        echo "There are no subscribers to the newsletter.";
    }

    mysqli_close($mysqli);
?>