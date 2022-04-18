<?php
  session_start();
  require "../config/config.php";

  if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in']) || empty($_SESSION['role'])) {
    header('Location: login.php');
    exit();
  }

  $stmt = $pdo->prepare("DELETE FROM categories WHERE id = :id");
  $result = $stmt->execute(array(':id'=>$_GET['id']));

  header("location: category.php");
  exit();
