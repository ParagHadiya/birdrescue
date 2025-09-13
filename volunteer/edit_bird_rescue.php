
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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bird = $_POST['bird'];
    $number_of_birds = $_POST['number_of_birds'];
    $location = $_POST['location'];
    $caller_name = $_POST['caller_name'];
    $sql = "UPDATE birds_rescue SET bird = '$bird', number_of_birds = $number_of_birds, location = '$location', caller_name = '$caller_name' WHERE id = $id";

    if ($conn->query($sql)) {
        echo "<script>alert('Record updated successfully!'); window.location.href='manage_birds_rescue.php';</script>";
    } else {
        echo "<script>alert('Failed to update record!');</script>";
    }
}

$sql = "SELECT * FROM birds_rescue WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bird Rescue</title>
</head>
<body>
    <h2>Edit Bird Rescue Details</h2>
    <form method="POST">
        <label>Bird Name:</label>
        <input type="text" name="bird" value="<?= htmlspecialchars($row['bird']) ?>" required>
        <br>
        <label>Number of Birds:</label>
        <input type="number" name="number_of_birds" value="<?= $row['number_of_birds'] ?>" required>
        <br>
        <label>Location:</label>
        <input type="text" name="location" value="<?= htmlspecialchars($row['location']) ?>" required>
        <br>
        <label>Caller Name:</label>
        <input type="text" name="caller_name" value="<?= htmlspecialchars($row['caller_name']) ?>" required>
        <br>
        <button type="submit">Save Changes</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>
