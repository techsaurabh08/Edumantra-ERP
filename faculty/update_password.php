<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['faculty'])) {
    header("Location: faculty_login.php");
    exit();
}

$id = $_SESSION['faculty'];

$current = $_POST['current_password'];
$new = $_POST['new_password'];
$confirm = $_POST['confirm_password'];

// Fetch current hashed password
$check = mysqli_fetch_assoc(mysqli_query($conn, "SELECT faculty_password FROM faculty WHERE id=$id"));

if (password_verify($current, $check['faculty_password'])) {
    if ($new === $confirm) {
        $new_hashed = password_hash($new, PASSWORD_DEFAULT);
        $update = mysqli_query($conn, "UPDATE faculty SET faculty_password='$new_hashed' WHERE id=$id");
        if ($update) {
            echo "<script>alert('✅ Password updated successfully'); window.location='faculty_dashboard.php';</script>";
        } else {
            echo "<script>alert('❌ Error updating password'); window.location='faculty_dashboard.php?page=change_password';</script>";
        }
    } else {
        echo "<script>alert('❌ New password and confirm password do not match'); window.location='faculty_dashboard.php?page=change_password';</script>";
    }
} else {
    echo "<script>alert('❌ Current password is incorrect'); window.location='faculty_dashboard.php?page=change_password';</script>";
}
?>
