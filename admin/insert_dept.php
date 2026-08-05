<?php
session_start();
if (!isset($_SESSION['admin'])) {
    // Not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

include '../config/db.php';

$dept_name = mysqli_real_escape_string($conn, $_POST['dept_name']);
$dept_code = mysqli_real_escape_string($conn, $_POST['dept_code']);
$course_id = $_POST['course_id'];

$query = "INSERT INTO departments (dept_name, dept_code, course_id)
VALUES ('$dept_name', '$dept_code', '$course_id')";

if (mysqli_query($conn, $query)) {
    echo "<script>
    alert('✅ Department Added Successfully');
    window.location='dashboard.php';
    </script>";
} else {
    echo "<script>
    alert('❌ Error: Department not added');
    window.location='dashboard.php';
    </script>";
}
?>
