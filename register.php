<?php
	session_start();
	require "config/config.php";
  require "config/common.php";

	if ($_POST) {
		if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) ||
      strlen($_POST['password']) < 5 || empty($_POST['address']) || empty($_POST['phone']) ||
      !is_numeric($_POST['phone'])) {
      if (empty($_POST['name'])) $nameError = 'This field is required!';
			if (empty($_POST['email'])) $emailError = 'This field is required!';
      if (empty($_POST['password'])) $passwordError = 'This field is required!';
      if (empty($_POST['address'])) $addressError = 'This field is required!';
      if (empty($_POST['phone'])) $phoneError = 'This field is required!';
      if (!empty($_POST['password']) && strlen($_POST['password']) < 5) $passwordError = 'The number of characters must be longer than 4!';
      if (!empty($_POST['phone']) && !is_numeric($_POST['phone'])) $phoneError = 'This field must contain munbers!';

		} else {
      $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
      $stmt->execute([':email'=>$_POST['email']]);
      $user = $stmt->fetchColumn();

      if (empty($user)) {
        $stmt = $pdo->prepare("INSERT INTO users(name, email, password, address, phone, created_at)
        VALUES(:name, :email, :password, :address, :phone, NOW())");
        $result = $stmt->execute([
          ':name'=>$_POST['name'], ':email'=>$_POST['email'],':password'=>password_hash($_POST['password'], PASSWORD_DEFAULT),
          ':address'=>$_POST['address'], ':phone'=>$_POST['phone'],
        ]);
        if ($result) {
          echo "<script>alert('New account is created. Please continue to log in.');
          window.location.href = 'login.php';</script>";
          exit();
        }
      }
      echo "<script>alert('Duplicated email!');</script>";
		}
	}
 ?>

<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
	<!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon-->
	<link rel="shortcut icon" href="img/fav.png">
	<!-- Author Meta -->
	<meta name="author" content="CodePixar">
	<!-- Meta Description -->
	<meta name="description" content="">
	<!-- Meta Keyword -->
	<meta name="keywords" content="">
	<!-- meta character set -->
	<meta charset="UTF-8">
	<!-- Site Title -->
	<title>tt's Shopping</title>

	<!--
		CSS
		============================================= -->
	<link rel="stylesheet" href="css/linearicons.css">
	<link rel="stylesheet" href="css/owl.carousel.css">
	<link rel="stylesheet" href="css/themify-icons.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/nice-select.css">
	<link rel="stylesheet" href="css/nouislider.min.css">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/main.css">
</head>

<body>

	<!-- Start Header Area -->
	<header class="header_area sticky-header">
		<div class="search_input" id="search_input_box">
			<div class="container">
				<form class="d-flex justify-content-between">
					<input type="text" class="form-control" id="search_input" placeholder="Search Here">
					<button type="submit" class="btn"></button>
					<span class="lnr lnr-cross" id="close_search" title="Close Search"></span>
				</form>
			</div>
		</div>
	</header>
	<!-- End Header Area -->

	<!-- Start Banner Area -->
	<section class="banner-area organic-breadcrumb">
		<div class="container">
			<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
				<div class="col-first">
					<h1>Login/Register</h1>
					<nav class="d-flex align-items-center">
						<a href="index.html">Home<span class="lnr lnr-arrow-right"></span></a>
						<a href="category.html">Login/Register</a>
					</nav>
				</div>
			</div>
		</div>
	</section>
	<!-- End Banner Area -->

	<!--================Login Box Area =================-->
	<section class="login_box_area">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="login_form_inner">
						<h3>Register a new account</h3>
						<form class="row login_form" action="" method="post" id="contactForm" novalidate="novalidate">
              <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
              <span style="font-size:13px; color:red;"><?= $nameError ?? "" ?></span>
              <div class="col-md-12 form-group">
								<input type="text" class="form-control" name="name" placeholder="Username">
							</div>
              <span style="font-size:13px; color:red;"><?= $emailError ?? "" ?></span>
              <div class="col-md-12 form-group">
								<input type="email" class="form-control" name="email" placeholder="Email">
							</div>
              <span style="font-size:13px; color:red;"><?= $passwordError ?? "" ?></span>
              <div class="col-md-12 form-group">
								<input type="password" class="form-control" name="password" placeholder="Password">
							</div>
              <span style="font-size:13px; color:red;"><?= $addressError ?? "" ?></span>
              <div class="col-md-12 form-group">
								<input type="text" class="form-control" name="address" placeholder="Address">
							</div>
              <span style="font-size:13px; color:red;"><?= $phoneError ?? "" ?></span>
              <div class="col-md-12 form-group">
								<input type="text" class="form-control" name="phone" placeholder="Phone">
							</div>
							<div class="col-md-12 form-group mt-3">
								<button type="submit" value="submit" class="primary-btn">Register</button>
                <a href="login.php" type="button" class="primary-btn" style="color:white">Login</a>

							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--================End Login Box Area =================-->

	<!-- start footer Area -->
	<footer>
	  <div class="">
	    <p class="d-flex justify-content-center align-items-center text-white p-5 mt-3" style="background-color:black">
	         Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved
	    </p>
	  </div>
	</footer>
	<!-- End footer Area -->


	<script src="js/vendor/jquery-2.2.4.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
	 crossorigin="anonymous"></script>
	<script src="js/vendor/bootstrap.min.js"></script>
	<script src="js/jquery.ajaxchimp.min.js"></script>
	<script src="js/jquery.nice-select.min.js"></script>
	<script src="js/jquery.sticky.js"></script>
	<script src="js/nouislider.min.js"></script>
	<script src="js/jquery.magnific-popup.min.js"></script>
	<script src="js/owl.carousel.min.js"></script>
	<!--gmaps Js-->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
	<script src="js/gmaps.min.js"></script>
	<script src="js/main.js"></script>
</body>

</html>
