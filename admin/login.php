<?php
session_start();
include '../config/db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM admin WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {

        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {

            $_SESSION['admin'] = $username;
            header("Location: dashboard.php");
            exit();

        } else {
            $error = "❌ Wrong Password!";
        }

    } else {
        $error = "❌ Username not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login</title>
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
    <h3>🔐 Admin Login</h3>

    <?php if($error != ""): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3 position-relative">
            <input type="text" name="username" class="form-control" placeholder="Enter Username" required>
        </div>

        <div class="mb-3 position-relative">
            <input type="password" name="password" class="form-control" placeholder="Enter Password" id="password" required>
            <span class="toggle-password" onclick="togglePassword()">👁️</span>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
<p class="text-center mt-3">
    <a href="set_admin_password.php">Forget Password?</a>
</p>

</div>

<script>
function togglePassword() {
    const input = document.getElementById("password");
    input.type = input.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
