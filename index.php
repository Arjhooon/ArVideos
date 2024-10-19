<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ArVideos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <style>
    /* General Styles */

*{
  padding: 0;
  margin: 0;
  box-sizing: border-box;
}


body {
  font-family: 'Roboto', sans-serif;
  background-color: #e7e5d9;
  color: #333;

}

h5 {
  font-weight: bold;
  color:#5e5946;
}

.container {
  width: 100%;
  max-width: 1200px;
  
}

.navbar {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  background-color: #c3c1ab;
}

.navbar-brand {
  font-size: 1.5rem;
  font-weight: bold;
  color:#5e5946;
}

.navbar-nav .nav-link {
  font-size: 1rem;
  margin-right: 20px;
}


/* Video Section */
.video-player {
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
  border-radius: 8px;
  overflow: hidden;

}

.video-upload h5 {
  margin-bottom: 15px;
  color:#5e5946;
}

.form-label {
  font-weight: 500;
}

.btn-primary {
  background-color: #5e5946;
  border: none;
  color:white;
}

.btn-primary:hover {
  background-color: #70684c;
}

.btn-danger {
  background-color: #5e5946;
  border: none;
}

.btn-danger:hover {
  background-color: #70684c;
}

/* Suggestions Sidebar */
.suggestions {
  background-color: #ffffff;
  border-radius: 8px;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
  padding: 20px;
}

.suggestions h5 {
  font-weight: 600;
  color:#5e5946;
  margin-bottom: 20px;
}

.suggested-video {
  border-bottom: 1px solid #e0e0e0;
  padding-bottom: 15px;
  margin-bottom: 15px;
}

.suggested-video h6 {
  font-size: 1rem;
  font-weight: bold;
  margin: 10px 0 5px;
  white-space: nowrap; /* Prevents the text from wrapping */
  overflow: hidden; /* Ensures that excess text is hidden */
  text-overflow: ellipsis; /* Displays an ellipsis ('...') if text overflows */
}


.suggested-video p {
  font-size: 0.85rem;
  color: #666;
}

.suggested-video button {
  margin-top: 10px;
}

/* Media Queries */
@media (max-width: 768px) {
  .navbar-nav .nav-link {
    font-size: 0.9rem;
    margin-right: 10px;
  }

  .video-player {
    height: auto;
  }

  .suggested-video video {
    height: 150px;
  }
}


  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg ">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">ArVideos</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="home-page.html">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Videos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">*******</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">*******</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container mt-4">
    <div class="row" >
      <!-- Video Section -->
      <div class="col-md-8">
        <div class="video-player">
          <video id="uploadedVideo" controls width="100%" height="450px">
            <source id="videoSource" src="vid.mp4" type="video/mp4">
            Your browser does not support the video tag.
          </video>
        </div>

        <div class="video-upload mt-5" >
          <h5>Upload Your Video</h5>
          <form id="videoUploadForm" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="videoFile" class="form-label">Choose a video file:</label>
              <input class="form-control" type="file" id="videoFile" name="videoFile" accept="video/*" required >
            </div>
            <button type="submit" class="btn btn-primary">Upload Video</button>
          </form>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="col-md-4" >
        <div class="suggestions">
          <h5>Up Next</h5>
          <div id="suggestedVideosList"></div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Handle video upload
    document.getElementById('videoUploadForm').addEventListener('submit', async function (event) {
      event.preventDefault();  // Prevent form submission

      const formData = new FormData(this);  // Automatically include all form data

      try {
        // Send the video file to the server
        const response = await fetch('upload.php', {
          method: 'POST',
          body: formData
        });

        const result = await response.json();
        if (result.success) {
          alert('Video uploaded successfully!');
          displayUploadedVideos();
        } else {
          alert('Video upload failed: ' + result.message);
        }
      } catch (error) {
        console.error('Error uploading video:', error);
        alert('Error uploading video.');
      }
    });

// Fetch and display uploaded videos
async function displayUploadedVideos() {
  try {
    const response = await fetch('get_videos.php');
    const videos = await response.json();

    const videoList = document.getElementById('suggestedVideosList');
    videoList.innerHTML = '';  // Clear the list before appending new videos

    videos.forEach(video => {
      const videoContainer = document.createElement('div');
      videoContainer.classList.add('suggested-video', 'mb-3');
      videoContainer.innerHTML = `
        <video width="100%" height="200px" style="pointer-events: none;">
          <source src="${video.file_path}" type="video/mp4">
          Your browser does not support the video tag.
        </video>
        <h6>${video.name}</h6>
        <p>${video.upload_date}</p>
        <button class="btn btn-danger delete-video" data-id="${video.id}">Delete</button>
      `;
      videoList.appendChild(videoContainer);

      // Add click event to play the video in the main player (excluding the video element itself)
      videoContainer.addEventListener('click', function (event) {
        if (!event.target.classList.contains('delete-video')) {
          const mainVideoPlayer = document.getElementById('uploadedVideo');
          const videoSource = document.getElementById('videoSource');
          
          videoSource.src = video.file_path;  // Update the main video player source
          mainVideoPlayer.load();  // Load the new video
          mainVideoPlayer.play();  // Automatically start playing
        }
      });
    });

    // Add delete functionality to each video
    document.querySelectorAll('.delete-video').forEach(button => {
      button.addEventListener('click', async function (event) {
        event.stopPropagation();  // Prevent click on video from firing

        const videoId = this.getAttribute('data-id');
        if (confirm('Are you sure you want to delete this video?')) {
          await deleteVideo(videoId);
        }
      });
    });
  } catch (error) {
    console.error('Error fetching videos:', error);
  }
}



    // Delete video function
    async function deleteVideo(videoId) {
      try {
        const response = await fetch('delete.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ id: videoId })
        });

        const result = await response.json();
        if (result.success) {
          alert('Video deleted successfully!');
          displayUploadedVideos();  // Refresh the list
        } else {
          alert('Failed to delete video: ' + result.message);
        }
      } catch (error) {
        console.error('Error deleting video:', error);
      }
    }

    window.onload = displayUploadedVideos;
  </script>
</body>
</html>
