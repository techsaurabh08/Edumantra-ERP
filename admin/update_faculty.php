<?php
session_start();
if (!isset($_SESSION['admin'])) {
    // Not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

include '../config/db.php';

$id = (int)$_POST['id'];
$name = mysqli_real_escape_string($conn, $_POST['faculty_name']);
$faculty_id = mysqli_real_escape_string($conn, $_POST['faculty_id']);
$email = mysqli_real_escape_string($conn, $_POST['faculty_email']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$dept = (int)$_POST['department_id'];
$password = $_POST['faculty_password']; // optional

// If password is entered, hash it; otherwise, don't change
$pass_sql = '';
if (!empty($password)) {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $pass_sql = ", faculty_password='$hashed'";
}

$sql = "UPDATE faculty 
        SET faculty_name='$name', faculty_id='$faculty_id', faculty_email='$email', phone='$phone', department_id=$dept $pass_sql
        WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('✏️ Faculty Updated'); window.location='dashboard.php?page=view_faculty';</script>";
} else {
    echo "<script>alert('❌ Error: " . mysqli_error($conn) . "'); window.location='dashboard.php?page=view_faculty';</script>";
}
?>
