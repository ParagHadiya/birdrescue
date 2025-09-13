
<style>

.feedback-header {
    padding: 40px; /* Add padding around the feedback header */
}
    /* Main feedback container */
    .feedback-container {
        background-color: #f9f9f9; /* Light gray for better readability */
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 0 auto; /* Center the container */
        
    }

    /* Feedback box for sliding messages */
    .feedback-box {
        height: 200px;
        width: 100%;
        overflow: hidden;
        margin-bottom: 20px;
        /* padding: 0px; */
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color:rgb(209, 241, 63);
        position: relative;
        word-wrap: break-word; 
        overflow-wrap: break-word;
    }

    .feedback-message {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    display: none;
    text-align: center;
    font-size: 18px;
    line-height: 1.5;
    word-wrap: break-word; 
    overflow-wrap: break-word; 
    transition: opacity 0.5s ease-in-out;
}

    .feedback-message.active {
        display: block;
    }

    /* Feedback header (logo and name) */
    .feedback-header {
        display: flex; /* Align items in a row */
        align-items: center; /* Vertically align logo and text */
        justify-content: center; /* Center content */
        gap: 10px; /* Space between the logo and the name */
        margin-top: 10px;
    }

    .feedback-logo {
        width: 40px; /* Set the desired width for the logo */
        height: 40px; /* Maintain a square aspect ratio */
        transition: opacity 0.5s ease-in-out;
    border: 2px solid #2f0eec; /* Solid border with color */
    border-radius: 50%; /* Rounded corners */

    }

    h3 {
        font-size: 24px;
        margin-bottom: 10px;
        color: #333;
    }

    h2 {
        font-size: 20px;
        margin-bottom: 15px;
        color: #555;
    }

    h4 {
        font-size: 16px;
        color: #666;
        margin: 0;
    }

    p {
        font-size: 18px;
        margin-bottom: 10px;
        color: #333;
    }

    /* Button container and button styles */
    .feedback-button-container {
        text-align: right;
    }

    .feedback-button {
        background-color: #2f0eec;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
    }

    .feedback-button:hover {
        background-color: #4CAF50;
    }

    .feedback-link {
        text-decoration: none;
        color: white;
        display: inline-block;
    }

    .feedback-link:hover {
        /* text-decoration: underline; */
    }
</style>

<?php
// Include the database configuration file
require_once 'config/app.php';  // Adjust path as needed

// Retrieve feedback from the database
$sql = "SELECT * FROM feedback ORDER BY id DESC";
$result = $conn->query($sql);
?>

<div class="feedback-container">
    <h3>Previous Feedback</h3>
    <h2>We value your feedback to help us improve our service.</h2>

    <div class="feedback-box">
        <?php
        // Check if feedback exists in the database
        if ($result->num_rows > 0) {
            // Loop through and display each feedback message
            while ($row = $result->fetch_assoc()) {
                echo "<div class='feedback-message'>";
                echo "<p>" . htmlspecialchars($row['message']) . "</p>";
                echo "<div class='feedback-header'>"; 
                echo "<img src='assets/images/user.png' alt='Logo' class='feedback-logo'>"; // Add your logo path
                echo "<h4>" . htmlspecialchars($row['name']) . "</h4>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            // If no feedback is available
            echo "<p>No feedback available.</p>";
        }

        // Close the database connection
        $conn->close();
        ?>
    </div>

    <div class="feedback-button-container">
        <button class="feedback-button">
            <a class="feedback-link" href="http://localhost/birdrescue/?page=feedback_form">
                Give Your Feedback
            </a>
        </button>
    </div>
</div>


<script>
    // JavaScript to implement the feedback slider
    let currentIndex = 0;
    let feedbackMessages = document.querySelectorAll('.feedback-message');

    function showFeedback() {
        // Hide all messages
        feedbackMessages.forEach(message => message.classList.remove('active'));

        // Show the current message
        feedbackMessages[currentIndex].classList.add('active');

        // Increment index and reset if at the end
        currentIndex = (currentIndex + 1) % feedbackMessages.length;
    }

    // Initialize slider if feedback messages exist
    if (feedbackMessages.length > 0) {
        feedbackMessages[0].classList.add('active'); // Show the first message
        setInterval(showFeedback, 4000); // Change message every 4 seconds
    }
</script>
