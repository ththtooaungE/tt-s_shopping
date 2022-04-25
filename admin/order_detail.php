<?php
  session_start();
  require "../config/config.php";
  require "../config/common.php";

  if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in']) || empty($_SESSION['role'])) {
    header("Location: login.php");
  }

  include "header.php";



  $stmt = $pdo->prepare("SELECT * FROM sale_order_details WHERE sale_order_id = :id");
  $stmt->execute(array(':id'=>$_GET['id']));
  $order_details = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                      <th>Product Name</th>
                      <th>Price</th>
                      <th>Order Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $i = 1;
                      $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
                      foreach ($order_details as $order_detail) :
                        $stmt->execute(array(':id'=>$order_detail['product_id']));
                        $product = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <tr>
                          <td><?= $i.'.' ?></td>
                          <td><?= escape($product[0]['name']) ?></td>
                          <td><?= escape($product[0]['price']) ?></td>
                          <td><?= date('d/m/Y', strtotime($order_detail['order_date'])) ?></td>
                        </tr>
                        <?php
                        $i++;
                      endforeach;
                     ?>
                  </tbody>
                </table>
                <a href="order_list.php?page_num=<?= $_GET['page_num'] ?>" type="button" class="btn btn-primary mt-2 float-right">Back</a>
              </div>
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
