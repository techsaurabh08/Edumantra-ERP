<?php
session_start();
if (!isset($_SESSION['admin'])) {
    // Not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

include '../config/db.php';

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM departments WHERE id=$id"));
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Department</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h3>Edit Department</h3>

<form action="update_dept.php" method="POST">

<input type="hidden" name="id" value="<?= $data['id'] ?>">

<input type="text" name="dept_name" value="<?= $data['dept_name'] ?>" class="form-control mb-2">

<input type="text" name="dept_code" value="<?= $data['dept_code'] ?>" class="form-control mb-2">

<select name="course_id" class="form-control mb-2">
<?php
$c = mysqli_query($conn, "SELECT * FROM courses");
while ($row = mysqli_fetch_assoc($c)) {
    $sel = ($row['id'] == $data['course_id']) ? "selected" : "";
    echo "<option value='{$row['id']}' $sel>{$row['course_name']}</option>";
}
?>
</select>

<button class="btn btn-success">Update</button>
<a href="dashboard.php?page=view_dept" class="btn btn-secondary">Back</a>

</form>
</body>
</html>
