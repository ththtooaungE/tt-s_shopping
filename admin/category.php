<?php
  session_start();
  require "../config/config.php";
  require "../config/common.php";

  if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in']) || empty($_SESSION['role'])) {
    header("Location: login.php");
  }

  if (isset($_GET['page_num'])) {
    $page_num = $_GET['page_num'];
  } else {
    $page_num = 1;
  }

  $records_per_page = 4;

  if (isset($_POST['search'])) {
    setcookie('search', $_POST['search'], time()+7200);
  } else {
    if (empty($_GET['page_num'])) {
      unset($_COOKIE['search']);
      setcookie('search', '', time()-1);
    }
  }

  if (!empty($_POST['search']) || !empty($_COOKIE['search'])) {
    //for search box
    $search = $_POST['search'] ?? $_COOKIE['search'];

    $sql = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE name LIKE CONCAT('%',:name,'%')");
    $sql->execute(array(':name'=>$search));
    $total_number = $sql->fetchColumn();

    $total_pages = ceil($total_number/$records_per_page);

    $offsetnum = ceil($page_num-1)*$records_per_page;

    $sql = $pdo->prepare("SELECT * FROM categories WHERE name LIKE CONCAT('%',:name,'%') ORDER BY id DESC LIMIT :offsetnum, :records_per_page");
    $sql->bindValue(':name', $search, PDO::PARAM_STR);
    $sql->bindValue(':offsetnum', $offsetnum, PDO::PARAM_INT);
    $sql->bindValue(':records_per_page', $records_per_page, PDO::PARAM_INT);
    $sql->execute();
    $categories = $sql->fetchAll(PDO::FETCH_ASSOC);

  } else {
    //not search
    $sql = $pdo->prepare("SELECT COUNT(*) FROM categories");
    $sql->execute();
    $total_number = $sql->fetchColumn();

    $total_pages = ceil($total_number/$records_per_page);
    $offsetnum = ($page_num - 1) * $records_per_page;

    $sql = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC LIMIT :offsetnum, :records_per_page");
    $sql->bindValue(':offsetnum', $offsetnum, PDO::PARAM_INT);
    $sql->bindValue(':records_per_page', $records_per_page, PDO::PARAM_INT);
    $sql->execute();
    $categories = $sql->fetchAll(PDO::FETCH_ASSOC);
  }

  include "header.php";

 ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Categories</h3>
              </div>

              <!-- /.card-header -->
              <div class="card-body">
                <a href="cat_add.php" type="button" class="btn btn-primary mb-3">New Category</a>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Description</th>
                      <th style="width: 200px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 0;
                    foreach($categories as $category) {
                      $i++;
                      ?>
                      <tr>
                        <td><?= $i ?></td>
                        <td><?= escape($category['name']) ?></td>
                        <td><?= escape($category['description']) ?></td>
                        <td>
                          <a type="button" class="btn btn-primary" href="cat_edit.php?id=<?= $category['id'] ?>">Edit</a>
                          <a type="button" class="btn btn-primary" onclick="return confirm('Are you sure you wnat to delete this item?')" href="cat_delete.php?id=<?= $category['id'] ?>">Delete</a>
                        </td>
                      </tr>
                      <?php
                    }
                     ?>
                  </tbody>
                </table>
              </div>
              <!-- card-body ends -->
              <nav aria-lable="Page naviagtion example">
                <ul class="pagination justify-content-center">
                  <li class="page-item  <?php if($page_num == 1) {echo "disabled";} ?>"><a class="page-link" href="?page_num=1"><<</a></li>
                  <li class="page-item <?php if($page_num <= 1) {echo "disabled";} ?>">
                    <a class="page-link" href="<?php if ($page_num <= 1) {echo "#";} else {echo "?page_num=".($page_num-1);} ?>">Previous</a>
                  </li>
                  <li class="page-item active"><a class="page-link" href="#"><?= $page_num ?></a></li>
                  <li class="page-item <?php if($page_num >= $total_pages) echo "disabled"; ?>">
                    <a class="page-link" href="<?php if ($page_num >= $total_pages) {echo "#";} else {echo "?page_num=".($page_num+1);} ?>">Next</a>
                  </li>
                  <li class="page-item  <?php if($page_num == $total_pages) {echo "disabled";} ?>"><a class="page-link" href="?page_num=<?= $total_pages ?>">>></a></li>
                </ul>
              </nav>
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

<?php

include "footer.html";
