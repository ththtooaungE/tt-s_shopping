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
  || empty($_POST['quantity']) || empty($_POST['category']) || !is_numeric($_POST['category'])
  || !is_numeric($_POST['quantity']) || !is_numeric($_POST['price'])) {

    if (empty($_POST['name'])) $nameError = 'This field is required!';
    if (empty($_POST['description'])) $descriptionError = 'This field is required!';
    if (empty($_POST['quantity'])) $quantityError = 'This field is required!';
    if (empty(is_numeric($_POST['category']))) $categoryError = 'This field is required!';
    if (empty($_POST['price'])) $priceError = 'This field is required!';

    if (!is_numeric($_POST['category'])) $categoryError = 'This field must be numeric!';
    if (!is_numeric($_POST['price'])) $priceError = 'This field must be numeric!';
    if (!is_numeric($_POST['quantity'])) $quantityError = 'This field must be numeric!';
  } else {
    //validation success
    if (empty($_FILES['image']['name'])) {
      $stmt = $pdo->prepare("UPDATE products SET name = :name, description = :description,
         price = :price, quantity = :quantity, category_id = :category_id WHERE id = :id");
      $result = $stmt->execute(array(
        ':name'=>$_POST['name'],':description'=>$_POST['description'],':price'=>$_POST['price'],
        ':quantity'=>$_POST['quantity'],':category_id'=>$_POST['category'],':id'=>$_GET['id'],
      ));

    } else {
      $imgType = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

      if ($imgType !== 'png' && $imgType !== 'jpg' && $imgType !== 'jpeg'
      && $imgType !== 'PNG' && $imgType !== 'JPG' && $imgType !== 'JPEG') {

        echo "<script>alert('Image type must be png or jpg or jpeg!')</script>";

      } else {
        //image type validation success
        move_uploaded_file($_FILES['image']['tmp_name'], 'image/'.$_FILES['image']['name']);

        $stmt = $pdo->prepare("UPDATE products SET name = :name, description = :description,
           price = :price, quantity = :quantity, category_id = :category_id, image = :image WHERE id = :id");
        $result = $stmt->execute(array(
          ':name'=>$_POST['name'],':description'=>$_POST['description'],':price'=>$_POST['price'],
          ':quantity'=>$_POST['quantity'],':category_id'=>$_POST['category'],'image'=>$_FILES['image']['name'],
          ':id'=>$_GET['id'],
        ));

      }
    }
    if ($result) {
      echo "<script>alert('The record is updated!');window.location.href='index.php';</script>";
      exit();
    }
  }
}

include 'header.php';

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute(array(':id'=>$_GET['id']));
$product = $stmt->fetchAll(PDO::FETCH_ASSOC);


 ?>

     <!-- Main content -->
     <div class="content">
       <div class="container-fluid">
             <div class="card">
               <div class="card-body login-card-body">
                 <p class="login-box-msg">Editing the product</p>

                 <form action="" method="post" enctype="multipart/form-data" id="category">
                   <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
                   <span style="font-size:13px; color:red;"><?= $nameError ?? "" ?></span>
                   <div class="form-group mb-3">
                     <label for="name">Product Name</label>
                     <input type="text" id='name' name="name" value="<?= escape($product[0]['name']) ?>" class="form-control" placeholder="Name" >
                   </div>

                   <span style="font-size:13px; color:red;"><?= $descriptionError ?? "" ?></span>
                   <div class="form-group mb-3">
                     <label for="description">Description Name</label><br>
                     <textarea name="description" rows="4" cols="80" class="form-control" ><?= escape($product[0]['description']) ?></textarea>
                   </div>

                   <span style="font-size:13px; color:red;"><?= $priceError ?? "" ?></span>
                   <div class="form-group mb-3">
                     <label for="price">Price</label><br>
                     <input type="number" name="price" value="<?= escape($product[0]['price']) ?>" class="form-control" placeholder="Price" >
                   </div>

                   <span style="font-size:13px; color:red;"><?= $quantityError ?? "" ?></span>
                   <div class="form-group mb-3">
                     <label for="quantity">In Stock</label><br>
                     <input type="number" name="quantity" value="<?= escape($product[0]['quantity']) ?>" class="form-control" placeholder="In Stock" >
                   </div>

                   <span style="font-size:13px; color:red;"><?= $categoryError ?? "" ?></span>
                   <div class="form-group">
                     <label for="category">Category</label><br>
                     <select name='category' class="form-select form-control" form="category">
                       <?php
                       $stmt = $pdo->prepare("SELECT * FROM categories");
                       $stmt->execute();
                       $catResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

                       foreach ($catResults as $catResult) : ?>
                         <option value="<?= $catResult['id'] ?>" <?php if ($product[0]['category_id'] == $catResult['id']) echo "selected"; ?>><?= $catResult['name'] ?></option>
                       <?php endforeach ?>


                     </select>
                   </div>


                   <span style="font-size:13px; color:red;"><?= $imageError ?? "" ?></span>
                   <div class="mb-3">
                      <label for="image" class="form-label">Image</label><br>
                      <img style="width:200px" src="image/<?= escape($product[0]['image']) ?>" alt="product"><br><br>
                      <input type="file" name="image" id="formFileMultiple" multiple>
                    </div>

                   <button type="submit" class="btn btn-primary btn-block mt-3">Update</button>

                 </form>
                 <a href="index.php?page_num=<?= $_GET['page_num'] ?>" class="btn btn-primary float-right mt-2">Back</a>
               </div>
             </div>
       </div><!-- /.container-fluid -->
     </div>
     <!-- /.content -->

 <?php

 include "footer.html";
