
<link rel="stylesheet" href="../assets/css/styles.css">
<?php
session_start();
require_once '../config/database.php'; // Database connection
include 'navbar.php';


$message = "";

// Check if OTP is expired (10 min limit)
if (isset($_SESSION['otp_generated_time'])) {
    $otp_time = $_SESSION['otp_generated_time'];
    if (time() - $otp_time > 600) { // 600 seconds = 10 minutes
        unset($_SESSION['email_otp']);
        unset($_SESSION['otp_generated_time']);
        $message = "<span style='color: red;'>❌ OTP expired. Please request a new one.</span>";
    }
}

// Process OTP Verification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_otp = trim($_POST['otp']);
    
    if (isset($_SESSION['email_otp']) && $_SESSION['email_otp'] == $entered_otp) {
        $_SESSION['otp_verified'] = true;
        header("Location: create_new_password.php");
        exit();
    } else {
        $message = "<span style='color: red;'>❌ Invalid OTP. Please try again.</span>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <style>
      
        .otp-container {
            width: 350px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin: auto;
            margin-top: 40px;
        }
        input[type="number"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #218838;
        }
        .message {
            margin-top: 10px;
            font-size: 14px;
            color: red;
        }
          footer {
            margin-top: 40px;
        }
    </style>
</head>
<body>


<center>
<div class="otp-container">
    <h2>OTP Verification</h2>
    <p>Enter the OTP sent to your email.</p>
    <form action="otp_verification.php" method="POST">
        <label for="otp">OTP:</label>
        <input type="number" name="otp" id="otp" required>
        
        <button type="submit">Verify OTP</button>
    </form>
    <div class="message"><?php echo $message; ?></div>
    <br>
    <a href="forgot_password.php">Back Page</a>
</div>
</center>
</body>
</html>

<?php 
include 'footer.php';
?>
