<?php
session_start();
if (!isset($_SESSION['admin'])) {
    // Not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

include '../config/db.php';

$id = $_GET['id'];

if (mysqli_query($conn, "DELETE FROM departments WHERE id=$id")) {
    echo "<script>
        alert('🗑️ Department Deleted Successfully');
        window.location='dashboard.php?page=view_dept';
    </script>";
} else {
    echo "<script>
        alert('❌ Error deleting department');
        window.location='dashboard.php?page=view_dept';
    </script>";
}
?>
