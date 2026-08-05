<?php
session_start();
include '../config/db.php';

// Student login check
if (!isset($_SESSION['student'])) {
    header("Location: student_login.php");
    exit();
}

$student_id = $_SESSION['student'];

// Fetch student info
$student = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT students.*, courses.course_name, departments.dept_name
    FROM students
    LEFT JOIN courses ON students.course_id = courses.id
    LEFT JOIN departments ON students.department_id = departments.id
    WHERE students.id=$student_id
"));

// Fetch assignments for student's department
$dept_id = $student['department_id'];
$assignments = mysqli_query($conn, "
    SELECT * FROM assignments 
    WHERE faculty_id IN (SELECT id FROM faculty WHERE department_id=$dept_id)
    ORDER BY due_date ASC
");

$page = $_GET['page'] ?? 'dashboard';
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { display: flex; }
.sidebar {
    width: 230px;
    height: 100vh;
    background: #1e293b;
    color: white;
    padding: 20px;
}
.sidebar a { color: white; display: block; padding: 10px; text-decoration: none; }
.sidebar a:hover { background: #334155; }
.content { flex: 1; padding: 20px; }
</style>
</head>
<body>

<div class="sidebar">
<h4>👨‍🎓 Student Panel</h4>
<a href="?page=dashboard">🏠 Dashboard</a>
<a href="?page=profile">👤 My Profile</a>
<a href="?page=assignments">📝 Assignments</a>
<a href="?page=submit_assignment">📤 Submit Assignment</a>
<a href="?page=view_submissions">📂 My Submissions</a>
<a href="?page=change_password">🔑 Change Password</a>
<hr>
<a href="student_logout.php">🚪 Logout</a>
</div>

<div class="content">

<?php

/* ================= DASHBOARD ================= */
if ($page == 'dashboard') {
    echo "<h3>Welcome, {$student['student_name']} 👋</h3>";
    $total_assignments = mysqli_num_rows($assignments);
    echo "<p>Total Assignments for your Department: <b>$total_assignments</b></p>";
}

/* ================= PROFILE ================= */
if ($page == 'profile') {
?>
<h4>My Profile</h4>
<table class="table table-bordered">
<tr><th>Name</th><td><?= $student['student_name'] ?></td></tr>
<tr><th>Email</th><td><?= $student['student_email'] ?></td></tr>
<tr><th>Student ID</th><td><?= $student['student_id'] ?></td></tr>
<tr><th>Department</th><td><?= $student['dept_name'] ?></td></tr>
<tr><th>Course</th><td><?= $student['course_name'] ?></td></tr>
</table>
<?php
}

/* ================= ASSIGNMENTS ================= */
if ($page == 'assignments') {
    echo "<h4>📋 Assignments</h4>
    <table class='table table-bordered'>
    <tr><th>ID</th><th>Title</th><th>Subject</th><th>Due Date</th><th>Description</th></tr>";

    while ($row = mysqli_fetch_assoc($assignments)) {
        echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['title']}</td>
            <td>{$row['subject']}</td>
            <td>{$row['due_date']}</td>
            <td>{$row['description']}</td>
        </tr>";
    }

    echo "</table>";
}

/* ================= SUBMIT ASSIGNMENT ================= */
if ($page == 'submit_assignment') {

    // Fetch assignments for this student department
    $all_assignments = mysqli_query($conn, "
        SELECT * FROM assignments 
        WHERE faculty_id IN (SELECT id FROM faculty WHERE department_id=$dept_id)
        ORDER BY due_date DESC
    ");

    echo "<h4>Submit Assignment</h4>";
    echo "<form action='submit_assignment_backend.php' method='POST' enctype='multipart/form-data'>";
    echo "<select name='assignment_id' class='form-control mb-2' required>";
    echo "<option value=''>-- Select Assignment --</option>";

    while ($row = mysqli_fetch_assoc($all_assignments)) {

        // Check if this assignment is already submitted by student
        $check = mysqli_query($conn, "
            SELECT id FROM submitted_assignments 
            WHERE student_id = $student_id AND assignment_id = {$row['id']}
        ");

        if (mysqli_num_rows($check) == 0) {
            // Only show if not submitted yet
            echo "<option value='{$row['id']}'>{$row['title']} (Due: {$row['due_date']})</option>";
        }
    }

    echo "</select>";
    echo "<input type='file' name='assignment_file' class='form-control mb-2' accept='application/pdf' required>";
    echo "<button class='btn btn-success'>Submit</button>";
    echo "</form>";

}

/* ================= VIEW SUBMISSIONS ================= */
if ($page == 'view_submissions') {

    $submissions = mysqli_query($conn, "
        SELECT s.*, a.title 
        FROM submitted_assignments s
        LEFT JOIN assignments a ON s.assignment_id = a.id
        WHERE s.student_id = $student_id
        ORDER BY s.submitted_at DESC
    ");

    echo "<h4>My Submitted Assignments</h4>";

    if (mysqli_num_rows($submissions) > 0) {
        echo "<table class='table table-bordered'>
            <tr>
            <th>ID</th>
            <th>Assignment</th>
            <th>File</th>
            <th>Submitted At</th>
            <th>Action</th>
            </tr>";

        while ($row = mysqli_fetch_assoc($submissions)) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['title']}</td>
                <td><a href='{$row['file_path']}' target='_blank'>View File</a></td>
                <td>{$row['submitted_at']}</td>
                <td>
                    <a href='delete_submission.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                </td>
            </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No assignments submitted yet.</p>";
    }
}

/* ================= CHANGE PASSWORD ================= */
if ($page == 'change_password') {
?>

<h4>🔑 Change Password</h4>
<form method="POST" action="update_password.php">
    <input type="password" name="current_password" placeholder="Current Password" class="form-control mb-2" required>
    <input type="password" name="new_password" placeholder="New Password" class="form-control mb-2" required>
    <input type="password" name="confirm_password" placeholder="Confirm New Password" class="form-control mb-2" required>
    <button class="btn btn-primary">Update Password</button>
</form>

<?php
}


?>
</div>
</body>
</html>
