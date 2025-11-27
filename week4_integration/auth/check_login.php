<?php
session_start();

// Kalau belum login, redirect ke login form
if (!isset($_SESSION['role'])) {
    header("Location: ../auth/login.php");
    exit;
}
