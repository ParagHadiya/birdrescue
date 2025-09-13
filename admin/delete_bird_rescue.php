<?php
include '../config/database.php';

$id = (int)$_GET['id']; // Ensure ID is an integer for security
$query = "DELETE FROM birds_rescue WHERE id = $id";

if (mysqli_query($conn, $query)) {
    header('Location: admin_dashboard.php?success=1');
    exit;
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
