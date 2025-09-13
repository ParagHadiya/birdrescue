<style>
         h2 {
    color: #34495e ;
    
}
</style>

<?php
// Include the database connection file
include '../config/database.php';


session_start();

// Redirect if the admin is not logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$admin_username = $_SESSION['username'] ?? "Admin";

$error = ""; // Initialize error message variable

// Handle the creation of a new admin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and fetch input for the new admin
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $contact_number = htmlspecialchars(trim($_POST['contact_number']));

    // Validate required fields
    if (!empty($username) && !empty($password) && !empty($first_name) && !empty($last_name) && !empty($email) && !empty($contact_number)) {
        // Check if the email already exists
        $email_check_query = "SELECT * FROM admin WHERE email = ?";
        $stmt = $conn->prepare($email_check_query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Email is already registered.";
        } else {
            // Hash the password before storing it
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new admin into the database
            $query = "INSERT INTO admin (username, password, first_name, last_name, email, contact_number, created_at) 
                      VALUES (?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($query);

            if ($stmt) {
                $stmt->bind_param('ssssss', $username, $hashed_password, $first_name, $last_name, $email, $contact_number);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    // Successfully added new admin
                    $error = "New admin created successfully!";
                } else {
                    $error = "Failed to add new admin.";
                }

                $stmt->close();
            } else {
                $error = "Database query failed.";
            }
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<div class="sidebar">
    <ul>
        <div class="welcome-message">
            <h4>Welcome, <?php echo htmlspecialchars($admin_username); ?>!</h4> <br><br>
        </div>
        <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
        <li><a href="create_admin.php">Add Admin</a></li>
        <li><a href="add_volunteer.php">Add Volunteer</a></li>
        <li><a href="rescue_status.php">Add Rescue Status</a></li>
        <li><a href="bird_species.php">Add Bird Species</a></li>
        <li><a href="bird_shelter.php">Add Bird Shelter</a></li>
        <li><a href="role.php">Add Role</a></li>
        <li><a href="contact.php">Add Contact</a></li>
        <li><a href="bird_rescue_form.php">Bird Rescue Form</a></li>
        <li><a href="donate.php">Add Donation</a></li>
        <li><a href="logout.php" class="btn logout">Logout</a></li>
    </ul>
</div>
<center>
<!-- Main Content on the Right -->
<div class="main-content">
    <div class="admin-form-container">
        <h2>Create New Admin</h2>
        <div class="form-box">
        <form method="POST" class="registration-form">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" id="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" id="last_name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number:</label>
                <input type="text" name="contact_number" id="contact_number" required>
            </div>
            <button type="submit" class="btn">Create Admin</button>
            <?php if (!empty($error)): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
        </form>
        </div>
    </div>
</div>



<!-- Link to your CSS file -->
<link rel="stylesheet" href="../assets/css/admin-styles.css">

<!-- <?php include '../includes/footer.php'; ?> -->
</center>