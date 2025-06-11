<?php
header('Content-Type: application/json');
include '../db.php';  // Ensure this matches your DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $userId = intval($_POST['id']);
        
        // Prepare and execute delete query
        $stmt = $conn->prepare("DELETE FROM customers WHERE CustomerID = ?");
        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'User deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete user.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'User ID is required.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

$conn->close();
?>
