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

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['q'])) {
  if (isset($_SESSION['logged_user'])) {
    echo json_encode(['user' => $_SESSION['logged_user']['name']]);
  } else {
    echo json_encode(['user' => 'guest']);
  }
  exit();
}
if ($_SERVER['REQUEST_METHOD'] == "GET") {
  if (isset($_SESSION['logged_user'])) {
    session_unset();
    session_destroy();
    echo json_encode(['logout' => true]);
  } else {
    echo json_encode(['logout' => false]);
  }
  exit();
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {

  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = "select * from user where username = ? and password = ?";
  $prep_stmt = $conn->prepare($stmt);
  $prep_stmt->bind_param("ss", $username, $password);
  $prep_stmt->execute();
  $result = $prep_stmt->get_result();
  $user_array = $result->fetch_assoc();
  // Check if user is found
  if ($user_array) {
    $_SESSION['logged_user']['name'] = $user_array['username'];
    $_SESSION['logged_user']['id'] = $user_array['id'];
    echo json_encode(["user" => $_SESSION['logged_user']['name']]);
  } else {
    echo json_encode(["error" => "Invalid username or password"]);
  }

  $prep_stmt->close();
  exit();
}
