<?php
include "../../auth/check_login.php";
include "../../config/db.php";

// only staff can view this
if ($_SESSION['role'] != "staff") {
    die("Access denied.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Doctor</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/2d3e5b1abc.js" crossorigin="anonymous"></script>

<style>
body {
    margin: 0;
    font-family: Poppins, sans-serif;
    background: #f3f0ff;
    display: flex;
}

/* Sidebar */
.sidebar {
    width: 250px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    background: linear-gradient(180deg, #8ab6ff,#c7a3ff);
    padding: 20px;
    color: white;
}
.sidebar h2 { text-align:center; margin-bottom:30px; }
.sidebar a {
    display:block;
    padding:12px 15px;
    border-radius:10px;
    text-decoration:none;
    color:white;
    margin-bottom:10px;
    transition:0.2s ease;
}
.sidebar a:hover {
    background: rgba(255,255,255,0.3);
    transform: translateX(5px);
}

/* Main */
.main {
    margin-left: 260px;
    padding: 40px;
    width: calc(100% - 260px);
}

/* Header */
.header {
    background:white;
    padding:25px 30px;
    border-radius:15px;
    box-shadow:0 3px 12px rgba(0,0,0,0.08);
    margin-bottom:30px;
}

.form-card {
    background: white;
    padding: 30px;
    border-radius: 18px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    max-width: 700px;
    margin: 20px auto;
}

.form-card label {
    font-weight:500;
    display:block;
    margin-top:10px;
}

.form-card input, 
.form-card select,
.form-card textarea {
    width:100%;
    padding:10px;
    border:1px solid #ccc;
    border-radius:10px;
    margin-top:5px;
    margin-bottom:15px;
    font-size:14px;
}

/* Working days checkbox */
.working-days input[type="checkbox"] {
    vertical-align: middle;
    margin-right: 8px;
    margin-bottom: 8px;
}

/* Button */
.btn-submit {
    background:#7b6dff;
    border:none;
    padding:12px;
    color:white;
    border-radius:10px;
    font-size:16px;
    cursor:pointer;
    transition:0.2s;
    width:100%;
    margin-top:20px;
}
.btn-submit:hover {
    background:#5e52d5;
}

.btn-back {
    background: #ff6b6b; /* merah cair */
    border:none;
    padding:12px;
    color:white;
    border-radius:10px;
    font-size:16px;
    cursor:pointer;
    transition:0.2s;
    width:97%;
    margin-top:10px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
}

.btn-back:hover {
    background: #e05555; /* lebih gelap sedikit bila hover */
}

.back-link {
    display:inline-block;
    margin-top:15px;
    text-decoration:none;
    color:#5a4fcf;
    font-weight:500;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>Clinic Admin</h2>
    <a href="../dashboard/staff_dashboard.php"><i class="fa-solid fa-gauge"></i> Dashboard</a>
    <a href="list.php"><i class="fa-solid fa-hospital-user"></i> Patients</a>
    <a href="../doctor/list.php"><i class="fa-solid fa-user-doctor"></i> Doctors</a>
    <a href="../appointment/list.php"><i class="fa-solid fa-calendar-check"></i> Appointments</a>
</div>

<!-- MAIN -->
<div class="main">
    <div class="header">
        <h1>Add New Doctor</h1>
        <p>Fill out the form below to add a new doctor.</p>
    </div>

    <div class="form-card">
        <form method="POST" action="../../backend/doctor/add_action.php">
            <label>Username</label>
            <input type="text" name="username" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <label>Full Name</label>
            <input type="text" name="full_name" required>

            <label>Gender</label>
            <select name="gender" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>

            <label>Room No</label>
            <input type="text" name="room_no">

            <label>Specialization</label>
            <input type="text" name="specialization">

            <label>Phone</label>
            <input type="text" name="phone">

            <label>Email</label>
            <input type="email" name="email">

            <label>Working Days</label>
            <div class="working-days">
                <input type="checkbox" name="working_days[]" value="Monday"> Monday<br>
                <input type="checkbox" name="working_days[]" value="Tuesday"> Tuesday<br>
                <input type="checkbox" name="working_days[]" value="Wednesday"> Wednesday<br>
                <input type="checkbox" name="working_days[]" value="Thursday"> Thursday<br>
                <input type="checkbox" name="working_days[]" value="Friday"> Friday<br>
                <input type="checkbox" name="working_days[]" value="Saturday"> Saturday<br>
                <input type="checkbox" name="working_days[]" value="Sunday"> Sunday<br>
            </div>

            <button type="submit" name="submit" class="btn-submit">Save</button>
        </form>

        <a href="list.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>
</div>

</body>
</html>
