<?php
$mysqli = new mysqli("localhost", "root", "", "isra_orbital");

if ($mysqli->connect_error) {
    die("❌ Connection failed: " . $mysqli->connect_error);
} else {
    echo "✅ Connected successfully to isra_orbital database!";
}
?>
