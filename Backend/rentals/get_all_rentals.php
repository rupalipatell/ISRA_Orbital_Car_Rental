<?php
// Backend API: get_all_rentals.php
header('Content-Type: application/json');
include '../db.php'; 

$sql = "SELECT 
          r.id, r.start_date, r.end_date, r.Status,
          v.Brand, v.Model,
          c.Name AS RenterName
        FROM rentals r
        JOIN vehicles v ON r.car_id = v.VehicleID
        JOIN customers c ON r.user_id = c.CustomerID
        ORDER BY r.start_date DESC";

$result = $conn->query($sql);

$current_rentals = [];
$past_rentals = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        if ($row['Status'] === 'Active') {
            $current_rentals[] = $row;
        } elseif ($row['Status'] === 'Completed') {
            $past_rentals[] = $row;
        }
        // You can add more status handling if needed
    }
    echo json_encode([
        'success' => true, 
        'current_rentals' => $current_rentals, 
        'past_rentals' => $past_rentals
    ]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}
?>
