

<link rel="stylesheet" href="../assets/css/styles.css">

<?php

session_start();
require_once '../config/database.php';

include 'navbar.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';
require '../PHPMailer-master/src/Exception.php';

$error = "";
$message = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);

    // Check if the admin exists
    $sql = "SELECT admin_id FROM admin WHERE email = ? AND username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id);
            $stmt->fetch();

            // Store user ID and email in session
            $_SESSION['reset_user_id'] = $id;
            $_SESSION['reset_email'] = $email;

            // Generate OTP and set expiry
            $otp = rand(100000, 999999);
            $_SESSION['email_otp'] = $otp;
            $_SESSION['otp_email'] = $email;
            $_SESSION['otp_generated_time'] = time(); // Store OTP generation time

            // Send OTP via Email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'hp12computer12@gmail.com'; // Use your email
                $mail->Password = 'zxjjnqmeughayome'; // Use an **App Password**, not the real password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                $mail->setFrom('hp12computer12@gmail.com', 'BirdRescue Team'); 
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset OTP';
                $mail->Body = "
                    <h2>Password Reset OTP</h2>
                    <p>Your OTP for password reset is: <strong>$otp</strong></p>
                    <p>This OTP is valid for 10 minutes.</p>
                    <p>If you didn't request a password reset, please ignore this email.</p>
                ";

                if ($mail->send()) {
                    header("Location: otp_verification.php");
                    exit();
                } else {
                    $message = "<span style='color: red;'>❌ Failed to send OTP. Please try again.</span>";
                }
            } catch (Exception $e) {
                $message = "<span style='color: red;'>❌ Email Error: " . $mail->ErrorInfo . "</span>";
            }
        } else {
            $error = "Invalid username or email.";
        }
        $stmt->close();
    } else {
        $error = "Database query error.";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Admin Forgot Password</title>
    <style>
        .container {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            margin-top: 30px;
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
        input[type="email"],
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
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
        .forgot-password {
            margin-top: 15px;
            text-align: center;
        }
        .forgot-password a {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }
        .forgot-password a:hover {
            text-decoration: underline;
        } 
    </style>
</head>
<body>
    <center>
    <div class="container">
        <h2>Admin Forgot Password</h2>
        <form method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
            </div>
            <button type="submit">Submit</button>

            <div class="forgot-password">
                <a href="login.php">Back to Login</a>
            </div>
        </form>

        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
    </center>
<?php include 'footer.php'; ?>
</body>
</html>
