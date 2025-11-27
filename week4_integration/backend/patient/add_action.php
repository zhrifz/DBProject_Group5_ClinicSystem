<?php

include "../../auth/check_login.php";
include "../../config/db.php";

if ($_SESSION['role'] != "staff") {
    die("Access denied.");
}

// Check jika form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $dob = $_POST['date_of_birth'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $emergency = $_POST['emergency_contact'];
    $age = $_POST['age'];

    $sql = "INSERT INTO Patient 
            (full_name, age, gender, date_of_birth, phone, address, emergency_contact)
            VALUES 
            ('$name', '$age', '$gender', '$dob', '$phone', '$address', '$emergency')";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../../frontend/patient/list.php?added=1");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

?>
