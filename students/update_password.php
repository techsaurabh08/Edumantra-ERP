<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['student'])) {
    header("Location: student_login.php");
    exit();
}

$student_id = $_SESSION['student'];

// Collect form inputs
$current = $_POST['current_password'] ?? '';
$new = $_POST['new_password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

// Check if new password matches confirm password
if ($new !== $confirm) {
    echo "<script>alert('New password and confirm password do not match.'); window.history.back();</script>";
    exit();
}

// Fetch current password from DB
$result = mysqli_query($conn, "SELECT student_password FROM students WHERE id=$student_id");
$row = mysqli_fetch_assoc($result);

// Verify current password
if (!password_verify($current, $row['student_password'])) {
    echo "<script>alert('Current password is incorrect.'); window.history.back();</script>";
    exit();
}

// Hash new password
$new_hash = password_hash($new, PASSWORD_DEFAULT);

// Update DB
mysqli_query($conn, "UPDATE students SET student_password='$new_hash' WHERE id=$student_id");

echo "<script>alert('Password updated successfully.'); window.location='student_dashboard.php?page=change_password';</script>";
