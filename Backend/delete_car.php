<?php
session_start();
include('../db.php');

header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $vehicleId = intval($_POST['id']);
    
    try {
        // Check if car exists first
        $checkStmt = $conn->prepare("SELECT Image FROM vehicles WHERE VehicleID = ?");
        $checkStmt->bind_param("i", $vehicleId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        if ($result->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'Car not found']);
            exit;
        }
        
        $car = $result->fetch_assoc();
        $imagePath = $car['Image'] ? '../../uploads/' . $car['Image'] : null;
        
        // Start transaction
        $conn->begin_transaction();
        
        // Delete from rentals first if foreign key exists
        $conn->query("DELETE FROM rentals WHERE VehicleID = $vehicleId");
        
        // Delete from vehicles
        $deleteStmt = $conn->prepare("DELETE FROM vehicles WHERE VehicleID = ?");
        $deleteStmt->bind_param("i", $vehicleId);
        $deleteStmt->execute();
        
        if ($deleteStmt->affected_rows > 0) {
            // Delete the image file if exists
            if ($imagePath && file_exists($imagePath)) {
                unlink($imagePath);
            }
            
            $conn->commit();
            echo json_encode(['success' => true, 'message' => 'Car deleted successfully']);
        } else {
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => 'Failed to delete car']);
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>