<?php
include '../config/database.php';
session_start();
$volunteers = $conn->query("SELECT id, first_name, last_name FROM volunteers");
$species = $conn->query("SELECT id, bird FROM birds_rescue");
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <title>Assign Rescue</title>
</head>
<body>
    <div class="container">
        <h2>Assign Bird Rescue</h2>
        <form method="POST" action="save_assignment.php">
            <label>Caller Name:</label>
            <input type="text" name="caller_name" required>

            <label>Caller Mobile:</label>
            <input type="text" name="caller_mobile" required>

            <label>Number of Birds:</label>
            <input type="number" name="number_of_birds" required>

            <label>Location:</label>
            <input type="text" name="location" required>

            <label>Assign to Volunteer:</label>
            <select name="assigned_to">
                <?php while ($vol = $volunteers->fetch_assoc()) : ?>
                    <option value="<?= $vol['id']; ?>"><?= $vol['first_name'] . ' ' . $vol['last_name']; ?></option>
                <?php endwhile; ?>
            </select>

            <label>Bird Species:</label>
            <select name="bird_species_id">
                <?php while ($sp = $species->fetch_assoc()) : ?>
                    <option value="<?= $sp['id']; ?>"><?= $sp['bird']; ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Assign Rescue</button>
        </form>
    </div>
</body>
</html>
