<?php
session_start();
if (!isset($_SESSION['admin'])) {
    // Not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

include '../config/db.php';

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM students WHERE id=$id"));
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Student</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h3>Edit Student</h3>

<form action="update_student.php" method="POST">

<input type="hidden" name="id" value="<?= $data['id'] ?>">

<!-- Name -->
<input type="text" name="student_name" value="<?= $data['student_name'] ?>" class="form-control mb-2" placeholder="Name" required>

<!-- Student ID -->
<input type="text" name="student_id" value="<?= $data['student_id'] ?>" class="form-control mb-2" placeholder="Student ID" required>

<!-- Email -->
<input type="email" name="student_email" value="<?= $data['student_email'] ?>" class="form-control mb-2" placeholder="Email" required>

<!-- Password (optional, leave blank to keep old) -->
<input type="password" name="student_password" class="form-control mb-2" placeholder="New Password (leave blank if unchanged)">

<!-- Course Dropdown -->
<select name="course_id" class="form-control mb-2" required>
    <option value="">-- Select Course --</option>
    <?php
    $c = mysqli_query($conn, "SELECT * FROM courses");
    while ($row = mysqli_fetch_assoc($c)) {
        $sel = ($row['id'] == $data['course_id']) ? "selected" : "";
        echo "<option value='{$row['id']}' $sel>{$row['course_name']}</option>";
    }
    ?>
</select>

<!-- Department Dropdown -->
<select name="department_id" class="form-control mb-2" required>
    <option value="">-- Select Department --</option>
    <?php
    $d = mysqli_query($conn, "SELECT * FROM departments");
    while ($row = mysqli_fetch_assoc($d)) {
        $sel = ($row['id'] == $data['department_id']) ? "selected" : "";
        echo "<option value='{$row['id']}' $sel>{$row['dept_name']}</option>";
    }
    ?>
</select>

<button class="btn btn-success">Update</button>
<a href="dashboard.php?page=view_student" class="btn btn-secondary">Back</a>

</form>
</body>
</html>
