<?php
include('../db.php');

$brand = $_POST['brand'] ?? '';
$model = $_POST['model'] ?? '';
$capacity = intval($_POST['capacity'] ?? 0);
$registrationNumber = $_POST['registrationNumber'] ?? '';
$rentPerHour = floatval($_POST['rentPerHour'] ?? 0);
$availableStatus = intval($_POST['availableStatus'] ?? 0);
$imageName = null;

// Handle image upload
if (isset($_FILES['carImage']) && $_FILES['carImage']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../../uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    $imageName = uniqid() . '_' . basename($_FILES['carImage']['name']);
    $uploadPath = $uploadDir . $imageName;
    if (!move_uploaded_file($_FILES['carImage']['tmp_name'], $uploadPath)) {
        echo json_encode(['success' => false, 'message' => 'Image upload failed']);
        exit;
    }
}

$stmt = $conn->prepare("INSERT INTO vehicles (Brand, Model, Capacity, RegistrationNumber, RentPerHour, AvailableStatus, Image) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssisdss", $brand, $model, $capacity, $registrationNumber, $rentPerHour, $availableStatus, $imageName);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Car added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add car']);
}

$stmt->close();
$conn->close();
?>
