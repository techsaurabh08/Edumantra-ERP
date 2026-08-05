<?php
include '../config/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check password match
    if ($password != $confirm_password) {
        $message = "❌ Password does not match!";
    } else {

        // Check username exists
        $check = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username'");

        if (mysqli_num_rows($check) > 0) {
            $message = "❌ Username already exists!";
        } else {

            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert data
            $query = "INSERT INTO admin (username, password)
                      VALUES ('$username', '$hashed_password')";

            if (mysqli_query($conn, $query)) {
                $message = "✅ Registration Successful!";
            } else {
                $message = "❌ Error occurred!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4 w-50 mx-auto">

        <h3 class="text-center mb-4">📝 Admin Register</h3>

        <?php if($message != "") { ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php } ?>

        <form method="POST">

            <input type="text" name="username"
                   placeholder="Enter Username"
                   class="form-control mb-3" required>

            <input type="password" name="password"
                   placeholder="Enter Password"
                   class="form-control mb-3" required>

            <input type="password" name="confirm_password"
                   placeholder="Confirm Password"
                   class="form-control mb-3" required>

            <button type="submit" class="btn btn-success w-100">
                Register
            </button>

        </form>

        <p class="text-center mt-3">
            Already have account? <a href="login.php">Login</a>
        </p>

    </div>
</div>

</body>
</html>
