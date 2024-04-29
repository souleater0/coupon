<?php
$servername = "localhost:3309";
$username = "root";
$password = "";
$database = "ecfoodstub_db";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
