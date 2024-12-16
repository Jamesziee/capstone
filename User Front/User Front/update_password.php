<?php
require_once 'connection.php'; 

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the current password, new password, and confirm password from the form
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate new password and confirm password
    if ($new_password !== $confirm_password) {
        $_SESSION['notification'] = "New password and confirm password do not match.";
        $_SESSION['notification_type'] = 'error';
        header("Location: settings.php"); // Redirect back to settings page
        exit();
    }

    // Retrieve the current password from the database
    $sql = "SELECT password FROM parent_acc WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        // Bind the user ID to the query
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($db_password);
        $stmt->fetch();
        $stmt->close();

        // Verify the current password
        if (password_verify($current_password, $db_password)) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $update_sql = "UPDATE parent_acc SET password = ? WHERE id = ?";
            if ($update_stmt = $conn->prepare($update_sql)) {
                // Bind the hashed password and user ID
                $update_stmt->bind_param("si", $hashed_password, $user_id);
                if ($update_stmt->execute()) {
                    $_SESSION['notification'] = "Password updated successfully.";
                    $_SESSION['notification_type'] = 'success';
                    header("Location: settings.php");
                } else {
                    $_SESSION['notification'] = "Error updating password: " . $update_stmt->error;
                    $_SESSION['notification_type'] = 'error';
                    header("Location: settings.php");
                }
                $update_stmt->close();
            }
        } else {
            $_SESSION['notification'] = "Current password is incorrect.";
            $_SESSION['notification_type'] = 'error';
            header("Location: settings.php");
        }
    }
}

// Close the connection
$conn->close();
?>
