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
<link href="pickup.css" rel="stylesheet">
<script>
    function handleScan(studentId) {
        if (studentId.trim() === '') return; // Prevent empty input
        const action = confirm("Mark Time In for Student ID: " + studentId + "?");
        const actionType = action ? 'time_in' : 'time_out';

        fetch('update_record.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ student_id: studentId, action: actionType })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Record updated successfully.");
                location.reload(); // Reload to reflect updated data
            } else {
                alert("Error updating record: " + data.message);
            }
        })
        .catch(err => console.error("Error:", err));
    }
</script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pick-up Records</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet" />
    <link href="css/pickup.css" rel="stylesheet" />
    <style>
        /* General */
    
                
    </style>
</head>
<body>
    <div class="containers">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo"><img src="pic/logo.png" height="70px" width="70px"/></div>
            <ul>
            <li><a href="dashboard.php"><i class="fas fa-th-large"></i> Dashboard</a></li>
            <li><a href="student.php"><i class="fas fa-child"></i> Student</a></li>
            <li><a class="active" a href="pickup.php"><i class="fas fa-file-alt"></i> Pick-Up Records</a></li>
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
            <div class="icon-search-container">
                <div class="icon"><i class="fas fa-filter"></i></div>
                <div class="search-bar">
                   <input placeholder="Search" type="text"/><i class="fas fa-search"></i>
                </div>
            </div>
            <!-- Table Container -->
                <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Authorized Person</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'connection.php'; 
                    $records_per_page = 10;

                  
                    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    if ($current_page < 1) $current_page = 1;
                    $start_from = ($current_page - 1) * $records_per_page;

                    $query = "
                        SELECT 
                            pr.student_id, 
                            s.child_name, 
                            pr.authorized_person_name, 
                            pr.time_in, 
                            pr.time_out, 
                            pr.date
                        FROM 
                            attendance pr
                        INNER JOIN 
                            child_acc s
                        ON 
                            pr.student_id = s.student_id
                        ORDER BY 
                            pr.date DESC
                        LIMIT $start_from, $records_per_page";
                    
                    $result = mysqli_query($conn, $query);
                    

                    $total_query = "
                        SELECT COUNT(*) as total_records
                        FROM attendance pr
                        INNER JOIN child_acc s
                        ON pr.student_id = s.student_id";
                    $total_result = mysqli_query($conn, $total_query);
                    $total_row = mysqli_fetch_assoc($total_result);
                    $total_records = $total_row['total_records'];
                    

                    $total_pages = ceil($total_records / $records_per_page);
                    ?>
                    <tbody>
                        <?php
                        if ($result) {
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>
                                        <td>{$row['student_id']}</td>
                                        <td>{$row['child_name']}</td>
                                        <td>{$row['authorized_person_name']}</td>
                                        <td>{$row['time_in']}</td>
                                        <td>{$row['time_out']}</td>
                                        <td>{$row['date']}</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No records found.</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>Error fetching data.</td></tr>";
                        }
                        ?>
                    </tbody>
                    </table>
                    
                    <!-- Pagination Controls -->
                    <div class="pagination">
                        <?php if ($current_page > 1): ?>
                            <a href="?page=<?= $current_page - 1 ?>">&laquo; Prev</a>
                        <?php endif; ?>
                    
                        <?php for ($page = 1; $page <= $total_pages; $page++): ?>
                            <a href="?page=<?= $page ?>" class="<?= $page == $current_page ? 'active' : '' ?>"><?= $page ?></a>
                        <?php endfor; ?>
                    
                        <?php if ($current_page < $total_pages): ?>
                            <a href="?page=<?= $current_page + 1 ?>">Next &raquo;</a>
                        <?php endif; ?>
                    </div>
        </div>
    </div>
</body>
</html>
