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
if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['category'])) {
  $stmt = "select * from product where status = 1 and category_id = (select id from category where name =?)";
  $prepare_stmt = $conn->prepare($stmt);
  $category = $_GET['category'];
  $prepare_stmt->bind_param('s', $category);
  $prepare_stmt->execute();
  if ($result = $prepare_stmt->get_result()) {
    $arr = array();
    while ($rowArr = $result->fetch_assoc()) {
      if (isset($rowArr)) {
        array_push($arr, $rowArr);
      }
    }
    echo json_encode(['products' => $arr]);
  } else {
    echo json_encode(['error' => 'Something went wrong,try again later']);
  }
  $prepare_stmt->close();
  exit;
}
