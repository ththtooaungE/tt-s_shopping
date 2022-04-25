<?php
  session_start();
  require "../config/config.php";
  require "../config/common.php";

  if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in']) || empty($_SESSION['role'])) {
    header("Location: login.php");
  }

  include "header.php";
  //setting page number
  if (isset($_GET['page_num'])) $page_num = $_GET['page_num'];
  else $page_num = 1;
  $records_per_page = 5;

  $stmt = $pdo->prepare("SELECT COUNT(*) FROM sale_orders");
  $stmt->execute();
  $total_number = $stmt->fetchColumn();

  $total_pages = ceil($total_number/$records_per_page);
  $offsetnum = ($page_num - 1) * $records_per_page;

  $stmt = $pdo->prepare("SELECT * FROM sale_orders ORDER BY id DESC LIMIT :offsetnum, :records_per_page");
  $stmt->bindValue(':offsetnum', $offsetnum, PDO::PARAM_INT);
  $stmt->bindValue(':records_per_page', $records_per_page, PDO::PARAM_INT);
  $stmt->execute();
  $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

 ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Order Lists</h3>
              </div>


              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>User Name</th>
                      <th>Total Price</th>
                      <th>Order Date</th>
                      <th style="width: 200px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // print"<pre>";
                    // print_r($users);
                    if ($orders) :
                      $i = 1;
                      $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
                      foreach ($orders as $order) :
                        $stmt->execute(array(':id'=>$order['user_id']));
                        $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <tr>
                          <td><?= $i.'.' ?></td>
                          <td><?= escape($user[0]['name']) ?></td>
                          <td><?= escape($order['total_price']) ?></td>
                          <td><?= date('d/m/Y', strtotime($order['order_date'])) ?></td>
                          <td><a href="order_detail.php?id=<?= $order['id'] ?>&page_num=<?= $page_num ?>" type="button" class="btn btn-warning">View</a></td>
                        </tr>
                        <?php
                        $i++;
                      endforeach;
                    endif;
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
