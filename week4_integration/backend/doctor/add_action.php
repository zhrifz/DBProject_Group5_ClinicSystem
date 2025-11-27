<?php
include "../../auth/check_login.php";
include "../../config/db.php";

// only staff can view this
if ($_SESSION['role'] != "staff") {
    die("Access denied.");
}

if (isset($_POST['submit'])) {

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $fullname = $_POST['full_name'];
    $special = $_POST['specialization'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    // Convert checkbox array → string
    $days = isset($_POST['working_days']) ? implode(", ", $_POST['working_days']) : "";

    $room = $_POST['room_no'];

    $sql = "INSERT INTO Doctor 
            (username, password, full_name, specialization, phone, email, working_days, room_no)
            VALUES 
            ('$username', '$password', '$fullname', '$special', '$phone', '$email', '$days', '$room')";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../../frontend/doctor/list.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

