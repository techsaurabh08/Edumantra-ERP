<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['faculty'])) {
    header("Location: faculty_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // sanitize input
    $fid = $_SESSION['faculty'];

    // Ensure the assignment belongs to the logged-in faculty
    $check = mysqli_query($conn, "SELECT * FROM assignments WHERE id=$id AND faculty_id=$fid");
    if (mysqli_num_rows($check) > 0) {
        $delete = mysqli_query($conn, "DELETE FROM assignments WHERE id=$id AND faculty_id=$fid");
        if ($delete) {
            echo "<script>
            alert('✅ Assignment deleted successfully');
            window.location='faculty_dashboard.php?page=view_assignment';
            </script>";
        } else {
            echo "<script>
            alert('❌ Error deleting assignment');
            window.location='faculty_dashboard.php?page=view_assignment';
            </script>";
        }
    } else {
        echo "<script>
        alert('❌ Assignment not found or unauthorized');
        window.location='faculty_dashboard.php?page=view_assignment';
        </script>";
    }
} else {
    echo "<script>
    alert('❌ Invalid request');
    window.location='faculty_dashboard.php?page=view_assignment';
    </script>";
}
?>
