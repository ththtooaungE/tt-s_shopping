<?php
session_start();
require "../config/config.php";
require "../config/common.php";

if ($_POST) {
  if (empty($_POST['email']) || empty($_POST['password'])) {
    if (empty($_POST['email'])) $emailError = "This field is required!";
    if (empty($_POST['password'])) $passwordError = "This field is required!";

  } else {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");

    $stmt->bindValue(':email',$_POST['email']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user) {
      if (password_verify($_POST['password'],$user['password']) && !empty($user['role'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = 1;
        $_SESSION['logged_in'] = time();

        header('Location: index.php');
      }
    }
    echo "<script>alert('Incorrect credentials');</script>";
  }
}
 ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>tt's Blogs | Log in</title>

   <!-- Google Font: Source Sans Pro -->
   <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
   <!-- Font Awesome -->
   <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
   <!-- icheck bootstrap -->
   <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
   <!-- Theme style -->
   <link rel="stylesheet" href="dist/css/adminlte.min.css">
 </head>
 <body class="hold-transition login-page">
 <div class="login-box">
   <div class="login-logo">
     <a href=""><b>TT's Shop </b>Admin</a>
   </div>
   <!-- /.login-logo -->
   <div class="card">
     <div class="card-body login-card-body">
       <p class="login-box-msg">Sign in to start your session</p>

       <form action="" method="post">
         <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
         <div class="input-group mb-3">
           <input type="email" name="email" class="form-control" placeholder="Email" required>
           <div class="input-group-append">
             <div class="input-group-text">
               <span class="fas fa-envelope"></span>
             </div>
           </div>
         </div>
         <div class="input-group mb-3">
           <input type="password" name="password" class="form-control" placeholder="Password" required>
           <div class="input-group-append">
             <div class="input-group-text">
               <span class="fas fa-lock"></span>
             </div>
           </div>
         </div>
         <div class="row">

           <!-- /.col -->
           <div class="col-4">
             <button type="submit" class="btn btn-primary btn-block">Sign In</button>
           </div>
           <!-- /.col -->
         </div>
       </form>
       <!-- <p class="mb-0">
         <a href="register.html" class="text-center">Register a new membership</a>
       </p> -->
     </div>
     <!-- /.login-card-body -->
   </div>
 </div>
 <!-- /.login-box -->

 <!-- jQuery -->
 <script src="plugins/jquery/jquery.min.js"></script>
 <!-- Bootstrap 4 -->
 <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
 <!-- AdminLTE App -->
 <script src="dist/js/adminlte.min.js"></script>
 </body>
 </html>
