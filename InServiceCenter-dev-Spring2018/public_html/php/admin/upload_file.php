<?php
    require "../../../resources/config.php";

    $file = $_FILES['the_file']['name'];
    $size = $_FILES['the_file']['size'];
    $error = $_FILES['the_file']['error'];
    $tmp = $_FILES['the_file']['tmp_name'];
    $table = $_POST['table'];
    $directory = $_POST['directory'];
    $target = "../../../Uploads/" . $directory . "/" . $file;

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

    // Check if a file already exists
    function does_file_exist($table, $file) {

        $sql = "SELECT name FROM $table WHERE name='$file'";

        if ($result = mysqli_query($GLOBALS['mysqli'], $sql))
        {
            if(mysqli_num_rows($result) > 0)
            {
                echo $file . " already exists. Please rename/remove the file and try again.";
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            echo "ERROR:  " . mysqli_error($GLOBALS['mysqli']);
            return true;
        }
    }

    // Add a new file to the database and activate it as the 'current' file
    function add_file_to_database($table, $file, $target) {

        // Deactivate the 'current' file
        $sql = "UPDATE $table SET current='no' WHERE current='yes'";

        if ($result = mysqli_query($GLOBALS['mysqli'], $sql))
        {
            $target = addslashes($target);

            $sql = "INSERT INTO $table (name, current, file_path) VALUES ('$file','yes','$target')";
            if ($result = mysqli_query($GLOBALS['mysqli'], $sql))
            {
                echo $file . " successfully uploaded.";
            }
            else
            {
                echo "ERROR adding file:  " . mysqli_error($GLOBALS['mysqli']);
            }
        }
        else
        {
            echo "ERROR resetting current:  " . mysqli_error($GLOBALS['mysqli']);
        }
    }

    if ( 0 < $error ) 
    {
        echo 'ERROR: ' . $error;
    }

    // Check if the file already exists
    if (!(does_file_exist($table, $file)))
    {
        // Try to upload the file
        if (move_uploaded_file($tmp, $target)) 
        {
            add_file_to_database($table, $file, $target);
        }
        else 
        {
            echo "$file was NOT uploaded.";
        }
    }

    mysqli_close($mysqli);
?>