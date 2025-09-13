<?php
session_start();
include '../config/database.php';
include 'dashbord_nav.php';

// Check if the volunteer is logged in
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

if (!$volunteer) {
    echo "<h2>Volunteer not found!</h2>";
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $contact_number = $_POST['contact_number'] ?? ''; // Added contact number

    // Handle Image Upload
    $image_name = $volunteer_id . ".jpg"; // Always save as id.jpg
    $target_path = "../assets/images/volunteers/" . $image_name;
    
    if (!empty($_FILES['image']['tmp_name']) && move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
        $image = "assets/images/volunteers/" . $image_name;
    } else {
        $image = $volunteer['image']; // Keep existing image if not updated
    }

    // Update volunteer details
    $query_update = "UPDATE volunteers SET 
        image = ?, username = ?, first_name = ?, last_name = ?, dob = ?, 
        email = ?, address = ?, contact_number = ?
        WHERE id = ?";

    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bind_param("ssssssssi", $image, $username, $first_name, $last_name, $dob, 
        $email, $address, $contact_number, $volunteer_id);

    if ($stmt_update->execute()) {
        $_SESSION['success'] = "Profile updated successfully!";
        header("Location: volunteer_dashboard.php");
        exit;
    } else {
        $_SESSION['error'] = "Failed to update profile. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Volunteer Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 50%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            background-color: #2c3e50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #34495e;
        }
        .image-preview img {
            width: 100px;
            border-radius: 10px;
        }
        .form-button {
            background-color: rgb(81, 40, 228);
            color: white;
            padding: 12px;
            border: none;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            border-radius: 5px;
            transition: background 0.3s ease-in-out, transform 0.2s ease-in-out;
        }
        .form-button:hover {
            background-color: #218838;
            transform: scale(1.05);
        }
        .form-button:active {
            transform: scale(0.98);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Volunteer Profile</h2>
        
        <?php if (isset($_SESSION['success'])): ?>
            <p style="color: green;"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
        <?php elseif (isset($_SESSION['error'])): ?>
            <p style="color: red;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <label>Profile Image:</label>
            <div class="image-preview">
                <img src="../<?= htmlspecialchars($volunteer['image']) ?>" alt="Profile Image">
            </div>
            <input type="file" name="image">

            <label>Username:</label>
            <input type="text" name="username" value="<?= htmlspecialchars($volunteer['username']) ?>" required>

            <label>First Name:</label>
            <input type="text" name="first_name" value="<?= htmlspecialchars($volunteer['first_name']) ?>" required>

            <label>Last Name:</label>
            <input type="text" name="last_name" value="<?= htmlspecialchars($volunteer['last_name']) ?>" required>

            <label>Date of Birth:</label>
            <input type="date" name="dob" value="<?= htmlspecialchars($volunteer['dob']) ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($volunteer['email']) ?>" required>

            <label>Address:</label>
            <textarea name="address"><?= htmlspecialchars($volunteer['address']) ?></textarea>

            <label>Contact Number:</label>
            <input type="text" name="contact_number" value="<?= htmlspecialchars($volunteer['contact_number']) ?>" required>

            <button type="submit" class="form-button">Update Profile</button>
        </form>
    </div>
</body>
</html>
