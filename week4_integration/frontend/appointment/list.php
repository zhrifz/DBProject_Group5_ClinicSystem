<?php
include "../../auth/check_login.php";
include "../../config/db.php";

// Ambil semua appointments untuk table
$sql = "SELECT 
            Appointment.*, 
            Patient.full_name AS patient_name, 
            Doctor.full_name AS doctor_name
        FROM Appointment
        JOIN Patient ON Appointment.patientID = Patient.patientID
        JOIN Doctor ON Appointment.doctorID = Doctor.doctorID
        ORDER BY appointment_time DESC";

$result = mysqli_query($conn, $sql);

// Ambil appointments untuk calendar
$appointments = [];
$result2 = mysqli_query($conn, "SELECT 
    Appointment.appointment_time, 
    Doctor.full_name AS doctor_name, 
    Doctor.gender AS doctor_gender, 
    Patient.full_name AS patient_name, 
    Patient.gender AS patient_gender
FROM Appointment 
JOIN Doctor ON Appointment.doctorID = Doctor.doctorID 
JOIN Patient ON Appointment.patientID = Patient.patientID");

while($row2 = mysqli_fetch_assoc($result2)){
    $date = date('Y-m-d', strtotime($row2['appointment_time']));
    $time = date('H:i', strtotime($row2['appointment_time']));

    // tentukan gelaran berdasarkan gender
    $patient_title = strtolower($row2['patient_gender']) == 'male' ? 'Mr.' : 'Ms.';
    $doctor_title = 'Dr.'; // selalu Dr.

    if(!isset($appointments[$date])) $appointments[$date] = [];
    $appointments[$date][] = [
        'time'=>$time,
        'doctor'=>$doctor_title . ' ' . $row2['doctor_name'],
        'patient'=>$patient_title . ' ' . $row2['patient_name']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Appointment List</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/2d3e5b1abc.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<style>
body {
    margin:0;
    font-family:Poppins,sans-serif;
    background:#f3f0ff;
    display:flex;
}

/* Sidebar */
.sidebar {
    width:250px;
    height:100vh;
    position:fixed;
    left:0;
    top:0;
    background:linear-gradient(180deg,#8ab6ff,#c7a3ff);
    padding:20px;
    color:white;
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
    margin-left:260px;
    padding:40px;
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
.header h1 {
    font-size:26px;
    font-weight:600;
    margin:0;
}
.header p {
    margin-top:5px;
    color:#555;
}

/* Buttons */
.btn {
    display:inline-block;
    padding:12px;
    border-radius:10px;
    text-decoration:none;
    color:white;
    font-weight:500;
    font-size:15px;
    margin-bottom:15px;
    cursor:pointer;
    transition:0.2s;
}
.btn-create { background:#7b6dff; }
.btn-create:hover { background:#5e52d5; }
.btn-back { background:#ff6b6b; }
.btn-back:hover { background:#e05555; }

/* Calendar */
#calendar {
    background:white;
    padding:15px;
    border-radius:15px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
    margin-bottom:30px;
}

/* Table */
.appointment-table {
    width:100%;
    border-collapse: collapse;
    background:white;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
}
.appointment-table th, .appointment-table td {
    padding:10px;
    text-align:left;
    border-bottom:1px solid #ddd;
}
.appointment-table th {
    background:#f0f0f0;
}
.appointment-table tr:hover {
    background:#f9f9f9;
}
.appointment-table td a {
    color:#5a4fcf;
    text-decoration:none;
}

/* Popup */
.app-popup {
    position: fixed;
    top:50%;
    left:50%;
    transform: translate(-50%, -50%);
    background:white;
    padding:15px;
    border-radius:10px;
    box-shadow:0 4px 12px rgba(0,0,0,0.2);
    z-index:9999;
    max-width: 300px;
    max-height: 400px;
    overflow-y: auto;

    font-size: 12px;      /* <-- kecilkan font */
    line-height: 1.3;     /* <-- rapatkan jarak antara baris */
}

.app-popup button {
    margin-top:10px;
    padding:8px 12px;
    border:none;
    background:#ff6b6b;
    color:white;
    border-radius:6px;
    cursor:pointer;
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
        <h1>Appointment List</h1>
        <p>View calendar and list of all appointments</p>
    </div>

    <!-- Calendar -->
    <div id="calendar"></div>

    <!-- Buttons -->
    <a href="add.php" class="btn btn-create">+ Create Appointment</a>
    <a href="../dashboard/staff_dashboard.php" class="btn btn-back">⬅ Back to Dashboard</a>

    <!-- Appointment Table -->
    <table class="appointment-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Appointment No.</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Reason</th>
                <th>Date & Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $row['appointmentID'] ?></td>
                <td><?= $row['appointment_number'] ?></td>
                <td><?= $row['patient_name'] ?></td>
                <td><?= $row['doctor_name'] ?></td>
                <td><?= $row['reason_for_appointment'] ?></td>
                <td><?= $row['appointment_time'] ?></td>
                <td><?= $row['status'] ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['appointmentID'] ?>">Edit</a> |
                    <a href="../../backend/appointment/delete_action.php?id=<?= $row['appointmentID'] ?>" 
                        onclick="return confirm('Delete appointment?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

</div>

<script>
var appointments = <?php echo json_encode($appointments); ?>;

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        events: Object.keys(appointments).map(date => ({
            title: appointments[date].length + ' Appointment(s)',
            start: date,
            allDay: true,
            backgroundColor: '#7b6dff',
            borderColor: '#5e52d5'
        })),
        eventClick: function(info) {
            var date = info.event.startStr;
            var details = appointments[date].map(a => `${a.time} - ${a.patient} with ${a.doctor}`).join('<br>');
            var popup = document.createElement('div');
            popup.classList.add('app-popup');
            popup.innerHTML = details + `<br><button onclick="this.parentElement.remove()">Close</button>`;
            document.body.appendChild(popup);
        }
    });
    calendar.render();
});
</script>

</body>
</html>
