<?php
// delete.php

// Database connection
require 'db_connect.php';  // Include your database connection

// Get the posted JSON data
$data = json_decode(file_get_contents("php://input"));

// Prepare and bind to fetch the video path before deletion
$stmt = $conn->prepare("SELECT file_path FROM videos WHERE id = ?");
$stmt->bind_param("i", $data->id);
$stmt->execute();
$stmt->bind_result($filePath);
$stmt->fetch();
$stmt->close();

$response = [];

// Check if the file exists and delete it
if (file_exists($filePath)) {
    if (unlink($filePath)) {
        // File deleted successfully, now delete from database
        $stmt = $conn->prepare("DELETE FROM videos WHERE id = ?");
        $stmt->bind_param("i", $data->id);
        
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Video deleted successfully!';
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to delete the video from the database.';
        }
        
        $stmt->close();
    } else {
        $response['success'] = false;
        $response['message'] = 'Failed to delete the video file.';
    }
} else {
    $response['success'] = false;
    $response['message'] = 'File does not exist.';
}

// Return response
header('Content-Type: application/json');
echo json_encode($response);
