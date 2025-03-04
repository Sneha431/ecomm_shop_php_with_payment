<?php

require './include/guest_user_cart.php';
require './include/logged_user_cart.php';
if ($_SERVER['REQUEST_METHOD'] === "GET") {
  if (isset($_SESSION['logged_user']))
    getLoggedUserCart();
  else
    getGuestUserCart();
  exit();
}
if ($_SERVER['REQUEST_METHOD'] === "POST") {
  if (isset($_SESSION['logged_user']))
    addtologgedUserCart();
  else
    addtoguestUserCart();
  exit();
}