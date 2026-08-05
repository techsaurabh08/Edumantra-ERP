<?php
session_start();
include '../config/db.php';

$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $message = "❌ Passwords do not match!";
    } else {
        // Check if admin exists
        $query = "SELECT * FROM admin WHERE username='$username'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE admin SET password='$hashed' WHERE username='$username'");
            $message = "✅ Password updated successfully!";
        } else {
            $message = "❌ Username not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Set Admin Password</title>
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
    padding: 40px 30px;
    border-radius: 12px;
    box-shadow: 0 6px 25px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
}
.login-card h3 {
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
    <h3>🔑 Set Admin Password</h3>

    <?php if($message != ""): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Enter Username" required>
        </div>

        <div class="mb-3 position-relative">
            <input type="password" name="new_password" class="form-control" placeholder="New Password" id="password1" required>
            <span class="toggle-password" onclick="togglePassword('password1')">👁️</span>
        </div>

        <div class="mb-3 position-relative">
            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" id="password2" required>
            <span class="toggle-password" onclick="togglePassword('password2')">👁️</span>
        </div>

        <button type="submit" class="btn btn-success w-100">Set Password</button>
    </form>

    <p class="text-center mt-3">
        Back to <a href="login.php">Login</a>
    </p>
</div>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
