<?php
session_start();
include '../config/database.php';

$admin_username = $_SESSION['username'] ?? "Admin";

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch options from database
$birds_result = $conn->query("SELECT B_id, bird_name FROM BirdsMaster");
$status_result = $conn->query("SELECT id, bird_status FROM StatusMaster");
$shelter_result = $conn->query("SELECT id, bird_shelter_name FROM BirdShelterMaster");
$roles_result = $conn->query("SELECT role_id, roles FROM RoleMaster");
$volunteers_result = $conn->query("SELECT id, username FROM volunteers WHERE approved = '1'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $caller_name = trim($_POST['caller_name'] ?? '');
    $caller_mobile = trim($_POST['caller_mobile'] ?? '');
    $number_of_birds = isset($_POST['number_of_birds']) ? intval($_POST['number_of_birds']) : 0;
    $location = trim($_POST['location'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $note = trim($_POST['note'] ?? '');
    $bird_species_id = isset($_POST['bird_species_id']) ? intval($_POST['bird_species_id']) : 0;
    $rescue_status_id = isset($_POST['rescue_status_id']) ? intval($_POST['rescue_status_id']) : 0;
    $assigned_to = isset($_POST['assigned_to']) ? intval($_POST['assigned_to']) : 0;

    // Handle file upload
    $bird_image = '';
    if (!empty($_FILES['bird_image']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $bird_image = $target_dir . basename($_FILES['bird_image']['name']);
        move_uploaded_file($_FILES['bird_image']['tmp_name'], $bird_image);
    }

    // Validate required fields
    if (!$caller_name || !$caller_mobile || !$number_of_birds || !$location || !$address || !$bird_species_id || !$rescue_status_id || !$assigned_to) {
        echo "<p style='color: red;'>Error: All fields are required!</p>";
    } else {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO birds_rescue (bird_image, caller_name, caller_mobile, number_of_birds, location, address, note, bird_species_id, rescue_status_id, assigned_to, created_at) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssssssssi", $bird_image, $caller_name, $caller_mobile, $number_of_birds, $location, $address, $note, $bird_species_id, $rescue_status_id, $assigned_to);

        
        if ($stmt->execute()) {
            echo "<p style='color: green;'>Form submitted successfully!</p>";
        } else {
            echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
}

// Fetch all records for display
$records_result = $conn->query("SELECT b.id, b.bird_image, b.caller_name, b.caller_mobile, b.number_of_birds, b.rescue_status_id, s.bird_status, b.address, b.created_at, b.assigned_to, v.username AS assigned_volunteer 
    FROM birds_rescue b 
    LEFT JOIN statusmaster s ON b.rescue_status_id = s.id 
    LEFT JOIN volunteers v ON b.assigned_to = v.id 
    ORDER BY b.created_at DESC");


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bird Rescue Form</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../assets/css/admin-styles.css">
    <style>
        .main-content { padding: 20px; }
        .form-box { width: 50%; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; background-color: #f9f9f9; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;
        }
        .form-button { background-color: rgb(81, 40, 228); color: white; padding: 12px; border: none; cursor: pointer; width: 100%; font-size: 16px; border-radius: 5px; transition: background 0.3s, transform 0.2s; }
        .form-button:hover { background-color: #218838; transform: scale(1.05); }
        .records-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .records-table th, .records-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .records-table th { background-color: #007bff; color: white; }
        .records-table img { max-height: 50px; max-width: 100px; }

    </style>
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
    <center><h2>Bird Rescue Form</h2></center>
    <div class="form-box">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="bird_image">Bird Image:</label>
                <input type="file" id="bird_image" name="bird_image">
            </div>
            <div class="form-group">
                <label for="caller_name">Caller Name:</label>
                <input type="text" id="caller_name" name="caller_name" required>
            </div>
            <div class="form-group">
                <label for="caller_mobile">Caller Mobile:</label>
                <input type="text" id="caller_mobile" name="caller_mobile" required>
            </div>
            <div class="form-group">
                <label for="number_of_birds">Number of Birds:</label>
                <input type="number" id="number_of_birds" name="number_of_birds" required>
            </div>
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address" required></textarea>
            </div>
            <div class="form-group">
    <label for="bird_species_id">Bird Species:</label>
    <select id="bird_species_id" name="bird_species_id" required>
        <option value="">Select Bird Species</option>
        <?php while ($bird = $birds_result->fetch_assoc()) : ?>
            <option value="<?= $bird['B_id']; ?>"><?= htmlspecialchars($bird['bird_name']); ?></option>
        <?php endwhile; ?>
    </select>
</div>

<div class="form-group">
    <label for="rescue_status_id">Rescue Status:</label>
    <select id="rescue_status_id" name="rescue_status_id" required>
        <option value="">Select Status</option>
        <?php while ($status = $status_result->fetch_assoc()) : ?>
            <option value="<?= $status['id']; ?>"><?= htmlspecialchars($status['bird_status']); ?></option>
        <?php endwhile; ?>
    </select>
</div>

            <div class="form-group">
                <label for="assigned_to">Assign to Volunteer:</label>
                <select id="assigned_to" name="assigned_to" required>
                    <option value="">Select Volunteer</option>
                    <?php while ($volunteer = $volunteers_result->fetch_assoc()) : ?>
                        <option value="<?= $volunteer['id']; ?>"><?= htmlspecialchars($volunteer['username']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="form-button">Add Rescue Record</button>
        </form>
    </div>

    <h3>Rescue Records</h3>
    <table class="records-table">
    <tr>
        <th>Image</th>
        <th>Caller Name</th>
        <th>Caller Mobile</th>
        <th>No. of Birds</th>
        <th>Rescue Status</th>
        <th>Address</th>
        <th>Assigned To</th>
        <th>Date</th>
    </tr>
    <?php while ($row = $records_result->fetch_assoc()) : ?>
        <tr>
            <td><img src="<?= htmlspecialchars($row['bird_image']); ?>" alt="Bird"></td>
            <td><?= htmlspecialchars($row['caller_name']); ?></td>
            <td><?= htmlspecialchars($row['caller_mobile']); ?></td>
            <td><?= htmlspecialchars($row['number_of_birds']); ?></td>

            <!-- Rescue Status Dropdown -->
            <td>
                <select class="update-field" data-id="<?= $row['id']; ?>" data-column="rescue_status_id">
                    <?php
                    $status_result->data_seek(0); // Reset the result pointer
                    while ($status = $status_result->fetch_assoc()) : ?>
                        <option value="<?= $status['id']; ?>" <?= ($status['id'] == $row['rescue_status_id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($status['bird_status']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </td>

            <td><?= htmlspecialchars($row['address']); ?></td>

            <!-- Assigned Volunteer Dropdown -->
            <td>
                <select class="update-field" data-id="<?= $row['id']; ?>" data-column="assigned_to">
                    <?php
                    $volunteers_result->data_seek(0); // Reset the result pointer
                    while ($volunteer = $volunteers_result->fetch_assoc()) : ?>
                        <option value="<?= $volunteer['id']; ?>" <?= ($volunteer['id'] == $row['assigned_to']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($volunteer['username']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </td>

            <td><?= htmlspecialchars($row['created_at']); ?></td>
        </tr>
    <?php endwhile; ?>
</table>

</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $(".update-field").change(function () {
        var record_id = $(this).data("id");
        var column_name = $(this).data("column");
        var new_value = $(this).val();

        $.ajax({
            url: "update_rescue_record.php", // Create this file
            type: "POST",
            data: {
                id: record_id,
                column: column_name,
                value: new_value
            },
            success: function (response) {
                alert(response); // Show response message
            },
            error: function () {
                alert("Error updating record.");
            }
        });
    });
});
</script>

</script>
</body>
</html>
