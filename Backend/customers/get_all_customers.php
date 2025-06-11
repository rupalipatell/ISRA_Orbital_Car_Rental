<?php
header('Content-Type: application/json');
include '../db.php';  // Adjust path as needed

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        // Fetch single user by ID
        $userId = intval($_GET['id']);
        $stmt = $conn->prepare("SELECT CustomerID, Name, Email, PhoneNumber, Address, DrivingLicenseNumber FROM customers WHERE CustomerID = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user) {
            echo json_encode($user);
        } else {
            echo json_encode(['error' => 'User not found']);
        }
    } else {
        // Fetch all users
        $result = $conn->query("SELECT CustomerID, Name, Email, PhoneNumber, Address, DrivingLicenseNumber FROM customers ORDER BY CustomerID DESC");
        $users = [];

        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        echo json_encode($users);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}

$conn->close();
?>
