<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .contact-container {
            background: white;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            width: 400px;
            text-align: center;
            margin-top: 20px;
        }

        h1 {
            margin-bottom: 20px;
        }

        .contact-form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        label {
            text-align: left;
            font-weight: bold;
        }

        input, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <?php include './includes/header.php'; ?>
    <?php include './config/database.php'; ?>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        if (empty($name) || empty($email) || empty($message)) {
            echo "<p style='color: red; text-align: center;'>All fields are required!</p>";
        } else {
            $query = "INSERT INTO contact_us (name, email, message) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sss', $name, $email, $message);

            if ($stmt->execute()) {
                echo "<p style='color: green; text-align: center;'>Your message has been sent successfully!</p>";
            } else {
                echo "<p style='color: red; text-align: center;'>Error: " . $stmt->error . "</p>";
            }

            $stmt->close();
        }
    }
    ?>

<center>
    <div class="contact-container">
        <h1>Contact Us</h1>
        <form action="http://localhost/birdrescue/?page=contact" method="POST" class="contact-form">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="4" required></textarea>

            <button type="submit" name="submit">Send</button>
        </form>
    </div>

    </center>
    <?php include './includes/footer.php'; ?>

</body>
</html>
