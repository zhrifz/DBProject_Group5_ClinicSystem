<?php
include "../../auth/check_login.php";
include "../../config/db.php";

if ($_SESSION['role'] != "staff") {
    die("Access denied.");
}

$id = $_GET['id'];

if (isset($_POST['update'])) {

    $username = $_POST['username'];
    $fullname = $_POST['full_name'];
    $special = $_POST['specialization'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    // Checkbox → string
    $days = isset($_POST['working_days']) ? implode(", ", $_POST['working_days']) : "";

    $room = $_POST['room_no'];

    // Password
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $pass_sql = ", password='$password'";
    } else {
        $pass_sql = "";
    }

    $update = "UPDATE Doctor SET
                username='$username',
                full_name='$fullname',
                specialization='$special',
                phone='$phone',
                email='$email',
                working_days='$days',
                room_no='$room'
                $pass_sql
               WHERE doctorID=$id";

    if (mysqli_query($conn, $update)) {
        header("Location: ../../frontend/doctor/list.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
