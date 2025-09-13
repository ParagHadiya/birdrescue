<?php
// Start the session
session_start();

// Destroy all session data
session_unset();

// Destroy the session itself
session_destroy();

// Redirect to the login page after logout
header('Location: login.php');
exit;
?>
