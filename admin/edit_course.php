<?php
session_start();
if (!isset($_SESSION['admin'])) {
    // Not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

include '../config/db.php';

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM courses WHERE id=$id"));
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Course</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h3>Edit Course</h3>

<form action="update_course.php" method="POST">
<input type="hidden" name="id" value="<?= $data['id'] ?>">

<input type="text" name="course_name" value="<?= $data['course_name'] ?>" class="form-control mb-2">

<input type="text" name="course_code" value="<?= $data['course_code'] ?>" class="form-control mb-2">

<button class="btn btn-success">Update</button>
<a href="dashboard.php?page=view_course" class="btn btn-secondary">Back</a>
</form>

</body>
</html>
