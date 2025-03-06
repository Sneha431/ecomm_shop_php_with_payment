<?php
require './include/db.php';

function getLoggedUserCart()
{
  global $conn;
  $cart = array();
  //step 1:get the id from cart table where user_id = logged_user user_id;
  $user_id = getUserId();
  $cart_id = getCartId($user_id);
  if ($cart_id) {
    $cart = getAllCartItems($cart_id);
    echo json_encode(['cart' => $cart]);
  }
}
function addtologgedUserCart()
{
  global $conn;
  $prod_id = $_POST['id'];
  $quantity = $_POST['quantity'];
  $cart_id = getCartId(getUserId());
  $stmt = "insert into cart_item(cart_id,prod_id,quantity) values(?,?,?) on duplicate key update
   quantity = quantity + ? ";
  $prep_stmt = $conn->prepare($stmt);
  $prep_stmt->bind_param("iiii", $cart_id, $prod_id, $quantity, $quantity);
  $prep_stmt->execute();
  $prep_stmt->close();
  $cart = getAllCartItems($cart_id);
  echo json_encode(['cart' => $cart]);
}
function updateLoggedUserCart($_PATCH)
{
  global $conn;
  $prod_id = $_PATCH['id'];
  $quantity = $_PATCH['quantity'];
  $cart_id = getCartId(getUserId());
  $stmt = "update cart_item set quantity = ? where cart_id=$cart_id and prod_id=$prod_id";
  $prep_stmt = $conn->prepare($stmt);
  $prep_stmt->bind_param("ii", $cart_id, $prod_id, $quantity, $quantity);
  $prep_stmt->execute();
  $prep_stmt->close();
  $cart = getAllCartItems($cart_id);
  echo json_encode(['cart' => $cart]);
}
function deleteLoggedUserCartProduct($_DELETE)
{
  global $conn;
  $prod_id = $_DELETE['id'];

  $cart_id = getCartId(getUserId());
  $stmt = "delete from cart_item where cart_id=$cart_id and prod_id=?";
  $prep_stmt = $conn->prepare($stmt);
  $prep_stmt->bind_param("i", $prod_id);
  $prep_stmt->execute();
  $prep_stmt->close();
  $cart = getAllCartItems($cart_id);
  echo json_encode(['cart' => $cart]);
}
//helper func
function getUserId()
{
  return $_SESSION['logged_user']['id'];
}
function getCartId($user_id)
{
  global $conn;
  $cart_id = null;
  $stmt = "select id from cart where user_id=$user_id";
  if ($result = $conn->query($stmt)) {
    if ($result->num_rows) {
      $cart_id = $result->fetch_assoc()['id'];
    } else {
      $stmt = "insert into cart(user_id) values ($user_id)";
      if ($result = $conn->query($stmt)) {
        if ($result->affected_rows) {
          $cart_id = $conn->insert_id;
        }
      }
    }
  }
  return $cart_id;
}
//helper - func
function getAllCartItems($cart_id)
{
  global $conn;
  $stmt = "select p.id,p.image,truncate((p.price * ci.quantity),2) as price,ci.quantity,
i.stock from product p inner join cart_item ci on p.id = ci.prod_id AND ci.cart_id = $cart_id 
inner join inventory i on p.id = i.product_id";
  if ($result = $conn->query($stmt)) {
    if ($result->num_rows) {
      while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $prod_array[$id]['image'] = $row['image'];
        $prod_array[$id]['stock'] = $row['stock'];
        $prod_array[$id]['quantity'] = $row['quantity'];
        $prod_array[$id]['price'] = $row['price'];
      }
    }
  }
  return updateTotalLoggedCart($prod_array);
}

//helper func - calculate total price for all the product
function updateTotalLoggedCart($prod_array)
{
  $total = 0.0;
  foreach ($prod_array as $item) {
    $total += $item['price'];
    $total = round($total, 2);
  }
  $prod_array['total'] = $total;
  return $prod_array;
}
