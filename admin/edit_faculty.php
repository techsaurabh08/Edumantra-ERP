<?php
session_start();
if (!isset($_SESSION['admin'])) {
    // Not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

include '../config/db.php';

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM faculty WHERE id=$id"));
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Faculty</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h3>Edit Faculty</h3>

<form action="update_faculty.php" method="POST">

<input type="hidden" name="id" value="<?= $data['id'] ?>">

<!-- Name -->
<input type="text" name="faculty_name" value="<?= $data['faculty_name'] ?>" class="form-control mb-2" placeholder="Name" required>

<!-- Faculty ID -->
<input type="text" name="faculty_id" value="<?= $data['faculty_id'] ?>" class="form-control mb-2" placeholder="Faculty ID" required>

<!-- Email -->
<input type="email" name="faculty_email" value="<?= $data['faculty_email'] ?>" class="form-control mb-2" placeholder="Email" required>

<!-- Phone -->
<input type="text" name="phone" value="<?= $data['phone'] ?>" class="form-control mb-2" placeholder="Phone Number" required>

<!-- Password (optional, leave blank to keep old) -->
<input type="password" name="faculty_password" class="form-control mb-2" placeholder="New Password (leave blank if unchanged)">

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
<a href="dashboard.php?page=view_faculty" class="btn btn-secondary">Back</a>

</form>
</body>
</html>
