<?php
    // View the current newsletter
    require "../../resources/config.php";

    $table = $_GET['table'];
    $page = $_GET['page'];

    $db = $config['db']['amsti_01'];
    $link = new mysqli($db['host'], $db['username'], $db['password'], $db['dbname']) or die('There was a problem connecting to the database.');

    // Get the viewable file
    $query = "SELECT file_path FROM $table WHERE current='yes'";
    $result = $link->query($query) or die("Error : ".mysqli_error($link));

    if ($result) {

        $row = mysqli_fetch_array($result);
        $path = substr($row['file_path'], 3);

        // Get the file's mime type to send the correct content type header
        if (file_exists($path)) {

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $path);

            // send the headers
            header("Content-Type: $mime_type");
            header('Content-Length: ' . filesize($path));

            // Stream the file
            $fp = fopen($path, 'rb');
            fpassthru($fp);  
        }
        else {
            if ($table == 'newsletters')
            {
                echo "<script type='text/javascript'>alert('ERROR: There was a problem opening the current newsletter. The file might have been removed $path.')</script>";
            }
            if ($table == 'bylaws')
            {
                echo "<script type='text/javascript'>alert('ERROR: There was a problem opening the current bylaws. The file might have been removed $path.')</script>";
            }

        }
 
    }
    else {
        echo "<script type='text/javascript'>alert('ERROR: There was an issue querying the database for the $table')</script>";
    }
?>