<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include your database connection file
include("connection.php");

// Get the posted data
$fullname = $_POST['fullname'];
$contact_number = $_POST['contact_number'];
$email = $_POST['email'];
$address = $_POST['address'];
$user_id = $_SESSION['user_id']; // Assume user_id is stored in session

// Update query
$sql = "UPDATE parent_acc SET fullname=?, contact_number=?, email=?, address=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $fullname, $contact_number, $email, $address, $user_id);

if ($stmt->execute()) {
    // Update session data after successful update
    $_SESSION['fullname'] = $fullname;
    $_SESSION['contact_number'] = $contact_number;
    $_SESSION['email'] = $email;
    $_SESSION['address'] = $address;

    // Set a success notification
    $_SESSION['notification'] = 'Your information has been successfully updated.';
    $_SESSION['notification_type'] = 'success';

    header("Location: settings.php"); // Redirect back to settings page after update
} else {
    // Set an error notification
    $_SESSION['notification'] = 'There was an error updating your information. Please try again.';
    $_SESSION['notification_type'] = 'error';

    header("Location: settings.php"); // Redirect back to settings page after error
}
?>
