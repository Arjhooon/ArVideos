<?php
// get_videos.php
header('Content-Type: application/json');
include 'db_connect.php';  // Include your database connection

// Query to fetch videos from the database
$query = "SELECT id, name, file_path, upload_date FROM videos ORDER BY upload_date DESC";
$result = $conn->query($query);
$videos = [];

while ($row = $result->fetch_assoc()) {
    $videos[] = $row;
}

echo json_encode($videos);
?>
