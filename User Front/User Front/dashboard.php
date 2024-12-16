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
$child_teacher = isset($_SESSION['child_teacher']) ? $_SESSION['child_teacher'] : 'Not available';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet" />
  <link href="css/dashboard.css" rel="stylesheet" />
  
</head>
<body>
  <!-- Notification Container -->
<div id="notification" class="notification"></div>

  <div class="containers">
    <!-- Sidebar Section -->
    <div class="sidebar">
    <br>
      <div class="logo"><img src="pic/logo.png" height="70px" width="70px"/></div>
      <ul>
        <li><a class="active" a href="dashboard.php"><i class="fas fa-th-large"></i> Dashboard</a></li>
        <li><a href="student.php"><i class="fas fa-child"></i> Student</a></li>
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
    
    <!-- Main Content Section -->
    <div class="main-content">
      <div class="carousel-bottom-container">
        <!-- Carousel Section -->
        <div class="carousel">
          <img src="https://storage.googleapis.com/a1aa/image/UPtx4i6YmJLkCBdecpSt4KSkk0ouZaG5bkgUgtNTlH6hOd2JA.jpg" alt="Carousel Image" />
          <div class="carousel-caption">
            <h3>Caring is the new marketing</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua...</p>
          </div>
        </div>
  
        <!-- Bottom Section -->
        <div class="bottom">
          <!-- Pick-up Status -->
          <div class="status">
            <h3>Pick-up Status</h3>
            <div class="student">
              <img src="https://storage.googleapis.com/a1aa/image/KHD7GUUckL5BOJ1CJ5lULnJolMS3eeyHlkoVMvpDbDfI60ZnA.jpg" alt="Student Image" width="50" height="50" />
              <div>
                <div class="name">Jerome Warren</div>
              </div>
            </div>
            <div class="picked-up-by">
              <div class="status">PICKED UP BY</div>
              <img src="https://storage.googleapis.com/a1aa/image/3GQckbqJZRrCN5k6pX7VZq72SCQXOPCy3WfgVTkc1a1jOd2JA.jpg" alt="Person Image"width="50" height="50" />
              <div class="name">Wade Warren</div>
            </div>
          </div>
          
          <!-- Add Student & Pick-up Options -->
          <div class="add-student"><i class="fas fa-plus"></i> Add Student</div>
          <div class="add-pickup"><i class="fas fa-plus"></i> Add Pick-up</div>
        </div>
      </div>
    </div>
    
    <!-- Additional Right Content Section -->
    <div class="content">
       <div class="profile">
          <div class="user-info" style="display: flex; align-items: center;">
        <!-- Notifications Icon -->
               <div class="notifications" style="margin-right: 10px; color: white;">
                    <i class="fas fa-bell"></i>
                </div>
                <!-- Profile Image and Full Name -->
                <div style="display: flex; align-items: center;">
                    <img src="data:image/jpeg;base64,<?= base64_encode($parent_image); ?>" alt="Profile Picture" style="width:50px;height:50px; margin-right: 25px;" />
                    <h4 style="margin: 0; color: white;"><?= htmlspecialchars($fullname); ?></h4>
               </div>
           </div>
        </div>

      <!-- Right Column -->
      <div class="rightpanel">

      <?php
require 'connection.php';

// Fetch child information from child_acc table
$child_id = 1; // Example: This ID should be dynamic based on context (e.g., from a query string)
$stmt_child = $pdo->prepare('SELECT * FROM child_acc WHERE id = ?');
$stmt_child->execute([$child_id]);
$child = $stmt_child->fetch();

if (!$child) {
  die('Child not found.');
}

// Fetch teacher information
if (isset($child['teacher_id'])) {
  $stmt_teacher = $pdo->prepare('SELECT fullname FROM admin_staff WHERE id = ?');
  $stmt_teacher->execute([$child['teacher_id']]);
  $teacher = $stmt_teacher->fetch();
} else {
  $teacher = ['fullname' => 'No teacher assigned'];
}

// Fetch authorized persons
try {
  $stmt_auth = $pdo->prepare('SELECT fullname FROM authorized_persons WHERE parent_id = ?');
  $stmt_auth->execute([$child_id]);
  $authorized_persons = $stmt_auth->fetchAll();
} catch (PDOException $e) {
  die('Error fetching authorized persons: ' . $e->getMessage());
}
?>

        
<div class="student-info">
  <input type="hidden" id="child-id" value="<?= $child['id']; ?>">

  <!-- Student Details -->
  <div class="student">
    <img src="data:image/jpeg;base64,<?= base64_encode($child['child_image']); ?>" alt="Student Image" />
    <div class="name"><h3><?= htmlspecialchars($child['child_name']); ?></h3></div>
    <div class="grade"><?= htmlspecialchars($child['child_grade'] . ' ' . $child['child_section']); ?></div>
  </div>

  <!-- Download QR Code -->
  <div class="download-qr">
    <a href="download_qr.php?child_id=<?= $child['id']; ?>" style="text-decoration: none; color: inherit;">
      Download QR Code <i class="fas fa-qrcode"></i>
    </a>
  </div>

  <!-- Adviser Information -->
  <div class="adviser">
    <h3>Adviser</h3>
    <div class="person">
      <img src="https://storage.googleapis.com/a1aa/image/zqbcwkcoeh2ZRSu4l1xsGS49xa7kNXkKwPISteTtclGGd6sTA.jpg" alt="Teacher Image" width="50" height="50" />
      <span class="name"><?= htmlspecialchars($teacher['fullname']); ?></span>
    </div>
  </div>

  <!-- Authorized Pick-up Persons -->
  <div class="authorized">
    <h3>Authorized Pick-up Person/s</h3>
    <?php foreach ($authorized_persons as $person): ?>
    <div class="person">
      <img src="https://storage.googleapis.com/a1aa/image/zqbcwkcoeh2ZRSu4l1xsGS49xa7kNXkKwPISteTtclGGd6sTA.jpg" alt="Person Image" width="50" height="50" />
      <span class="name"><?= htmlspecialchars($person['fullname']); ?></span>
      <i class="fas fa-cog settings"></i>
    </div>
    <?php endforeach; ?>
  </div>
      <div class="add-authorized">
        Add Authorized Person <i class="fas fa-plus"></i>
      </div>
    </div>
  </div>
</div>
<!-- Add this Modal HTML near the end of your body -->
<div id="addAuthorizedModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h3>Add Authorized Person</h3>
    <form id="addAuthorizedForm" method="post" enctype="multipart/form-data" action="add_authorized_person.php">
      <label for="fullname">Full Name:</label>
      <input type="text" id="fullname" name="fullname" required />

      <label for="address">Address:</label>
      <input type="text" id="address" name="address" required />

      <label for="age">Age:</label>
      <input type="number" id="age" name="age" required />

      <label for="image">Upload Image:</label>
      <input type="file" id="image" name="image" accept="image/*" required />

      <input type="hidden" name="parent_id" value="<?= $child['id']; ?>" />

      <button type="submit">Add Authorized Person</button>
    </form>
  </div>
</div>

<!-- Modal CSS -->
<style>
  .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
  }

  .modal-content {
    background-color: white;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 40%;
    border-radius: 5px;
  }

  .close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
  }

  .close:hover,
  .close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
  }

  form label {
    display: block;
    margin: 10px 0 5px;
  }

  form input,
  form button {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }

  form button {
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
  }

  form button:hover {
    background-color: #45a049;
  }
</style>

<!-- Modal JavaScript -->
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("addAuthorizedModal");
    const addButton = document.querySelector(".add-authorized");
    const closeButton = document.querySelector(".modal .close");

    addButton.addEventListener("click", () => {
      modal.style.display = "block";
    });

    closeButton.addEventListener("click", () => {
      modal.style.display = "none";
    });

    window.addEventListener("click", (event) => {
      if (event.target === modal) {
        modal.style.display = "none";
      }
    });
  });
  function showNotification(message, type = "success") {
    const notification = document.getElementById("notification");
    notification.className = `notification ${type}`;
    notification.textContent = message;
    notification.style.display = "block";

    // Hide the notification after 3 seconds
    setTimeout(() => {
      notification.style.display = "none";
    }, 3000);
  }
</script>
<script>
  document.getElementById("addAuthorizedForm").addEventListener("submit", async function (event) {
    event.preventDefault();

    const formData = new FormData(this);
    try {
      const response = await fetch("add_authorized_person.php", {
        method: "POST",
        body: formData,
      });

      if (response.ok) {
        showNotification("Authorized person added successfully!", "success");
        this.reset(); // Reset the form after submission
        document.getElementById("addAuthorizedModal").style.display = "none";
      } else {
        const errorText = await response.text();
        showNotification(`Error: ${errorText}`, "error");
      }
    } catch (error) {
      showNotification("An unexpected error occurred.", "error");
    }
  });
</script>

</body>
</html>
