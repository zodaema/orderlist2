<?php
// Database configuration
$host = 'localhost';
$dbname = 'rebello_orderlist';
$username = 'root';
$password = 'root';

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
