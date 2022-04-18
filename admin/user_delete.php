<?php

  session_start();
  require "../config/config.php";

  $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
  $stmt->execute(array(':id'=>$_GET['id']));

  header('location: user_list.php');
  exit();
