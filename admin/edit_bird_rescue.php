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
        echo "<script>alert('Record updated successfully!'); window.location.href='admin_dashboard.php';</script>";
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        .container {
            width: 50%;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 50px auto;
        }
        h2 {
            color: #333;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn {
            margin-top: 15px;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 4px;
        }
        .btn-save {
            background: #28a745;
            color: white;
        }
        .btn-save:hover {
            background: #218838;
        }
        .btn-back {
            background: #007bff;
            color: white;
            display: inline-block;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .btn-back:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

    <a href="admin_dashboard.php" class="btn-back">⬅ Back</a>

    <div class="container">
        <h2>Edit Bird Rescue Details</h2>
        <form method="POST">
            <label>Bird Name:</label>
            <input type="text" name="bird" value="<?= htmlspecialchars($row['bird']) ?>" required>

            <label>Number of Birds:</label>
            <input type="number" name="number_of_birds" value="<?= $row['number_of_birds'] ?>" required>

            <label>Location:</label>
            <input type="text" name="location" value="<?= htmlspecialchars($row['location']) ?>" required>

            <label>Caller Name:</label>
            <input type="text" name="caller_name" value="<?= htmlspecialchars($row['caller_name']) ?>" required>

            <button type="submit" class="btn btn-save">Save Changes</button>
        </form>
    </div>

</body>
</html>

<?php
$conn->close();
?>
