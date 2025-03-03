<?php
//header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Origin: http://localhost');
header('Access-Control-Allow-Credentials:true');
session_start(['cookie_samesite' => 'None', 'cookie_secure' => true]);
$conn = new mysqli("localhost:3306", "root", "root", "eshop");
if ($conn->connect_errno) {
  echo json_encode(["error" => $conn->connect_error]);
  exit;
}
