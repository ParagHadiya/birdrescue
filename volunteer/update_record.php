<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $column = $_POST['column'];
    $value = intval($_POST['value']); // Ensure it's an integer for security

    // Validate allowed column names to prevent SQL Injection
    $allowed_columns = ['rescue_status_id', 'assigned_to'];
    if (!in_array($column, $allowed_columns)) {
        die("Invalid column");
    }

    // Prepare the update query
    $stmt = $conn->prepare("UPDATE birds_rescue SET $column = ? WHERE id = ?");
    $stmt->bind_param("ii", $value, $id);

    if ($stmt->execute()) {
        echo "Record updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
