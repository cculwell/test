<?php
    require "../../../resources/config.php";

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

    $file = $_POST['file'];
    $table = $_POST['table'];

    // Check if the file exists
    $sql = "SELECT name from $table WHERE name='$file'";

    if (mysqli_query($mysqli, $sql))
    {
        // Reset all files to 'deactived'
        $sql = "UPDATE $table SET current='no' WHERE current='yes'";

        if ($reset_result = mysqli_query($mysqli, $sql))
        {
            $sql = "UPDATE $table SET current='yes' WHERE name='$file' LIMIT 1";
            if (mysqli_query($mysqli, $sql))
            {
                echo "set_current";
            }
            else
            {
                echo "ERROR:  " . mysqli_error($mysqli);
            }
        }
        else 
        {
            echo "ERROR:  " . mysqli_error($mysqli);
        }
    }
    else
    {
        echo "ERROR:  " . mysqli_error($mysqli);
    }

    mysqli_close($mysqli);
?>