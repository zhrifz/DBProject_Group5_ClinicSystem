<?php
include "../../auth/check_login.php";
include "../../config/db.php";

if ($_SESSION['role'] != "staff") {
    die("Access denied.");
}

$id = $_GET['id'];

// Get patient info
$sql = "SELECT * FROM Patient WHERE patientID = $id";
$result = mysqli_query($conn, $sql);
$patient = mysqli_fetch_assoc($result);

if (!$patient) {
    die("Patient not found!");
}

// Handle update
if (isset($_POST['update'])) {

    $name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $dob = $_POST['date_of_birth'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $emergency = $_POST['emergency_contact'];
    $age = $_POST['age'];

    $update = "UPDATE Patient SET
                full_name='$name',
                age='$age',
                gender='$gender',
                date_of_birth='$dob',
                phone='$phone',
                address='$address',
                emergency_contact='$emergency'
               WHERE patientID=$id";

   if (mysqli_query($conn, $update)) {
    header("Location: ../../frontend/patient/list.php");
    exit;
} else {
    echo "Error: " . mysqli_error($conn);
}

}
?>


