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

// Fetch rescue details with species name
$sql = "SELECT b.id, bm.bird_name, b.number_of_birds, b.location, b.caller_name, b.created_at 
        FROM birds_rescue b
        LEFT JOIN BirdsMaster bm ON b.bird_species_id = bm.B_id
        WHERE b.id = ?";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bird Rescue</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .back-button {
            display: inline-block;
            padding: 10px 15px;
            margin-bottom: 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>

<a href="admin_dashboard.php" class="back-button">⬅ Back</a>

<div class="container">
    <h2>Bird Rescue Details</h2>
    <table>
        <tr>
            <th>ID</th>
            <td><?= htmlspecialchars($row['id'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <th>Bird Name</th>
            <td><?= htmlspecialchars($row['bird_name'] ?? 'Unknown') ?></td>
        </tr>
        <tr>
            <th>Number of Birds</th>
            <td><?= htmlspecialchars($row['number_of_birds'] ?? '0') ?></td>
        </tr>
        <tr>
            <th>Location</th>
            <td><?= htmlspecialchars($row['location'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <th>Caller Name</th>
            <td><?= htmlspecialchars($row['caller_name'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <th>Created At</th>
            <td><?= htmlspecialchars($row['created_at'] ?? 'N/A') ?></td>
        </tr>
    </table>
</div>

</body>
</html>
