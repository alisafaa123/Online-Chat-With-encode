<?php
$servername = "localhost";
$usernam = "root";
$password = "";
$dbname = "security";

// Create connection
$conn = new mysqli($servername, $usernam, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
