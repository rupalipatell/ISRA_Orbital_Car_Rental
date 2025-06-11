<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

include '../db.php';  // Make sure this file sets up $conn correctly

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Check required fields
if (
    empty($_POST['userId']) || empty($_POST['name']) || empty($_POST['email']) || empty($_POST['phoneNumber'])
) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Sanitize inputs
$id = intval($_POST['userId']);
$name = $conn->real_escape_string(trim($_POST['name']));
$email = $conn->real_escape_string(trim($_POST['email']));
$phone = $conn->real_escape_string(trim($_POST['phoneNumber']));
$address = isset($_POST['address']) ? $conn->real_escape_string(trim($_POST['address'])) : '';
$driving_license = isset($_POST['drivingLicenseNumber']) ? $conn->real_escape_string(trim($_POST['drivingLicenseNumber'])) : '';

/* Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}*/

// Build UPDATE query
$sql = "UPDATE customers SET 
            Name='$name',
            Email='$email',
            PhoneNumber='$phone',
            Address='$address',
            DrivingLicenseNumber='$driving_license'
        WHERE CustomerID=$id";

// Execute and check result
if ($conn->query($sql) === TRUE) {
    if ($conn->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'User updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No changes made to the user']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
}
?>
