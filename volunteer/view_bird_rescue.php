<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$admin_username = $_SESSION['username'] ?? "Admin";

// Database connection
include '../config/database.php';


$id = (int)$_GET['id'];
$sql = "SELECT * FROM birds_rescue WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bird Rescue</title>
</head>
<body>
    <h2>View Bird Rescue Details</h2>
    <p><strong>ID:</strong> <?= $row['id'] ?></p>
    <p><strong>Bird Name:</strong> <?= htmlspecialchars($row['bird']) ?></p>
    <p><strong>Number of Birds:</strong> <?= $row['number_of_birds'] ?></p>
    <p><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
    <p><strong>Caller Name:</strong> <?= htmlspecialchars($row['caller_name']) ?></p>
    <p><strong>Created At:</strong> <?= $row['created_at'] ?></p>
    <a href="manage_birds_rescue.php">Back</a>
</body>
</html>

<?php
$conn->close();
?>
