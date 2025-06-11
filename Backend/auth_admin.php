<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Registration logic
    if (isset($_POST['register'])) {
        $admin_name = trim($_POST['admin_name']);
        $admin_email = trim($_POST['admin_email']);
        $admin_password = $_POST['admin_password'];

        if (empty($admin_name) || empty($admin_email) || empty($admin_password)) {
            die("All fields are required.");
        }

        // Check if admin email exists
        $stmt = $conn->prepare("SELECT id FROM admins WHERE admin_email = ?");
        $stmt->bind_param("s", $admin_email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            die("Admin email already registered. Please login.");
        }
        $stmt->close();

        $password_hash = password_hash($admin_password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO admins (admin_name, admin_email, admin_password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $admin_name, $admin_email, $password_hash);
        if ($stmt->execute()) {
            header("Location: ../Frontend/login.php?registered=1");
            exit;
        } else {
            die("Error in admin registration: " . $conn->error);
        }
    }
    // Login logic
    elseif (isset($_POST['login'])) {
        $admin_email = trim($_POST['admin_email']);
        $admin_password = $_POST['admin_password'];

        if (empty($admin_email) || empty($admin_password)) {
            die("Email and password are required.");
        }

        $stmt = $conn->prepare("SELECT id, admin_name, admin_password FROM admins WHERE admin_email = ?");
        $stmt->bind_param("s", $admin_email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $admin_name, $password_hash);
            $stmt->fetch();

            if (password_verify($admin_password, $password_hash)) {
                $_SESSION['admin_id'] = $id;
                $_SESSION['admin_name'] = $admin_name;
                header("Location: ../Frontend/admin_dashboard.php");
                exit;
            } else {
                die("Incorrect password.");
            }
        } else {
            die("Admin not found.");
        }
    } else {
        die("Invalid action.");
    }
} else {
    die("Invalid request.");
}
?>
