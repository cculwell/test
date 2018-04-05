<?php
/* Variblized the connection string to mysqli */

require "../resources/config.php";

$mysqli = new mysqli($config['db']['amsti_01']['host']
                    , $config['db']['amsti_01']['username']
                    , $config['db']['amsti_01']['password']
                    , $config['db']['amsti_01']['dbname']);

/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

/* check if server is alive */
if ($mysqli->ping()) {
    printf ("Our connection is ok!\n");
} else {
    printf ("Error: %s\n", $mysqli->error);
}

/* close connection */
$mysqli->close()
?>
