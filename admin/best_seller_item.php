<?php
  session_start();
  require "../config/config.php";
  require "../config/common.php";

  if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in']) || empty($_SESSION['role'])) {
    header("Location: login.php");
  }

  include "header.php";

  date_default_timezone_set("Asia/Yangon");

//getting distinct products
  $stmt = $pdo->prepare("SELECT DISTINCT product_id FROM sale_order_details");
  $stmt->execute();
  $product_ids = $stmt->fetchAll(PDO::FETCH_ASSOC);
//filtering the best seller items
  foreach ($product_ids as $product_id) {
    $stmt = $pdo->prepare("SELECT * FROM sale_order_details WHERE product_id = :product_id");
    $stmt->execute([':product_id'=>$product_id['product_id']]);
    $sale_order_details = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $total_product_amounts = 0;
    foreach ($sale_order_details as $sale_order_detail) {
      $total_product_amounts += $sale_order_detail['quantity'];
    }

    if ($total_product_amounts > 5) {
      $best_seller_items[$product_id['product_id']] = $total_product_amounts;
    }
  }

 ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">BEST SELLER ITEMS</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered" id="data-table">
                  <thead style="text-align:center">
                    <tr>
                      <th style="width: 15%">#</th>
                      <th style="width: 50%">Product</th>
                      <th style="width: 35%">Number</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 1;
                    $stmt = $pdo->prepare("SELECT name FROM products WHERE id = :id");
                    foreach ($best_seller_items as $id => $amount): ?>
                      <tr>
                        <td><?= $i ?></td>
                        <?php
                        $stmt->execute(array(':id'=>$id));
                        $product_name = $stmt->fetch(PDO::FETCH_ASSOC);
                         ?>
                         <td><?= escape($product_name['name']) ?></td>
                         <td><?= $amount ?></td>
                      </tr>
                    <?php $i++;
                   endforeach; ?>
                  </tbody>
                </table>
              </div>

              <!-- card-body ends -->
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

<?php include "footer.html"; ?>

<script>
$(document).ready(function() {
    $('#data-table').DataTable();
} );
</script>
