<?php
header('Content-Type: application/json');
include('../db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $vehicleId = intval($_POST['carId']);
  $brand = $_POST['brand'];
  $model = $_POST['model'];
  $capacity = intval($_POST['capacity']);
  $registrationNumber = $_POST['registrationNumber'];
  $rentPerHour = floatval($_POST['rentPerHour']);
  $availableStatus = intval($_POST['availableStatus']);

  $sql = "UPDATE vehicles SET 
            Brand = ?, 
            Model = ?, 
            Capacity = ?, 
            RegistrationNumber = ?, 
            RentPerHour = ?, 
            AvailableStatus = ? 
          WHERE VehicleID = ?";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssisdii", $brand, $model, $capacity, $registrationNumber, $rentPerHour, $availableStatus, $vehicleId);

  if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Car updated successfully"]);
  } else {
    echo json_encode(["success" => false, "message" => "Update failed: " . $conn->error]);
  }

  $stmt->close();
} else {
  echo json_encode(["success" => false, "message" => "Invalid request"]);
}

$conn->close();
?>
