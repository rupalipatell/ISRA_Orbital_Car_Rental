<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        // Register user
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $address = trim($_POST['address']);
        $dl_number = trim($_POST['dl_number']);
        $password = $_POST['password'];

        if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($dl_number) || empty($password)) {
            die("All fields are required.");
        }

        // Check if user exists
        $stmt = $conn->prepare("SELECT CustomerID FROM customers WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            die("Email already registered. Please login.");
        }
        $stmt->close();

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO customers (Name, Email, PhoneNumber, Address, DrivingLicenseNumber, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $phone, $address, $dl_number, $password_hash);
        if ($stmt->execute()) {
            header("Location: ../Frontend/login.php?registered=1");
            exit;
        } else {
            die("Error in registration: " . $conn->error);
        }
    } elseif (isset($_POST['login'])) {
        // Login user
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            die("Email and password are required.");
        }

        $stmt = $conn->prepare("SELECT CustomerID, Name, password FROM customers WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $name, $password_hash);
            $stmt->fetch();
            if (password_verify($password, $password_hash)) {
                // Login successful
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $name;
                header("Location: ../Frontend/user_dashboard.php");
                exit;
            } else {
                die("Incorrect password.");
            }
        } else {
            die("User not found.");
        }
    }
} else {
    die("Invalid request.");
}
?>
