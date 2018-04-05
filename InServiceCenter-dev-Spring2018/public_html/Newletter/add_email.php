<?php

//require 'newsletter_db.php';

// Make sure the form is being submitted with method="post"


        // We get $_POST['email'] from the hidden input field of newsletter form at home.html
      //  $email = $mysqli->escape_string($_POST['email']);
/*
$host = 'myathensricorg.ipowermysql.com';
$user = 'accounts';
$pass = 'password';
$db = 'accounts';
$mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);

*/
$conn = new mysqli("myathensricorg.ipowermysql.com", "accounts", "password", "accounts");   
if ($conn -> connect_error) { die("Connection failed: " . $conn->connect_error); } 

        
        $sql = "INSERT INTO newsletter_emails (id, email, subscribe) VALUES ('".$_GET['email'] . "' ,'1')";


        if ($conn->query($sql) === TRUE) {
        
         echo "New Record created";
        } else {
    echo "Error: " . $sql . "<br>" . mysqli->error;
}
       

?>
