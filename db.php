<?php
$host = "localhost";
$user = "root";         // or your MySQL username
$pass = "";             // your MySQL password
$dbname = "eduplay";    // your database name — make sure it exists in phpMyAdmin

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
