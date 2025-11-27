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
// TODAY APPOINTMENTS
// ==========================
$q_today_list = mysqli_query($conn, "
    SELECT Appointment.*, Patient.full_name, Patient.gender
    FROM Appointment
    JOIN Patient ON Appointment.patientID = Patient.patientID
    WHERE doctorID = $doctorID
    AND DATE(appointment_time) = '$today'
    ORDER BY appointment_time ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Doctor Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/2d3e5b1abc.js" crossorigin="anonymous"></script>

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

.flex-container { display:flex; gap:20px; flex-wrap:wrap; margin-bottom:30px; }

.split { display:flex; gap:20px; flex-wrap:wrap; margin-top:20px; }
.left { flex:1; min-width:300px; }
.right { flex:1; min-width:300px; background:white; padding:20px; border-radius:15px; box-shadow:0 4px 12px rgba(0,0,0,0.08); max-height:500px; overflow-y:auto; }

table { width:100%; border-collapse:collapse; margin-top:10px; }
th { background:#8ab6ff; color:white; padding:12px; font-size:14px; text-align:left; }
td { padding:10px 12px; border-bottom:1px solid #ddd; font-size:14px; }
tr:hover { background:#f0f6ff; }

.action-btn a { display:inline-block; padding:6px 12px; border-radius:8px; text-decoration:none; color:white; margin-right:5px; font-size:13px; }
.view-btn { background:#7b6dff; }
.view-btn:hover { background:#5e52d5; }

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

    <div class="flex-container">
        <div class="card" onclick="showTodayAppointments()">
            <h3>Total Appointments Today</h3>
            <p><?= $today_count ?></p>
        </div>
        <div class="card">
            <h3>Next Appointment</h3>
            <?php if ($next_appt) { ?>
                <p><?= $next_appt['full_name'] ?></p>
                <p><?= date("d M Y, h:i A", strtotime($next_appt['appointment_time'])) ?></p>
            <?php } else { ?>
                <p>No upcoming appointments</p>
            <?php } ?>
        </div>
    </div>

    <!-- Split Section -->
    <div class="split">
        <!-- Left: Today Appointments List -->
        <div class="left" id="today-appointments" style="display:none;">
            <h3>Today's Appointments</h3>
            <?php if (mysqli_num_rows($q_today_list) == 0) { ?>
                <p>No appointments today.</p>
            <?php } else { ?>
                <table>
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($q_today_list)) { ?>
                            <tr>
                                <td><?= $row['full_name'] ?></td>
                                <td><?= date('h:i A', strtotime($row['appointment_time'])) ?></td>
                                <td class="action-btn">
                                    <a href="#" class="view-btn" onclick="showPatientDetail(<?= $row['appointmentID'] ?>)">View</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>

        <!-- Right: Patient Detail -->
        <div class="right" id="patient-detail">
            <h3>Patient Detail</h3>
            <p>Select an appointment to view details here.</p>
        </div>
    </div>
</div>

<script>
function showTodayAppointments(){
    var el = document.getElementById('today-appointments');
    if(el.style.display === 'none') el.style.display = 'block';
    else el.style.display = 'none';
}

function showPatientDetail(id){
    // AJAX fetch for appointment detail
    fetch('../../backend/appointment/get_appointment_detail.php?id='+id)
    .then(response => response.json())
    .then(data => {
        var detailDiv = document.getElementById('patient-detail');
        detailDiv.innerHTML = `
            <h3>${data.patient_name}</h3>
            <p><strong>Gender:</strong> ${data.patient_gender}</p>
            <p><strong>Reason:</strong> ${data.reason_for_appointment}</p>
            <p><strong>Date & Time:</strong> ${new Date(data.appointment_time).toLocaleString()}</p>
            <p><strong>Status:</strong> ${data.status}</p>
            <hr>
            <h4>Comments</h4>
            <textarea id="comment-box" style="width:100%;height:80px;"></textarea>
            <button onclick="submitComment(${id})" style="margin-top:10px;padding:8px 12px;background:#7b6dff;color:white;border:none;border-radius:6px;cursor:pointer;">Submit Comment</button>
        `;
    });
}

function submitComment(id){
    var comment = document.getElementById('comment-box').value;
    if(comment.trim() === '') return alert('Comment cannot be empty');
    
    fetch('../../backend/appointment/add_comment.php', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({appointmentID:id, comment:comment})
    })
    .then(res=>res.json())
    .then(resp=>{
        if(resp.status==='success'){
            alert('Comment submitted');
            document.getElementById('comment-box').value = '';
        }else{
            alert('Error submitting comment');
        }
    });
}
</script>

</body>
</html>
