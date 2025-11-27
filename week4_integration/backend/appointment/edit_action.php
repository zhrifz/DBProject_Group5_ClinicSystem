<?php
include "../../auth/check_login.php";
include "../../config/db.php";

// only staff can edit
if ($_SESSION['role'] != "staff") {
    die("Access denied.");
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid appointment ID");
}

$id = $_GET['id'];

// Fetch appointment data
$sql = "SELECT * FROM Appointment WHERE appointmentID = $id";
$result = mysqli_query($conn, $sql);
$appt = mysqli_fetch_assoc($result);

if (!$appt) {
    die("Appointment not found.");
}

// Only run if form submitted
if (isset($_POST['update'])) {

    $number    = $_POST['appointment_number'];
    $reason    = $_POST['reason_for_appointment'];
    $time      = $_POST['appointment_time'];
    $status    = $_POST['status'];
    $come      = $_POST['patient_come_into_hospital'];

    // STAFF CANNOT CHANGE DOCTOR COMMENT
    $comment   = $appt['doctor_comment'];

    $doctorID  = $_POST['doctorID'];
    $patientID = $_POST['patientID'];

    // Update query
    $update = "UPDATE Appointment SET
                appointment_number='$number',
                reason_for_appointment='$reason',
                appointment_time='$time',
                status='$status',
                patient_come_into_hospital='$come',
                doctor_comment='$comment',
                doctorID='$doctorID',
                patientID='$patientID'
               WHERE appointmentID=$id";

    if (mysqli_query($conn, $update)) {
        header("Location: ../../frontend/appointment/list.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
