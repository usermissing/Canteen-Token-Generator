<?php
// Connect to your database
include('../connection.php');

// Get the order ID from the POST request
$data = json_decode(file_get_contents('php://input'), true);
$oid = $data['oid'];

// Update the order status to 'Completed'
$sql = "UPDATE orders SET status = 'Completed' WHERE oid = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $oid);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

$stmt->close();
$con->close();
?>