<?php
/* Database connection settings*/

$host = 'myathensricorg.ipowermysql.com';
$user = 'accounts';
$pass = 'password';
$db = 'accounts';
$mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);


/* Database connection settings 
$user = 'root';
$pass = '';
$db = 'accounts';
$mysqli = new mysqli('localhost',$user,$pass,$db) or die($mysqli->error);
*/