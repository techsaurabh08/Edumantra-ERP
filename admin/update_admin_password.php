<?php
session_start();
include '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['admin'];

$current = $_POST['current_password'] ?? '';
$new = $_POST['new_password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

// Fetch admin's current password from DB
$res = mysqli_query($conn, "SELECT password FROM admin WHERE id=$admin_id");
$row = mysqli_fetch_assoc($res);

if (!$row) {
    echo "<script>
        alert('Admin not found!');
        window.location='admin_dashboard.php?page=change_password';
    </script>";
    exit();
}

// Verify current password
if (!password_verify($current, $row['password'])) {
    echo "<script>
        alert('Current password is incorrect!');
        window.location='admin_dashboard.php?page=change_password';
    </script>";
    exit();
}

// Check new password confirmation
if ($new !== $confirm) {
    echo "<script>
        alert('New password and confirmation do not match!');
        window.location='admin_dashboard.php?page=change_password';
    </script>";
    exit();
}

// Hash new password and update
$new_hashed = password_hash($new, PASSWORD_DEFAULT);
$update = mysqli_query($conn, "UPDATE admin SET password='$new_hashed' WHERE id=$admin_id");

if ($update) {
    echo "<script>
        alert('Password updated successfully!');
        window.location='admin_dashboard.php?page=change_password';
    </script>";
} else {
    echo "<script>
        alert('Database error! Please try again.');
        window.location='admin_dashboard.php?page=change_password';
    </script>";
}
?>
