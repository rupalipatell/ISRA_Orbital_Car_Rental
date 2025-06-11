<?php
session_start();
include('../db.php');

// Optional: check if admin is logged in (if you have admin session management)
// if (!isset($_SESSION['admin_id'])) {
//     http_response_code(401);
//     echo json_encode([]);
//     exit;
// }

header('Content-Type: application/json');

$sql = "SELECT 
    b.BookingID, b.CustomerID, b.VehicleID, b.StartDateTime, b.EndDateTime, b.TotalHours, b.TotalAmount, b.PaymentStatus, b.status,
    u.Name AS CustomerName,
    v.Brand AS VehicleBrand,
    v.Model AS VehicleModel
FROM booking b
JOIN customers u ON b.customerID = u.customerID
LEFT JOIN vehicles v ON b.VehicleID = v.VehicleID
WHERE b.status = 'Pending';
";


$result = $conn->query($sql);

$requests = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
}

echo json_encode($requests);
?>
