<?php
    // Unsubscribe from the newsletter
    require "../../../resources/config.php";
    require "../captcha/get_captcha_hash.php";
    
    $email = $_POST['email'];
    $captcha_entered = $_POST['captcha_entered'];
    $captcha_hash = $_POST['captcha_hash'];


    $db = $config['db']['amsti_01'];
    $link = new mysqli($db['host'], $db['username'], $db['password'], $db['dbname']) or die('There was a problem connecting to the database.');

    if ($email != "") {

        if (rpHash($captcha_entered) == $captcha_hash) {

            if(filter_var($email, FILTER_VALIDATE_EMAIL)) {

                // Check if the email is in the database
                $check_query = "SELECT email FROM subscribers WHERE email='$email'";
                $check_result = $link->query($check_query) or die("Error : ".mysqli_error($link)); 

                if (mysqli_num_rows($check_result) != 0) {

                    $query = "DELETE FROM subscribers WHERE email='$email' LIMIT 1"; 
                    if ($link->query($query) or die($link->error)) {
                        echo "Successfully Unsubscribed!";
                    }
                    else {
                        echo "There was a problem unsubscribing. Please try again or contact the administrator."; // Error deleting email from the database
                    }
                }
                else {
                    echo "This E-mail is not subscribed to the newsletter. Check the spelling and try again."; // Unsubscribed email address
                }
            }
            else {
                echo "Please enter a valid E-mail adress."; // Email address is formatted incorrectly
            }
        }
        else {
            echo "Captcha response is incorrect."; // Invalid user entered captcha
        }
    }
    else {
        echo "Please provide an E-mail address."; // Empty email address
    }

    $link->close();
?>