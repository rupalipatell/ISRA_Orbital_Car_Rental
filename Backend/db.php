<?php
$host     = "localhost";
$username = "root";
$password = "";
$dbname   = "isra_orbital";

$conn = new mysqli($host, $username, $password, $dbname);


if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}


echo "✅ Database connected successfully!";
?>
