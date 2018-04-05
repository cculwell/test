<?php
    // Delete a subscriber from the database
    require "../../../resources/config.php";

    $email = $_POST['email'];

    # create connection to database
    $mysqli = new mysqli($config['db']['amsti_01']['host']
        , $config['db']['amsti_01']['username']
        , $config['db']['amsti_01']['password']
        , $config['db']['amsti_01']['dbname']);

    /* check connection */
    if ($mysqli->connect_errno) {
        echo "Connect failed: " . $mysqli->connect_error;
        exit();
    }

    $sql = "DELETE FROM subscribers WHERE email='$email' LIMIT 1"; 
    $result = mysqli_query($mysqli, $sql);

    if ($result) {
        echo "removed";
    }
    else {
        echo "ERROR:  " . mysqli_error($mysqli);
    }

    mysqli_close($mysqli);
?>