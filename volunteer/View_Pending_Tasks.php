<?php
session_start();
include '../config/database.php';
include 'dashbord_nav.php';

// Check if user is logged in and has the 'Volunteer' role
if (!isset($_SESSION['volunteer_id'])) {
    header("Location: login.php");
    exit;
}

$volunteer_id = $_SESSION['volunteer_id'];

// Fetch pending rescues with the volunteer's username
$query = "SELECT br.caller_name, br.caller_mobile, br.number_of_birds, br.rescue_status_id, br.address, v.username AS assigned_volunteer, br.created_at 
          FROM birds_rescue AS br
          JOIN volunteers AS v ON br.assigned_to = v.id
          WHERE br.assigned_to = ? AND br.rescue_status_id = 1 
          ORDER BY br.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $volunteer_id);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Rescue Tasks</title>
    <style>
body { 
    font-family: Arial, sans-serif; 
    background: #f4f4f4; 
    padding: 20px; 
    display: flex;
    justify-content: center; 
    align-items: center;
    height: 100vh; /* Full height of the viewport */
}

.container { 
    max-width: 900px; 
    width: 100%;
    background: white; 
    padding: 20px; 
    border-radius: 10px; 
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-top: -250px; /* Adjusted margin */
    margin-right: -200px;
}

table { 
    width: 100%; 
    border-collapse: collapse; 
    margin-top: 20px; 
}

th, td { 
    padding: 10px; 
    border: 1px solid #ddd; 
    text-align: center;
}

th { 
    background: #007bff; 
    color: white; 
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Pending Rescue Tasks</h2>
        <table>
            <tr>
                <th>Caller Name</th>
                <th>Caller Mobile</th>
                <th>No. of Birds</th>
                <th>Rescue Status</th>
                <th>Address</th>
                <th>Assigned To</th>
                <th>Date</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row['caller_name']); ?></td>
                <td><?= htmlspecialchars($row['caller_mobile']); ?></td>
                <td><?= htmlspecialchars($row['number_of_birds']); ?></td>
                <td>Pending</td>
                <td><?= htmlspecialchars($row['address']); ?></td>
                <td><?= htmlspecialchars($row['assigned_volunteer']); ?></td> <!-- Show Username Instead of ID -->
                <td><?= date('d-m-Y', strtotime($row['created_at'])); ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
<?php
// Close connection
$stmt->close();
$conn->close();
?>
