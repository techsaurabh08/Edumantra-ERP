<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['faculty'])) {
    header("Location: faculty_login.php");
    exit();
}

$id = $_SESSION['faculty'];

$data = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT faculty.*, departments.dept_name 
FROM faculty
LEFT JOIN departments ON faculty.department_id = departments.id
WHERE faculty.id=$id
"));

$dept_id = $data['department_id'];

$page = $_GET['page'] ?? 'dashboard';
?>

<!DOCTYPE html>
<html>
<head>
<title>Faculty Panel</title>

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

.sidebar a {
    color: white;
    display: block;
    padding: 10px;
    text-decoration: none;
}

.sidebar a:hover {
    background: #334155;
}

.content {
    flex: 1;
    padding: 20px;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

<h4>👨‍🏫 Faculty Panel</h4>

<a href="?page=dashboard">🏠 Dashboard</a>
<a href="?page=profile">👤 My Profile</a>
<a href="?page=students">📚 My Students</a>
<a href="?page=add_assignment">📝 Add Assignment</a>
<a href="?page=view_assignment">📋 My Assignments</a>
<a href="?page=view_submissions">📥 View Submissions</a>
<a href="?page=change_password">🔑 Change Password</a>
<hr>
<a href="faculty_logout.php">🚪 Logout</a>

</div>

<!-- CONTENT -->
<div class="content">

<?php
if ($page == 'dashboard') {

    // Total Students (same department)
    $student_count = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT COUNT(*) as total 
        FROM students 
        WHERE department_id = $dept_id
    "))['total'];

    // Total Assignments (same faculty)
    $assignment_count = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT COUNT(*) as total 
        FROM assignments 
        WHERE faculty_id = $id
    "))['total'];

?>

<h3>Welcome, <?= $data['faculty_name'] ?> 👋</h3>

<div class="row mt-4">

    <!-- Total Students -->
    <div class="col-md-6">
        <div class="card shadow p-3 text-center">
            <h5>📚 Total Students</h5>
            <h2><?= $student_count ?></h2>
        </div>
    </div>

    <!-- Total Assignments -->
    <div class="col-md-6">
        <div class="card shadow p-3 text-center">
            <h5>📝 Total Assignments</h5>
            <h2><?= $assignment_count ?></h2>
        </div>
    </div>

</div>

<?php
}

/* ================= PROFILE ================= */
if ($page == 'profile') {
?>

<h4>My Profile</h4>

<table class="table table-bordered">
<tr><th>Name</th><td><?= $data['faculty_name'] ?></td></tr>
<tr><th>Email</th><td><?= $data['faculty_email'] ?></td></tr>
<tr><th>Phone</th><td><?= $data['phone'] ?></td></tr>
<tr><th>Department</th><td><?= $data['dept_name'] ?></td></tr>
</table>

<?php
}

/* ================= STUDENTS ================= */
if ($page == 'students') {

$students = mysqli_query($conn, "
SELECT students.*, courses.course_name 
FROM students
LEFT JOIN courses ON students.course_id = courses.id
WHERE students.department_id = $dept_id
");

echo "<h4>📚 My Department Students</h4>

<table class='table table-bordered'>
<tr>
<th>ID</th>
<th>Name</th>
<th>Student ID</th>
<th>Email</th>
<th>Course</th>
</tr>";

while ($row = mysqli_fetch_assoc($students)) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['student_name']}</td>
        <td>{$row['student_id']}</td>
        <td>{$row['student_email']}</td>
        <td>{$row['course_name']}</td>
    </tr>";
}

echo "</table>";
}

/* ================= ADD ASSIGNMENT ================= */
if ($page == 'add_assignment') {
?>

<h4>Add Assignment</h4>
<form action="insert_assignment.php" method="POST">
<input type="text" name="title" placeholder="Title" class="form-control mb-2" required>
<textarea name="description" placeholder="Description" class="form-control mb-2"></textarea>
<input type="text" name="subject" placeholder="Subject" class="form-control mb-2">
<input type="date" name="due_date" class="form-control mb-2" required>
<button class="btn btn-success">Add</button>
</form>

<?php
}


/* ================= VIEW ASSIGNMENT ================= */
if ($page == 'view_assignment') {

$fid = $_SESSION['faculty'];

$result = mysqli_query($conn, "
SELECT * FROM assignments 
WHERE faculty_id = $fid
ORDER BY id DESC
");

echo "<h4>📋 My Assignments</h4>

<table class='table table-bordered'>
<tr>
<th>ID</th>
<th>Title</th>
<th>Subject</th>
<th>Due Date</th>
<th>Action</th>
</tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['title']}</td>
        <td>{$row['subject']}</td>
        <td>{$row['due_date']}</td>
        <td>
            <a href='delete_assignment.php?id={$row['id']}' class='btn btn-danger btn-sm'>Delete</a>
        </td>
    </tr>";
}

echo "</table>";
}

if ($page == 'view_submissions') {

    $faculty_id = $_SESSION['faculty']; // Logged in faculty ID

    // Fetch all assignments of this faculty
    $assignments = mysqli_query($conn, "
        SELECT * FROM assignments
        WHERE faculty_id = $faculty_id
        ORDER BY due_date DESC
    ");

    echo "<h4>📥 Submitted Assignments (PDF)</h4>";

    while ($assignment = mysqli_fetch_assoc($assignments)) {
        $assignment_id = $assignment['id'];

        echo "<h5>{$assignment['title']} (Due: {$assignment['due_date']})</h5>";

        // Fetch submissions from submitted_assignments table
        $submissions = mysqli_query($conn, "
            SELECT s.*, st.student_name, st.student_id
            FROM submitted_assignments s
            LEFT JOIN students st ON s.student_id = st.id
            WHERE s.assignment_id = $assignment_id
            ORDER BY s.submitted_at DESC
        ");

        if (mysqli_num_rows($submissions) > 0) {
            echo "<table class='table table-bordered'>
                <tr>
                    <th>Student Name</th>
                    <th>Student ID</th>
                    <th>PDF</th>
                    <th>Submitted At</th>
                    <th>Action</th>
                </tr>";

            while ($sub = mysqli_fetch_assoc($submissions)) {
                echo "<tr>
                    <td>{$sub['student_name']}</td>
                    <td>{$sub['student_id']}</td>
                    <td>
                        <a href='../students/{$sub['file_path']}' target='_blank'>View PDF</a>
                    </td>
                    <td>{$sub['submitted_at']}</td>
                    <td>
                        <a href='delete_submission_faculty.php?id={$sub['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this submission?\")'>Delete</a>
                    </td>
                </tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No submissions yet for this assignment.</p>";
        }

        echo "<hr>";
    }
}



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
