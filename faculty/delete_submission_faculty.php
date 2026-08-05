<?php
session_start();
include '../config/db.php';

// Check if faculty is logged in
if (!isset($_SESSION['faculty'])) {
    header("Location: faculty_login.php");
    exit();
}

$faculty_id = $_SESSION['faculty'];

// Get submission ID from GET request
$submission_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($submission_id > 0) {
    // Check if this submission belongs to an assignment of this faculty
    $check = mysqli_query($conn, "
        SELECT s.file_path 
        FROM submitted_assignments s
        JOIN assignments a ON s.assignment_id = a.id
        WHERE s.id=$submission_id AND a.faculty_id=$faculty_id
    ");

    if (mysqli_num_rows($check) > 0) {
        $row = mysqli_fetch_assoc($check);

        // Delete the file from the server
        $file_path = "../students/" . $row['file_path']; // adjust path as needed
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // Delete the record from database
        $delete = mysqli_query($conn, "DELETE FROM submitted_assignments WHERE id=$submission_id");

        if ($delete) {
            echo "<script>
                alert('Submission deleted successfully!');
                window.location.href='faculty_dashboard.php?page=view_submissions';
            </script>";
        } else {
            echo "<script>
                alert('Database error! Please try again.');
                window.location.href='faculty_dashboard.php?page=view_submissions';
            </script>";
        }

    } else {
        echo "<script>
            alert('Submission not found or you do not have permission!');
            window.location.href='faculty_dashboard.php?page=view_submissions';
        </script>";
    }

} else {
    echo "<script>
        alert('Invalid request!');
        window.location.href='faculty_dashboard.php?page=view_submissions';
    </script>";
}
?>
