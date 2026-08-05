<?php
session_start();
include '../config/db.php';

$error = "";
$success = "";

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if email exists
    $query = mysqli_query($conn, "SELECT * FROM students WHERE student_email='$email' AND status=1");

    if (mysqli_num_rows($query) == 1) {
        if ($new_password === $confirm_password) {
            $hashed = password_hash($new_password, PASSWORD_BCRYPT);
            mysqli_query($conn, "UPDATE students SET student_password='$hashed' WHERE student_email='$email'");
            $success = "✅ Password changed successfully. <a href='student_login.php'>Login now</a>";
        } else {
            $error = "❌ Passwords do not match.";
        }
    } else {
        $error = "❌ Email not found or inactive.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Set New Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width: 500px;">
    <h3 class="text-center mb-4">Set New Password</h3>

    <?php if($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <?php if($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="New password" required>
        </div>
        <div class="mb-3">
            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary w-100">Set Password</button>
    </form>
</div>
</body>
</html>
