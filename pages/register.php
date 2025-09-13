<style>
    .form-container {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .form-row {
        display: flex;
        gap: 1rem;
    }

    .form-label {
        width: 120px;
    }

    .form-input, .form-textarea {
        flex: 1;
    }

    .form-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .form-row .form-label {
        flex: 1;
        margin-right: 1000px;
        font-weight: bold;
        margin: 15px;
    }

    .form-row .form-input,
    .form-row .form-select,
    .form-row textarea {
        flex: 2;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .data-box {
        margin-top: 20px;
        padding: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        font-size: 16px;
        color: #333;
        margin: 0px 220px 0px 220px;
    }

    .data-box h3 {
        margin-bottom: 10px;
        font-size: 18px;
        color: #555;
        text-decoration: underline;
    }

    .data-box .form-heading {
        text-align: center;
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 30px;
        color: #4CAF50;
    }

    .submit-button {
        background-color: #5c48ce;
        color: white;
        font-size: 16px;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .submit-button:hover {
        background-color: #45a049;
    }

    .error {
        color: red;
        font-weight: bold;
        margin: 15px;
    }

    .success {
        color: green;
        font-weight: bold;
        margin: 15px;
    }

    .checkbox-group input[type="checkbox"] {
        margin-right: 10px;
    }

    .checkbox-group label {
        margin-right: 15px;
    }

    .form-textarea {
        height: 80px;
    }

    .form-label {
    display: inline-block; /* This makes the label stay on one line */
    width: ; /* Allows label to take up only necessary space */
    white-space: nowrap; /* Prevents text from breaking into a new line */
}

</style>

<?php

// Include PHPMailer files using the correct relative path
require_once './PHPMailer-master/src/PHPMailer.php';
require_once './PHPMailer-master/src/SMTP.php';
require_once './PHPMailer-master/src/Exception.php';

// Use PHPMailer namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Gmail SMTP Configuration
function sendEmail($recipientEmail, $recipientName) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'hp12computer12@gmail.com'; // Your Gmail address
        $mail->Password = 'zxjjnqmeughayome';         // Your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('hp12computer12@gmail.com', 'Bird Rescue Admin');
        $mail->addAddress($recipientEmail, $recipientName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to the Volunteer From!';

        $mail->Body = "Dear $recipientName,<br><br>
            Thank you for registering as a volunteer! We appreciate your willingness to contribute to our cause.<br><br>
            Your registration is currently under review. Once the admin approves your registration, you will be granted login access to the system.<br><br>
            You will receive an email notification once your registration is approved.<br><br>
            Best Regards,<br>
            Volunteer Team";

        
        $mail->send();
        return true;
    } catch (Exception $e) {
        echo 'Error: ' . $mail->ErrorInfo;
        return false;
    }
}


include './includes/header.php';
require_once './config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input fields
    $username = $conn->real_escape_string($_POST['username']);
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $address = $conn->real_escape_string($_POST['address']);
    $contact_number = $conn->real_escape_string($_POST['contact_number']);
    $family_contact_number = $conn->real_escape_string($_POST['family_contact_number']);
    $preferred_areas = isset($_POST['preferred_areas']) ? implode(',', $_POST['preferred_areas']) : '';
    $preferred_birds = isset($_POST['preferred_birds']) ? implode(',', $_POST['preferred_birds']) : '';
    $monthly_rescue_capacity = $conn->real_escape_string($_POST['monthly_rescue_capacity']);
    $preferred_days = isset($_POST['preferred_days']) && is_array($_POST['preferred_days']) ? implode(',', $_POST['preferred_days']) : '';
    $preferred_time = $conn->real_escape_string($_POST['preferred_time']);
    $blood_group = $conn->real_escape_string($_POST['blood_group']);

    // Check if password and confirmation match
    if ($password !== $password_confirm) {
        echo '<p class="error">Passwords do not match. Please try again.</p>';
    } else {
        // Check password strength
        if (strlen($password) < 8) {
            echo '<p class="error">Password should be at least 8 characters long.</p>';
        } else {
            // Hash the password
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            // Prepare SQL query with placeholders for a more secure approach
            $sql = "INSERT INTO volunteers (username, first_name, last_name, dob, email, password, address, contact_number, family_contact_number, preferred_areas, preferred_birds, monthly_rescue_capacity, preferred_days, preferred_time, blood_group)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            // Prepare the statement
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("sssssssssssssss", $username, $first_name, $last_name, $dob, $email, $password_hashed, $address, $contact_number, $family_contact_number, $preferred_areas, $preferred_birds, $monthly_rescue_capacity, $preferred_days, $preferred_time, $blood_group);

                // Execute the statement
                if ($stmt->execute()) {
                    // Send confirmation email
                    $emailSent = sendEmail($email, $first_name . ' ' . $last_name);
                    if ($emailSent) {
                        echo '<p class="success">Registration successful! A welcome email has been sent.</p>';
                    } else {
                        echo '<p class="error">Error sending the email.</p>';
                    }
                } else {
                    echo '<p class="error">Error: ' . $stmt->error . '</p>';
                }
                $stmt->close();
            } else {
                echo '<p class="error">Error preparing the query.</p>';
            }
        }
    }
}
?>

<h1 class="form-heading">Volunteer Registration</h1>
<form method="POST" class="data-box">
<div class="form-container">
    <div class="form-row">
        <label for="username" class="form-label">Username:</label>
        <input type="text" name="username" id="username" class="form-input" required>

        <label for="first_name" class="form-label">First Name:</label>
        <input type="text" name="first_name" id="first_name" class="form-input" required>
    </div>
    <div class="form-row">
        <label for="last_name" class="form-label">Last Name:</label>
        <input type="text" name="last_name" id="last_name" class="form-input" required>

        <label for="dob" class="form-label">Date of Birth:</label>
        <input type="date" name="dob" id="dob" class="form-input" required>
    </div>
    <div class="form-row">
        <label for="email" class="form-label">Email:</label>
        <input type="email" name="email" id="email" class="form-input" required>

        <label for="contact_number" class="form-label">Contact Number:</label>
        <input type="text" name="contact_number" id="contact_number" class="form-input" required pattern="\d{10}">
    </div>
    <div class="form-row">
        <label for="password" class="form-label">Password:</label>
        <input type="password" name="password" id="password" class="form-input" required>

        <label for="password_confirm" class="form-label">Confirm Password:</label>
        <input type="password" name="password_confirm" id="password_confirm" class="form-input" required>
    </div>
    <div class="form-row">
        <label for="address" class="form-label">Address:</label>
        <textarea name="address" id="address" class="form-textarea" required></textarea>

        <label for="family_contact_number" class="form-label">Family Number:</label>
        <input type="text" name="family_contact_number" id="family_contact_number" class="form-input" required pattern="\d{10}">
    </div>
</div>

<label for="preferred_areas" class="form-label">Preferred Areas:</label>
<div class="checkbox-group">
<div>
        <input type="checkbox" name="preferred_areas[]" id="amroli" value="Amroli">
        <label for="amroli">Amroli</label>
    </div>
    <div>
        <input type="checkbox" name="preferred_areas[]" id="vesu" value="Vesu">
        <label for="vesu">Vesu</label>
    </div>
    <div>
        <input type="checkbox" name="preferred_areas[]" id="bhatar" value="Bhatar">
        <label for="bhatar">Bhatar</label>
    </div>
    <div>
        <input type="checkbox" name="preferred_areas[]" id="kailash_nagar" value="Kailash Nagar">
        <label for="kailash_nagar">Kailash Nagar</label>
    </div>
    <div>
        <input type="checkbox" name="preferred_areas[]" id="udhna_darwaja" value="Udhna Darwaja">
        <label for="udhna_darwaja">Udhna Darwaja</label>
    </div>
    <div>
        <input type="checkbox" name="preferred_areas[]" id="majura_gate" value="Majura Gate">
        <label for="majura_gate">Majura Gate</label>
    </div>
    <div>
        <input type="checkbox" name="preferred_areas[]" id="mota_varachha" value="Mota Varachha">
        <label for="mota_varachha">Mota Varachha</label>
    </div>
    <div>
        <input type="checkbox" name="preferred_areas[]" id="matavadi" value="Matavadi,Hirabaug,A.K.Road">
        <label for="matavadi">Matavadi, Hirabaug, A.K.Road</label>
    </div>
    <div>
        <input type="checkbox" name="preferred_areas[]" id="nana_varachha" value="Nana Varachha">
        <label for="nana_varachha">Nana Varachha</label>
    </div>
    <div>
        <input type="checkbox" name="preferred_areas[]" id="pal" value="Pal">
        <label for="pal">Pal</label>
    </div>
    <div>
        <input type="checkbox" name="preferred_areas[]" id="katargam" value="Katargam,Katargam Darwaja,Gotalawadi,Sumul Dairy Road">
        <label for="katargam">Katargam, Katargam Darwaja, Gotalawadi, Sumul Dairy Road</label>
    </div>
    <div>
        <input type="checkbox" name="preferred_areas[]" id="jahangirpura" value="Jahangirpura">
        <label for="jahangirpura">Jahangirpura</label>
    </div>
    <div>
        <input type="checkbox" name="preferred_areas[]" id="ved_road" value="Ved Road,Singanpore road,Dabholi Gam">
        <label for="ved_road">Ved Road, Singanpore Road, Dabholi Gam</label>
    </div>
    <div>
        <input type="checkbox" name="preferred_areas[]" id="gopipura" value="Gopipura,Chauta Bajar">
        <label for="gopipura">Gopipura, Chauta Bajar</label>
    </div>
    <div>
        <input type="checkbox" name="preferred_areas[]" id="piplod" value="Piplod,City Light Road,Athwalines">
        <label for="piplod">Piplod, City Light Road, Athwalines</label>
    </div>
    <div>
        <input type="checkbox" name="preferred_areas[]" id="adajan" value="Adajan,Palanpur Patiya">
        <label for="adajan">Adajan, Palanpur Patiya</label>
    </div>
    <div>
        <input type="checkbox" name="preferred_areas[]" id="mahidharpura" value="Mahidharpura,Saiyadpura,Bhagal">
        <label for="mahidharpura">Mahidharpura, Saiyadpura, Bhagal</label>
    </div>
    <div>
        <input type="checkbox" name="preferred_areas[]" id="all_areas" value="All Areas">
        <label for="all_areas">All Areas</label>
    </div>
</div>
<br>
<label class="form-label">Preferred Birds:</label>
<div class="checkbox-group">
    <input type="checkbox" class="form-checkbox" name="preferred_birds[]" value="Pigeon" id="pigeon">
    <label for="pigeon">Pigeon</label><br>

    <input type="checkbox" class="form-checkbox" name="preferred_birds[]" value="Raptors" id="raptors">
    <label for="raptors">Raptors</label><br>

    <input type="checkbox" class="form-checkbox" name="preferred_birds[]" value="Parrot" id="parrot">
    <label for="parrot">Parrot</label><br>

    <input type="checkbox" class="form-checkbox" name="preferred_birds[]" value="Sparrow" id="sparrow">
    <label for="sparrow">Sparrow</label><br>

    <input type="checkbox" class="form-checkbox" name="preferred_birds[]" value="Crow" id="crow">
    <label for="crow">Crow</label><br>
</div>
<br>

<label for="monthly_rescue_capacity" class="form-label">Monthly Rescue Capacity:</label>
<input type="number" name="monthly_rescue_capacity" id="monthly_rescue_capacity" class="form-input" required>

<br>
<label for="preferred_days" class="form-label">Preferred Days:</label>
<div class="checkbox-group">
<input type="checkbox" class="form-checkbox" name="preferred_birds[]" value="Pigeon" id="pigeon">
            <label for="pigeon">All Day</label><br>

            <input type="checkbox" class="form-checkbox" name="preferred_birds[]" value="Raptors" id="raptors">
            <label for="raptors">Monday</label><br>

            <input type="checkbox" class="form-checkbox" name="preferred_birds[]" value="Parrot" id="parrot">
            <label for="parrot">Tuesday</label><br>

            <input type="checkbox" class="form-checkbox" name="preferred_birds[]" value="Sparrow" id="sparrow">
            <label for="sparrow">Wednesday</label><br>

            <input type="checkbox" class="form-checkbox" name="preferred_birds[]" value="Crow" id="crow">
            <label for="crow">Thursday</label><br>

            <input type="checkbox" class="form-checkbox" name="preferred_birds[]" value="Crow" id="crow">
            <label for="crow">Friday</label><br>

            <input type="checkbox" class="form-checkbox" name="preferred_birds[]" value="Crow" id="crow">
            <label for="crow">Saturday</label><br>
            <input type="checkbox" class="form-checkbox" name="preferred_birds[]" value="Crow" id="crow">
            <label for="crow">Sunday</label><br>
</div>
<br>
<label for="preferred_time" class="form-label">Preferred Time:</label>
<input type="time" name="preferred_time" id="preferred_time" class="form-input" required>

<label for="blood_group" class="form-label">Blood Group:</label>
<select name="blood_group" id="blood_group" class="form-input">
    <option value="A+">A+</option>
    <option value="A-">A-</option>
    <option value="B+">B+</option>
    <option value="B-">B-</option>
    <option value="O+">O+</option>
    <option value="O-">O-</option>
    <option value="AB+">AB+</option>
    <option value="AB-">AB-</option>
</select>

 <center><button type="submit" class="submit-button">Submit</button></center>
</form>
</div>
<br><br><br>

<!-- Footer content if necessary -->
<?php include './includes/footer.php'; ?>
