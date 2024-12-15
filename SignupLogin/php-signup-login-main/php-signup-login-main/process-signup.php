<?php

if (empty($_POST["name"])) {
    die("Name is required");
}

if (empty($_POST["bscraid"])) {
    die("BSCRAid is required");
}

if ( ! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Valid email is required");
}

if (strlen($_POST["password"]) < 8) {
    die("Password must be at least 8 characters");
}

if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password must contain at least one letter");
}

if ( ! preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one number");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Passwords must match");
}

/************** check bscraid is valid and current ********/
$table1 = "bscra_mem";
$table2 = "bscra_mem_details";
$bscraid = $_POST["bscraid"];

$today = date("Y-m-d");
/****************************************************/
$dbhost = 'db5000151512.hosting-data.io';
$dbuser = 'dbu137865';
$dbpass = '5Sq467X5s_2407';
$dbname = 'dbs146630';
$table1 = "bscra_mem";
$table2 = "bscra_mem_details";
/****************************************************/
$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($mysqli->connect_errno) {
   printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}
$query  = "SELECT * FROM $table1 where (id = $bscraid) order by sname,fname";
$result = $mysqli->query($query);

while($row = $result->fetch_assoc()) 
{
$id = $row['id'];
$area = $row['area'];

$query2 = "SELECT * FROM $table2 where (bscra_id = $id) order by end_date desc";
$result2 = $mysqli->query($query2);
$row2 = $result2->fetch_assoc();

$originalDate = $row2['end_date'];
$newDate = date("jS-M-Y", strtotime($originalDate));

if ($originalDate >= $today)
{
//echo "BSCRA id IS valid";
 $valid = "y";
}
else
{
//echo "BSCRA id IS NOT valid";
 $valid = "n";
}


} 
/************** END check bscraid is valid and current ********/
if ($valid == "y")
{
    

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$mysqli = require __DIR__ . "/database.php";

$sql = "INSERT INTO user (name, email, bscraid, password_hash)
        VALUES (?, ?, ?, ?)";
        
$stmt = $mysqli->stmt_init();

if ( ! $stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("ssss",
                  $_POST["name"],
                  $_POST["email"],
                  $_POST["bscraid"],
                  $password_hash);
                  
if ($stmt->execute()) {

    header("Location: signup-success.html");
    exit;
    
} else {
    
    if ($mysqli->errno === 1062) {
        die("email already taken");
    } else {
        die($mysqli->error . " " . $mysqli->errno);
    }
}
}
else
{
    //echo "BSCRA id is NOT valid";
        header("Location: signin_fail.php");
    exit;
}
