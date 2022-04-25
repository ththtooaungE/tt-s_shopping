<?php

session_start();
require "../config/config.php";

if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in']) || empty($_SESSION['role'])) {
  header('Location: login.php');
  exit();
}

$stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
$result = $stmt->execute(array(':id'=>$_GET['id']));

header("location: index.php?page_num=".$_GET['page_num']);
exit();
