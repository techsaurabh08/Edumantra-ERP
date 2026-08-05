<?php
session_start();
include '../config/db.php';

$error = "";
$success = "";

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if faculty exists
    $query = mysqli_query($conn, "SELECT * FROM faculty WHERE faculty_email='$email'");

    if (mysqli_num_rows($query) == 1) {
        if ($new_password === $confirm_password) {
            $hashed = password_hash($new_password, PASSWORD_BCRYPT);
            mysqli_query($conn, "UPDATE faculty SET faculty_password='$hashed' WHERE faculty_email='$email'");
            $success = "✅ Password changed successfully. <a href='faculty_login.php'>Login now</a>";
        } else {
            $error = "❌ Passwords do not match.";
        }
    } else {
        $error = "❌ Email not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Faculty Forgot Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: #f5f7fa;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.login-card {
    background: white;
    padding: 35px 30px;
    border-radius: 12px;
    box-shadow: 0 6px 25px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
}
.login-card h4 {
    margin-bottom: 25px;
    text-align: center;
}
.toggle-password {
    cursor: pointer;
    position: absolute;
    right: 15px;
    top: 10px;
    color: #6c757d;
}
</style>
</head>
<body>

<div class="login-card position-relative">
    <h4>🔑 Reset Password</h4>

    <?php if($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <?php if($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <?php if(!$success): ?>
    <form method="POST">
        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <div class="mb-3 position-relative">
            <input type="password" name="password" class="form-control" placeholder="New password" id="password" required>
            <span class="toggle-password" onclick="togglePassword()">👁️</span>
        </div>
        <div class="mb-3 position-relative">
            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password" id="confirm_password" required>
            <span class="toggle-password" onclick="toggleConfirmPassword()">👁️</span>
        </div>
        <button type="submit" name="submit" class="btn btn-primary w-100">Set Password</button>
    </form>
    <?php endif; ?>
</div>

<script>
function togglePassword() {
    const input = document.getElementById("password");
    input.type = input.type === "password" ? "text" : "password";
}
function toggleConfirmPassword() {
    const input = document.getElementById("confirm_password");
    input.type = input.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
