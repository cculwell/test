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
    $sql = "SELECT * from $table WHERE name='$file'";

    if ($result = mysqli_query($mysqli, $sql))
    {
        $row = mysqli_fetch_row($result);
        $file_to_delete = $row[3];

        // If it exists delete it
        if (file_exists($file_to_delete)) 
        {
            // Delete file
            if (unlink($file_to_delete)) 
            {
                // Delete database reference after file deletion
                $sql = "DELETE FROM $table WHERE name='$file' LIMIT 1"; 

                if (mysqli_query($mysqli, $sql)) 
                {
                    echo "deleted";
                }
                else 
                {
                    echo "ERROR:  " . mysqli_error($mysqli);
                }
            }
            else 
            {
                echo "Unable to delete: " . $file . " from the server.";
            }
        }
        else 
        {
            // Delete database reference when file isn't on the server for some reason
            $sql = "DELETE FROM $table WHERE name='$file' LIMIT 1"; 

            if (mysqli_query($mysqli, $sql))
            {
                echo "deleted";
            }
            else 
            {
                echo "ERROR:  " . mysqli_error($mysqli);
            }
        }
    }
    // Can't fine database reference
    else
    {
        echo "No database entry found for file: " . $file;
    }

    mysqli_close($mysqli);
?>