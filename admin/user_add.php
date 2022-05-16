<?php
session_start();
require "../config/config.php";
require "../config/common.php";

if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in']) || empty($_SESSION['role'])) {
  header("Location: login.php");
  exit();
}

include 'header.php';

if ($_POST) {
  if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['address']) ||
   empty($_POST['phone']) || empty($_POST['role']) || strlen($_POST['password'] < 5) || !is_numeric($_POST['phone']) {
    if (empty($_POST['name'])) $nameError = 'This field can\'t be empty';
    if (empty($_POST['email'])) $emailError = 'This field can\'t be empty';
    if (empty($_POST['password'])) $passwordError = 'This field can\'t be empty';
    if (empty($_POST['address'])) $adderssError = 'This field can\'t be empty';
    if (empty($_POST['phone'])) $phoneError = 'This field can\'t be empty';
    if (empty($_POST['role'])) $roleError = 'This field can\'t be empty';
    if (!empty($_POST['password']) && strlen($_POST['password']) < 5) $passwordError = "The number of characters must be longer than 4!";
    if (!empty($_POST['phone']) && !is_numeric($_POST['phone'])) $phoneError = 'This field must contain munbers!';

  } else {
    $stmt = $pdo->prepare("SELECT COUNT(email) FROM users WHERE email = :email");
    $stmt->execute(array(':email'=>$_POST['email']));
    $user = $stmt->fetchColumn();

    if ($_POST['role'] === 'admin') $role = 1;
    else $role = 0;

    if (empty($user)) {
      $stmt = $pdo->prepare("INSERT INTO users(name, email, password, address, phone, role, created_at) VALUES(:name, :email, :password, :address, :phone, :role, NOW())");
      $result = $stmt->execute(
        array(
          ':name'=>$_POST['name'],
          ':email'=>$_POST['email'],
          ':password'=>password_hash($_POST['password'], PASSWORD_DEFAULT),
          ':address'=>$_POST['address'],
          ':phone'=>$_POST['phone'],
          ':role'=>$role
        )
      );
      if ($result) {
        echo "<script>alert('New record is added!');window.location.href = 'user_list.php';</script>";
      }
    }
  }
}

 ?>

     <!-- Main content -->
     <div class="content">
       <div class="container-fluid">
         <div class="row">
           <div class="col-12">
             <div class="card">
               <div class="card-body login-card-body">
                 <p class="login-box-msg">Create a new account</p>

                 <form action="" method="post">
                   <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
                   <span style="font-size:13px; color:red;"><?= $nameError ?? "" ?></span>
                   <div class="input-group mb-3">
                     <input type="text" name="name" class="form-control" placeholder="Name" >
                     <div class="input-group-append">
                       <div class="input-group-text">
                         <i class="fas fa-user"></i>
                       </div>
                     </div>
                   </div>
                   <span style="font-size:13px; color:red;"><?= $emailError ?? "" ?></span>
                   <div class="input-group mb-3">
                     <input type="email" name="email" class="form-control" placeholder="Email" >
                     <div class="input-group-append">
                       <div class="input-group-text">
                         <span class="fas fa-envelope"></span>
                       </div>
                     </div>
                   </div>
                   <span style="font-size:13px; color:red;"><?= $passwordError ?? "" ?></span>
                   <div class="input-group mb-3">
                     <input type="password" name="password" class="form-control" placeholder="Password" >
                     <div class="input-group-append">
                       <div class="input-group-text">
                         <span class="fas fa-lock"></span>
                       </div>
                     </div>
                   </div>
                   <span style="font-size:13px; color:red;"><?= $adderssError ?? "" ?></span>
                   <div class="input-group mb-3">
                     <input type="text" name="address" class="form-control" placeholder="Address" >
                     <div class="input-group-append">
                       <div class="input-group-text">
                         <span class="fas fa-address-card"></span>
                       </div>
                     </div>
                   </div>
                   <span style="font-size:13px; color:red;"><?= $phoneError ?? "" ?></span>
                   <div class="input-group mb-3">
                     <input type="number" name="phone" class="form-control" placeholder="Phone" >
                     <div class="input-group-append">
                       <div class="input-group-text">
                         <span class="fas fa-phone"></span>
                       </div>
                     </div>
                   </div>
                   <span style="font-size:13px; color:red;"><?= $roleError ?? "" ?></span>
                   <div class="form-check">
                     <input class="form-check-input" type="radio" value="admin" name="role" id="admin" >
                     <label class="form-check-label" for="admin">Admin</label>
                   </div>
                   <div class="form-check">
                     <input class="form-check-input" type="radio" value="user" name="role" id="user" >
                     <label class="form-check-label" for="user">User</label>
                   </div>

                   <button type="submit" class="btn btn-primary btn-block mt-3">Create</button>

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
