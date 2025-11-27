<?php
include "../../auth/check_login.php";
include "../../config/db.php";

// only staff
if ($_SESSION['role'] != "staff") {
    die("Access denied.");
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid appointment ID");
}

$id = $_GET['id'];

$sql = "DELETE FROM Appointment WHERE appointmentID = $id";

if (mysqli_query($conn, $sql)) {
    header("Location: ../../frontend/appointment/list.php");
    exit;
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
