<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>tt's Shopping</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">


    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <!-- Right navbar links -->

      <?php
      $active = explode('/', $_SERVER['PHP_SELF']);
      $active = end($active);
      if ($active == 'index.php' || $active == 'user_list.php' || $active == 'category.php') :
       ?>
       <ul class="navbar-nav ml-auto">
         <!-- Navbar Search -->
         <li class="nav-item">
           <a class="nav-link" data-widget="navbar-search" href="#" role="button">
             <i class="fas fa-search"></i>
           </a>
           <div class="navbar-search-block">
             <form class="form-inline" action="" method="post">
               <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
               <div class="input-group input-group-sm">
                 <input class="form-control form-control-navbar" name="search" value="<?= $_POST['search'] ?? "" ?>" type="search" placeholder="Search" aria-label="Search">
                 <div class="input-group-append">
                   <button class="btn btn-navbar" type="submit">
                     <i class="fas fa-search"></i>
                   </button>
                   <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                     <i class="fas fa-times"></i>
                   </button>
                 </div>
               </div>
             </form>
           </div>
         </li>
       </ul>
      <?php
    endif;
       ?>
    </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">TT's Shopping</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <!-- <div class="image">
          <img src="" class="img-circle elevation-2" alt="User Image">
        </div> -->
        <div class="info">
          <a href="#" class="d-block"><?= $_SESSION['user_name']; ?></a>
        </div>
      </div>


      <!-- Sidebar Menu -->
      <nav class="mt-2">

        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="index.php" class="nav-link <?php if ($active === 'index.php') echo "active"; ?>">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Product
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="category.php" class="nav-link <?php if ($active === 'category.php') echo "active"; ?>">
              <i class="nav-icon fas fa-list"></i>
              <p>
                Category
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="user_list.php" class="nav-link <?php if ($active === 'user_list.php') echo "active"; ?>">
              <i class="nav-icon fas fa-user"></i>
              <p>
                User
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="order_list.php" class="nav-link <?php if ($active === 'order_list.php') echo "active"; ?>">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Orders
              </p>
            </a>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">

      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
