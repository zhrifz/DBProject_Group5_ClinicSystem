<?php
include "../../auth/check_login.php";
include "../../config/db.php";

if ($_SESSION['role'] != "doctor") {
    die("Access denied.");
}

$doctorID = $_SESSION['id'];
$today = date("Y-m-d");

// ==========================
// TOTAL APPOINTMENTS TODAY
// ==========================
$q_today = mysqli_query($conn, "
    SELECT COUNT(*) AS total_today 
    FROM Appointment 
    WHERE doctorID = $doctorID 
    AND DATE(appointment_time) = '$today'
");
$today_count = mysqli_fetch_assoc($q_today)['total_today'];

// ==========================
// NEXT UPCOMING APPOINTMENT
// ==========================
$q_next = mysqli_query($conn, "
    SELECT Appointment.*, Patient.full_name 
    FROM Appointment
    JOIN Patient ON Appointment.patientID = Patient.patientID
    WHERE doctorID = $doctorID
    AND appointment_time > NOW()
    ORDER BY appointment_time ASC
    LIMIT 1
");
$next_appt = mysqli_fetch_assoc($q_next);

// ==========================
// FETCH APPOINTMENTS FOR CALENDAR
// ==========================
$appointments = [];
$result = mysqli_query($conn, "
    SELECT Appointment.*, Patient.full_name AS patient_name
    FROM Appointment
    JOIN Patient ON Appointment.patientID = Patient.patientID
    WHERE doctorID = $doctorID
");

while($row = mysqli_fetch_assoc($result)){
    $date = date('Y-m-d', strtotime($row['appointment_time']));
    $time = date('H:i', strtotime($row['appointment_time']));
    if(!isset($appointments[$date])) $appointments[$date] = [];
    $appointments[$date][] = [
        'time' => $time,
        'patient' => $row['patient_name']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Doctor Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/2d3e5b1abc.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<style>
body { margin:0; font-family:Poppins,sans-serif; background:#f3f0ff; display:flex; }
.sidebar { width:250px; height:100vh; position:fixed; left:0; top:0; background:linear-gradient(180deg,#8ab6ff,#c7a3ff); padding:20px; color:white; }
.sidebar h2 { text-align:center; margin-bottom:30px; }
.sidebar a { display:block; padding:12px 15px; border-radius:10px; text-decoration:none; color:white; margin-bottom:10px; transition:0.2s ease; }
.sidebar a:hover { background: rgba(255,255,255,0.3); transform: translateX(5px); }

.main { margin-left:260px; padding:40px; width:calc(100% - 260px); }

.header { background:white; padding:25px 30px; border-radius:15px; box-shadow:0 3px 12px rgba(0,0,0,0.08); margin-bottom:30px; }
.header h1 { font-size:26px; font-weight:600; margin:0; }
.header p { margin-top:5px; color:#555; }

.card { background:white; padding:20px; border-radius:15px; box-shadow:0 4px 12px rgba(0,0,0,0.08); flex:1; min-width:200px; margin-bottom:20px; cursor:pointer; }
.card h3 { margin-top:0; font-size:16px; color:#333; }
.card p { font-size:20px; font-weight:600; margin:10px 0 0 0; }

#calendar { background:white; padding:15px; border-radius:15px; box-shadow:0 4px 12px rgba(0,0,0,0.08); margin-top:30px; }
.app-popup { position: fixed; top:50%; left:50%; transform: translate(-50%, -50%); background:white; padding:15px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.2); z-index:9999; max-width: 300px; max-height: 400px; overflow-y: auto; font-size:12px; line-height:1.3; }
.app-popup button { margin-top:10px; padding:8px 12px; border:none; background:#ff6b6b; color:white; border-radius:6px; cursor:pointer; }
</style>
</head>

<body>
<div class="sidebar">
    <h2>Doctor Panel</h2>
    <a href="doctor_dashboard.php"><i class="fa-solid fa-gauge"></i> Dashboard</a>

    <a href="../../backend/auth/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
</div>

<div class="main">
    <div class="header">
        <h1>Welcome Dr. <?= $_SESSION['name'] ?></h1>
        <p>Here's a summary of your appointments</p>
    </div>

    <div style="display:flex; gap:20px; flex-wrap:wrap; margin-bottom:30px;">
        <!-- Clickable card to today_appointments.php -->
        <a href="today_appointments.php" style="flex:1; min-width:200px; text-decoration:none;">
            <div class="card" style="background:#7b6dff; color:white;">
                <h3>Total Appointments Today</h3>
                <p><?= $today_count ?></p>
            </div>
        </a>

        <div class="card" style="background:#ff6b6b; color:white;">
            <h3>Next Appointment</h3>
            <?php if ($next_appt) { ?>
                <p><?= $next_appt['full_name'] ?></p>
                <p><?= date("d M Y, h:i A", strtotime($next_appt['appointment_time'])) ?></p>
            <?php } else { ?>
                <p>No upcoming appointments</p>
            <?php } ?>
        </div>
    </div>

    <!-- Full Calendar -->
    <div id="calendar"></div>
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
            var details = appointments[date].map(a => `${a.time} - ${a.patient}`).join('<br>');
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
