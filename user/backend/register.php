<?php
require './include/db.php';
// Allow all origins (you can specify the domain as needed for security)
// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
// header('Access-Control-Allow-Headers: Content-Type');
// if ($_SERVER['REQUEST_METHOD'] === "OPTIONS") {
//   // Just return an empty response to satisfy preflight check
//   http_response_code(200);
//   exit;
// }

if ($_SERVER['REQUEST_METHOD'] == "GET") {
  $stmt = 'describe user';
  $result = $conn->query($stmt);
  $arr = array();
  while ($row = $result->fetch_assoc()) {
    array_push($arr, $row);
  }
  array_splice($arr, 0, 1);
  echo json_encode(['columns' => $arr]);
  exit();
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];
  $first_name = $_POST["first_name"];
  $last_name = $_POST["last_name"];
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  $stmt = "insert into user(username,email,password,first_name,last_name)value(?,?,?,?,?)";
  $prep_stmt = $conn->prepare($stmt);
  $prep_stmt->bind_param('sssss', $username, $email, $hashed_password, $first_name, $last_name);
  if ($prep_stmt === false) {
    echo json_encode(["error" => 'Failed to prepare statement: ' . $conn->error]);
    exit();
  }
  if ($prep_stmt->execute()) {
    echo json_encode(["registration" => true]);
  } else {
    echo json_encode(["error" => 'Registration failed ! Try again later']);
  }
  $prep_stmt->close();
  exit();
}