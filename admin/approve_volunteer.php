<?php
require_once '../config/database.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';
require '../PHPMailer-master/src/Exception.php';

session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit;
}

// Check if the volunteer ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<p class="error">Invalid volunteer ID.</p>';
    exit;
}

$volunteer_id = intval($_GET['id']);

// Fetch volunteer details
$sql = "SELECT email, first_name FROM volunteers WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo '<p class="error">Error preparing the query: ' . $conn->error . '</p>';
    exit;
}

$stmt->bind_param("i", $volunteer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<p class="error">Volunteer not found.</p>';
    $stmt->close();
    exit;
}

$volunteer = $result->fetch_assoc();
$email = $volunteer['email'];
$first_name = $volunteer['first_name'];

// Approve the volunteer
$sql_update = "UPDATE volunteers SET approved = 1 WHERE id = ?";
$update_stmt = $conn->prepare($sql_update);

if (!$update_stmt) {
    echo '<p class="error">Error preparing the update query: ' . $conn->error . '</p>';
    exit;
}

$update_stmt->bind_param("i", $volunteer_id);
if ($update_stmt->execute()) {
    // Email the volunteer about the approval
    $mail = new PHPMailer\PHPMailer\PHPMailer();

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'hp12computer12@gmail.com'; // Your email
        $mail->Password = 'zxjjnqmeughayome';         // Your app password
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('hp12computer12@gmail.com', 'Bird Rescue Admin');
        $mail->addAddress($email, $first_name);

        $mail->isHTML(true);
        $mail->Subject = 'Volunteer Approval Notification';  // diffent msg i nedd Your Volunteer Application Has Been Approved!
        $mail->Body = "Dear $first_name,<br><br>
            Your application to join Bird Rescue as a volunteer has been approved. Welcome to the team!<br><br>
            You can now access the volunteer login and begin your work with us.<br><br>
            Thank you for your dedication and willingness to help.<br><br>
            Best Regards,<br>
            Bird Rescue Team";

        $mail->send();
        echo '<p>Volunteer approved successfully, and an email has been sent.</p>';
    } catch (Exception $e) {
        echo '<p>Volunteer approved successfully, but the email could not be sent. Error: ' . $mail->ErrorInfo . '</p>';
    }

    // Redirect to add_volunteer.php
    header('Location: add_volunteer.php');
    exit;
} else {
    echo '<p class="error">Error approving the volunteer.</p>';
}

$update_stmt->close();
$stmt->close();
?>
