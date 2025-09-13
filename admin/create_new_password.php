<?php
include 'navbar.php';
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['reset_admin_id'])) {
    header("Location: forgot_password.php");
    exit;
}

$error = "";
$success = "";

// Handle new password submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $admin_id = $_SESSION['reset_admin_id'];

        // Update the password in the database
        $sql = "UPDATE admin SET password = ? WHERE admin_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("si", $hashed_password, $admin_id);
            if ($stmt->execute()) {
                $success = "Password updated successfully! Redirecting to login...";
                
                session_unset(); // Clear reset session variables
                session_destroy();
                
                // JavaScript redirect after 3 seconds
                echo "<script>
                        setTimeout(function() {
                            window.location.href = 'login.php';
                        }, 3000);
                      </script>";
            } else {
                $error = "Error updating password.";
            }
            $stmt->close();
        }
    } else {
        $error = "Passwords do not match.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Admin Reset Password</title>
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
    </style>
</head>
<body>
    <center>
    <div class="container">
        <h2>Reset Your Password</h2>
        <form method="POST">
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" id="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <button type="submit" class="btn">Reset Password</button>
        </form>
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
    </div>
    </center>
<?php include 'footer.php'; ?>
</body>
</html>
