<?php
header('Content-Type: application/json');

// Connect to DB
include('../db.php');

if (isset($_GET['id'])) {
  // Get single car
  $id = intval($_GET['id']);
  $sql = "SELECT * FROM vehicles WHERE VehicleID=$id";
  $result = $conn->query($sql);
  if ($result->num_rows == 1) {
    echo json_encode($result->fetch_assoc());
  } else {
    echo json_encode(null);
  }
} else {
  // Get all cars
  $sql = "SELECT * FROM vehicles ORDER BY VehicleID DESC";
  $result = $conn->query($sql);
  $cars = [];
  while ($row = $result->fetch_assoc()) {
    $cars[] = $row;
  }
  echo json_encode($cars);
}

$conn->close();
?>
