<?php
session_start();
if (!isset($_SESSION['admin'])) {
    // Not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

include '../config/db.php';

$id = $_POST['id'];
$name = $_POST['dept_name'];
$code = $_POST['dept_code'];
$course_id = $_POST['course_id'];

$query = "UPDATE departments 
SET dept_name='$name', dept_code='$code', course_id='$course_id' 
WHERE id=$id";

if (mysqli_query($conn, $query)) {
    echo "<script>
        alert('✏️ Department Updated Successfully');
        window.location='dashboard.php?page=view_dept';
    </script>";
} else {
    echo "<script>
        alert('❌ Error updating department');
        window.location='dashboard.php?page=view_dept';
    </script>";
}
?>
