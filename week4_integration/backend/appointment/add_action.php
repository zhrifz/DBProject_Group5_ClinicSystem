<?php
include "../../auth/check_login.php";
include "../../config/db.php";

// only staff
if ($_SESSION['role'] != "staff") {
    die("Access denied.");
}


if (isset($_POST['submit'])) {

    $number = $_POST['appointment_number'];
    $reason = $_POST['reason_for_appointment'];
    $time = $_POST['appointment_time'];
    $doctorID = $_POST['doctorID'];
    $patientID = $_POST['patientID'];

    // default values (staff cannot touch this)
    $status = "Upcoming";
    $come = "Upcoming";
    $comment = "";

    $sql = "INSERT INTO Appointment 
            (appointment_number, reason_for_appointment, appointment_time, status, 
             patient_come_into_hospital, doctor_comment, doctorID, patientID)
            VALUES 
            ('$number', '$reason', '$time', '$status', '$come', '$comment', '$doctorID', '$patientID')";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../../frontend/appointment/list.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

