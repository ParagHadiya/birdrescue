<?php
include '../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'];
    $status = $input['status'];

    if (!isset($id) || !isset($status)) {
        echo json_encode(['success' => false, 'message' => 'Invalid input.']);
        exit;
    }

    $query = "UPDATE volunteers SET approved = ? WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param('ii', $status, $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $message = $status == 1 ? 'Volunteer approved successfully.' : 'Volunteer rejected successfully.';
            echo json_encode(['success' => true, 'message' => $message]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No changes were made.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Database query failed.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
