<?php
session_start();
if (!isset($_SESSION['admin'])) {
    // Not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

include '../config/db.php';

$id = $_POST['id'];
$name = $_POST['course_name'];
$code = $_POST['course_code'];

if (mysqli_query($conn, "UPDATE courses SET course_name='$name', course_code='$code' WHERE id=$id")) {
    echo "<script>alert('✏️ Course Updated'); window.location='dashboard.php?page=view_course';</script>";
} else {
    echo "<script>alert('❌ Error'); window.location='dashboard.php?page=view_course';</script>";
}
?>
