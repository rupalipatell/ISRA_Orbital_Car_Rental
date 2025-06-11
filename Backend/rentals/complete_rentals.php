<?php
// complete_rentals.php
include('../db.php');

// Get current date only (assuming end_date is of type DATE)
$now = new DateTime();
$nowStr = $now->format('Y-m-d');

// Prepare and execute the update query
$stmt = $conn->prepare("UPDATE rentals SET status = 'Completed' WHERE end_date <= ? AND status = 'Active'");
$stmt->bind_param("s", $nowStr);

if ($stmt->execute()) {
    echo "Completed rentals updated successfully.";
} else {
    echo "Error updating rentals: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
