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
  $th_start = date('j');
  $th_end;
  switch ($th_start) {
    case $th_start<=7:
      $th_start = 'first';
      $th_end = 'second';
      break;

      case $th_start<=14:
        $th_start = 'second';
        $th_end = 'third';
        break;

        case $th_start<=21:
          $th_start = 'third';
          $th_end = 'fourth';
          break;

          case $th_start<=28:
            $th_start = 'fourth';
            $th_end = 'fifth';
            break;

            case $th_start<=31:
              $th_start = 'fifth';
              $th_end = 'first';
              $month = date('M', strtotime('next month'));
              break;
  }
  $start_date = date('Y-m-d H:i:s', strtotime("$th_start monday of $month"));
  $end_date = date('Y-m-d H:i:s', strtotime("$th_end monday of $month"));

  // echo $start_date.'<br>';
  // echo $end_date;
  //
  // die();

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
                <h3 class="card-title">Weekly Reports</h3>
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
                         <td><?= escape($order_list['total_price']) ?> MMK</td>
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
