<?php

  session_start();
  require 'config/config.php';
  require 'config/common.php';

  $id = $_POST['id'];
  $qty = $_POST['qty'];

  $stmt = $pdo->prepare("SELECT quantity FROM products WHERE id = :id");
  $stmt->execute([':id'=>$id]);
  $product_quantity = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($product_quantity['quantity'] < $qty) {
    echo "<script>alert('Stock is not enough!');window.location.href='single_product.php?id=$id';</script>";
    exit();
  }

  if (empty($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id] = $qty;
  } else {
    $_SESSION['cart'][$id] += $qty;
  }

  header('location: index.php');
