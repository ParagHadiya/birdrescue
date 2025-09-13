<?php
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST["id"]);
    $column = $_POST["column"];
    $value = $_POST["value"];

    // Allowed columns to prevent SQL injection
    $allowed_columns = ["rescue_status_id", "assigned_to"];

    if (in_array($column, $allowed_columns)) {
        $stmt = $conn->prepare("UPDATE birds_rescue SET $column = ? WHERE id = ?");
        $stmt->bind_param("ii", $value, $id);
        
        if ($stmt->execute()) {
            echo "Record updated successfully!";
        } else {
            echo "Error updating record.";
        }
        
        $stmt->close();
    } else {
        echo "Invalid column!";
    }
}

$conn->close();
?>
