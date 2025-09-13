<!-- <link rel="stylesheet" href="../assets/css/admin-styles.css"> -->
<style>

    
     h2 {
    color: #34495e ;
    
}
/* Main Content */
.container {
    width: 70%;

    padding: 20px;
    margin-left: 280px;
    /* background: white; */
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background: #ffffff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
}

th, td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #2c3e50;
    color: white;
    font-weight: bold;
}

tr:hover {
    background-color: #f1f1f1;
}

/* Form Styling */
form {
    margin-top: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

input[type="text"] {
    width: 80%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    padding: 10px 15px;
    background: #1abc9c;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: #16a085;
}

</style>

<?php
include '../config/database.php';
// session_start();

// Fetch volunteers with total rescued birds count
$query = "SELECT v.*, 
                 (SELECT COUNT(*) FROM birds_rescue WHERE assigned_to = v.id) AS total_birds 
          FROM volunteers v";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles.css">
    <title>Volunteers</title>
</head>
<body>
    <div class="container">
        <center><h2>Volunteers List</h2></center>
        <table>
            <tr>
                <th style="color: ; background-color: #2c3e50;">ID</th>
                <th style="color: ; background-color: #2c3e50;">Name</th>
                <th style="color: ; background-color: #2c3e50;">Contact</th>
                <th style="color: ; background-color: #2c3e50;">Total Rescued Birds</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td>
                        <a href="volunteer_details.php?id=<?= $row['id']; ?>">
                            <?= $row['first_name'] . ' ' . $row['last_name']; ?>
                        </a>
                    </td>
                    <td><?= $row['contact_number']; ?></td>
                    <td><?= $row['total_birds']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
