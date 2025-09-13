<link rel="stylesheet" href="../assets/css/admin-styles.css">
<style>

body {
    
    margin-top:00px ;
    margin-bottom: 200px;

}
</style>
<?php

include '../config/database.php';

session_start();


if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$error = ""; 

$admin_username = $_SESSION['username'] ?? "Admin";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $contact_number = htmlspecialchars(trim($_POST['contact_number']));

    
    if (!empty($username) && !empty($password) && !empty($first_name) && !empty($last_name) && !empty($email) && !empty($contact_number)) {
       
        $email_check_query = "SELECT * FROM volunteers WHERE email = ?";
        $stmt = $conn->prepare($email_check_query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Email is already registered.";
        } else {
           
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

          
            $query = "INSERT INTO volunteers (username, password, first_name, last_name, email, contact_number, approved, created_at) 
                      VALUES (?, ?, ?, ?, ?, ?, 0, NOW())"; // default approval is 0 (Pending)
            $stmt = $conn->prepare($query);

            if ($stmt) {
                $stmt->bind_param('ssssss', $username, $hashed_password, $first_name, $last_name, $email, $contact_number);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    // Successfully added new volunteer
                    $error = "New volunteer created successfully!";
                } else {
                    $error = "Failed to add new volunteer.";
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

// Fetch all volunteers
$volunteers_query = "SELECT * FROM volunteers";
$volunteers_result = $conn->query($volunteers_query);
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
<!-- Main Content (Right side) -->
<div class="dashboard-container">
    <h2>Add New Volunteer</h2>
    <div class="form-box">
    <form method="POST">
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
        <button type="submit" class="btn">Create Volunteer</button>
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </form>
</div>
    <br><br>

    <!-- Display Volunteers -->
    <h2>All Volunteers</h2>
    <?php if ($volunteers_result->num_rows > 0): ?>
        <table class="volunteers-table">
            <tr>
                <th>Username</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Approval Status</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $volunteers_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['first_name']) ?></td>
                    <td><?= htmlspecialchars($row['last_name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= $row['approved'] == 1 ? 'Approved' : 'Pending' ?></td>
                    <td>
                        <!-- Approve or Reject buttons -->
                        <?php if ($row['approved'] == 0): ?>
                            <a href="approve_volunteer.php?id=<?= $row['id'] ?>">Approve</a> |
                        <?php endif; ?>
                        <a href="reject_volunteer.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to reject this volunteer?')">Reject</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No volunteers found.</p>
    <?php endif; ?>
</div>

<!-- <link rel="stylesheet" href="../assets/css/styles.css"> -->


</center>