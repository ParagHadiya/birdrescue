<link rel="stylesheet" href="../assets/css/styles.css">

<?php
session_start();
 include 'navbar.php';
// Include database connection
require_once '../config/database.php';


// Initialize error variable
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Query to check if the volunteer exists and is approved
    $sql = "SELECT id, first_name, password, approved FROM volunteers WHERE email = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $first_name, $hashed_password, $approved);
            $stmt->fetch();

            if ($approved == 1) {
                if (password_verify($password, $hashed_password)) {
                    // Set session variables
                    $_SESSION['volunteer_id'] = $id;
                    $_SESSION['volunteer_name'] = $first_name;
                    $_SESSION['role'] = 'Volunteer'; // Set role as Volunteer

                    // Redirect to the dashboard
                    header('Location: volunteer_dashboard.php');
                    exit;
                } else {
                    $error = "Invalid credentials.";
                }
            } else {
                $error = "Your account is not approved yet. Please wait for admin approval.";
            }
        } else {
            $error = "No user found with this email.";
        }
        $stmt->close();
    } else {
        $error = "Error with the query.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Login</title>
    <style>
       .login-container {
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
       input[type="email"],
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
       .forgot-password {
           margin-top: 15px;
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
    <div class="login-container">
        <h2>Volunteer Login</h2>
        <form method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        
        <!-- Forgot Password Link -->
        <div class="forgot-password">
            <a href="forgot_password.php">Forgot Password?</a>
        </div>
    </div>
    </center>
</body>
</html>

<?php include 'footer.php'; ?>
