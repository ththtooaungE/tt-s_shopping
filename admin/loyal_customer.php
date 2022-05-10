<?php
  session_start();
  require "../config/config.php";
  require "../config/common.php";

  if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in']) || empty($_SESSION['role'])) {
    header("Location: login.php");
  }

  include "header.php";

//getting distinct user id
  $stmt = $pdo->prepare("SELECT DISTINCT user_id FROM sale_orders");
  $stmt->execute();
  $user_ids = $stmt->fetchAll(PDO::FETCH_ASSOC);
//selecting the loyal customers and getting their id and total money
  foreach ($user_ids as $user_id) {
    $stmt = $pdo->prepare("SELECT * FROM sale_orders WHERE user_id = :user_id");
    $stmt->execute([':user_id'=>$user_id['user_id']]);
    $sale_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $total_price = 0;
    foreach ($sale_orders as $sale_order) {
      $total_price += $sale_order['total_price'];
    }

    if ($total_price > 300000) {
      $loyal_customers[$user_id['user_id']] = $total_price;
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
                <h3 class="card-title">Loyal Customers</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <a href="product_add.php" type="button" class="btn btn-primary mb-3">Create new product</a>
                <table class="table table-bordered" id="data-table">
                  <thead style="text-align:center">
                    <tr>
                      <th style="width: 5%">#</th>
                      <th style="width: 35%">User</th>
                      <th style="width: 35%">Total Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 1;

                    if (!empty($loyal_customers)) {
                      $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
                      foreach ($loyal_customers as $loyal_customer_id => $loyal_customer_total_amount) {?>
                        <tr>
                          <td><?= $i ?></td>
                          <?php
                          $stmt->execute(array(':user_id'=>$loyal_customer_id));
                          $loyal_customer_result = $stmt->fetch(PDO::FETCH_ASSOC);
                           ?>
                           <td><?= escape($loyal_customer_result['name']) ?></td>
                           <td><?= escape($loyal_customer_total_amount) ?></td>
                        </tr>
                      <?php $i++;
                      }
                    } ?>
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
