<?php
header('Content-Type: application/json');
require_once '../db.php';

$response = [
    'success' => false,
    'data' => [],
    'error' => ''
];

if (!isset($_GET['user_id'])) {
    $response['error'] = 'User ID not provided.';
    echo json_encode($response);
    exit;
}

if ($conn->connect_error) {
    $response['error'] = "DB Connection failed: " . $conn->connect_error;
    echo json_encode($response);
    exit;
}

$user_id = intval($_GET['user_id']);

$sql = "SELECT r.id, r.start_date, r.end_date, r.car_id, 
               v.Brand, v.Model, v.RegistrationNumber,
               r.status
        FROM rentals r
        JOIN vehicles v ON r.car_id = v.VehicleID
        WHERE r.user_id = ?
        ORDER BY r.start_date DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    $response['error'] = "Prepare statement failed: " . $conn->error;
    echo json_encode($response);
    exit;
}

$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $rentals = [];

    while ($row = $result->fetch_assoc()) {
        $rentals[] = $row;
    }

    $response['success'] = true;
    $response['data'] = $rentals;
} else {
    $response['error'] = 'Query execution failed: ' . $stmt->error;
}

$stmt->close();
$conn->close();

echo json_encode($response);
