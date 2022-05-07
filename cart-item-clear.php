<?php

  session_start();
  if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in'])) {
    header("Location: login.php");
  }
  unset($_SESSION['cart'][$_GET['id']]);
  header('location: cart.php');
