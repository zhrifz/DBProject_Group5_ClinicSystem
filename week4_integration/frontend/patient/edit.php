<?php
include "../../auth/check_login.php";
include "../../config/db.php";

if ($_SESSION['role'] != "staff") {
    die("Access denied.");
}

// check id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid patient ID");
}

$id = $_GET['id'];

$sql = "SELECT * FROM Patient WHERE patientID = $id";
$result = mysqli_query($conn, $sql);
$patient = mysqli_fetch_assoc($result);

if (!$patient) {
    die("Patient not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Patient</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/2d3e5b1abc.js" crossorigin="anonymous"></script>

<style>
body {
    margin: 0;
    font-family: Poppins, sans-serif;
    background: #f3f0ff;
    display: flex;
}

/* Sidebar */
.sidebar {
    width: 250px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    background: linear-gradient(180deg, #8ab6ff, #c7a3ff);
    padding: 20px;
    color: white;
}
.sidebar h2 { 
    text-align:center; 
    margin-bottom:30px; 
}
.sidebar a {
    display:block;
    padding:12px 15px;
    border-radius:10px;
    text-decoration:none;
    color:white;
    margin-bottom:10px;
    transition:0.2s ease;
}
.sidebar a:hover {
    background: rgba(255,255,255,0.3);
    transform: translateX(5px);
}

/* Main */
.main {
    margin-left: 260px;
    padding: 40px;
    width: calc(100% - 250px);
}

/* Header */
.header {
    background:white;
    padding:25px 30px;
    border-radius:15px;
    box-shadow:0 3px 12px rgba(0,0,0,0.08);
    margin-bottom:30px;
}

.header h1 {
    font-size: 26px;
    font-weight:600;
    margin:0;
}

/* Form Card */
.form-card {
    background: white;
    padding: 30px;
    border-radius: 18px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    max-width: 700px;
    margin: 0 auto;
}

.form-card label {
    font-weight:500;
    display:block;
    margin-top:10px;
}

.form-card input, 
.form-card select,
.form-card textarea {
    width:100%;
    padding:10px;
    border:1px solid #ccc;
    border-radius:10px;
    margin-top:5px;
    font-size:14px;
}

/* Button */
.btn-submit {
    background:#7b6dff;
    border:none;
    padding:12px;
    color:white;
    border-radius:10px;
    font-size:16px;
    cursor:pointer;
    transition:0.2s;
    width:100%;
    margin-top:20px;
}
.btn-submit:hover {
    background:#5e52d5;
}

.btn-back {
    background: #ff6b6b; /* merah cair */
    border:none;
    padding:12px;
    color:white;
    border-radius:10px;
    font-size:16px;
    cursor:pointer;
    transition:0.2s;
    width:97%;
    margin-top:10px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
}

.btn-back:hover {
    background: #e05555; /* lebih gelap sedikit bila hover */
}

</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>Clinic Admin</h2>
    <a href="../dashboard/staff_dashboard.php"><i class="fa-solid fa-gauge"></i> Dashboard</a>
    <a href="list.php"><i class="fa-solid fa-hospital-user"></i> Patients</a>
    <a href="../doctor/list.php"><i class="fa-solid fa-user-doctor"></i> Doctors</a>
    <a href="../appointment/list.php"><i class="fa-solid fa-calendar-check"></i> Appointments</a>
</div>

<!-- MAIN CONTENT -->
<div class="main">

    <div class="header">
        <h1>Edit Patient</h1>
    </div>

    <div class="form-card">

        <form method="POST" action="../../backend/patient/edit_action.php?id=<?= $id ?>">

            <label>Name</label>
            <input type="text" name="full_name" value="<?= $patient['full_name'] ?>" required>

            <label>Date of Birth</label>
            <input type="date" name="date_of_birth" id="dob" value="<?= $patient['date_of_birth'] ?>" required>

            <label>Age</label>
            <input type="number" name="age" id="age" value="<?= $patient['age'] ?>" readonly>

            <label>Gender</label>
            <select name="gender" required>
                <option <?= $patient['gender']=="Male" ? "selected" : "" ?>>Male</option>
                <option <?= $patient['gender']=="Female" ? "selected" : "" ?>>Female</option>
            </select>

            <label>Phone</label>
            <input type="text" name="phone" value="<?= $patient['phone'] ?>">

            <label>Address</label>
            <textarea name="address" rows="3"><?= $patient['address'] ?></textarea>

            <label>Emergency Contact</label>
            <input type="text" name="emergency_contact" value="<?= $patient['emergency_contact'] ?>">

            <button type="submit" name="update" class="btn-submit">Save</button>
        </form>

        <a href="list.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </div>

    </div>

</div>

<script>
// auto calculate age
document.getElementById("dob").addEventListener("change", function() {
    let dob = new Date(this.value);
    let today = new Date();
    if (!isNaN(dob)) {
        let age = today.getFullYear() - dob.getFullYear();
        let month = today.getMonth() - dob.getMonth();
        if (month < 0 || (month === 0 && today.getDate() < dob.getDate())) age--;
        document.getElementById("age").value = age;
    }
});
</script>

</body>
</html>
