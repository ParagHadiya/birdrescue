<?php
include '../config/database.php';
// session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get total volunteers count
$volunteer_count = $conn->query("SELECT COUNT(*) as total FROM volunteers")->fetch_assoc()['total'];

// Get total birds rescued count
$rescue_count = $conn->query("SELECT SUM(number_of_birds) as total FROM birds_rescue")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            /* background-color: #f4f4f4; */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 50%;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        .summary {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .card {
            width: 100%;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #fff;
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: center;
        }
        .card h3 {
            flex: 1;
            font-size: 18px;
            text-align: center;
        }
        .card p {
            flex: 1;
            font-size: 20px;
            font-weight: bold;
            color: #333;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Overview Dashboard</h2>
        <div class="summary">
            <div class="card">
                <h3>Total Volunteers</h3>
                <h3>Total Rescued Birds</h3>
            </div>
            <div class="card">
                <p><?= $volunteer_count ?></p>
                <p><?= $rescue_count ?></p>
            </div>
        </div>
    </div>
</body>
</html>
