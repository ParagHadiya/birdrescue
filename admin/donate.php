
<link rel="stylesheet" href="../assets/css/admin-styles.css">
<style>

body {
    
    margin-top:00px ;
    margin-bottom: 200px;

}

h2 {
    color: #34495e ;
    
}
</style>

<?php
// Include the database connection file
include '../config/database.php';

session_start();

// Redirect if the admin is not logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Set the admin username for display
$admin_username = $_SESSION['username'] ?? "Admin";

// Handle form submission for donations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donor_name = $_POST['donor_name'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $amount = $_POST['amount'] ?? 0;
    $donation_date = $_POST['donation_date'] ?? '';
    $method = $_POST['method'] ?? '';
    $description = $_POST['description'] ?? '';

    // Handle file upload
    $payment_image = null;
    if (isset($_FILES['payment_image']) && $_FILES['payment_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $file_name = basename($_FILES['payment_image']['name']);
        $target_file = $upload_dir . uniqid() . '_' . $file_name;

        if (move_uploaded_file($_FILES['payment_image']['tmp_name'], $target_file)) {
            $payment_image = $target_file;
        }
    }

    // Insert donation details into the database
    $query = "INSERT INTO donations (donor_name, contact, amount, donation_date, payment_method, description, payment_image)
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssdssss", $donor_name, $contact, $amount, $donation_date, $method, $description, $payment_image);

    if ($stmt->execute()) {
        echo "<script>alert('Donation added successfully!');</script>";
    } else {
        echo "<script>alert('Error adding donation: " . $conn->error . "');</script>";
    }
    $stmt->close();
}
?>

    <title>Donation Management</title>
    <link rel="stylesheet" href="styles.css">

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
<center><h2>Add Donation</h2></center>
    <div class="form-box">
 
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="donor_name">Donor Name:</label>
                <input type="text" id="donor_name" name="donor_name" required>
            </div>
            <div class="form-group">
                <label for="contact">Contact:</label>
                <input type="text" id="contact" name="contact" required>
            </div>
            <div class="form-group">
                <label for="amount">Donation Amount:</label>
                <input type="number" id="amount" name="amount" required>
            </div>
            <div class="form-group">
                <label for="donation_date">Donation Date:</label>
                <input type="date" id="donation_date" name="donation_date" required>
            </div>
            <div class="form-group">
                <label for="method">Payment Method:</label>
                <select id="method" name="method" required>
                    <option value="">Select Payment Method</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="upi">QR Code / UPI</option>
                    <option value="bank_transfer">Bank Transfer</option>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description"></textarea>
            </div>
            <div class="form-group">
                <label for="payment_image">Payment Image:</label>
                <input type="file" id="payment_image" name="payment_image">
            </div>
            <div class="form-group">
                <button type="submit">Donate</button>
            </div>
        </form>
    </div>

    <!-- Donation Table -->
    <div class="donation-table">
        <h2>Donation Details</h2>
        <table class="volunteers-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Donor Name</th>
                    <th>Contact</th>
                    <th>Amount</th>
                    <th>Donation Date</th>
                    <th>Payment Method</th>
                    <th>Payment Image</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM donations ORDER BY created_at DESC";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>" . $row['id'] . "</td>
                            <td>" . htmlspecialchars($row['donor_name']) . "</td>
                            <td>" . htmlspecialchars($row['contact']) . "</td>
                            <td>" . htmlspecialchars($row['amount']) . "</td>
                            <td>" . htmlspecialchars($row['donation_date']) . "</td>
                            <td>" . htmlspecialchars($row['payment_method']) . "</td>
                            <td>" . ($row['payment_image'] ? "<img src='" . htmlspecialchars($row['payment_image']) . "' alt='Payment Image' width='50'>" : "N/A") . "</td>
                            <td>" . htmlspecialchars($row['description']) . "</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No donations found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

