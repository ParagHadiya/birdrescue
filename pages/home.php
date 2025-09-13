<style>
  body {
    background-image: url('./assets/images/Bird_Flyingvideo.gif');
    background-size: cover;  /* Ensures the image covers the entire screen */
    background-position: center center;  /* Centers the image */
    background-repeat: no-repeat;  /* Prevents the image from repeating */
    height: 100vh;  /* Makes the body take full viewport height */
    margin: 0;  /* Removes default margin */
}
/* Transparent box container */
.box-container {
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black background */
    padding: 20px;
    margin-top: 20px;
    text-align: center;
    border-radius: 8px;
    width: 80%; /* Adjust the width as needed */
    max-width: 600px; /* Set a max width */
    margin-left: auto;
    margin-right: auto;
}

/* Styling for the buttons */
.button-container {
    margin-top: 10px;
}

.action-button {
    background-color: #5c48ce; /* Green background for buttons */
    color: white;
    border: none;
    padding: 10px 20px;
    margin: 5px;
    cursor: pointer;
    border-radius: 20px 1px 20px 1px ;
    font-size: 16px;
    box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease-in-out;
}

/* Hover effect for the buttons */
.action-button:hover {
    background-color: #45a049; /* Slightly darker green when hovered */
    border-radius: 1px 20px 1px 20px ;
    transform: translateY(-2px); 
    border: 2px solid #5c48ce;

}

  /* Grid Layout */
  .video-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr); /* 3 columns */
      gap: 20px; /* Space between grid items */
      justify-items: center; /* Center content horizontally */
      padding: 20px;
  }

  /* Box Styling */
  .video-grid video,
  .video-grid iframe {
      border: 2px solid white; /* White border for contrast */
      border-radius: 8px; /* Rounded corners */
      background-color: #000; /* Black background */
      box-shadow: 0 4px 8px rgba(255, 255, 255, 0.2); /* Subtle white glow */
  }

  /* Section Styling */
  #save-birds {
      background-color: black; /* Black background */
  }

  #save-birds h2 {
      color: white; /* White text for header */
      margin-bottom: 20px;
  }

  .feedback-button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    font-size: 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    
}

.feedback-button:hover {
    background-color: #45a049;
}

.feedback-link {
    display: inline-block;
    padding: 10px 20px;
  
    color: white;
    text-decoration: none;
    border-radius: 4px;
}

 h3 {
        font-size: 18px;
    }


</style>

<?php
include './includes/header.php';
include './config/database.php'; // Include the database connection file


$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture form data
    $birdImage = $_FILES['bird_image']['name'];
    $bird = $_POST['bird'];
    $callerMobile = $_POST['caller_mobile'];
    $callerName = $_POST['caller_name'];
    $numberOfBirds = $_POST['number_of_birds'];
    $location = $_POST['location'];
    $address = $_POST['address'];
    $note = $_POST['note'];

    // Server-side validations
    if (empty($bird) || empty($callerMobile) || empty($callerName) || empty($numberOfBirds) || empty($address)) {
        $message = "<p style='color: red;'>All required fields must be filled out.</p>";
    } else {
        // File upload handling
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($birdImage);
        move_uploaded_file($_FILES['bird_image']['tmp_name'], $targetFile);

        // Insert data into the database
        $query = "INSERT INTO birds_rescue (bird_image, bird, caller_mobile, caller_name, number_of_birds, location, address, note) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssssisss', $targetFile, $bird, $callerMobile, $callerName, $numberOfBirds, $location, $address, $note);

        if ($stmt->execute()) {
            $message = "<p style='color: green;'>Injured bird details submitted successfully!</p>";
        } else {
            $message = "<p style='color: red;'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }
}
?>
<center>    


<div class="box-container">
<h2 style="color: white; font-size: 22px;">Welcome to Bird Rescue</h2>
<h3 style="color: white;font-size: 18px; " >Our mission is to help injured birds by connecting people with bird rescue services. Save the birds, save our environment.</h3>

    <p style="color: white;" >Get Involved Today!</p>
  
    <div class="button-container">

    <a href="http://localhost/birdrescue/?page=donate"><button class="action-button">Donate</button></a>
    <a href="http://localhost/birdrescue/?page=register"><button class="action-button">Volunteer</button></a>
    <a href="http://localhost/birdrescue/?page=about"> <button class="action-button">Learn More</button></a>
    <!-- <a href="http://localhost/birdrescue/?page=feedback"> <button class="action-button">Learn More</button></a> -->
    </div>
</div>

<!-- <div id="image-carousel">  
    <div class="carousel-container">
        <div class="carousel-image">
            <img src="./assets/images/bb.jpg" alt="Image 1">
        </div>
        <div class="carousel-image">
            <img src="./assets/images/slider1.jpg" alt="Image 2">
        </div>
        <div class="carousel-image">
            <img src="./assets/images/slider2.jpg" alt="Image 3">
        </div>
        <div class="carousel-image">
            <img src="./assets/images/slider.png" alt="Image 4">
        </div>
    </div>
</div> -->


<br><br><br><br>
<br><br><br><br>

<br><br><br><br>




<div class="carousel-container">
    <div class="carousel-image">
        <img src="./assets/images/IMG_0166-1-1.jpg" alt="Avian and Reptile Rehabilitation Centre">
        <div class="carousel-text">
            <h2>Avian and Reptile Rehabilitation Centre</h2>
            <button onclick="window.location.href='tel:8000874240'" class="call-button"><p>Call Us: <a href="tel:8000874240" class="call-link">8000874240</a></p></button>
            <h4>We rescue, rehabilitate, and release Surat's injured, orphaned, and diseased Urban Wildlife.</h4>
        </div>
    </div>
</div>


<section id="injured-birds">
    <h1>Injured Birds Detail</h1>
    <?php echo $message; ?>
    <form action="#" method="POST" enctype="multipart/form-data">
        
        <div class="form-field">
            <label for="birdImage">Bird Image (No file chosen):</label>
            <input type="file" id="birdImage" name="bird_image">
        </div>

        <div class="form-field">
            <label for="bird">Bird *</label>
            <input type="text" id="bird" name="bird" required>
        </div>

        <div class="form-field">
            <label for="callerMobile">Caller Mobile Number *</label>
            <input type="tel" id="callerMobile" name="caller_mobile" placeholder="Enter caller mobile number" required>
        </div>

        <div class="form-field">
            <label for="callerName">Caller Name *</label>
            <input type="text" id="callerName" name="caller_name" placeholder="Enter caller name" required>
        </div>

        <div class="form-field">
            <label for="numberOfBirds">Number of Birds *</label>
            <input type="number" id="numberOfBirds" name="number_of_birds" min="1" required>
        </div>

        <div class="form-field">
            <label for="location">Location (Google Map Link)</label>
            <input type="url" id="location" name="location" placeholder="Enter Location link">
        </div>

        <div class="form-field">
            <label for="address">Address *</label>
            <input type="text" id="address" name="address" placeholder="Enter full address here..." required>
        </div>

        <div class="form-field">
            <label for="note">Note</label>
            <textarea id="note" name="note" placeholder="Enter note here..."></textarea>
        </div>

    
<br>
        <button type="submit">Submit</button>
    </form>
</section>
</center>
<!-- Save Birds Section -->

<section id="save-birds" style="background-color: black; padding: 20px;">
    <center>
        <h2 style="color: white;">Save Birds Video</h2>
    </center>
    <!-- Video Section -->
    <div class="video-grid">
        <!-- Local Video 1 -->
        <video id="video1" width="320" height="240" controls>
            <source src="./assets/images/fram1.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>

        <!-- Local Video 2 -->
        <video id="video2" width="320" height="240" controls>
            <source src="./assets/images/faram2.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>

        <!-- Local Video 3 -->
        <video id="video3" width="320" height="240" controls>
            <source src="./assets/images/fram3.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>

        <!-- YouTube Video 1 -->
        <iframe id="iframe1" width="320" height="240" 
        src="https://www.youtube.com/embed/sSq3COo4Ygo?si=lhpkwbP2UDZAQ4MF" title="YouTube video player" 
        frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    
        
        <!-- YouTube Video 2 -->
        <iframe id="iframe2" width="320" height="240" 
        src="https://www.youtube.com/embed/pf70dInenZM?si=xEy2W_fdsxatwtqn" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>

        <!-- YouTube Video 3 -->
        <iframe id="iframe3" width="320" height="240"
         src="https://www.youtube.com/embed/x8y0tLZgJYI?si=pydWkiI3tO6T3_GU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </div>
</section>


<div>
        <?php include 'feedback_display.php'; ?>
    </div>

    <br><br><br><br>
<?php include './includes/footer.php'; ?>

<script>
    // Get all video elements
    const videos = document.querySelectorAll('video');
    // const iframes = document.querySelectorAll('iframe');

    // Add event listeners to each video
    videos.forEach(video => {
        video.addEventListener('mouseover', () => {
            video.play(); // Start playing the video when the mouse hovers over it
        });
        video.addEventListener('mouseout', () => {
            video.pause(); // Pause the video when the mouse leaves the video
        });
    });

    // Add event listeners to each iframe (YouTube videos)
    iframes.forEach(iframe => {
        iframe.addEventListener('mouseover', () => {
            let src = iframe.src;
            iframe.src = src + "&autoplay=1"; // Ensure autoplay is enabled
        });
        iframe.addEventListener('mouseout', () => {
            let src = iframe.src;
            iframe.src = src.replace("&autoplay=1", ""); // Remove autoplay when mouse leaves
        });
    });
</script>
