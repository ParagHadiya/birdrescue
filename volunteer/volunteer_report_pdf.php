<?php
require_once 'TCPDF-main/tcpdf.php'; // Load TCPDF
session_start();
include '../config/database.php';

// Ensure volunteer is logged in
if (!isset($_SESSION['volunteer_id'])) {
    die("Unauthorized access.");
}

$volunteer_id = $_SESSION['volunteer_id'];

// Fetch Volunteer Details
$query = "SELECT username, email, contact_number, image FROM volunteers WHERE id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Database error: " . $conn->error);
}
$stmt->bind_param("i", $volunteer_id);
$stmt->execute();
$result = $stmt->get_result();
$volunteer = $result->fetch_assoc();
$stmt->close();

if (!$volunteer) {
    die("Volunteer not found.");
}

// Fetch Assigned Rescue Details with Proper Joins
$query_rescues = "
    SELECT 
        bm.bird_name AS bird_species, 
        br.number_of_birds, 
        br.caller_name, 
        v.username AS assigned_to, 
        sm.bird_status AS rescue_status, 
        br.created_at 
    FROM birds_rescue br
    LEFT JOIN volunteers v ON br.assigned_to = v.id
    LEFT JOIN birdsmaster bm ON br.bird_species_id = bm.B_id
    LEFT JOIN statusmaster sm ON br.rescue_status_id = sm.id
    WHERE br.assigned_to = ?";

$stmt_rescue = $conn->prepare($query_rescues);
if (!$stmt_rescue) {
    die("Database error: " . $conn->error);
}
$stmt_rescue->bind_param("i", $volunteer_id);
$stmt_rescue->execute();
$result_rescue = $stmt_rescue->get_result();
$rescues = $result_rescue->fetch_all(MYSQLI_ASSOC);
$stmt_rescue->close();

// Initialize TCPDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Bird Rescue Organization');
$pdf->SetTitle('Volunteer Report');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();

// Ensure image path is valid
$image_directory = '../assets/images/volunteers/';
$image_path = !empty($volunteer['image']) && file_exists($image_directory . $volunteer['image']) 
    ? $image_directory . htmlspecialchars($volunteer['image']) 
    : '../assets/images/default.jpg';

// Convert to absolute path for TCPDF
$absolute_image_path = realpath($image_path);

// Volunteer Profile Section (Rounded Image)
$html = '
<table>
    <tr>
        <td>
            <div style="width: 100px; height: 100px; border-radius: 50%; overflow: hidden;">
                <img src="' . $absolute_image_path . '" width="100" height="100" style="border-radius:50%;">
            </div>
        </td>
        <td>
            <h3>' . htmlspecialchars($volunteer['username']) . '</h3>
            <p>Email: ' . htmlspecialchars($volunteer['email']) . '</p>
            <p>Contact: ' . htmlspecialchars($volunteer['contact_number']) . '</p>
        </td>
    </tr>
</table>';

// Rescue Assignments Header
$html .= '<hr><h3 style="text-align:center; color:blue;">Rescue Assignments</h3>';

// Table Header
$html .= '<table border="1" cellpadding="5" style="background-color:green; color:white; text-align:center;">
            <tr>
                <th>Bird Species</th>
                <th>No. of Birds</th>
                <th>Caller Name</th>
                <th>Assigned To</th>
                <th>Rescue Status</th>
                <th>Date</th>
            </tr>';

// Populate Table Data
foreach ($rescues as $rescue) {
    $html .= '<tr style="background-color:#ffffff; color:#000000;">
                <td>' . htmlspecialchars($rescue['bird_species']) . '</td>
                <td>' . htmlspecialchars($rescue['number_of_birds']) . '</td>
                <td>' . htmlspecialchars($rescue['caller_name']) . '</td>
                <td>' . htmlspecialchars($rescue['assigned_to']) . '</td>
                <td>' . htmlspecialchars($rescue['rescue_status']) . '</td>
                <td>' . htmlspecialchars(date('d-m-Y H:i:s', strtotime($rescue['created_at']))) . '</td>
            </tr>';
}
$html .= '</table>';

// Write HTML to PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Output PDF
$pdf->Output('volunteer_report.pdf', 'D'); // Forces download

$conn->close();
?>
