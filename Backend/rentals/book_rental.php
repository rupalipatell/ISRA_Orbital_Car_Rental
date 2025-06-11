<?php
session_start();

// Redirect to login if user not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Frontend/login.php");
    exit;
}

include('../db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $vehicle_id = intval($_POST['vehicle_id'] ?? 0);
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';

    // Basic validation
    if (!$vehicle_id || empty($start_date) || empty($end_date)) {
        die("Please fill all fields.");
    }

    if ($start_date >= $end_date) {
        die("End date/time must be after start date/time.");
    }

    // Calculate total hours
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $interval = $start->diff($end);
    $totalHours = $interval->days * 24 + $interval->h + ($interval->i / 60);

    // Get rent per hour for the selected vehicle
    $rentQuery = $conn->prepare("SELECT RentPerHour FROM vehicles WHERE VehicleID = ?");
    $rentQuery->bind_param("i", $vehicle_id);
    $rentQuery->execute();
    $rentResult = $rentQuery->get_result();

    if ($rentResult->num_rows === 0) {
        die("Vehicle not found.");
    }

    $rentData = $rentResult->fetch_assoc();
    $rentPerHour = $rentData['RentPerHour'];
    $totalAmount = $totalHours * $rentPerHour;

    // Insert booking with status 'Pending'
    $stmt = $conn->prepare("INSERT INTO booking 
        (CustomerID, VehicleID, StartDateTime, EndDateTime, BookingDate, PaymentStatus, TotalHours, TotalAmount, status) 
        VALUES (?, ?, ?, ?, NOW(), 'Pending', ?, ?, 'Pending')");

    $stmt->bind_param("iissdd", $user_id, $vehicle_id, $start_date, $end_date, $totalHours, $totalAmount);

    if ($stmt->execute()) {
        // Redirect on success
        header("Location: ../../Frontend/user_dashboard.php?booking=success");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    // Redirect if accessed without POST
    header("Location: ../Frontend/user_dashboard.php");
    exit;
}
?>
