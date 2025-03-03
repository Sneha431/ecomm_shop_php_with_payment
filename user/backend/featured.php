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
if ($_SERVER['REQUEST_METHOD'] === "GET") {
  $stmt = "select * from product where status = 1 order by rand() limit 3";
  if ($result = $conn->query($stmt)) {
    $arr = array();
    while ($rowArr = $result->fetch_assoc()) {
      if (isset($rowArr)) {
        array_push($arr, $rowArr);
      }
    }
    echo json_encode(['featured' => $arr]);
  } else {
    echo json_encode(['error' => 'Something went wrong,try again later']);
  }
  exit;
}
