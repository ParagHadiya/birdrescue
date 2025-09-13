<?php
include '../config/database.php';
session_start();
$result = $conn->query("SELECT br.*, v.first_name, v.last_name 
                        FROM birds_rescue br 
                        LEFT JOIN volunteers v ON br.assigned_to = v.id");
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <title>Bird Rescues</title>
</head>
<body>
    <div class="container">
        <h2>Bird Rescues</h2>
        <table>
            <tr>
                <th>Bird Image</th>
                <th>Caller Name</th>
                <th>Caller Mobile</th>
                <th>Number of Birds</th>
                <th>Location</th>
                <th>Assigned Volunteer</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><img src="<?= $row['bird_image']; ?>" width="50"></td>
                    <td><?= $row['caller_name']; ?></td>
                    <td><?= $row['caller_mobile']; ?></td>
                    <td><?= $row['number_of_birds']; ?></td>
                    <td><?= $row['location']; ?></td>
                    <td><?= $row['first_name'] . ' ' . $row['last_name']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
