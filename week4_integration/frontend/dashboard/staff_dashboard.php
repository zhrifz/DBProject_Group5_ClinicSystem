<?php
include "../../auth/check_login.php";

if ($_SESSION['role'] != "staff") {
    die("Access denied.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Staff Dashboard</title>

<!-- GOOGLE FONTS -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<!-- ICON -->
<script src="https://kit.fontawesome.com/2d3e5b1abc.js" crossorigin="anonymous"></script>

<style>
    body {
        margin: 0;
        font-family: "Poppins", sans-serif;
        background: #f3f0ff; /* pastel purple bg */
        display: flex;
    }

    /* SIDEBAR */
    .sidebar {
        width: 250px;
        height: 100vh;
        background: linear-gradient(180deg, #8ab6ff, #c7a3ff);
        padding: 20px;
        box-sizing: border-box;
        position: fixed;
        left: 0;
        top: 0;
        color: white;
    }

    .sidebar h2 {
        text-align: center;
        margin-bottom: 30px;
        letter-spacing: 1px;
        font-weight: 600;
    }

    .sidebar a {
        display: block;
        padding: 12px 15px;
        margin-bottom: 10px;
        border-radius: 10px;
        color: white;
        text-decoration: none;
        font-weight: 500;
        transition: 0.2s ease;
    }

    .sidebar a:hover {
        background: rgba(255,255,255,0.3);
        transform: translateX(5px);
    }

    /* MAIN CONTENT */
    .main {
        margin-left: 250px;
        padding: 30px;
        width: calc(100% - 250px);
        box-sizing: border-box;
    }

    .header {
        background: white;
        padding: 18px 25px;
        border-radius: 15px;
        margin-bottom: 25px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.08);
    }

    .header h1 {
        margin: 0;
        font-size: 26px;
        color: #5a4fcf;
        font-weight: 600;
    }

    .header p {
        margin: 3px 0 0;
        font-size: 14px;
        color: #777;
    }

    /* CARDS */
    .dashboard-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 25px;
        margin-top: 20px;
    }

    .card {
        background: white;
        padding: 25px;
        border-radius: 20px;
        text-decoration: none;
        color: #333;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: 0.25s ease;
        display: block;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.13);
    }

    .card-icon {
        font-size: 45px;
        color: #7b6dff;
        margin-bottom: 15px;
    }

    .card h2 {
        margin: 0;
        font-size: 20px;
        color: #5a4fcf;
        font-weight: 600;
    }

    .card p {
        margin-top: 8px;
        font-size: 14px;
        color: #666;
    }

    /* LOGOUT BUTTON */
    .logout-btn {
        background: #ff5d7a;
        padding: 12px;
        text-align: center;
        border-radius: 12px;
        color: white;
        margin-top: 30px;
        display: block;
        text-decoration: none;
        font-weight: 500;
        transition: 0.2s ease;
    }

    .logout-btn:hover {
        background: #ff3c61;
    }
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>Clinic Admin</h2>

    <a href="staff_dashboard.php"><i class="fa-solid fa-gauge"></i> &nbsp; Dashboard</a>
    <a href="../patient/list.php"><i class="fa-solid fa-hospital-user"></i> &nbsp; Patients</a>
    <a href="../doctor/list.php"><i class="fa-solid fa-user-doctor"></i> &nbsp; Doctors</a>
    <a href="../appointment/list.php"><i class="fa-solid fa-calendar-check"></i> &nbsp; Appointments</a>

    <a href="../../backend/auth/logout.php" class="logout-btn">
        <i class="fa-solid fa-right-from-bracket"></i> &nbsp; Logout
    </a>
</div>

<!-- MAIN CONTENT -->
<div class="main">

    <div class="header">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['name']) ?> 👋</h1>
        <p>Staff Dashboard — Manage system operations easily.</p>
    </div>

    <div class="dashboard-cards">

        <!-- Patients -->
        <a href="../patient/list.php" class="card">
            <div class="card-icon">
                <i class="fa-solid fa-hospital-user"></i>
            </div>
            <h2>Patients</h2>
            <p>View, add, edit and manage all patients.</p>
        </a>

        <!-- Doctors -->
        <a href="../doctor/list.php" class="card">
            <div class="card-icon">
                <i class="fa-solid fa-user-doctor"></i>
            </div>
            <h2>Doctors</h2>
            <p>Manage doctor information and schedules.</p>
        </a>

        <!-- Appointments -->
        <a href="../appointment/list.php" class="card">
            <div class="card-icon">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <h2>Appointments</h2>
            <p>View, update and organize appointment records.</p>
        </a>

    </div>

</div>

</body>
</html>
