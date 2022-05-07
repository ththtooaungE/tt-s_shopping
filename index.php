<?php
	session_start();
	require "config/config.php";
	require "config/common.php";

	if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in'])) {
    header("Location: login.php");
  }
  //creating cookies for search
  if (!empty($_POST['search'])) {
    setcookie('search', $_POST['search'], time()+7200);
  } else {
    if (empty($_GET['page_num'])) {
      unset($_COOKIE['search']);
      setcookie('search', '', time()-1) ;
    }
  }

	include('header.php');

	if(!empty($_GET['page_num'])) $page_num = $_GET['page_num'];
  else $page_num = 1;
  $records_per_page = 6;

	//showing categories
	$stmt = $pdo->prepare("SELECT * FROM categories");
	$stmt->execute();
	$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

	//showing and searching products
  if (empty($_GET['category_id'])) {
		if (!empty($_POST['search']) || !empty($_COOKIE['search'])) {
	    $search = $_POST['search'] ?? $_COOKIE['search'];

	    $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE name LIKE CONCAT('%', :search, '%')
			OR description LIKE CONCAT('%', :search, '%')");
	    $stmt->execute(array(':search'=>$search));
	    $total_records = $stmt->fetchColumn();

	    $total_pages = ceil($total_records/$records_per_page);
	    $offsetnum = ($page_num - 1) * $records_per_page;

	    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE CONCAT('%', :search, '%')
			OR description LIKE CONCAT('%', :search, '%') ORDER BY id DESC LIMIT :offsetnum, :recordsperpage");
	    $stmt->bindValue(':search', $search, PDO::PARAM_STR);
	    $stmt->bindValue(':offsetnum', $offsetnum, PDO::PARAM_INT);
	    $stmt->bindValue(':recordsperpage', $records_per_page, PDO::PARAM_INT);
	    $stmt->execute();
	    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

	  } else {

	    $stmt = $pdo->prepare("SELECT COUNT(*) FROM products");
	    $stmt->execute();
	    $total_records = $stmt->fetchColumn();

	    $total_pages = ceil($total_records/$records_per_page);
	    $offsetnum = ($page_num - 1) * $records_per_page;

	    $stmt = $pdo->prepare("SELECT * FROM products ORDER BY id DESC LIMIT :offsetnum, :recordsperpage");
	    $stmt->bindValue(':offsetnum', $offsetnum, PDO::PARAM_INT);
	    $stmt->bindValue(':recordsperpage', $records_per_page, PDO::PARAM_INT);
	    $stmt->execute();
	    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
	  }
	}
	//showing a selected category
	if (!empty($_GET['category_id'])) {
		$stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id = :id");
		$stmt->execute([':id'=>$_GET['category_id']]);
		$total_records = $stmt->fetchColumn();

		$total_pages = ceil($total_records/$records_per_page);
		$offsetnum = ($page_num - 1) * $records_per_page;

		$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = :id");
		$stmt->execute([':id'=>$_GET['category_id']]);
		$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
 ?>
	 <div class="container">
		 <div class="row">
			 <div class="col-xl-3 col-lg-4 col-md-5">
				 <div class="sidebar-categories">
					 <div class="head">Browse Categories</div>
					 <ul class="main-categories">
						<?php
						 foreach ($categories as $category) : ?>
							<li class="main-nav-list"><a href="?category_id=<?= $category['id'] ?>"><?= escape($category['name']) ?></a></li>
						<?php endforeach; ?>
					</ul>
				 </div>
			 </div>
			 <div class="col-xl-9 col-lg-8 col-md-7">
			 <!-- Start Filter Bar -->
			  <div class="filter-bar d-flex flex-wrap align-items-center">
				  <div class="pagination">
					  <a href="?page_num=1" class="<?php if ($page_num == 1) echo "disabled"; ?>"><<</a>
						<a <?php if ($page_num <= 1) {echo "disabled";} ?>
							href="<?php if ($page_num <= 1) {echo "#";} else {echo "?page_num=".($page_num-1);} ?>"  class="">Prev</a>
						<a href="#" class="active"><?= $page_num ?></a>
					  <a <?php if ($page_num >= $total_pages) {echo "disabled";} ?>
							href="<?php if ($page_num >= $total_pages) {echo "#";} else {echo "?page_num=".($page_num+1);} ?>" class="" >Next</a>
					  <a href="?page_num=<?= $total_pages ?>" class="next-arrow <?php if ($page_num == $total_pages) echo "disabled"; ?>" href="">>></a>
				  </div>
			  </div>
				<!-- End Filter Bar -->
				<!-- Start Best Seller -->
				<section class="lattest-product-area pb-40 category-list">
					<div class="row">
						<?php
						foreach ($products as $product) {
							?>

							<div class="col-lg-4 col-md-6">
								<div class="single-product">
									<a href="single_product.php?id=<?= $product['id'] ?>&page_num=<?= $page_num ?>">
										<img class="img-fluid" style="height:250px;width:230px;object-fit: cover" src="admin/image/<?= escape($product['image']) ?>" alt="">
									</a>
									<div class="product-details">
										<h6><?= escape($product['name']) ?></h6>
										<div class="price">
											<h6><?= escape($product['price']) ?> MMK</h6>
										</div>
										<div class="prd-bottom">

											<form action="addtocart.php" method="post">
												<input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
												<input type="hidden" name="id" value="<?php echo $product['id'] ?>">
												<input type="hidden" name="qty" value="1">
													<div class="social-info">
														<button type="submit" style="display:contents" class="social-info">
															<span class="ti-bag"></span>
															<p class="hover-text" style="left:20px">add to bag</p>
														</button>
													</div>
													<a href="single_product.php?id=<?= $product['id'] ?>&page_num=<?= $page_num ?>" class="social-info">
														<span class="lnr lnr-move"></span>
														<p class="hover-text">view more</p>
													</a>
											</form>
										</div>
									</div>
								</div>
							</div>
							<?php
						}
						 ?>

					</div>
				</section>
				<!-- End Best Seller -->
<?php include('footer.php');?>
