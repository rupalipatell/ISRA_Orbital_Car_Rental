<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('../db.php');

header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookingID = intval($_POST['id']);
    $newStatus = $_POST['status']; // 'Approved' or 'Rejected'

    if (!in_array($newStatus, ['Approved', 'Rejected'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid status']);
        exit;
    }

    // Get booking data
    $stmt = $conn->prepare("SELECT customerID, VehicleID, StartDateTime, EndDateTime FROM booking WHERE BookingID = ? AND status = 'Pending'");
    $stmt->bind_param("i", $bookingID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Booking not found or already processed.']);
        exit;
    }

    $booking = $result->fetch_assoc();

    // Update status
    $updateStmt = $conn->prepare("UPDATE booking SET status = ? WHERE BookingID = ?");
    $updateStmt->bind_param("si", $newStatus, $bookingID);

    if ($updateStmt->execute()) {
        if ($newStatus === 'Approved') {
            // Insert into rentals table
            $insertStmt = $conn->prepare("INSERT INTO rentals (user_id, car_id, start_date, end_date, status) VALUES (?, ?, ?, ?, 'Active')");
            $insertStmt->bind_param(
                "iiss",
                $booking['customerID'],
                $booking['VehicleID'],
                $booking['StartDateTime'],
                $booking['EndDateTime']
            );

            if (!$insertStmt->execute()) {
                // Rollback booking update
                $conn->query("UPDATE booking SET status = 'Pending' WHERE BookingID = $bookingID");
                echo json_encode(['success' => false, 'message' => 'Failed to insert into rentals.']);
                exit;
            }

            // Update payment status to 'Completed'
            $paymentUpdateStmt = $conn->prepare("UPDATE booking SET PaymentStatus = 'Completed' WHERE BookingID = ?");
            $paymentUpdateStmt->bind_param("i", $bookingID);
            if (!$paymentUpdateStmt->execute()) {
                // Rollback if payment status update fails
                $conn->query("UPDATE booking SET status = 'Pending' WHERE BookingID = $bookingID");
                echo json_encode(['success' => false, 'message' => 'Failed to update payment status.']);
                exit;
            }
        }

        echo json_encode(['success' => true, 'message' => "Request $newStatus successfully."]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update booking status.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
