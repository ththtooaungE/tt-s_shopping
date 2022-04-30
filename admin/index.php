<?php
  session_start();
  require "../config/config.php";
  require "../config/common.php";

  if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in']) || empty($_SESSION['role'])) {
    header("Location: login.php");
  }
  //creating cookies for search
  if (!empty($_POST['search'])) {
    setcookie('search', $_POST['search'], time()+7200);
  } else {
    if (empty($_GET['page_num'])) {
      unset($_COOKIE['search']);
      setcookie('search', '', time()-1);
    }
  }

  include "header.php";

  if(!empty($_GET['page_num'])) $page_num = $_GET['page_num'];
  else $page_num = 1;
  $records_per_page = 4;

  if (!empty($_POST['search']) || !empty($_COOKIE['search'])) {
    $search = $_COOKIE['search'] ?? $_POST['search'];

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE name LIKE CONCAT('%', :search, '%')");
    $stmt->execute(array(':search'=>$search));
    $total_records = $stmt->fetchColumn();

    $total_pages = ceil($total_records/$records_per_page);
    $offsetnum = ($page_num - 1) * $records_per_page;

    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE CONCAT('%', :search, '%') ORDER BY id DESC LIMIT :offsetnum, :recordsperpage");
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

 ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Product Listings</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <a href="product_add.php" type="button" class="btn btn-primary mb-3">Create new product</a>
                <table class="table table-bordered">
                  <thead style="text-align:center">
                    <tr>
                      <th style="width: 5%">#</th>
                      <th style="width: 25%">Name</th>
                      <th style="width: 30%">Description</th>
                      <th style="width: 10%">Category</th>
                      <th style="width: 10%">In Stock</th>
                      <th style="width: 10%">Price</th>
                      <th style="width: 10%">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 1;
                    $stmt = $pdo->prepare("SELECT name FROM categories WHERE id = :id");
                    foreach ($products as $product): ?>
                      <tr>
                        <td><?= $i ?></td>
                        <td><?= escape($product['name']) ?></td>
                        <td><?= escape(substr($product['description'], 0, 100)).'...' ?></td>
                        <?php
                        $stmt->execute(array(':id'=>$product['category_id']));
                        $categoryName = $stmt->fetchAll(PDO::FETCH_ASSOC);
                         ?>
                        <td><?= escape($categoryName[0]['name']) ?></td>
                        <td><?= escape($product['quantity']) ?></td>
                        <td><?= escape($product['price']) ?></td>
                        <td>
                          <a type="button" class="btn btn-warning" href="product_edit.php?id=<?= $product['id'] ?>&page_num=<?= $page_num ?>">Edit</a>
                          <a type="button" class="btn btn-primary mt-1" href="product_delete.php?id=<?= $product['id'] ?>&page_num=<?= $page_num ?>"
                            onclick="return confirm('Are you sure you wnat to delete this item?')">Delete</a>
                        </td>
                      </tr>
                    <?php $i++;
                   endforeach; ?>
                  </tbody>
                </table>
              </div>
              <nav aria-lable="Page naviagtion example">
                <ul class="pagination justify-content-center">
                  <li class="page-item  <?php if($page_num == 1) {echo "disabled";} ?>"><a class="page-link" href="?page_num=1"><<</a></li>
                  <li class="page-item <?php if($page_num <= 1) {echo "disabled";} ?>">
                    <a class="page-link" href="<?php if ($page_num <= 1) {echo "";} else {echo "?page_num=".($page_num-1);} ?>">Previous</a>
                  </li>
                  <li class="page-item active"><a class="page-link" href=""><?= $page_num ?></a></li>
                  <li class="page-item <?php if($page_num >= $total_pages) echo "disabled"; ?>">
                    <a class="page-link" href="<?php if ($page_num >= $total_pages) {echo "";} else {echo "?page_num=".($page_num+1);} ?>">Next</a>
                  </li>
                  <li class="page-item  <?php if($page_num == $total_pages) {echo "disabled";} ?>"><a class="page-link" href="?page_num=<?= $total_pages ?>">>></a></li>
                </ul>
              </nav>
              <!-- card-body ends -->
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

<?php

include "footer.html";
