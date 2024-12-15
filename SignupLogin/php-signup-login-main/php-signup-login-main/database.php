<?php
$host = 'db5000151512.hosting-data.io';
$username = 'dbu137865';
$password = '5Sq467X5s_2407';
$dbname = 'dbs146630';

//$host = "localhost";
//$dbname = "login_db";
//$username = "root";
//$password = "";

$mysqli = new mysqli(hostname: $host,
                     username: $username,
                     password: $password,
                     database: $dbname);
                     
if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli;
