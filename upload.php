<?php
// upload.php
include 'db_connect.php'; // Include your database connection file

$targetDirectory = "uploads/";  // Ensure this directory exists and is writable
$targetFile = $targetDirectory . basename($_FILES["videoFile"]["name"]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// Check if video file is an actual video
if (isset($_FILES["videoFile"])) {
    // Check file size (e.g., limit to 50MB)
    if ($_FILES["videoFile"]["size"] > 50000000) {
        echo json_encode(["success" => false, "message" => "File is too large."]);
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (!in_array($fileType, ["mp4", "avi", "mov"])) {
        echo json_encode(["success" => false, "message" => "Only MP4, AVI & MOV files are allowed."]);
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo json_encode(["success" => false, "message" => "Your file was not uploaded."]);
    } else {
        // Attempt to move the uploaded file
        if (move_uploaded_file($_FILES["videoFile"]["tmp_name"], $targetFile)) {
            // Insert the video details into the database
            $stmt = $conn->prepare("INSERT INTO videos (name, file_path, upload_date) VALUES (?, ?, NOW())");
            $stmt->bind_param("ss", $_FILES["videoFile"]["name"], $targetFile);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "file_path" => $targetFile]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to save video information to the database: " . $stmt->error]);
            }
            $stmt->close(); // Close the prepared statement
        } else {
            echo json_encode(["success" => false, "message" => "There was an error uploading your file."]);
        }
    }
} else {
    echo json_encode(["success" => false, "message" => "No video file was uploaded."]);
}

// Close the database connection
$conn->close();
?>
