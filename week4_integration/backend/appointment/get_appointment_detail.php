<?php
include "../../auth/check_login.php";
include "../../config/db.php";

if ($_SESSION['role'] != "doctor") die("Access denied.");

if(!isset($_GET['id'])) die(json_encode(['error'=>'No ID']));

$id = intval($_GET['id']);
$doctorID = $_SESSION['id'];

$sql = "SELECT Appointment.*, Patient.full_name AS patient_name, Patient.gender AS patient_gender
        FROM Appointment
        JOIN Patient ON Appointment.patientID = Patient.patientID
        WHERE appointmentID = $id AND doctorID = $doctorID
        LIMIT 1";

$res = mysqli_query($conn, $sql);
if(mysqli_num_rows($res)==0) die(json_encode(['error'=>'Appointment not found']));

$row = mysqli_fetch_assoc($res);
echo json_encode($row);
