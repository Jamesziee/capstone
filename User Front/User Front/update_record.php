<?php
include 'db_connection.php'; // Adjust this line to match your database connection file

// Get JSON input data
$data = json_decode(file_get_contents('php://input'), true);

$student_id = $data['student_id'];
$action = $data['action'];

if ($student_id && $action) {
    $current_time = date('Y-m-d H:i:s');
    $query = '';

    if ($action === 'time_in') {
        $query = "UPDATE pickup_records SET time_in = '$current_time' WHERE student_id = '$student_id'";
    } elseif ($action === 'time_out') {
        $query = "UPDATE pickup_records SET time_out = '$current_time' WHERE student_id = '$student_id'";
    }

    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
}
?>
