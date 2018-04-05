<?php
	// Subscribe to the newsletter

    require "../../../resources/config.php";
    require "../captcha/get_captcha_hash.php";
    
    $email = $_POST['email'];
    $captcha_entered = $_POST['captcha_entered'];
    $captcha_hash = $_POST['captcha_hash'];

//    echo $captcha_entered;
//    echo $captcha_hash;
//    echo rpHash($captcha_entered);


    $db = $config['db']['amsti_01'];
    $link = new mysqli($db['host'], $db['username'], $db['password'], $db['dbname']) or die('There was a problem connecting to the database.');

    if ($email != "") {

        if (rpHash($captcha_entered) == $captcha_hash) {

            if(filter_var($email, FILTER_VALIDATE_EMAIL)) {

                // Check if user is already subscribed
                $check_query = "SELECT email FROM subscribers WHERE email='$email'";
                $check_result = $link->query($check_query) or die("Error : ".mysqli_error($link)); 

                if (mysqli_num_rows($check_result) == 0) {
            	    $query = "INSERT INTO subscribers (email) VALUES ('$email')";
            	    $result = $link->query($query) or die("Error : ".mysqli_error($link)); 

            	    if ($result) {
            	    	echo "Successfully Subscribed!"; // Successfully subscribed
            	    }
            	    else {
                        echo "There was a problem subscribing. Please try again or contact the administrator."; // Error entering email into database
            	    }
                }
                else {
                    echo "The E-mail address provided is already being used."; // Already subscribed
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