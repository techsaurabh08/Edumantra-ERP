<?php
session_start();
include '../config/db.php';


$error = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Fetch student by email and active status
    $query = mysqli_query($conn, "SELECT * FROM students WHERE student_email='$email' AND status=1");

    if (mysqli_num_rows($query) == 1) {
        $row = mysqli_fetch_assoc($query);
        if (password_verify($password, $row['student_password'])) {
            // Login success
            $_SESSION['student'] = $row['id'];
            header("Location: student_dashboard.php");
            exit();
        } else {
            $error = "❌ Invalid password";
        }
    } else {
        $error = "❌ Email not found or inactive";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Student Login</title>
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
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
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
    <h3>🎓 Student Login</h3>

    <?php if($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

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

    <p class="text-center mt-3"><a href="forgot_password.php">Forgot Password?</a></p>
</div>

<script>
function togglePassword() {
    var input = document.getElementById("password");
    input.type = input.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
