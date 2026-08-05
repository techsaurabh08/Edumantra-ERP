<?php
session_start();
if (!isset($_SESSION['admin'])) {
    // Not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

include '../config/db.php';

$id = intval($_GET['id']);

if (mysqli_query($conn, "DELETE FROM students WHERE id=$id")) {
    echo "<script>alert('🗑️ Student Deleted'); window.location='dashboard.php?page=view_student';</script>";
} else {
    echo "<script>alert('❌ Error'); window.location='dashboard.php?page=view_student';</script>";
}
?>
