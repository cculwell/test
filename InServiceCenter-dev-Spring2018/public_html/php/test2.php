<?php
/**
 * User: rickmilliken
 * Date: 9/3/17
 * Time: 2:16 PM
 * PHP file that will add a request to the database.
 */

require "../../resources/config.php";
require "../php/captcha/get_captcha_hash.php";


$captcha_entered = $_POST['captcha_entered'];
$captcha_hash = $_POST['captcha_hash'];
$server_hash = rpHash($captcha_entered);

echo "captch_entered: " . $captcha_entered . PHP_EOL;
echo "captcha_hash: " . $captcha_hash . PHP_EOL;
echo "server_hash:  " . $server_hash . PHP_EOL;


if ($server_hash == $captcha_hash) {
    debug_to_console("matched");
} else {
    debug_to_console("did not match");
}

