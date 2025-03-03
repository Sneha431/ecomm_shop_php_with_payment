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
if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['id'])) {
  $stmt = "select stock from inventory where product_id = ?";
  $prepare_stmt = $conn->prepare($stmt);
  $id = $_GET['id'];
  $prepare_stmt->bind_param('i', $id);
  $prepare_stmt->execute();
  if ($result = $prepare_stmt->get_result()) {
    // $arr = array();
    // while ($rowArr = $result->fetch_assoc()) {
    // if (isset($rowArr)) {
    // array_push($arr, $result->fetch_assoc());
    // }
    // }
    echo json_encode(['stock' => $result->fetch_assoc()['stock']]);
  } else {
    echo json_encode(['error' => 'Something went wrong,try again later']);
  }
  $prepare_stmt->close();
  exit;
}
