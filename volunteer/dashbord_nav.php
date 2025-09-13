<style>
/* Reset some basic styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    display: flex;
    min-height: 100vh; /* Full viewport height */
}

/* Sidebar Menu */
nav {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: flex-start;
    background-color: #222; /* Dark background */
    width: 250px; /* Sidebar width */
    height: 100vh; /* Full height */
    position: fixed; /* Fixed position */
    left: 0;
    top: 0;
    padding: 20px;
    overflow-y: auto; /* Scroll if content is too long */
}

/* Navigation Links */
.nav-menu {
    display: flex;
    flex-direction: column;
    gap: 15px;
    width: 100%;
}

.nav-menu a {
    text-decoration: none;
    color: white;
    font-weight: bold;
    padding: 12px;
    display: block;
    border-radius: 5px;
    transition: background 0.3s, color 0.3s;
    background: rgba(56, 148, 148, 0.1); /* Light transparency */
}

.nav-menu a:hover {
    background-color: #007bff; /* Blue on hover */
    color: #fff;
}

/* Main Content */
.main-content {
    margin-left: 270px; /* Adjust based on sidebar width */
    padding: 20px;
    width: calc(100% - 270px);
    overflow-x: auto;
}

/* Form Styling */
.form-box {
    max-width: 600px;
    margin: auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f9f9f9;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    font-weight: bold;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.form-button {
    background-color: #28a745;
    color: white;
    padding: 10px 15px;
    border: none;
    cursor: pointer;
    width: 100%;
}

.form-button:hover {
    background-color: #218838;
}

/* Table Styling */
.volunteers-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.volunteers-table th,
.volunteers-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

.volunteers-table th {
    background-color: #007bff;
    color: white;
}

.volunteers-table img {
    max-height: 50px;
    max-width: 100px;
}

/* Responsive Design */
@media (max-width: 768px) {
    nav {
        width: 100%;
        height: auto;
        position: relative;
        padding: 10px;
    }

    .main-content {
        margin-left: 0;
        width: 100%;
        padding: 15px;
    }

    .nav-menu {
        flex-direction: row;
        justify-content: space-around;
    }

    .nav-menu a {
        padding: 8px;
        font-size: 14px;
    }
}
h2 {
    color: white;
    background-color: #333; 
    padding: 10px;
    border-radius: 5px;
    text-align: center;
}


</style>

<nav>

    
 
   
    <h2 style=>Welcome, <?php echo htmlspecialchars($_SESSION['volunteer_name']); ?>!</h2> <br> <br>
    <div class="nav-menu">
        <a href="http://localhost/birdrescue/volunteer/volunteer_dashboard.php">Volunteer Dashboard</a>
        <a href="http://localhost/birdrescue/volunteer/bird_rescue_form.php">Bird Rescue Form</a>
      
        <a href="http://localhost/birdrescue/volunteer/donate.php">Donate Now</a>


        <a href="http://localhost/birdrescue/volunteer/edit_voluteer_profile.php">Volunteer Profile </a>

        <a href="http://localhost/birdrescue/volunteer/logout.php">Logout</a>
    </div>
</nav>
