<?php
//notice that I reversed the working flow of code block

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['_token'],$_POST['_token'])) {
      echo "Invalid csrf token";
      unset($_SESSION['_token']);
      die();
    } else {
      unset($_SESSION['_token']);
    }
  }

  if (empty($_SESSION['_token'])) {

    if (function_exists('radom_bytes')) {
      $_SESSION['_token'] = bin2hex(random_bytes(32));
    } elseif (function_exists('mcrypt_create_iv')) {
      $_SESSION['_token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
    } elseif (function_exists('openssl_random_pseudo_bytes')) {
      //this condition works
      $_SESSION['_token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
  }

  //escape html for output
  function escape($html) {
    return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
  }
