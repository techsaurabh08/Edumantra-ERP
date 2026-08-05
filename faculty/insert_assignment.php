<?php
session_start();
include '../config/db.php';

/* SECURITY CHECK */
if (!isset($_SESSION['faculty'])) {
    header("Location: faculty_login.php");
    exit();
}

$title = mysqli_real_escape_string($conn, $_POST['title']);
$desc = mysqli_real_escape_string($conn, $_POST['description']);
$subject = mysqli_real_escape_string($conn, $_POST['subject']);
$due = $_POST['due_date'];

$faculty_id = $_SESSION['faculty'];

/* INSERT QUERY */
$query = "INSERT INTO assignments (title, description, subject, due_date, faculty_id)
VALUES ('$title', '$desc', '$subject', '$due', '$faculty_id')";

if (mysqli_query($conn, $query)) {
    echo "<script>
        alert('✅ Assignment Added Successfully');
        window.location='faculty_dashboard.php?page=view_assignment';
    </script>";
} else {
    echo "<script>
        alert('❌ Error adding assignment');
        window.location='faculty_dashboard.php?page=add_assignment';
    </script>";
}
?>
