<?php
  session_start();
  require "../config/config.php";
  require "../config/common.php";

  if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in']) || empty($_SESSION['role'])) {
    header("Location: login.php");
  }

  include "header.php";

  date_default_timezone_set("Asia/Yangon");

  $month = date('M');
  $start_date = date('Y-m-d h:i:s', strtotime("$month 1"));
  $end_date = date('Y-m-d h:i:s', strtotime("last day of $month"));

  $stmt = $pdo->prepare("SELECT * FROM sale_orders WHERE order_date BETWEEN :start_date AND :end_date ORDER BY id DESC");
  $stmt->execute([':start_date'=>$start_date,':end_date'=>$end_date,]);
  $order_lists = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // print "<pre>";
  // print_r($order_lists);

 ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Monthly Reports</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered" id="data-table">
                  <thead style="text-align:center">
                    <tr>
                      <th style="width: 5%">#</th>
                      <th style="width: 35%">User</th>
                      <th style="width: 35%">Total Amount</th>
                      <th style="width: 25%">Order Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 1;
                    $stmt = $pdo->prepare("SELECT name FROM users WHERE id = :id");
                    foreach ($order_lists as $order_list): ?>
                      <tr>
                        <td><?= $i ?></td>
                        <?php
                        $stmt->execute(array(':id'=>$order_list['user_id']));
                        $user_name = $stmt->fetch(PDO::FETCH_ASSOC);
                         ?>
                         <td><?= escape($user_name['name']) ?></td>
                         <td><?= escape($order_list['total_price']) ?></td>
                         <td><?= date('d/m/Y h:i:s l', strtotime($order_list['order_date'])) ?></td>
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
