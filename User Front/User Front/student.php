<?php
session_start();
include 'connection.php';
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet" />
    <link href="css/student.css" rel="stylesheet" />
</head>
    <?php
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }

        // Retrieve user details from session
        $fullname = $_SESSION['fullname'];
        $parent_image = $_SESSION['parent_image'];

        // Include database connection
        include('connection.php');  // Assuming this file handles the database connection

        // Fetch student information
        $query = "SELECT student_id, child_name, child_grade, child_section, child_address, child_teacher FROM child_acc";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }

        $students = mysqli_fetch_all($result, MYSQLI_ASSOC);
    ?>
<body>
    <div class="containers">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo"><img src="pic/logo.png" height="70px" width="70px"/></div>
            <ul>
            <li><a href="dashboard.php"><i class="fas fa-th-large"></i> Dashboard</a></li>
            <li><a class="active" a href="student.php"><i class="fas fa-child"></i> Student</a></li>
            <li><a href="pickup.php"><i class="fas fa-file-alt"></i> Pick-Up Records</a></li>
            <li><a href="concern.php"><i class="fas fa-envelope"></i> Contact Us</a></li>
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
        
            <div class="button">
                <button class="create-button"><i class="fas fa-plus"></i> CREATE</button>
                
            </div>
            <!-- Table Container -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Grade</th>
                            <th>Section</th>
                            <th>Address</th>
                            <th>Adviser</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($students)): ?>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?= htmlspecialchars($student['student_id']) ?></td>
                                    <td><?= htmlspecialchars($student['child_name']) ?></td>
                                    <td><?= htmlspecialchars($student['child_grade']) ?></td>
                                    <td><?= htmlspecialchars($student['child_section']) ?></td>
                                    <td><?= htmlspecialchars($student['child_address']) ?></td>
                                    <td><?= htmlspecialchars($student['child_teacher']) ?></td>
                                    <td>
                                        <a href="edit_student.php?id=<?= $student['student_id'] ?>" class="action-icon"><i class="fas fa-edit"></i></a>
                                        <a href="delete_student.php?id=<?= $student['student_id'] ?>" class="action-icon" onclick="return confirm('Are you sure you want to delete this record?');"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center;">No students found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
