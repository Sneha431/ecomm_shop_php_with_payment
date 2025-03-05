<?php

require './include/guest_user_cart.php';
require './include/logged_user_cart.php';

//return cart to the user
if ($_SERVER['REQUEST_METHOD'] === "GET") {
  if (isset($_SESSION['logged_user']))
    getLoggedUserCart();
  else
    getGuestUserCart();
  exit();
}

//add new product to cart
if ($_SERVER['REQUEST_METHOD'] === "POST") {
  if (isset($_SESSION['logged_user']))
    addtologgedUserCart();
  else
    addtoguestUserCart();
  exit();
}
//update cart quantity
if ($_SERVER['REQUEST_METHOD'] === "PATCH") {
  parse_str(file_get_contents("php://input"), $_PATCH);
  if (isset($_SESSION['logged_user']))
    updateLoggedUserCart($_PATCH);
  else
    updateGuestUserCart($_PATCH);
  exit();
}

//delete cart quantity
if ($_SERVER['REQUEST_METHOD'] === "DELETE") {
  parse_str(file_get_contents("php://input"), $_DELETE);
  if (isset($_SESSION['logged_user']))
    deleteLoggedUserCartProduct($_DELETE);
  else
    deleteGuestUserCartProduct($_DELETE);
  exit();
}