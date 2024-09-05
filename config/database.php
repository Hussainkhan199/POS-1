<?php
$servername = "localhost";
$username = "root";
$password = ""; // Add your MySQL password here
$dbname = "app";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully";
