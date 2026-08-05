<?php
session_start();
include '../config/db.php';

// Check if student is logged in
if (!isset($_SESSION['student'])) {
    header("Location: student_login.php");
    exit();
}

$student_id = $_SESSION['student'];

// Get submission ID from GET request
$submission_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($submission_id > 0) {
    // Check if this submission belongs to the logged-in student
    $check = mysqli_query($conn, "SELECT * FROM submitted_assignments WHERE id=$submission_id AND student_id=$student_id");
    
    if (mysqli_num_rows($check) > 0) {
        $row = mysqli_fetch_assoc($check);

        // Delete the file from the server
        if (file_exists($row['file_path'])) {
            unlink($row['file_path']);
        }

        // Delete the record from database
        $delete = mysqli_query($conn, "DELETE FROM submitted_assignments WHERE id=$submission_id");

        if ($delete) {
            echo "<script>
                alert('Submission deleted successfully!');
                window.location.href='student_dashboard.php?page=view_submissions';
            </script>";
        } else {
            echo "<script>
                alert('Database error! Please try again.');
                window.location.href='student_dashboard.php?page=view_submissions';
            </script>";
        }
    } else {
        echo "<script>
            alert('Invalid submission!');
            window.location.href='student_dashboard.php?page=view_submissions';
        </script>";
    }
} else {
    echo "<script>
        alert('Invalid request!');
        window.location.href='student_dashboard.php?page=view_submissions';
    </script>";
}
?>
