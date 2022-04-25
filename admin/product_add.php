<?php
session_start();
require "../config/config.php";
require "../config/common.php";

if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in']) || empty($_SESSION['role'])) {
  header("Location: login.php");
  exit();
}

if ($_POST) {

  if (empty($_POST['name']) || empty($_POST['description']) || empty($_POST['price'])
  || empty($_POST['quantity']) || empty($_POST['category']) || empty($_FILES['image']['name'])
  || !is_numeric($_POST['category']) || !is_numeric($_POST['quantity']) || !is_numeric($_POST['price'])) {

    if (empty($_POST['name'])) $nameError = 'This field is required!';
    if (empty($_POST['description'])) $descriptionError = 'This field is required!';
    if (empty($_POST['quantity'])) $quantityError = 'This field is required!';
    if (empty($_POST['category']) || $_POST['category'] == 'Category') $categoryError = 'This field is required!';
    if (empty($_POST['price'])) $priceError = 'This field is required!';
    if (empty($_FILES['image']['name'])) $imageError = 'This field is required!';

    if (!is_numeric($_POST['category']) && !empty($_POST['category']) && $_POST['category'] !== 'Category') $categoryError = 'This field must be numeric!';
    if (!is_numeric($_POST['price']) && !empty($_POST['price'])) $priceError = 'This field must be numeric!';
    if (!is_numeric($_POST['quantity']) && !empty($_POST['quantity'])) $quantityError = 'This field must be numeric!';
  } else {
    //validation success
    $imgType = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

    if ($imgType !== 'png' && $imgType !== 'jpg' && $imgType !== 'jpeg'
    && $imgType !== 'PNG' && $imgType !== 'JPG' && $imgType !== 'JPEG') {

      echo "<script>alert('Image type must be png or jpg or jpeg!')</script>";

    } else {
      //image type validation success
      move_uploaded_file($_FILES['image']['tmp_name'], 'image/'.$_FILES['image']['name']);

      $stmt = $pdo->prepare("INSERT INTO products(name, description, price, quantity, category_id, image, created_at)
      VALUES(:name, :description, :price, :quantity, :category_id, :image, NOW())");
      $result = $stmt->execute(array(':name'=>$_POST['name'],':description'=>$_POST['description'],':price'=>$_POST['price'],
      ':quantity'=>$_POST['quantity'], ':category_id'=>$_POST['category'], ':image'=>$_FILES['image']['name']));

      if ($result) {
        echo "<script>alert('New record is added!');window.location.href='index.php';</script>";
        exit();
      }
    }
  }
}

include 'header.php';

 ?>

     <!-- Main content -->
     <div class="content">
       <div class="container-fluid">
             <div class="card">
               <div class="card-body login-card-body">
                 <p class="login-box-msg">Create a new product</p>

                 <form action="" method="post" enctype="multipart/form-data" id="category">
                   <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
                   <span style="font-size:13px; color:red;"><?= $nameError ?? "" ?></span>
                   <div class="form-group mb-3">
                     <input type="text" name="name" class="form-control" placeholder="Name" >
                   </div>

                   <span style="font-size:13px; color:red;"><?= $descriptionError ?? "" ?></span>
                   <div class="form-group mb-3">
                     <textarea name="description" class="form-control" rows="8" cols="80" placeholder="Description"></textarea>
                   </div>

                   <span style="font-size:13px; color:red;"><?= $priceError ?? "" ?></span>
                   <div class="form-group mb-3">
                     <input type="number" name="price" class="form-control" placeholder="Price" >
                   </div>

                   <span style="font-size:13px; color:red;"><?= $quantityError ?? "" ?></span>
                   <div class="form-group mb-3">
                     <input type="number" name="quantity" class="form-control" placeholder="In Stock" >
                   </div>

                   <span style="font-size:13px; color:red;"><?= $categoryError ?? "" ?></span>
                   <div class="form-group">
                     <select name='category' class="form-select form-control" form="category">
                       <option >Category</option>
                       <?php
                       $stmt = $pdo->prepare("SELECT * FROM categories");
                       $stmt->execute();
                       $catResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

                       foreach ($catResults as $catResult) {
                         ?>
                         <option value="<?= $catResult['id'] ?>"><?= $catResult['name'] ?></option>
                         <?php
                       }
                        ?>

                     </select>
                   </div>


                   <span style="font-size:13px; color:red;"><?= $imageError ?? "" ?></span>
                   <div class="mb-3">
                      <label for="formFileMultiple" class="form-label">Image</label><br>
                      <input type="file" name="image" id="formFileMultiple" multiple>
                    </div>

                   <button type="submit" class="btn btn-primary btn-block mt-3">Create</button>

                 </form>
               </div>
             </div>

       </div><!-- /.container-fluid -->
     </div>
     <!-- /.content -->

 <?php

 include "footer.html";
