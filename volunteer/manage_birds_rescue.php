<?php
// Database Connection
$conn = new mysqli("localhost", "username", "password", "database_name");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Records
$sql = "SELECT * FROM birds_rescue ORDER BY created_at DESC";
$result = $conn->query($sql);

// Handle Deletion
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $delete_sql = "DELETE FROM birds_rescue WHERE id = $delete_id";
    if ($conn->query($delete_sql)) {
        echo "<script>alert('Record deleted successfully!'); window.location.href='manage_birds_rescue.php';</script>";
    } else {
        echo "<script>alert('Failed to delete record!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bird Rescue</title>
    <link rel="stylesheet" href="../assets/css/admin-styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #f4f4f4;
        }
        .actions a {
            margin: 0 5px;
            text-decoration: none;
            padding: 5px 10px;
            color: white;
            background-color: #007bff;
            border-radius: 5px;
        }
        .actions a.delete {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <h2>Manage Bird Rescue</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Bird Name</th>
                    <th>Number of Birds</th>
                    <th>Location</th>
                    <th>Caller Name</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['bird']) ?></td>
                            <td><?= $row['number_of_birds'] ?></td>
                            <td><?= htmlspecialchars($row['location']) ?></td>
                            <td><?= htmlspecialchars($row['caller_name']) ?></td>
                            <td><?= $row['created_at'] ?></td>
                            <td class="actions">
                                <a href="view_bird_rescue.php?id=<?= $row['id'] ?>">View</a>
                                <a href="edit_bird_rescue.php?id=<?= $row['id'] ?>">Edit</a>
                                <a href="?delete_id=<?= $row['id'] ?>" class="delete" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
