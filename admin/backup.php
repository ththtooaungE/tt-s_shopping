<?php

// $link_array = explode('/', $_SERVER['PHP_SELF']);
//
//
// if ($_SERVER['PHP_SELF'] == "/blog/admin/index.php" || $_SERVER['PHP_SELF'] == "/blog/admin/user_list.php") {

session_start();
require "../config/config.php";
require "../config/common.php";

if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in']) || empty($_SESSION['role'])) {
  header("Location: login.php");
}


if (isset($_POST['search'])) {
  setcookie('search', $_POST['search'], time()+3600);
} else {
  if (empty($_GET['page_num'])) {
    unset($_COOKIE['search']);
    setcookie('search', '', time()-1);
  }
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
                <h3 class="card-title">User Listings</h3>
              </div>

              <?php

              if (!empty($_GET['page_num'])) {
                $page_num = $_GET['page_num'];
              } else {
                $page_num = 1;
              }
//pagination
              $recordsperpage = 5;

              if (empty($_POST['search']) && empty($_COOKIE['search'])) {

                $stmt = $pdo->prepare("SELECT COUNT(*) FROM products");
                $stmt->execute();
                $total_records = $stmt->fetchColumn();

                $total_pages = ceil($total_records/$recordsperpage);
                $offsetnum = ($page_num-1)*$recordsperpage;
//
                $stmt = $pdo->prepare("SELECT * FROM products ORDER BY id DESC LIMIT :offsetnum, :recordsperpage");

                $stmt->bindValue(':offsetnum', $offsetnum, PDO::PARAM_INT);
                $stmt->bindValue(':recordsperpage', $recordsperpage, PDO::PARAM_INT);
                $stmt->execute();

                $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

              } else {
//for search box
                $search = $_POST['search'] ?? $_COOKIE['search'];

                $stmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE title LIKE CONCAT('%', :title, '%') OR content LIKE CONCAT('%', :title, '%')");
                $stmt->execute(array(':title'=>$search,':content'=>$search));
                $total_records = $stmt->fetchColumn();

                $total_pages = ceil($total_records/$recordsperpage);
                $offsetnum = ($page_num-1)*$recordsperpage;

                $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE CONCAT('%', :title, '%') OR content LIKE CONCAT('%', :content, '%') ORDER BY id DESC LIMIT :offsetnum, :recordsperpage");

                $stmt->bindValue(':title', $search, PDO::PARAM_STR);
                $stmt->bindValue(':content', $search, PDO::PARAM_STR);
                $stmt->bindValue(':offsetnum', $offsetnum, PDO::PARAM_INT);
                $stmt->bindValue(':recordsperpage', $recordsperpage, PDO::PARAM_INT);
                $stmt->execute();

                $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

              }

               ?>
              <!-- /.card-header -->
              <div class="card-body">
                <a href="add.php" type="button" class="btn btn-primary mb-3">Create new post</a>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Title</th>
                      <th>Content</th>
                      <th style="width: 200px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if ($blogs) {
                      $i = 1;
                      foreach ($blogs as $blog) {
                        ?>
                        <tr>
                          <td><?= $i.'.' ?></td>
                          <td><?= escape($blog['title']) ?></td>
                          <td><?= escape(substr($blog['content'],0,50)) ?><span class="text-muted"> ... continue reading</span></td>
                          <td>
                            <a href="edit.php?id=<?= $blog['id'] ?>" type="button" class="btn btn-warning">Edit</a>
                            <a href="delete.php?id=<?= $blog['id'] ?>" type="button"
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
