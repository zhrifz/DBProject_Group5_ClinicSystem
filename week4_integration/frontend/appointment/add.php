<?php
include "../../auth/check_login.php";
include "../../config/db.php";

if ($_SESSION['role'] != "staff") {
    die("Access denied.");
}

// load patients
$p_result = mysqli_query($conn, "SELECT * FROM Patient");

// load doctors
$d_result = mysqli_query($conn, "SELECT * FROM Doctor");
$doctors = [];
while ($d = mysqli_fetch_assoc($d_result)) {
    $doctors[$d['doctorID']] = $d; // simpan doctor info untuk JS
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Appointment</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>

/* --- General Body Styles --- */
body {
    margin: 0; /* remove default margin */
    font-family: Poppins, sans-serif; /* main font */
    background: #f3f0ff; /* light background color */
    display: flex; /* layout sidebar + main */
}

/* --- Sidebar --- */
.sidebar {
    width: 250px; /* fixed width */
    height: 100vh; /* full viewport height */
    position: fixed;
    left: 0;
    top: 0;
    background: linear-gradient(180deg, #8ab6ff, #c7a3ff); /* gradient background */
    padding: 20px;
    color: white; /* text color */
}

/* Sidebar title */
.sidebar h2 {
    text-align: center;
    margin-bottom: 30px;
}

/* Sidebar links */
.sidebar a {
    display: block;
    padding: 12px 15px;
    border-radius: 10px;
    text-decoration: none;
    color: white;
    margin-bottom: 10px;
    transition: 0.2s ease;
}

/* Sidebar link hover effect */
.sidebar a:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateX(5px);
}

/* --- Main Content --- */
.main {
    margin-left: 260px; /* space for sidebar */
    padding: 40px;
    width: calc(100% - 260px); /* remaining width */
}

/* --- Header --- */
.header {
    background: white;
    padding: 25px 30px;
    border-radius: 15px;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
    margin-bottom: 30px;
}

/* Header title */
.header h1 {
    font-size: 26px;
    font-weight: 600;
    margin: 0;
}

/* Header subtitle/paragraph */
.header p {
    margin-top: 5px;
    color: #555;
}

/* --- Form Card --- */
.form-card {
    background: white;
    padding: 30px;
    border-radius: 18px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    max-width: 600px;
    margin: 0 auto;
}

/* Form labels */
.form-card label {
    font-weight: 500;
    display: block;
    margin-top: 10px;
}

/* Form inputs, selects, textarea */
.form-card input,
.form-card select,
.form-card textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 10px;
    margin-top: 5px;
    font-size: 14px;
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

/* Back link button */
.back-link {
    display: inline-block;
    margin-top: 15px;
    text-decoration: none;
    color: #ff6b6b;
    font-weight: 500;
    padding: 8px 12px;
    border-radius: 8px;
    background: #ffe5e5;
}

/* Working days text display */
#workingDaysText {
    margin-top: 5px;
    font-weight: 500;
    color: #333;
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

<div class="main">
    <div class="header">
        <h1>Create Appointment</h1>
        <p>Fill the form to schedule a new appointment</p>
    </div>

    <div class="form-card">
        <form method="POST" action="../../backend/appointment/add_action.php">
            <label>Appointment Number</label>
            <input type="text" name="appointment_number" required>

            <label>Patient</label>
            <select id="patientSelect" name="patientID" required>
                <option value="">-- Select Patient --</option>
                <?php while ($p = mysqli_fetch_assoc($p_result)) { ?>
                    <option value="<?= $p['patientID'] ?>"><?= $p['full_name'] ?></option>
                <?php } ?>
            </select>

            <label>Doctor</label>
            <select id="doctorSelect" name="doctorID" required onchange="showWorkingDays()">
                <option value="">-- Select Doctor --</option>
                <?php foreach($doctors as $d) { ?>
                    <option value="<?= $d['doctorID'] ?>"><?= $d['full_name'] ?> (<?= $d['specialization'] ?>)</option>
                <?php } ?>
            </select>
            <p id="workingDaysText"></p>

            <label>Reason</label>
            <textarea name="reason_for_appointment"></textarea>

            <label>Date & Time</label>
            <input type="datetime-local" name="appointment_time" required>

            <button type="submit" name="submit" class="btn-submit">Save</button>
        </form>

        <a href="list.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#patientSelect').select2({ placeholder: "Search patient...", allowClear: true });
    $('#doctorSelect').select2({ placeholder: "Search doctor...", allowClear: true });
});

var doctors = <?php echo json_encode($doctors); ?>;

function showWorkingDays() {
    var select = document.getElementById('doctorSelect');
    var doctorID = select.value;
    var text = '';
    if (doctorID && doctors[doctorID]) {
        text = "Working Days: " + doctors[doctorID]['working_days'];
    }
    document.getElementById('workingDaysText').innerText = text;
}
</script>
</body>
</html>
