<?php
  session_start();
  require "../config/config.php";
  require "../config/common.php";

  if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in']) || empty($_SESSION['role'])) {
    header("Location: login.php");
  }

  if (isset($_POST['search'])) {
    setcookie('search', $_POST['search'], time()+7200);
  } else {
    if (empty($_GET['page_num'])) {
      unset($_COOKIE['search']);
      setcookie('search', '', time()-1);
    }
  }

  include "header.php";

  if (isset($_GET['page_num'])) $page_num = $_GET['page_num'];
  else $page_num = 1;
  $records_per_page = 5;

  if (empty($_POST['search']) && empty($_COOKIE['search'])) {

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users");
    $stmt->execute();
    $total_number = $stmt->fetchColumn();

    $total_pages = ceil($total_number/$records_per_page);
    $offsetnum = ($page_num - 1) * $records_per_page;

    $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT :offsetnum, :records_per_page");
    $stmt->bindValue(':offsetnum', $offsetnum, PDO::PARAM_INT);
    $stmt->bindValue(':records_per_page', $records_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

  } else {

    $search = $_POST['search'] ?? $_COOKIE['search'];

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE name LIKE CONCAT('%', :name, '%')");
    $stmt->execute(array(':name'=>$search));
    $total_number = $stmt->fetchColumn();

    $total_pages = ceil($total_number/$records_per_page);
    $offsetnum = ($page_num - 1) * $records_per_page;

    $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE CONCAT('%', :name, '%') ORDER BY id DESC LIMIT :offsetnum, :records_per_page");
    $stmt->bindValue(':name', $search, PDO::PARAM_STR);
    $stmt->bindValue(':offsetnum', $offsetnum, PDO::PARAM_INT);
    $stmt->bindValue(':records_per_page', $records_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

 ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users Listings</h3>
              </div>


              <!-- /.card-header -->
              <div class="card-body">
                <a href="user_add.php" type="button" class="btn btn-primary mb-3">Create new user</a>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th style="width: 200px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // print"<pre>";
                    // print_r($users);
                    if ($users) {
                      $i = 1;
                      foreach ($users as $user) {
                        ?>
                        <tr>
                          <td><?= $i.'.' ?></td>
                          <td><?= escape($user['name']) ?></td>
                          <td><?= escape($user['email']) ?></td>
                          <td><?php echo (int)$user['role'] ? "admin" : "user" ?></td>
                          <td>
                            <a href="user_edit.php?id=<?= $user['id'] ?>" type="button" class="btn btn-warning">Edit</a>
                            <a href="user_delete.php?id=<?= $user['id'] ?>" type="button"
                              onclick="return confirm('Are you sure you want do delete this item?')" class="btn btn-danger">Delete</a>
                          </td>
                        </tr>
                        <?php
                        $i++;
                      }
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
