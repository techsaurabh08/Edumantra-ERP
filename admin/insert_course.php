<?php
session_start();
if (!isset($_SESSION['admin'])) {
    // Not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

include '../config/db.php';

$course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
$course_code = mysqli_real_escape_string($conn, $_POST['course_code']);

$query = "INSERT INTO courses (course_name, course_code)
VALUES ('$course_name', '$course_code')";

if (mysqli_query($conn, $query)) {
    echo "<script>
    alert('✅ Course Added Successfully');
    window.location='dashboard.php';
    </script>";
} else {
    echo "<script>
    alert('❌ Error adding course');
    window.location='dashboard.php';
    </script>";
}
?>
