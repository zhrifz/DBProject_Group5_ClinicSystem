<?php
session_start();

// Path database (2 levels up)
include __DIR__ . '/../../config/db.php';

$login_success = false;

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // ---- CHECK STAFF ----
    $stmt = $conn->prepare("SELECT staffID, full_name, password FROM Staff WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $staff_res = $stmt->get_result();

    if ($staff_res->num_rows === 1) {
        $staff = $staff_res->fetch_assoc();

        if (password_verify($password, $staff['password'])) {

            $_SESSION['role'] = "staff";
            $_SESSION['id'] = $staff['staffID'];
            $_SESSION['name'] = $staff['full_name'];

            header("Location: ../../frontend/dashboard/staff_dashboard.php");
            exit;
        }
    }

    // ---- CHECK DOCTOR ----
    $stmt = $conn->prepare("SELECT doctorID, full_name, password FROM Doctor WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $doc_res = $stmt->get_result();

    if ($doc_res->num_rows === 1) {
        $doctor = $doc_res->fetch_assoc();

        if (password_verify($password, $doctor['password'])) {

            $_SESSION['role'] = "doctor";
            $_SESSION['id'] = $doctor['doctorID'];
            $_SESSION['name'] = $doctor['full_name'];

            header("Location: ../../frontend/dashboard/doctor_dashboard.php");
            exit;
        }
    }

    // ---- Login gagal ----
    $_SESSION['login_error'] = "Incorrect username or password!";
    header("Location: ../../auth/login.php");
    exit;
}
