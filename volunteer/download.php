<?php
if (!isset($_GET['file'])) {
    die("No file specified.");
}

$image_name = basename($_GET['file']); // Prevent directory traversal
$image_path = '../assets/images/volunteers/' . $image_name;

if (file_exists($image_path)) {
    header('Content-Description: File Transfer');
    header('Content-Type: image/jpeg'); // Change to image/png if needed
    header('Content-Disposition: attachment; filename="' . $image_name . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($image_path));
    readfile($image_path);
    exit;
} else {
    echo "File not found.";
}
?>
