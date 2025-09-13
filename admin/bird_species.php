<link rel="stylesheet" href="../assets/css/admin-styles.css">

<style>
     h2 {
    color: #34495e ;
    
}

.container {
    width: 70%;
    padding: 20px;
    margin-left: 280px;
    background: white;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background: #ffffff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
}

th, td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #2c3e50;
    color: white;
    font-weight: bold;
}

tr:hover {
    background-color: #f1f1f1;
}

/* Form Styling */
form {
    margin-top: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

input[type="text"] {
    width: 80%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    padding: 10px 15px;
    background: #1abc9c;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: #16a085;
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


// Fetch bird species
$birds_query = "SELECT * FROM BirdsMaster";
$birds_result = $conn->query($birds_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bird_name = $_POST['bird_name'];
    $stmt = $conn->prepare("INSERT INTO BirdsMaster (bird_name) VALUES (?)");
    $stmt->bind_param("s", $bird_name);
    $stmt->execute();
    header("Location: bird_species.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <title>Bird Species</title>
</head>
<body>
    <div class="container">
        <h2>Bird Species</h2>
        <table>
            <tr>
                <th style="color: ; background-color: #2c3e50;">ID</th>
                <th style="color: ; background-color: #2c3e50;">Bird Name</th>  
            </tr>
            <?php while ($row = $birds_result->fetch_assoc()) : ?>
                <tr>
                    <td><?= $row['B_id']; ?></td>
                    <td><?= htmlspecialchars($row['bird_name']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        <form method="POST">
            <input type="text" name="bird_name" required placeholder="Enter Bird Species">
            <button type="submit">Add Bird</button>
        </form>
    </div>

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
</body>
</html>
