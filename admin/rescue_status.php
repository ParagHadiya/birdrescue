<link rel="stylesheet" href="../assets/css/admin-styles.css">

<style>
   
   h2 {
    color: #34495e ;
    
}

/* Main content area */
.content {
    margin-left: 270px; /* Adjust based on sidebar width */
    flex-grow: 1;
    padding: 20px;
    text-align: center;
}

/* Table styling */
.table-container {
    display: flex;
    justify-content: center;
}

table {
    width: 80%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
}

th {
    background: #3498db;
    color: white;
}

tr:nth-child(even) {
    background: #f2f2f2;
}

/* Form Styling */
form {
    margin-top: 20px;
}

input[type="text"] {
    padding: 10px;
    width: 250px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    padding: 10px 15px;
    background: #27ae60;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background: #2ecc71;
}


</style>
<?php
include '../config/database.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$admin_username = $_SESSION['username'] ?? "Admin";

$status_query = "SELECT * FROM StatusMaster";
$status_result = $conn->query($status_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bird_status = $_POST['bird_status'];
    $stmt = $conn->prepare("INSERT INTO StatusMaster (bird_status) VALUES (?)");
    $stmt->bind_param("s", $bird_status);
    $stmt->execute();
    header("Location: rescue_status.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../assets/css/admin-styles.css">
    <title>Rescue Status</title>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
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

        <!-- Main Content -->
        <div class="content">
            <h2>Rescue Status</h2>
            <div class="table-container">
                <table>
                    <tr>
                        <th style="color: ; background-color: #2c3e50;">ID</th>
                        <th style="color: ; background-color: #2c3e50;">Bird Status</th>

                    </tr>
                    <?php while ($row = $status_result->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td><?= htmlspecialchars($row['bird_status']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
            <form method="POST">
                <input type="text" name="bird_status" required placeholder="Enter Rescue Status">
                <button type="submit">Add Status</button>
            </form>
        </div>
    </div>
</body>
</html>
