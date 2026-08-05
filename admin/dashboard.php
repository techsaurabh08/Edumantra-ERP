<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include '../config/db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { display: flex; }
        .sidebar {
            width: 240px;
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
        .sidebar a:hover { background: #334155; }
        .content { flex: 1; padding: 20px; }
    </style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>🎓 Admin Panel</h4>

    <a href="?page=dashboard">🏠 Dashboard</a>

    <hr>

    <b>Departments</b>
    <a href="?page=add_dept">➕ Add Department</a>
    <a href="?page=view_dept">📋 View Departments</a>

    <b>Courses</b>
    <a href="?page=add_course">➕ Add Course</a>
    <a href="?page=view_course">📋 View Courses</a>

    <b>Students</b>
    <a href="?page=add_student">➕ Add Student</a>
    <a href="?page=view_student">📋 View Students</a>

    <b>Faculty</b>
    <a href="?page=add_faculty">➕ Add Faculty</a>
    <a href="?page=view_faculty">📋 View Faculty</a>

    <b>Status Management</b>
    <a href="?page=student_status">👨‍🎓 Student Status</a>
    <a href="?page=faculty_status">👨‍🏫 Faculty Status</a>

    <b>Password Management</b>
    <a href="?page=change_password">🔑 Change Password</a>


    <hr>
    <a href="logout.php">🚪 Logout</a>
</div>

<!-- Content -->
<div class="content">

<?php
$page = $_GET['page'] ?? 'dashboard';
/* DASHBOARD */
if ($page == 'dashboard') {

    // Fetch logged-in admin username from session
    $admin_id = $_SESSION['admin'];
    $admin_query = mysqli_query($conn, "SELECT username FROM admin WHERE username='$admin_id'");
    $admin = mysqli_fetch_assoc($admin_query);
    $admin_name = $admin['username'] ?? "Admin";

    // Fetch counts safely
    $total_departments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM departments"))['total'] ?? 0;
    $total_courses     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM courses"))['total'] ?? 0;
    $total_students    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM students"))['total'] ?? 0;
    $total_faculty     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM faculty"))['total'] ?? 0;

    echo "<h2>Welcome, <b>$admin_name</b> 👋</h2>";
    echo "<div class='row mt-4'>";

    // Dashboard cards
    $cards = [
        ['title'=>'Departments', 'count'=>$total_departments, 'color'=>'primary', 'icon'=>'🏢'],
        ['title'=>'Courses', 'count'=>$total_courses, 'color'=>'success', 'icon'=>'📚'],
        ['title'=>'Students', 'count'=>$total_students, 'color'=>'warning', 'icon'=>'👨‍🎓'],
        ['title'=>'Faculty', 'count'=>$total_faculty, 'color'=>'info', 'icon'=>'👩‍🏫'],
    ];

    foreach ($cards as $c) {
        echo "
        <div class='col-md-3 mb-3'>
            <div class='card text-white bg-{$c['color']} h-100'>
                <div class='card-body d-flex flex-column justify-content-center align-items-center'>
                    <h3>{$c['icon']}</h3>
                    <h5 class='card-title mt-2'>{$c['title']}</h5>
                    <h4>{$c['count']}</h4>
                </div>
            </div>
        </div>
        ";
    }

    echo "</div>";
}


/* ================= DEPARTMENT ================= */
if ($page == 'add_dept') {
?>

<h3>Add Department</h3>
<form action="insert_dept.php" method="POST">

<input type="text" name="dept_name" placeholder="Department Name" class="form-control mb-2" required>

<input type="text" name="dept_code" placeholder="Dept Code" class="form-control mb-2" required>

<!-- Course Dropdown -->
<select name="course_id" class="form-control mb-2" required>
    <option value="">-- Select Course --</option>
    <?php
    $course = mysqli_query($conn, "SELECT * FROM courses");
    while ($row = mysqli_fetch_assoc($course)) {
        echo "<option value='{$row['id']}'>{$row['course_name']}</option>";
    }
    ?>
</select>

<button class="btn btn-success">Save</button>
</form>

<?php
}

/* ================= VIEW DEPARTMENT ================= */
if ($page == 'view_dept') {
$result = mysqli_query($conn, "
SELECT departments.*, courses.course_name 
FROM departments 
LEFT JOIN courses ON departments.course_id = courses.id
");

echo "<h3>Departments</h3>
<table class='table table-bordered'>
<tr>
<th>ID</th>
<th>Name</th>
<th>Code</th>
<th>Course</th>
<th>Action</th>
</tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['dept_name']}</td>
        <td>{$row['dept_code']}</td>
        <td>{$row['course_name']}</td>
        <td>
            <a href='edit_dept.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
            <a href='delete_dept.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Delete?')\">Delete</a>
        </td>
    </tr>";
}
echo "</table>";

}

/* ================= COURSE ================= */

if ($page == 'add_course') {
?>

<h3>Add Course</h3>
<form action="insert_course.php" method="POST">
<input type="text" name="course_name" placeholder="Course Name" class="form-control mb-2" required>
<input type="text" name="course_code" placeholder="Course Code" class="form-control mb-2" required>

<button class="btn btn-primary">Save</button>
</form>

<?php
}

/* ================= VIEW COURSE ================= */

if ($page == 'view_course') {

$result = mysqli_query($conn, "SELECT * FROM courses");

echo "<h3>Courses</h3><table class='table table-bordered'>
<tr>
<th>ID</th>
<th>Course Name</th>
<th>Course Code</th>
<th>Action</th>
</tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['course_name']}</td>
        <td>{$row['course_code']}</td>
        <td>
            <a href='edit_course.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
            <a href='delete_course.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Delete?')\">Delete</a>
        </td>
    </tr>";
}


echo "</table>";
}


/* ================= STUDENT ================= */

if ($page == 'add_student') {
?>

<h3>Add Student</h3>
<form action="insert_student.php" method="POST">

<input type="text" name="student_name" placeholder="Name" class="form-control mb-2" required>

<input type="text" name="student_id" placeholder="Student ID" class="form-control mb-2" required>

<input type="email" name="student_email" placeholder="Email" class="form-control mb-2" required>

<!-- Course Dropdown -->
<select name="course_id" class="form-control mb-2" required>
    <option value="">-- Select Course --</option>
    <?php
    $c = mysqli_query($conn, "SELECT * FROM courses");
    while ($row = mysqli_fetch_assoc($c)) {
        echo "<option value='{$row['id']}'>{$row['course_name']}</option>";
    }
    ?>
</select>

<!-- Department Dropdown -->
<select name="department_id" class="form-control mb-2" required>
    <option value="">-- Select Department --</option>
    <?php
    $d = mysqli_query($conn, "SELECT * FROM departments");
    while ($row = mysqli_fetch_assoc($d)) {
        echo "<option value='{$row['id']}'>{$row['dept_name']}</option>";
    }
    ?>
</select>

<input type="password" name="student_password" placeholder="Password" class="form-control mb-2" required>

<button class="btn btn-success">Save</button>
</form>

<?php
}

/* ================= VIEW STUDENT ================= */
if ($page == 'view_student') {
$result = mysqli_query($conn, "
SELECT students.*, courses.course_name, departments.dept_name
FROM students
LEFT JOIN courses ON students.course_id = courses.id
LEFT JOIN departments ON students.department_id = departments.id
");

echo "<h3>Students</h3>
<table class='table table-bordered'>
<tr>
<th>ID</th>
<th>Name</th>
<th>Student ID</th>
<th>Email</th>
<th>Course</th>
<th>Department</th>
<th>Action</th>
</tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['student_name']}</td>
        <td>{$row['student_id']}</td>
        <td>{$row['student_email']}</td>
        <td>{$row['course_name']}</td>
        <td>{$row['dept_name']}</td>
        <td>
          <a href='edit_student.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
          <a href='delete_student.php?id={$row['id']}' class='btn btn-danger btn-sm'>Delete</a>
        </td>
    </tr>";
}
echo "</table>";

}

/* ================= FACULTY ================= */
if ($page == 'add_faculty') {
?>

<h3>Add Faculty</h3>
<form action="insert_faculty.php" method="POST">
<input type="text" name="faculty_name" placeholder="Name" class="form-control mb-2" required>
<input type="text" name="faculty_id" placeholder="Faculty ID" class="form-control mb-2" required>
<input type="email" name="faculty_email" placeholder="Email" class="form-control mb-2" required>
<input type="text" name="phone" placeholder="Phone Number" class="form-control mb-2" required>
<select name="department_id" class="form-control mb-2" required>
    <option value="">-- Select Department --</option>
    <?php
    $d = mysqli_query($conn, "SELECT * FROM departments");
    while ($row = mysqli_fetch_assoc($d)) {
        echo "<option value='{$row['id']}'>{$row['dept_name']}</option>";
    }
    ?>
</select>
<input type="password" name="faculty_password" placeholder="Password" class="form-control mb-2" required>

<button class="btn btn-success">Save</button>
</form>

<?php
}

/* ================= VIEW FACULTY ================= */
if ($page == 'view_faculty') {

$result = mysqli_query($conn, "
SELECT faculty.*, departments.dept_name 
FROM faculty
LEFT JOIN departments ON faculty.department_id = departments.id
");

echo "<h3>Faculty</h3><table class='table table-bordered'>
<tr>
<th>ID</th>
<th>Name</th>
<th>Faculty ID</th>
<th>Email</th>
<th>Phone</th>
<th>Department</th>
<th>Action</th>
</tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['faculty_name']}</td>
        <td>{$row['faculty_id']}</td>
        <td>{$row['faculty_email']}</td>
        <td>{$row['phone']}</td>
        <td>{$row['dept_name']}</td>
        <td>
        <a href='edit_faculty.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
        <a href='delete_faculty.php?id={$row['id']}' class='btn btn-danger btn-sm'>Delete</a>
        </td>
    </tr>";
}
echo "</table>";
}

if ($page == 'student_status') {
    $result = mysqli_query($conn, "
        SELECT students.*, departments.dept_name, courses.course_name
        FROM students
        LEFT JOIN departments ON students.department_id = departments.id
        LEFT JOIN courses ON students.course_id = courses.id
    ");

    echo "<h3>Manage Student Status</h3>";
    echo "<table class='table table-bordered'>
        <tr>
            <th>ID</th><th>Name</th><th>Email</th><th>Course</th><th>Department</th><th>Status</th><th>Action</th>
        </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        $status_text = $row['status'] ? "Active" : "Inactive";
        // Opposite action text
        $action_text = $row['status'] ? "Set Inactive" : "Set Active";
        $new_status = $row['status'] ? 0 : 1;

        echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['student_name']}</td>
            <td>{$row['student_email']}</td>
            <td>{$row['course_name']}</td>
            <td>{$row['dept_name']}</td>
            <td>$status_text</td>
            <td>
                <a href='?page=student_status&toggle={$row['id']}&status=$new_status' class='btn btn-sm btn-info'>$action_text</a>
            </td>
        </tr>";
    }
    echo "</table>";

    // Status toggle logic
    if (isset($_GET['toggle']) && isset($_GET['status'])) {
        $sid = (int)$_GET['toggle'];
        $status = (int)$_GET['status'];
        mysqli_query($conn, "UPDATE students SET status=$status WHERE id=$sid");
        echo "<script>window.location='?page=student_status';</script>";
    }
}

if ($page == 'faculty_status') {
    $result = mysqli_query($conn, "
        SELECT faculty.*, departments.dept_name
        FROM faculty
        LEFT JOIN departments ON faculty.department_id = departments.id
    ");

    echo "<h3>Manage Faculty Status</h3>";
    echo "<table class='table table-bordered'>
        <tr>
            <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Department</th><th>Status</th><th>Action</th>
        </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        $status_text = $row['status'] ? "Active" : "Inactive";
        // Opposite action text
        $action_text = $row['status'] ? "Set Inactive" : "Set Active";
        $new_status = $row['status'] ? 0 : 1;

        echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['faculty_name']}</td>
            <td>{$row['faculty_email']}</td>
            <td>{$row['phone']}</td>
            <td>{$row['dept_name']}</td>
            <td>$status_text</td>
            <td>
                <a href='?page=faculty_status&toggle={$row['id']}&status=$new_status' class='btn btn-sm btn-info'>$action_text</a>
            </td>
        </tr>";
    }
    echo "</table>";

    // Status toggle logic
    if (isset($_GET['toggle']) && isset($_GET['status'])) {
        $fid = (int)$_GET['toggle'];
        $status = (int)$_GET['status'];
        mysqli_query($conn, "UPDATE faculty SET status=$status WHERE id=$fid");
        echo "<script>window.location='?page=faculty_status';</script>";
    }
}


if ($page == 'change_password') {
?>

<h3>🔑 Change Password</h3>
<form method="POST" action="update_admin_password.php">
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
