<?php
include "../../auth/check_login.php";
include "../../config/db.php";

// Only staff can edit appointments
if ($_SESSION['role'] != "staff") {
    die("Access denied.");
}

// Must have appointment ID
if (!isset($_GET['id'])) {
    die("No appointment ID provided.");
}

$id = $_GET['id'];

// Fetch appointment data
$sql = "SELECT * FROM Appointment WHERE appointmentID = $id";
$result = mysqli_query($conn, $sql);
$appt = mysqli_fetch_assoc($result);

if (!$appt) {
    die("Appointment not found.");
}

// Load patients
$p_result = mysqli_query($conn, "SELECT * FROM Patient");

// Load doctors
$d_result = mysqli_query($conn, "SELECT * FROM Doctor");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Appointment</title>

<!-- Fonts & Select2 -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Styles -->
<style>
/* --- General Body --- */
body {
    margin: 0;
    font-family: Poppins, sans-serif;
    background: #f3f0ff;
    display: flex;
}

/* --- Sidebar --- */
.sidebar {
    width: 250px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    background: linear-gradient(180deg,#8ab6ff,#c7a3ff);
    padding: 20px;
    color: white;
}
.sidebar h2 { text-align: center; margin-bottom: 30px; }
.sidebar a {
    display: block;
    padding: 12px 15px;
    border-radius: 10px;
    text-decoration: none;
    color: white;
    margin-bottom: 10px;
    transition: 0.2s ease;
}
.sidebar a:hover { background: rgba(255,255,255,0.3); transform: translateX(5px); }

/* --- Main content --- */
.main {
    margin-left: 260px;
    padding: 40px;
    width: calc(100% - 260px);
}

/* --- Header --- */
.header {
    background: white;
    padding: 25px 30px;
    border-radius: 15px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}
.header h1 { font-size: 26px; font-weight: 600; margin: 0; }
.header p { margin-top: 5px; color: #555; }

/* --- Form Card --- */
.form-card {
    background: white;
    padding: 30px;
    border-radius: 18px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    max-width: 600px;
    margin: 0 auto;
}
.form-card label {
    font-weight: 500;
    display: block;
    margin-top: 10px;
}
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
/* Back link */
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

<!-- Main -->
<div class="main">
    <div class="header">
        <h1>Edit Appointment</h1>
        <p>Update the appointment details below.</p>
    </div>

    <div class="form-card">
        <form method="POST" action="../../backend/appointment/edit_action.php?id=<?= $id ?>">

            <!-- Appointment Number -->
            <label>Appointment Number</label>
            <input type="text" name="appointment_number" value="<?= $appt['appointment_number'] ?>" required>

            <!-- Patient Selection -->
            <label>Patient</label>
            <select id="patientSelect" name="patientID" required>
                <?php while ($p = mysqli_fetch_assoc($p_result)) { ?>
                    <option value="<?= $p['patientID'] ?>" <?= $p['patientID']==$appt['patientID']?'selected':'' ?>>
                        <?= $p['full_name'] ?>
                    </option>
                <?php } ?>
            </select>

            <!-- Doctor Selection -->
            <label>Doctor</label>
            <select id="doctorSelect" name="doctorID" required>
                <?php while ($d = mysqli_fetch_assoc($d_result)) { ?>
                    <option value="<?= $d['doctorID'] ?>" <?= $d['doctorID']==$appt['doctorID']?'selected':'' ?>>
                        <?= $d['full_name'] ?> (<?= $d['specialization'] ?>)
                    </option>
                <?php } ?>
            </select>

            <!-- Reason -->
            <label>Reason</label>
            <textarea name="reason_for_appointment"><?= $appt['reason_for_appointment'] ?></textarea>

            <!-- Date & Time -->
            <label>Date & Time</label>
            <input type="datetime-local" name="appointment_time"
                   value="<?= date('Y-m-d\TH:i', strtotime($appt['appointment_time'])) ?>" required>

            <!-- Status -->
            <label>Status</label>
            <select name="status">
                <option <?= $appt['status']=="Upcoming" ? "selected":"" ?>>Upcoming</option>
                <option <?= $appt['status']=="Completed" ? "selected":"" ?>>Completed</option>
                <option <?= $appt['status']=="Cancelled" ? "selected":"" ?>>Cancelled</option>
            </select>

            <!-- Patient Arrival -->
            <label>Patient Arrived?</label>
            <select name="patient_come_into_hospital">
                <option value="Upcoming" <?= $appt['patient_come_into_hospital']=="Upcoming"?"selected":"" ?>>Upcoming</option>
                <option value="yes" <?= $appt['patient_come_into_hospital']=="yes"?"selected":"" ?>>Yes</option>
                <option value="no" <?= $appt['patient_come_into_hospital']=="no"?"selected":"" ?>>No</option>
            </select>

            <!-- Doctor Comment (read-only) -->
            <label>Doctor Comment</label>
            <textarea name="doctor_comment" readonly style="background:#f0f0f0;"><?= $appt['doctor_comment'] ?></textarea>

            <!-- Submit -->
            <button type="submit" name="update" class="btn-submit">Update</button>
        </form>

        <a href="list.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize Select2 for search
    $('#patientSelect').select2({ placeholder: "Search patient...", allowClear: true });
    $('#doctorSelect').select2({ placeholder: "Search doctor...", allowClear: true });
});
</script>

</body>
</html>
