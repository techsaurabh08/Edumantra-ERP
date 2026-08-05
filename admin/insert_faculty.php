<?php
session_start();
if (!isset($_SESSION['admin'])) {
    // Not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

include '../config/db.php';

$name = mysqli_real_escape_string($conn, $_POST['faculty_name']);
$fid = mysqli_real_escape_string($conn, $_POST['faculty_id']);
$email = mysqli_real_escape_string($conn, $_POST['faculty_email']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$department_id = mysqli_real_escape_string($conn, $_POST['department_id']);

$password = password_hash($_POST['faculty_password'], PASSWORD_DEFAULT);

$query = "INSERT INTO faculty 
(faculty_name, faculty_id, faculty_email, phone, department_id, faculty_password)
VALUES 
('$name', '$fid', '$email', '$phone', '$department_id', '$password')";

if (mysqli_query($conn, $query)) {
    echo "<script>
    alert('✅ Faculty Added Successfully');
    window.location='dashboard.php?page=view_faculty';
    </script>";
} else {
    echo "<script>
    alert('❌ Error adding faculty');
    window.location='dashboard.php?page=add_faculty';
    </script>";
}
?>
