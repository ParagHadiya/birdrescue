<?php
include './config/database.php'; // Include the database connection file

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
    $captcha = $_POST['captcha'];

    // Server-side validations
    if (empty($bird) || empty($callerMobile) || empty($callerName) || empty($numberOfBirds) || empty($address) || empty($captcha)) {
        echo "<p style='color: red;'>All required fields must be filled out.</p>";
    } else {
        // File upload handling
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($birdImage);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Validate file type (only allow certain types like JPEG, PNG, etc.)
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo "<p style='color: red;'>Only JPG, JPEG, PNG & GIF files are allowed.</p>";
        } else {
            // Move uploaded file to target directory
            if (move_uploaded_file($_FILES['bird_image']['tmp_name'], $targetFile)) {
                // Insert data into the database
                $query = "INSERT INTO birds_rescue (bird_image, bird, caller_mobile, caller_name, number_of_birds, location, address, note, created_at) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ssssisss', $targetFile, $bird, $callerMobile, $callerName, $numberOfBirds, $location, $address, $note);

                if ($stmt->execute()) {
                    echo "<p style='color: green;'>Injured bird details submitted successfully!</p>";
                } else {
                    echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
                }

                $stmt->close();
            } else {
                echo "<p style='color: red;'>Sorry, there was an error uploading your file.</p>";
            }
        }
    }
}
?>

<center>
<section id="injured-birds">
    <h2>Injured Birds Detail</h2>
    <p>Our volunteer team will call you in 10 mins once you fill the form.</p>

    <form action="#" method="POST" enctype="multipart/form-data">
        
        <div class="form-field">
            <label for="birdImage">Bird Image (No file chosen):</label>
            <input type="file" id="birdImage" name="bird_image" required>
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

        <div class="form-field">
            <label for="captcha">Captcha *</label>
            <input type="text" id="captcha" name="captcha" required>
        </div>

        <button type="submit">Submit</button>
    </form>
</section>
</center>
