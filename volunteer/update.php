<?php
include '../config/database.php';

if (isset($_POST['id'], $_POST['column'], $_POST['value'])) {
    $id = $_POST['id'];
    $column = $_POST['column'];
    $value = $_POST['value'];

    // Prevent SQL injection
    $stmt = $conn->prepare("UPDATE birds_rescue SET $column = ? WHERE id = ?");
    $stmt->bind_param("si", $value, $id);

    if ($stmt->execute()) {
        echo "Updated successfully";
    } else {
        echo "Update failed";
    }

    $stmt->close();
}

$conn->close();
?>
