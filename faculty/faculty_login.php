<?php
session_start();
include '../config/db.php';

/* LOGIN PROCESS */
if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM faculty WHERE faculty_email='$email' AND status=1");
    $user = mysqli_fetch_assoc($query);

    if ($user && password_verify($password, $user['faculty_password'])) {

        $_SESSION['faculty'] = $user['id'];
        $_SESSION['faculty_name'] = $user['faculty_name'];

        echo "<script>
            alert('✅ Login Successful');
            window.location='faculty_dashboard.php';
        </script>";

    } else {
        echo "<script>alert('❌ Invalid Login');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Faculty Login</title>
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
    <h4>👨‍🏫 Faculty Login</h4>

    <form method="POST">
        <div class="mb-3 position-relative">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>

        <div class="mb-3 position-relative">
            <input type="password" name="password" class="form-control" placeholder="Password" id="password" required>
            <span class="toggle-password" onclick="togglePassword()">👁️</span>
        </div>

        <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
    </form>

    <p class="text-center mt-3"><a href="faculty_forgot_password.php">Forgot Password?</a></p>
</div>

<script>
function togglePassword() {
    const input = document.getElementById("password");
    input.type = input.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
