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


// Fetch volunteer details
$query = "SELECT * FROM volunteers WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $volunteer_id);
$stmt->execute();
$result = $stmt->get_result();
$volunteer = $result->fetch_assoc();

// Check if volunteer data exists
if (!$volunteer) {
    echo "<p>No volunteer data found for ID: " . htmlspecialchars($volunteer_id) . "</p>";
    $volunteer = [
        'username' => 'Unknown',
        'image' => 'default_profile.png',
        'first_name' => 'Unknown',
        'last_name' => '',
        'email' => 'Not Available',
        'contact_number' => 'Not Available'
    ];
}

// Fetch pending rescues count
$query_tasks = "SELECT COUNT(*) AS pending_rescues FROM birds_rescue WHERE assigned_to = ? AND rescue_status_id = 1";
$stmt_tasks = $conn->prepare($query_tasks);
$stmt_tasks->bind_param("i", $volunteer_id);
$stmt_tasks->execute();
$result_tasks = $stmt_tasks->get_result();
$tasks = $result_tasks->fetch_assoc() ?: ['pending_rescues' => 0];

// Debugging Output
// echo "<p>Pending Rescues: " . htmlspecialchars($tasks['pending_rescues']) . "</p>";

// Fetch total rescues for the current month
$query_report = "SELECT COUNT(*) AS total_rescues FROM birds_rescue WHERE assigned_to = ? AND rescue_status_id = 2 AND MONTH(created_at) = MONTH(CURRENT_DATE())";
$stmt_report = $conn->prepare($query_report);
$stmt_report->bind_param("i", $volunteer_id);
$stmt_report->execute();
$result_report = $stmt_report->get_result();
$report = $result_report->fetch_assoc() ?: ['total_rescues' => 0];

// Debugging Output
// echo "<p>Total Rescues This Month: " . htmlspecialchars($report['total_rescues']) . "</p>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Dashboard</title>
    <style>
    body { 
        font-family: Arial, sans-serif; 
        background: #f4f4f4; 
        margin: 0; 
        padding: 20px; 
        display: flex; 
        justify-content: center; 
        align-items: center; 
        height: 100vh; 
    }
    .container { 
        width: 50%; 
    }
    .card { 
        background: white; 
        padding: 20px; 
        margin: 15px 0; 
        border-radius: 10px; 
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
        text-align: center; 
        width: 100%; 
    }
    .card img { 
        width: 100px; 
        height: 100px; 
        border-radius: 50%; 
        object-fit: cover; 
    }
    .card h3, .card p { 
        margin: 10px 0; 
    }
    .card a { 
        display: inline-block; 
        margin-top: 10px; 
        padding: 10px; 
        background: #007bff; 
        color: white; 
        text-decoration: none; 
        border-radius: 5px; 
    }
    .card a:hover { 
        background: #0056b3; 
    }
    
    /* 📌 New CSS for side-by-side layout */
    .rescue-container { 
        display: flex; 
        justify-content: space-between; 
        gap: 15px; 
    }
    .rescue-container .card { 
        width: 48%; /* Adjust width for proper alignment */
    }
</style>

</head>
<body>
    <div class="container">
        <h2>Welcome, <?= htmlspecialchars($volunteer['username']); ?>!</h2>

        <!-- Profile Section -->
        <div class="card">
            <img src="../<?= htmlspecialchars($volunteer['image']); ?>" alt="Profile Image">
            <h3><?= htmlspecialchars($volunteer['first_name'] . ' ' . $volunteer['last_name']); ?></h3>
            <p>Email: <?= htmlspecialchars($volunteer['email']); ?></p>
            <p>Contact: <?= htmlspecialchars($volunteer['contact_number']); ?></p>
            <a href="edit_voluteer_profile.php">Edit Profile</a>
        </div>

        <!-- Rescue Tasks (Side-by-Side Layout) -->
<div class="rescue-container">
    <!-- Pending Rescues -->
    <div class="card">
        <h3>Pending Rescues</h3>
        <p>You have <strong><?= $tasks['pending_rescues']; ?> pending </strong> rescues.</p>
        <a href="View_Pending_Tasks.php">View Tasks</a>
    </div>

    <!-- <div class="card">
        <h3>Total Rescues This Month</h3>
        <p><strong><?= $report['total_rescues']; ?></strong></p>
        <a href="#">View Report</a>
    </div> -->

    <div class="card">
        <h3>Total Rescues This Month</h3>
        <p>You have <strong><?= $report['total_rescues']; ?> Rescues</strong> Month.</p>
       <!-- Add this inside your container div -->
<a href="volunteer_report_pdf.php" target="_blank" class="btn btn-danger">Download Report</a>
    </div>
</div>

    </div>
</body>
</html>

<?php
// Close connections
$stmt->close();
$stmt_tasks->close();
$stmt_report->close();
$conn->close();
?>
