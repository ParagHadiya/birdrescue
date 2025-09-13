<link rel="stylesheet" href="../assets/css/styles.css">

<?php
session_start();
require_once '../config/database.php'; // Database connection

include 'navbar.php';


// Check if the user came from forgot_password.php
if (!isset($_SESSION['reset_user_id'])) {
    header("Location: forgot_password.php");
    exit;
}

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_id = $_SESSION['reset_user_id']; // Get user ID from session

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password in the database
        $sql = "UPDATE volunteers SET password = ? WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("si", $hashed_password, $user_id);
            if ($stmt->execute()) {
                $success = "Password reset successfully! Redirecting to login...";
                unset($_SESSION['reset_user_id']); // Clear session data
                header("refresh:3;url=login.php"); // Redirect after 3 seconds
            } else {
                $error = "Error updating password.";
            }
            $stmt->close();
        } else {
            $error = "Database query error.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
       .container {
           background: #ffffff;
           border-radius: 8px;
           box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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
       input[type="password"] {
           width: 100%;
           padding: 10px;
           margin-top: 5px;
           border: 1px solid #ccc;
           border-radius: 4px;
           box-sizing: border-box;
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
</head>
<body>
    <center>
    <div class="container">
        <h2>Reset Password</h2>
        <form method="POST">
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" id="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <button type="submit">Reset Password</button>
        </form>
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
    </div>
    </center>
</body>
</html>



<?php include 'footer.php'; ?>