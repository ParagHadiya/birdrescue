<?php
include '../includes/db_connection.php';

$id = $_GET['id'];
$query = "DELETE FROM bird_rescue WHERE id = $id";

if (mysqli_query($conn, $query)) {
    header('Location: bird_rescue_list.php');
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
