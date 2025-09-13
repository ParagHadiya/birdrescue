

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    h2 {
    color: #34495e ;
    
}

.table-container {
        width: 100%;
        display: block; 
    }

    .chart-button {
        display: block;
        margin-top: 50px;
        text-align: center;
    }

    .volunteers-container {
        margin-top: 50px; 
        width: 90%; 
        margin-left: -280px;
        margin-right: auto;
        /* padding: 5px; */
        /* background: #f8f9fa; */
        border-radius: 10px;
        /* box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); */
        display: block; /* Ensure this is on a new line */
    }
    .main-container {
        margin-top: -230px; /* Space from the button */
        width: 90%; /* Adjust table width */
        margin-left: 300px;
        margin-right: auto;
        /* padding: 50px; */
        /* background: #f8f9fa; */
        border-radius: 10px;
        /* box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); */
        display: block; /* Ensure this is on a new line */
    }

    .sidebar {
    position: fixed;
    width: 250px; /* Adjust width as per your sidebar */
    height: 100vh; /* Full height */
    background-color: #fff; /* Sidebar background color */
    padding-top: 20px; /* Space from top */
    left: 0;
    top: 0;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    overflow-y: auto; /* Enables scrolling */
}

.sidebar .logo {
    position: fixed;
    top: 10px; /* Adjust based on need */
    left: 20px; /* Moves it to the left */
    width: 200px; /* Adjust width */
    height: auto; /* Maintain aspect ratio */
    text-align: center;
}

.sidebar .logo img {
    max-width: 100%; /* Ensures the image stays within the sidebar */
    height: auto;
}


</style>

<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$admin_username = $_SESSION['username'] ?? "Admin";

// Database connection
include '../config/database.php';

// Pagination settings
$limit_options = [5, 20, 50, 100, 250]; // Available options for records per page
$limit = $_GET['limit'] ?? 5; // Default limit to 5 records per page
$page = $_GET['page'] ?? 1; // Current page, default is 1
$offset = ($page - 1) * $limit; // Calculate offset for SQL query

// Get total number of records
$total_query = "SELECT COUNT(*) AS total FROM birds_rescue";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];

// Calculate total pages
$total_pages = ceil($total_records / $limit);

// Fetch paginated data
$query = "SELECT * FROM birds_rescue ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($query);

$rescue_data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rescue_data[] = $row;
    }
}

// Get the date range selection from the request (optional)
$filter = $_GET['filter'] ?? 'today'; // Default filter: today

// Set the date range based on filter
switch ($filter) {
    case 'yesterday':
        $date_condition = "DATE(created_at) = CURDATE() - INTERVAL 1 DAY";
        break;
    case 'last_7_days':
        $date_condition = "created_at >= CURDATE() - INTERVAL 7 DAY";
        break;
    case 'this_month':
        $date_condition = "MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())";
        break;
    case 'this_year':
        $date_condition = "YEAR(created_at) = YEAR(CURRENT_DATE())";
        break;
    case 'today':
    default:
        $date_condition = "DATE(created_at) = CURDATE()";
        break;
}

// SQL Query to fetch bird rescue data based on the selected filter
$query = "SELECT * FROM birds_rescue WHERE $date_condition";
$result = $conn->query($query);

// Handle the search functionality
$search_term = $_GET['search'] ?? ''; // Get the search term
if (!empty($search_term)) {
    $search_query = "SELECT * FROM birds_rescue WHERE (bird LIKE ? OR caller_name LIKE ?) AND $date_condition";
    $stmt = $conn->prepare($search_query);
    $search_term = "%$search_term%";
    $stmt->bind_param('ss', $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($query);
}

// Fetch the data
$rescue_data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rescue_data[] = $row;
    }
} else {
    $rescue_data = [];
}

// Close the database connection
$conn->close();
?>


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bird Rescue</title>
    <link rel="stylesheet" href="../assets/css/admin-styles.css">

   
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

<br> <br> <br> <br> <br> <br> <br> <br><br> <br> <br> <br>

<style>

.main-content{
    margin-top: 150px;
}
</style>
<div class="main-content">
    <center><h2>Bird Rescue Data</h2></center>  
    
    <br>
    <!-- Filter and Search in One Line -->
    <div class="filter-options" style="display: flex;display: inline-block; white-space: nowrap; align-items: center; gap: 20px; margin-bottom: 20px;">
        <a class="btn" href="?filter=today">Today</a>
        <a class="btn" href="?filter=yesterday">Yesterday</a>
        <a class="btn" href="?filter=last_7_days">Last 7 Days</a>
        <a class="btn" href="?filter=this_month">This Month</a>
        <a class="btn" href="?filter=this_year">This Year</a>
        <input 
            type="text" 
            id="search-input" 
            placeholder="Search by bird name or caller" 
            style="margin-left: auto; padding: 5px; flex-grow: 1;" 
            oninput="filterTable()">
    </div>

   <!-- Table to Display Data -->
   <div class="filter-options">
    <label for="records-per-page">Show: </label>
    <select id="records-per-page" onchange="changeLimit()">
        <?php foreach ($limit_options as $option): ?>
            <option value="<?= $option ?>" <?= $option == $limit ? 'selected' : '' ?>><?= $option ?></option>
        <?php endforeach; ?>
    </select>
    records per page
</div>

<div class="table-container">
    <table class="volunteers-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Bird</th>
                <th>Number of Birds</th>
                <th>Location</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($rescue_data) > 0): ?>
                <?php foreach ($rescue_data as $row): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['bird']) ?></td>
                        <td><?= $row['number_of_birds'] ?></td>
                        <td><?= htmlspecialchars($row['location']) ?></td>
                        <td><?= $row['created_at'] ?></td>
                        <td>
                            <a href="view_bird_rescue.php?id=<?= $row['id'] ?>">View</a>
                            <a href="edit_bird_rescue.php?id=<?= $row['id'] ?>">Edit</a>
                            <a href="delete_bird_rescue.php?id=<?= $row['id'] ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No data found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>">Previous</a>
    <?php endif; ?>
    
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?= $i ?>&limit=<?= $limit ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
    
    <?php if ($page < $total_pages): ?>
        <a href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>">Next</a>
    <?php endif; ?>
</div>

<script>
function changeLimit() {
    var limit = document.getElementById("records-per-page").value;
    window.location.href = "?limit=" + limit + "&page=1";
}
</script>

<style>
/* Pagination Styles */
.pagination {
    margin-top: 20px;
    text-align: center;
}
.pagination a {
    display: inline-block;
    padding: 8px 12px;
    margin: 0 5px;
    background: #3498db;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}
.pagination a.active {
    background: #2c3e50;
}
</style>

<!-- Button should be on a new line -->
<div class="chart-button">
    <center>
        <a class="btn" href="bird_rescue_chart.php">Bird Rescue Chart</a>
    </center>
</div>

<!-- Volunteers section should appear on a new line -->
<div class="volunteers-container">
    <?php include 'volunteers.php'; ?>
    
</div>

<!-- Volunteers section should appear on a new line -->
<div class="main-container">
    <?php include 'dashboard.php'; ?>
</div>


<script>
    // Real-Time Table Filter
    function filterTable() {
        const input = document.getElementById("search-input").value.toLowerCase();
        const table = document.getElementById("rescue-data");
        const rows = table.getElementsByTagName("tr");

        for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName("td");
            let match = false;

            // Check each cell in the row for the input value
            for (let j = 0; j < cells.length; j++) {
                if (cells[j] && cells[j].textContent.toLowerCase().startsWith(input)) {
                    match = true;
                    break;
                }
            }

            rows[i].style.display = match ? "" : "none"; // Show or hide rows
        }
    }
</script>

