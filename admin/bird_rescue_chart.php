<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$admin_username = $_SESSION['username'] ?? "Admin";

// Database connection
include '../config/database.php';

// Fetch bird rescue data
$query = "SELECT bird, SUM(number_of_birds) AS count FROM birds_rescue GROUP BY bird";
$result = $conn->query($query);

$chart_data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $chart_data[] = [
            'bird' => $row['bird'],
            'count' => (int)$row['count'] // Ensure count is an integer
        ];
    }
} else {
    $chart_data = [];
}

$json_chart_data = json_encode($chart_data);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bird Rescue Chart</title>
    <link rel="stylesheet" href="../assets/css/admin-styles.css">
</head>
<body>
<div class="sidebar">
    <ul>
        <div class="welcome-message">
            <h4>Welcome, <?php echo htmlspecialchars($admin_username); ?>!</h4> <br><br>
        </div>
        <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
        <li><a href="create_admin.php">Add Admin</a></li>
        <li><a href="add_volunteer.php">Add Volunteer</a></li>
        <li><a href="rescue_status.php">Add Rescue Status</a></li>
        <li><a href="bird_species.php">Add Bird Species</a></li>
        <li><a href="bird_shelter.php">Add Bird Shelter</a></li>
        <li><a href="role.php">Add Role</a></li>
        <li><a href="contact.php">Add Contact</a></li>
        <li><a href="bird_rescue_form.php">Bird Rescue Form</a></li>
        <li><a href="donate.php">Add Donation</a></li>
        <li><a href="logout.php" class="btn logout">Logout</a></li>
    </ul>
</div>


    <div class="main-content">
        <h2 style="text-align: center;">Bird Rescue Data Visualization</h2>
        <div class="chart-container" style="width: 80%; margin: auto;">
            <canvas id="birdRescueChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Get the chart data from PHP
        const chartData = <?= $json_chart_data ?>;

        // Prepare data for the chart
        const labels = chartData.map(data => data.bird); // Bird names
        const counts = chartData.map(data => data.count); // Number of birds

        // Create the chart
        const ctx = document.getElementById('birdRescueChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar', // Bar chart type
            data: {
                labels: labels, // X-axis labels
                datasets: [{
                    label: 'Number of Rescued Birds',
                    data: counts, // Y-axis data
                    backgroundColor: 'rgba(54, 162, 235, 0.5)', // Bar color
                    borderColor: 'rgba(54, 162, 235, 1)', // Border color
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true // Y-axis starts at 0
                    }
                }
            }
        });
    </script>
</body>
</html>
