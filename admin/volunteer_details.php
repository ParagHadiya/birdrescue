<?php
include '../config/database.php';

// Get volunteer ID from URL
$volunteer_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch volunteer details
$query = "SELECT * FROM volunteers WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $volunteer_id);
$stmt->execute();
$result = $stmt->get_result();
$volunteer = $result->fetch_assoc();

if (!$volunteer) {
    echo "<h2>Volunteer not found!</h2>";
    exit;
}

// Fetch total number of rescued birds assigned to the volunteer
$query_birds = "SELECT COUNT(*) AS total_birds FROM birds_rescue WHERE assigned_to = ?";
$stmt_birds = $conn->prepare($query_birds);
$stmt_birds->bind_param("i", $volunteer_id);
$stmt_birds->execute();
$result_birds = $stmt_birds->get_result();
$total_birds = $result_birds->fetch_assoc()['total_birds'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Details - <?= $volunteer['first_name'] . ' ' . $volunteer['last_name']; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 80%;
            margin: auto;
            display: flex;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .image-box {
            width: 30%;
            text-align: center;
            padding-right: 20px;
        }
        .image-box img {
            width: 100%;
            max-width: 200px;
            border-radius: 10px;
        }
        .details-box {
            width: 70%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
    </style>
</head>
<body>



    <div class="container">
        <!-- Right Side: Volunteer Image -->
        <div class="image-box">
            <!-- <img src="../assets/images/volunteers/<?= $volunteer['id']; ?>.jpg" alt="Volunteer Image"> -->
            <?php
$image_path = "../assets/images/volunteers/" . $volunteer['id'] . ".jpg";
if (file_exists($image_path)) {
    $image_url = $image_path;
} else {
    $image_url = "../assets/images/default.jpg"; // Show a default image if not found
}
?>
<img src="<?= $image_url ?>" alt="Volunteer Image">


            <h2><?= $volunteer['first_name'] . ' ' . $volunteer['last_name']; ?></h2>
        </div>
        
        <!-- Left Side: Volunteer Details -->
        <div class="details-box">
            <table>
                <tr><th>Username</th><td><?= $volunteer['username']; ?></td></tr>
                <tr><th>Full Name</th><td><?= $volunteer['first_name'] . ' ' . $volunteer['last_name']; ?></td></tr>
                <tr><th>Date of Birth</th><td><?= $volunteer['dob']; ?></td></tr>
                <tr><th>Email</th><td><?= $volunteer['email']; ?></td></tr>
                <tr><th>Address</th><td><?= $volunteer['address']; ?></td></tr>
                <tr><th>Contact Number</th><td><?= $volunteer['contact_number']; ?></td></tr>
                <tr><th>Family Contact</th><td><?= $volunteer['family_contact_number']; ?></td></tr>
                <tr><th>Preferred Areas</th><td><?= $volunteer['preferred_areas']; ?></td></tr>
                <tr><th>Preferred Birds</th><td><?= $volunteer['preferred_birds']; ?></td></tr>
                <tr><th>Monthly Rescue Capacity</th><td><?= $volunteer['monthly_rescue_capacity']; ?></td></tr>
                <tr><th>Preferred Days</th><td><?= $volunteer['preferred_days']; ?></td></tr>
                <tr><th>Preferred Time</th><td><?= $volunteer['preferred_time']; ?></td></tr>
                <tr><th>Blood Group</th><td><?= $volunteer['blood_group']; ?></td></tr>
                <tr><th>Created At</th><td><?= $volunteer['created_at']; ?></td></tr>
                <tr><th>Approval Status</th><td><?= ($volunteer['approved'] ? 'Approved' : 'Pending'); ?></td></tr>
                <tr><th>Total Birds Rescued</th><td><strong><?= $total_birds; ?></strong></td></tr>
            </table>
            <div style="margin-top: 20px; text-align: ;">
    <a href="admin_dashboard.php" style="text-decoration: none; background: #2c3e50; color: white; padding: 10px 20px; border-radius: 5px;">Back to Dashboard</a>
</div>

        </div>
    </div>
</body>
</html>
