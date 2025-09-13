<?php
require_once '../config/database.php';

// Check if admin is logged in
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit;
}

if (isset($_GET['id'])) {
    $volunteer_id = $_GET['id'];

    // Delete the volunteer from the database
    $sql = "DELETE FROM volunteers WHERE id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $volunteer_id);
        if ($stmt->execute()) {
            echo '<p>Volunteer rejected successfully.</p>';
            header('Location: admin_dashboard.php'); // Redirect back to the admin dashboard
            exit;
        } else {
            echo '<p class="error">Error rejecting volunteer.</p>';
        }
        $stmt->close();
    } else {
        echo '<p class="error">Error preparing the query.</p>';
    }
} else {
    echo '<p class="error">No volunteer selected.</p>';
}
?>
