<?php
include('../db.php');

header('Content-Type: application/json');

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Prepare and execute delete query
    $stmt = $conn->prepare("DELETE FROM vehicles WHERE VehicleID = ?");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Car deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Delete failed: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'ID not provided']);
}

$conn->close();
?>
