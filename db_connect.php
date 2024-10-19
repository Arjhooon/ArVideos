<?php
// Database connection details
$host = 'localhost';    // Hostname
$username = 'root';      // MySQL username
$password = '';          // MySQL password
$database = 'youtube_clones';  // The database you created

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
