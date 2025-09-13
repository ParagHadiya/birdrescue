<style>
    /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}



.login-container {
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 30px;
    width: 100%;
    max-width: 400px;
    text-align: center;
    margin-top: 60px;
}

h2 {
    margin-bottom: 20px;
    color: #333;
}

.form-group {
    margin-bottom: 20px;
    text-align: left;
}

label {
    display: block;
    margin-bottom: 5px;
    color: #555;
    font-weight: bold;
}

input[type="text"],
textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 16px;
}

textarea {
    height: 100px; /* Increase height for better text input */
}

button {
    width: 100%;
    padding: 12px;
    background-color: #2f0eec;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #1e08b3;
}

p.error {
    color: red;
    margin-top: 10px;
    font-size: 14px;
}

p.success {
    color: green;
    margin-top: 10px;
    font-size: 14px;
}

</style>


<?php
include './includes/header.php';
include './config/database.php'; // Ensure the database connection is included here

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $message = $_POST['message'];

    // Insert feedback into the database
    $sql = "INSERT INTO feedback (name, message) VALUES ('$name', '$message')";

    if ($conn->query($sql) === TRUE) {
        $success_message = "Feedback submitted successfully!";
    } else {
        $error_message = "Error: " . $conn->error;
    }

    $conn->close();
}
?>

<center>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form</title>
</head>
<body>
<div class="login-container">
    <h2>Submit Your Feedback</h2>

    <!-- Feedback Form -->
    <form method="POST" action="http://localhost/birdrescue/?page=feedback_form">
        <div class="form-group">
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="message">Your Message:</label>
            <textarea id="message" name="message" required></textarea>
        </div>
        <button type="submit">Submit Feedback</button>
    </form>

    <?php
    // Show success or error messages after form submission
    if (isset($success_message)) {
        echo "<p class='success'>$success_message</p>";
    } elseif (isset($error_message)) {
        echo "<p class='error'>$error_message</p>";
    }
    ?>

</div>

</center>
<?php include './includes/footer.php'; ?>
</body>
</html>
