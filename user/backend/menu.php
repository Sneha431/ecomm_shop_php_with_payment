<?php
require './include/db.php';
// Allow all origins (you can specify the domain as needed for security)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
// if ($_SERVER['REQUEST_METHOD'] === "OPTIONS") {
//   // Just return an empty response to satisfy preflight check
//   http_response_code(200);
//   exit;
// }
if ($_SERVER['REQUEST_METHOD'] === "GET") {
  $stmt = "select name from category where status = 1";
  if ($result = $conn->query($stmt)) {
    $arr = array();
    while ($row = $result->fetch_assoc()) {
      if (isset($row['name'])) {
        array_push($arr, $row['name']);
      }
    }
    echo json_encode(['categories' => $arr]);
  } else {
    echo json_encode(['error' => 'Something went wrong,try again later']);
  }
  exit;
}