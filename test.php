<?php
// Make sure mysqli is available
if (!extension_loaded('mysqli')) {
    die('mysqli extension not loaded!');
}

$conn = new mysqli("localhost", "root", "root", "eshop");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully!";
?>
