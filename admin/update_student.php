<?php
session_start();
if (!isset($_SESSION['admin'])) {
    // Not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

include '../config/db.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;  // ensure numeric
$name = mysqli_real_escape_string($conn, $_POST['student_name'] ?? '');
$student_id = mysqli_real_escape_string($conn, $_POST['student_id'] ?? '');
$email = mysqli_real_escape_string($conn, $_POST['student_email'] ?? '');
$course = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;
$dept = isset($_POST['department_id']) ? (int)$_POST['department_id'] : 0;
$password = $_POST['student_password'] ?? ''; // optional

// If password is entered, hash it; otherwise, don't change
$pass_sql = '';
if (!empty($password)) {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $pass_sql = ", student_password='$hashed'";
}

// Prevent updating if ID is invalid
if ($id <= 0) {
    echo "<script>alert('❌ Invalid Student ID'); window.location='dashboard.php?page=view_student';</script>";
    exit();
}

$sql = "UPDATE students 
        SET student_name='$name', student_id='$student_id', student_email='$email', course_id=$course, department_id=$dept $pass_sql
        WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('✏️ Student Updated'); window.location='dashboard.php?page=view_student';</script>";
} else {
    echo "<script>alert('❌ Error: " . mysqli_error($conn) . "'); window.location='dashboard.php?page=view_student';</script>";
}
?>
