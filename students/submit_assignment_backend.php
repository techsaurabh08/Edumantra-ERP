<?php
session_start();
include '../config/db.php';

// Check student login
if (!isset($_SESSION['student'])) {
    header("Location: student_login.php");
    exit();
}

$student_id = $_SESSION['student'];

if (isset($_POST['assignment_id']) && isset($_FILES['assignment_file'])) {

    $assignment_id = $_POST['assignment_id'];

    // Check if student already submitted this assignment
    $check = mysqli_query($conn, "
        SELECT id FROM submitted_assignments 
        WHERE student_id = $student_id AND assignment_id = $assignment_id
    ");

    if (mysqli_num_rows($check) > 0) {
        echo "<script>
                alert('⚠️ You have already submitted this assignment!');
                window.location.href='student_dashboard.php?page=submit_assignment';
              </script>";
        exit();
    }

    $file_name = $_FILES['assignment_file']['name'];
    $file_tmp = $_FILES['assignment_file']['tmp_name'];

    $upload_dir = "uploads/assignments/";

    // Create folder if not exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $target_file = $upload_dir . time() . "_" . basename($file_name);

    if (move_uploaded_file($file_tmp, $target_file)) {

        // Insert into database
        $stmt = $conn->prepare("INSERT INTO submitted_assignments (assignment_id, student_id, file_path) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $assignment_id, $student_id, $target_file);

        if ($stmt->execute()) {
            // Success message
            echo "<script>
                    alert('✅ Assignment submitted successfully!');
                    window.location.href='student_dashboard.php?page=submit_assignment';
                  </script>";
            exit();
        } else {
            $error = "Database error: " . $conn->error;
        }

    } else {
        $error = "File upload failed!";
    }

} else {
    $error = "Please select an assignment and file!";
}

// If error occurs
echo "<script>
        alert('❌ $error');
        window.location.href='student_dashboard.php?page=submit_assignment';
      </script>";
exit();
?>
