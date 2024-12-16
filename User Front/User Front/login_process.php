<?php
session_start();
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($email) || empty($password)) {
        header("Location: login.php?status=error");
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT id, fullname, parent_image, contact_number, address, email, password FROM parent_acc WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['parent_image'] = $user['parent_image'];
                $_SESSION['contact_number'] = $user['contact_number'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['address'] = $user['address'];
                // Redirect to dashboard with success message
                header("Location: dashboard.php?status=success");
                exit();
            } else {
                header("Location: login.php?status=error");
                exit();
            }
        } else {
            header("Location: login.php?status=error");
            exit();
        }
    } catch (PDOException $e) {
        header("Location: login.php?status=error");
        exit();
    }
} else {
    header("Location: login.php?status=error");
    exit();
}
?>
