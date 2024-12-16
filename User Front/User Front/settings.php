<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Retrieve user details from session
$fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Not available';
$parent_image = isset($_SESSION['parent_image']) ? $_SESSION['parent_image'] : 'default_image_path.jpg'; 
$contact_number = isset($_SESSION['contact_number']) ? $_SESSION['contact_number'] : 'Not available';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : 'Not available';
$address = isset($_SESSION['address']) ? $_SESSION['address'] : 'Not available';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Settings</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet" />
    <link href="css/settings.css" rel="stylesheet" />
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
            <li><a href="concern.php"><i class="fas fa-envelope"></i> Contact Us</a></li>
            </ul>
            <div class="settings-logout">
            <ul>
            <li><a class="active" href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
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
            <div class="Account">
                <div class="account-header">
                <img src="data:image/jpeg;base64,<?= base64_encode($parent_image); ?>" alt="Profile Picture" />
                <h2 style="margin: 0; color: white;">Hi, <?= htmlspecialchars($fullname); ?></h2>
                </div>
                <h3>Account Information</h3>
                <div class="info">
                    <label>Full Name</label>
                    <div class="value"><?= htmlspecialchars($fullname); ?></div>
                </div>
                <div class="info">
                    <label>Contact Number</label>
                    <div class="value"><?= htmlspecialchars($contact_number); ?></div>
                </div>
                <div class="info">
                    <label>Email Address</label>
                    <div class="value"><?= htmlspecialchars($email); ?></div>
                </div>
                <div class="info">
                    <label>Address</label>
                    <div class="value"><?= htmlspecialchars($address); ?></div>
                </div>
                <div class="button">
                <button class="edit-info-btn" id="editInfoBtn">Click to Edit Info</button>
                   
                   
                <button class="change-password-btn" id="changePasswordBtn">Change Password</button>
                </div>
            </div>
        </div>     
    </div>
    
    <!-- Edit Information Modal -->
    <div id="editInfoModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Your Information</h3>
            </div>
            <div class="modal-body">
                <form action="update_info.php" method="POST">
                    <div>
                        <label for="fullname">Full Name:</label>
                        <input type="text" id="fullname" name="fullname" value="<?= htmlspecialchars($fullname); ?>" required>
                    </div>
                    <div>
                        <label for="contact_number">Contact Number:</label>
                        <input type="text" id="contact_number" name="contact_number" value="<?= htmlspecialchars($contact_number); ?>" required>
                    </div>
                    <div>
                        <label for="email">Email Address:</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email); ?>" required>
                    </div>
                    <div>
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" value="<?= htmlspecialchars($address); ?>" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit">Submit</button>
                        <button type="button" id="closeEditModal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div id="changePasswordModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Change Your Password</h3>
            </div>
            <div class="modal-body">
                <form action="update_password.php" method="POST">
                    <div>
                        <label for="current_password">Current Password:</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    <div>
                        <label for="new_password">New Password:</label>
                        <input type="password" id="new_password" name="new_password" required>
                    </div>
                    <div>
                        <label for="confirm_password">Confirm New Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit">Submit</button>
                        <button type="button" id="closePasswordModal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Get modals
        var editInfoModal = document.getElementById('editInfoModal');
        var changePasswordModal = document.getElementById('changePasswordModal');

        // Get buttons
        var editInfoBtn = document.getElementById('editInfoBtn');
        var changePasswordBtn = document.getElementById('changePasswordBtn');

        // Get close buttons
        var closeEditModal = document.getElementById('closeEditModal');
        var closePasswordModal = document.getElementById('closePasswordModal');

        // Open the Edit Info modal
        editInfoBtn.onclick = function() {
            editInfoModal.style.display = "block";
        }

        // Open the Change Password modal
        changePasswordBtn.onclick = function() {
            changePasswordModal.style.display = "block";
        }

        // Close the Edit Info modal
        closeEditModal.onclick = function() {
            editInfoModal.style.display = "none";
        }

        // Close the Change Password modal
        closePasswordModal.onclick = function() {
            changePasswordModal.style.display = "none";
        }

        // Close the modals when clicking outside
        window.onclick = function(event) {
            if (event.target == editInfoModal) {
                editInfoModal.style.display = "none";
            }
            if (event.target == changePasswordModal) {
                changePasswordModal.style.display = "none";
            }
        }
        
    </script>
    <!-- Notification Box -->
<div class="notification" id="notification" style="display: none;">
    <span id="notification-text"></span>
</div>

<script>
// Check if there's a notification in session
<?php if (isset($_SESSION['notification'])): ?>
    const notificationType = '<?= $_SESSION['notification_type']; ?>';
    const notificationText = '<?= $_SESSION['notification']; ?>';
    
    // Set the notification text and background color based on type
    const notification = document.getElementById('notification');
    const notificationTextElem = document.getElementById('notification-text');
    
    notificationTextElem.textContent = notificationText;
    
    if (notificationType === 'success') {
        notification.style.backgroundColor = '#28a745'; // Green for success
    } else {
        notification.style.backgroundColor = '#dc3545'; // Red for error
    }

    // Show the notification
    notification.style.display = 'block';
    
    // Hide notification after 5 seconds
    setTimeout(() => {
        notification.style.display = 'none';
    }, 5000);

    // Unset session data after displaying the notification
    <?php unset($_SESSION['notification'], $_SESSION['notification_type']); ?>
<?php endif; ?>
</script>
<script>
<?php if (isset($_SESSION['notification'])): ?>
    const notificationType = '<?= $_SESSION['notification_type']; ?>';
    const notificationText = '<?= $_SESSION['notification']; ?>';
    
    // Set the notification text and background color based on type
    const notification = document.getElementById('notification');
    const notificationTextElem = document.getElementById('notification-text');
    
    notificationTextElem.textContent = notificationText;
    
    if (notificationType === 'success') {
        notification.style.backgroundColor = '#28a745'; // Green for success
    } else {
        notification.style.backgroundColor = '#dc3545'; // Red for error
    }

    // Show the notification
    notification.style.display = 'block';
    
    // Hide notification after 5 seconds
    setTimeout(() => {
        notification.style.display = 'none';
    }, 5000);

    // Unset session data after displaying the notification
    <?php unset($_SESSION['notification'], $_SESSION['notification_type']); ?>
<?php endif; ?>
</script>
</body>
</html>