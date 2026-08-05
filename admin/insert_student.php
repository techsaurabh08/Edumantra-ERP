<?php
session_start();
if (!isset($_SESSION['admin'])) {
    // Not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

include '../config/db.php';

$name = mysqli_real_escape_string($conn, $_POST['student_name']);
$student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
$email = mysqli_real_escape_string($conn, $_POST['student_email']);

$course_id = mysqli_real_escape_string($conn, $_POST['course_id']);
$department_id = mysqli_real_escape_string($conn, $_POST['department_id']);

$password = password_hash($_POST['student_password'], PASSWORD_DEFAULT);

$query = "INSERT INTO students 
(student_name, student_id, student_email, course_id, department_id, student_password)
VALUES 
('$name', '$student_id', '$email', '$course_id', '$department_id', '$password')";

if (mysqli_query($conn, $query)) {
    echo "<script>
    alert('✅ Student Added Successfully');
    window.location='dashboard.php?page=view_student';
    </script>";
} else {
    echo "<script>
    alert('❌ Error adding student');
    window.location='dashboard.php?page=add_student';
    </script>";
}
?>
