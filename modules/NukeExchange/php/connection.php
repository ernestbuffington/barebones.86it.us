<?php
/**
** php code to connect to db, just include in file
**/

$servername = 'localhost';
$username = 'barebones_user';
$user_password = 'xwdNPADv86bm';
$dbServerHost = "barebones.86it.us";
$dbName = "barebones_db";

$conn = new mysqli($servername, $username, $user_password, $dbName, 3306);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


