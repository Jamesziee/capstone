<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Retrieve user details from session
$fullname = $_SESSION['fullname'];
$parent_image = $_SESSION['parent_image'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet" />
    <link href="css/concern.css" rel="stylesheet" />
</head>
<body>
    <div class="containers">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo"><img src="pic/logo.png" height="70px" width="70px"/></div>
            <ul>
            <li><a href="dashboard.php"><i class="fas fa-th-large"></i> Dashboard</a></li>
            <li><a href="student.php"><i class="fas fa-child"></i> Student</a></li>
            <li><a href="pickup.php"><i class="fas fa-file-alt"></i> Pick-Up Records</a></li>
            <li><a class="active" a href="concern.php"><i class="fas fa-envelope"></i> Contact Us</a></li>
            </ul>
            <div class="settings-logout">
            <ul>
            <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
            <li><a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
        <div class="profile">
          <div class="user-info" style="display: flex; align-items: center;">
        <!-- Notifications Icon -->
               <div class="notifications" style="margin-right: 10px; color: white;">
                    <i class="fas fa-bell"></i>
                </div>
                <!-- Profile Image and Full Name -->
                <div style="display: flex; align-items: center;">
                    <img src="data:image/jpeg;base64,<?= base64_encode($parent_image); ?>" alt="Profile Picture" style="width:50px;height:50px; margin-right: 10px;" />
                    <h4 style="margin: 0; color: white;"><?= htmlspecialchars($fullname); ?></h4>
               </div>
           </div>
        </div>

            <div class="contact-us">
                <h2>Contact Us</h2>
                <div class="info">
                    <label>Contact Number</label>
                    <div class="value">(63) 909-555-3939</div>
                </div>
                <div class="info">
                    <label>Email Address</label>
                    <div class="value">studentshaven@gmail.com</div>
                </div>
                <div class="info">
                    <label>Land Line</label>
                    <div class="value">(046) 434 7365</div>
                </div>
                <div class="info">
                    <label>Facebook</label>
                    <div class="value">Students' Haven Tutorial and Learning Center</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>