<?php
require("./include/logged_user_cart.php");
require_once '../../vendor/autoload.php';
\Stripe\Stripe::setApiKey('sk_test_51OSKsaSEr3MdfqulXhf8BkX8n64qlvIMF2Z3w0UKejsUzcbCUinagskCXV8WcDvPKnquiVd0suKmKk2rDUWhfKSz00FENsCsZu');
header('Content-Type: application/json');

$cart = [];
$cart_id = null;
$logged_user_id = null;
$total_price = 0;
$name = '';
$stripe_data = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_SESSION["logged_user"])) {
    // Logged-in user
    $name = $_SESSION['logged_user']['name'];
    $logged_user_id = $_SESSION['logged_user']['id'];
    $cart_id = getCartId($logged_user_id);
    $cart = getAllCartItems($cart_id);
  } else {
    // Non-logged-in user
    $name = isset($_POST['name']) ? $_POST['name'] : 'Guest';
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : []; // Default to empty array if cart isn't set
  }

  if (empty($cart)) {
    echo json_encode(["error" => "Cart is empty"]);
    exit();
  }

  $total_price = $cart['total'] ?? 0; // Handle case if 'total' is not set
  unset($cart['total']);
  $stripe_data = initiateCheckout($total_price);

  addToOrderTable();
  clearCart();

  echo json_encode(["url" => $stripe_data["url"]]);

  exit();
}

function initiateCheckout($price)
{
  $YOUR_DOMAIN = 'http://localhost:80/ecomm_shop_php_with_payment/user/frontend/';
  $checkout_session = \Stripe\Checkout\Session::create([
    'line_items' => [[
      'price_data' => [
        'currency' => 'usd',
        'product_data' => ['name' => 'customer shopping bill'],
        'unit_amount' => $price * 100,
      ],
      'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => $YOUR_DOMAIN . 'success.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => $YOUR_DOMAIN . 'cancel.html',
  ]);

  return [
    'url' => $checkout_session->url,
    'payment_id' => $checkout_session->payment_intent
  ];
}

function addToOrderTable()
{
  global $cart, $conn, $logged_user_id, $total_price, $name, $stripe_data;

  $address = $_POST["address"];
  $city = $_POST["city"];
  $postcode = $_POST["postcode"];
  $user_id = $logged_user_id == NULL ? "NULL" : $logged_user_id;

  $payment_id = $stripe_data["payment_id"];

  $stmt = "insert into eshop.order(user_id,name,address,city,post_code,total_price,payment_id,order_status)
  values($user_id,'$name','$address','$city','$postcode',$total_price,'$payment_id','pending')";
  $conn->query($stmt);
  $order_id = $conn->insert_id;



  foreach ($cart as $id => $product) {
    $quantity = $product['quantity'];
    $prod_price = $product['price'];
    $stmt = "insert into eshop.order_item(order_id,product_id,quantity,price)
    values($order_id,$id,$quantity,$prod_price)";

    $conn->query($stmt);


    $_SESSION["transaction_id"] =  $payment_id;
    $_SESSION["total"] = $total_price;
    $_SESSION["user_id"] = $user_id;
  }

  return;
}

function clearCart()
{
  global $cart_id, $conn;
  if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart']);
  } else {
    $stmt = "delete from cart where id = $cart_id";
    $conn->query($stmt);
  }
}
