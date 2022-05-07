<?php

session_start();
require 'config/config.php';
require 'config/common.php';

if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in'])) {
  header("Location: login.php");
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
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/themify-icons.css">
    <link rel="stylesheet" href="css/nice-select.css">
    <link rel="stylesheet" href="css/nouislider.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
</head>

<body>

    <!-- Start Header Area -->
	<header class="header_area sticky-header">
		<div class="main_menu">
			<nav class="navbar navbar-expand-lg navbar-light main_box">
        <div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<a class="navbar-brand logo_h" href="index.php"><h4>AP Shopping<h4></a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
					 aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse offset" id="navbarSupportedContent">
						<ul class="nav navbar-nav navbar-right">
							<li class="nav-item"><a href="#" class="cart"><span class="ti-bag"></span></a></li>
							<li class="nav-item">
								<button class="search"><span class="lnr lnr-magnifier" id="search"></span></button>
							</li>
						</ul>
					</div>
				</div>
			</nav>
		</div>
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
                    <h1>Shopping Cart</h1>
                    <nav class="d-flex align-items-center">
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->

    <!--================Cart Area =================-->
    <section class="cart_area">
        <div class="container">
            <div class="cart_inner">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                          <?php
                          if (!empty($_SESSION['cart'])) {
                            $total_costs = 0;
                            foreach ($_SESSION['cart'] as $id =>$qty) {
                              $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
                              $stmt->execute([':id'=>$id]);
                              $item = $stmt->fetch(PDO::FETCH_ASSOC);
                              ?>
                              <tr>
                                  <td>
                                      <div class="media">
                                          <div class="d-flex">
                                              <img src="admin/image/<?= escape($item['image']) ?>" style="width:150px" alt="">
                                          </div>
                                          <div class="media-body">
                                              <p><?= escape($item['name']) ?></p>
                                          </div>
                                      </div>
                                  </td>
                                  <td>
                                      <h5><?= escape($item['price']) ?>MMK</h5>
                                  </td>
                                  <td>
                                    <h5><?= $qty ?></h5>
                                  </td>
                                  <td>
                                      <h5><?= escape($item['price']) * $qty ?> MMK</h5>
                                  </td>
                                  <td><a href="cart-item-clear.php?id=<?= $id ?>" class="">Clear</a></td>
                              </tr>
                              <?php
                              $total_costs += $item['price'] * $qty;
                            }
                          }
                           ?>
                            <tr>
                                <td></td>
                                <td>
                                    <h5>Subtotal</h5>
                                </td>
                                <td></td>
                                <td>
                                    <h5><?= $total_costs ?? "" ?> MMK</h5>
                                </td>
                                <td></td>
                            </tr>

                            <tr class="out_button_area">
                              <td></td>
                              <td>
                              </td>

                                <td>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="checkout_btn_inner d-flex align-items-center">
                        <a class="primary-btn" href="clear.php">Clear All</a>
                        <a class="primary-btn" href="index.php">Continue Shopping</a>
                        <a class="primary-btn" href="sale_order.php">Proceed to checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================End Cart Area =================-->

    <!-- start footer Area -->
    <footer>
      <div class="mt-5">
        <p class="d-flex justify-content-center align-items-center text-white p-5" style="background-color:black">
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
