<?php
session_start();
if (!isset($_SESSION['admin'])) {
    // Not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

include '../config/db.php';

$id = intval($_GET['id']);

if (mysqli_query($conn, "DELETE FROM faculty WHERE id=$id")) {
    echo "<script>alert('🗑️ Faculty Deleted'); window.location='dashboard.php?page=view_faculty';</script>";
} else {
    echo "<script>alert('❌ Error'); window.location='dashboard.php?page=view_faculty';</script>";
}
?>
