<?php
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $age = $_POST['age'];
    $parent_id = $_POST['parent_id'];

    // Handle the uploaded image
    $image = $_FILES['image'];
    $imagePath = 'uploads/' . basename($image['name']);

    if (move_uploaded_file($image['tmp_name'], $imagePath)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO authorized_persons (fullname, address, age, authorized_image, parent_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$fullname, $address, $age, $imagePath, $parent_id]);

            http_response_code(200);
            echo "Success";
        } catch (PDOException $e) {
            http_response_code(500);
            echo "Error: " . $e->getMessage();
        }
    } else {
        http_response_code(500);
        echo "Failed to upload the image.";
    }
} else {
    http_response_code(400);
    echo "Invalid request method.";
}


?>
