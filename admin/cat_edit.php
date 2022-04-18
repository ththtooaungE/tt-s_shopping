<?php
  session_start();
  require "../config/config.php";
  require "../config/common.php";

  if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in']) || empty($_SESSION['role'])) {
    header("Location: login.php");
  }

  include "header.php";

  if ($_POST) {
    if (empty($_POST['name']) || empty($_POST['description'])) {
      //backend validation
      if (empty($_POST['name'])) $nameError = "This field can't be empty";
      if (empty($_POST['description'])) $descriptionError = "This field can't be empty";

    } else {

      $stmt = $pdo->prepare("UPDATE categories SET name = :name , description = :description WHERE id = :id");
      $result = $stmt->execute(array(':name'=>$_POST['name'],':description'=>$_POST['description'], ':id'=> $_GET['id']));
      if ($result) echo "<script>alert('New Record is UPDATED!');window.location.href='category.php';</script>";
    }
  }

  $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = :id");
  $stmt->execute(array(':id'=>$_GET['id']));
  $category = $stmt->fetchAll(PDO::FETCH_ASSOC);

 ?>



    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <form class="form" action="" method="post">
                  <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
                  <div class="form-group">
                    <label for="title">Category Name</label>
                    <br><span style="font-size:13px; color:red;"><?= $nameError ?? "" ?></span>
                    <input type="text" name="name" class="form-control" value="<?= escape($category[0]['name']) ?>" >
                  </div>
                  <div class="form-group">
                    <label for="content">Description</label>
                    <br><span style="font-size:13px; color:red;"><?= $descriptionError ?? "" ?></span>
                    <textarea name="description" rows="8" cols="80" class="form-control" ><?= escape($category[0]['description']) ?></textarea>
                  </div>

                  <div class="form-group">
                    <input type="submit" class="btn btn-success" name="" value="Submit">
                    <a href="category.php" type="button" class="btn btn-warning">Back</a>
                  </div>
                </form>
              </div>
            </div>

          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

<?php

include "footer.html";
