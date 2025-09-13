<?php
// Load PHPMailer using Composer's autoloader
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();                                    // Use SMTP
    $mail->Host = 'smtp.gmail.com';                     // Gmail SMTP server
    $mail->SMTPAuth = true;                             // Enable SMTP authentication
    $mail->Username = 'your_email@gmail.com';           // Your Gmail address
    $mail->Password = 'your_app_password';              // Your Gmail app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
    $mail->Port = 587;                                  // TCP port for TLS

    // Sender and recipient details
    $mail->setFrom('your_email@gmail.com', 'Your Name'); // Sender email and name
    $mail->addAddress('recipient_email@example.com', 'Recipient Name'); // Recipient email and name
    $mail->addReplyTo('your_email@gmail.com', 'Your Name'); // Reply-to address

    // Email content
    $mail->isHTML(true);                                // Set email format to HTML
    $mail->Subject = 'Test Email from PHPMailer';
    $mail->Body    = '<h1>Hello!</h1><p>This is a test email sent using PHPMailer.</p>';
    $mail->AltBody = 'This is a test email sent using PHPMailer (non-HTML version).';

    // Send the email
    $mail->send();
    echo 'Message has been sent successfully!';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
