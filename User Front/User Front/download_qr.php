<?php
require 'connection.php';
header('Content-Type: image/png');
header('Content-Length: ' . strlen($child['qrimage']));
echo $child['qrimage'];

// Check for child_id in the URL
if (!isset($_GET['child_id'])) {
    die('Child ID is required.');
}

$child_id = intval($_GET['child_id']);

// Fetch the QR code BLOB from the database
$stmt = $pdo->prepare('SELECT qrimage FROM child_acc WHERE id = ?');
$stmt->execute([$child_id]);
$child = $stmt->fetch();

if (!$child) {
    die('Child not found.');
}

// Check if QR code exists in the database
if (empty($child['qrimage'])) {
    die('No QR code available for this student.');
}

// Output the image
header('Content-Type: image/png');
echo $child['qrimage'];
exit;
?>
