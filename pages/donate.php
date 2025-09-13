<style>

    
body{
        background-image: url('./assets/images//Bird_Flyingvideo-1.gif');
        background-repeat: no-repeat;
       
    }
    .donation-form {
    width: 60%;
    margin: 0 auto;
    padding: 200px;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #f9f9f9;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.form-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.form-row .form-label {
    flex: 1;
    margin-right: 10px;
    font-weight: bold;
}

.form-row .form-input,
.form-row .form-select,
.form-row textarea {
    flex: 2;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

textarea {
    resize: vertical;
}


</style>

<center>
<?php 
include './includes/header.php'; 
include './config/database.php'; // Include database connection
?>

<h1  class="donation-heading">Donate to Bird Rescue</h1>
<p style="color:black" class="donation-description">Your donations help us provide better care for rescued birds. Thank you for your support!</p>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $donor_name = $_POST['donor_name'];
    $contact = $_POST['contact'];
    $amount = $_POST['amount'];
    $donation_date = $_POST['donation_date'];
    $payment_method = $_POST['method'];
    $description = $_POST['description'] ?? '';

    // Handle file upload for payment image
    $payment_image = null;
    if (isset($_FILES['payment_image']) && $_FILES['payment_image']['error'] === UPLOAD_ERR_OK) {
        $uploads_dir = 'uploads/';
        $file_tmp = $_FILES['payment_image']['tmp_name'];
        $file_name = basename($_FILES['payment_image']['name']);
        $file_path = $uploads_dir . $file_name;

        // Ensure uploads directory exists
        if (!file_exists($uploads_dir)) {
            mkdir($uploads_dir, 0777, true);
        }

        // Move uploaded file to the uploads directory
        if (move_uploaded_file($file_tmp, $file_path)) {
            $payment_image = $file_path;
        }
    }

    // Insert data into the database
    $query = "INSERT INTO donations (donor_name, contact, amount, donation_date, payment_method, payment_image, description) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssdssss', $donor_name, $contact, $amount, $donation_date, $payment_method, $payment_image, $description);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Thank you for your donation!</p>";
    } else {
        echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
</center>
<form action="" method="POST" enctype="multipart/form-data" class="donation-form">
    <div class="form-row">
        <label for="donor_name" class="form-label">Donor Name:</label>
        <input type="text" id="donor_name" name="donor_name" class="form-input" required>
        &nbsp;&nbsp;&nbsp;
        <label for="contact" class="form-label">Contact:</label>
        <input type="text" id="contact" name="contact" class="form-input" required>
    </div>

    <div class="form-row">
        <label for="amount" class="form-label">Donation Amount:</label>
        <input type="number" id="amount" name="amount" class="form-input" required>
        &nbsp;&nbsp;&nbsp;
        <label for="donation_date" class="form-label">Donation Date:</label>
        <input type="date" id="donation_date" name="donation_date" class="form-input" required>
    </div>

    <div class="form-row">
        <label for="method" class="form-label">Payment Method:</label>
        <select id="method" name="method" class="form-select" required onchange="showPaymentFields()">
            <option value="">Select Payment Method</option>
            <option value="credit_card">Credit Card</option>
            <option value="upi">QR Code / UPI</option>
            <option value="bank_transfer">Bank Transfer</option>
        </select>
    </div>

    <div id="credit_card_fields" class="form-row" style="display: none;">
        <label for="card_number" class="form-label">Card Number:</label>
        <input type="text" id="card_number" name="card_number"  value="753214692" class="form-input" readonly>

        <label for="expiry_date" class="form-label">Expiry Date:</label>
<input type="month" id="expiry_date" name="expiry_date" value="2029-12" class="form-input" readonly>

    </div>

    <center>
        <div id="upi_fields" style="display: none;">
            <label for="upi_label" class="form-label">UPI</label>
            <img src="assets/images/QR.jpeg" alt="Bird Rescue" height="200" width="300"><br>
            <p>UPI ID: <strong>8000874240@ybl</strong></p>
        </div>
    </center>

    <div id="bank_transfer_fields" class="form-row" style="display: none;">
        <label for="account_name" class="form-label">Account Name:</label>
        <input type="text" id="account_name" name="account_name" class="form-input" value="Bird Rescue Foundation" readonly>

        <label for="ifsc" class="form-label">IFSC Code:</label>
        <input type="text" id="ifsc" name="ifsc" class="form-input" value="BRF123456" readonly>
    </div>

    <div class="form-row">
        <label for="description" class="form-label">Description:</label>
        <textarea id="description" name="description" class="form-input" placeholder="Enter description here..."></textarea>
    </div>

    <center>
        <button type="submit" class="form-button">Donate</button>
    </center>
</form>
<br>
<br><br>


<script>
    function showPaymentFields() {
        // Get selected payment method
        const method = document.getElementById('method').value;

        // Hide all fields
        document.getElementById('credit_card_fields').style.display = 'none';
        document.getElementById('upi_fields').style.display = 'none';
        document.getElementById('bank_transfer_fields').style.display = 'none';

        // Show fields based on selection
        if (method === 'credit_card') {
            document.getElementById('credit_card_fields').style.display = 'block';
        } else if (method === 'upi') {
            document.getElementById('upi_fields').style.display = 'block';
        } else if (method === 'bank_transfer') {
            document.getElementById('bank_transfer_fields').style.display = 'block';
        }
    }
</script>

<?php include './includes/footer.php'; ?>
