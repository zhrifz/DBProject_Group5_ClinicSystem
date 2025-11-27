<?php
include "../../auth/check_login.php";
include "../../config/db.php";

if ($_SESSION['role'] != "doctor") die(json_encode(['status'=>'error','msg'=>'Access denied']));

$data = json_decode(file_get_contents("php://input"), true);
$appointmentID = intval($data['appointmentID']);
$comment = trim($data['comment']);
$doctorID = $_SESSION['id'];

if(empty($comment)) die(json_encode(['status'=>'error','msg'=>'Comment empty']));

$sql = "UPDATE Appointment SET comments = ? WHERE appointmentID = ? AND doctorID = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sii", $comment, $appointmentID, $doctorID);

if(mysqli_stmt_execute($stmt)){
    echo json_encode(['status'=>'success']);
}else{
    echo json_encode(['status'=>'error','msg'=>'DB error']);
}
