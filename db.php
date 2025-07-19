<?php
$servername = "sql111.infinityfree.com";
$username   = "if0_39300051";
$password   = "Sijjunani"; // Your MySQL password
$database   = "if0_39300051_sijjubhai";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
