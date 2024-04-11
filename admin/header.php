
<?php
include '../../db_connection.php';
include 'time_zone.php';
require 'process/session_restrict.php';
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EC Solution - Admin</title>
    <style>
      .table-container{
        max-height: 700px;
        overflow-y: scroll;
        width: 100%;
      }
      .table-container table {
        width: 100%;
      }

      .table-container thead {
        position: sticky;
        top: 0px;
        background-color: white;
      }
      textarea:focus,
        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="datetime"]:focus,
        input[type="datetime-local"]:focus,
        input[type="date"]:focus,
        input[type="month"]:focus,
        input[type="time"]:focus,
        input[type="week"]:focus,
        input[type="number"]:focus,
        input[type="email"]:focus,
        input[type="url"]:focus,
        input[type="search"]:focus,
        input[type="tel"]:focus,
        input[type="color"]:focus,
        .uneditable-input:focus {   
          
          box-shadow: none;
          /* border-color: rgba(126, 239, 104, 0.8); */
          /* box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(126, 239, 104, 0.6); */
          outline: 0 none;
        }
    </style>
    <link href="../../assets/bootstrap533.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/toastr.min.css">
    <script src="../../assets/jquery.min.js"></script>
    <link rel="stylesheet" href="../../assets/bootstrap-datepicker.css" />
  </head>
  <body>
  <div class="container">
    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
      <div class="col-2">
        <div class="text-black border">
          <?php echo $day_name.', '.$current_date_format.'<br>'.$current_time_format?>
        </div>
      </div>
      <ul class="nav nav-pills col-8 mb-2 justify-content-center" id="headerNav">
        <?php
        $route = $_GET['route'] ?? 'home';
        ?>
        <li class="nav-item"><a href="index.php?route=dashboard" class="nav-link <?php echo ($route == 'dashboard') ? 'active' : ''; ?>">Food Stub Reports</a></li>
        <li class="nav-item"><a href="index.php?route=report_SD" class="nav-link <?php echo ($route == 'report_SD') ? 'active' : ''; ?>">Salary Deduction Reports</a></li>
        <?php
        if($_SESSION['admin_session_role']==2 && !empty($_SESSION['admin_session_role'])){
        ?>
        <li class="nav-item"><a href="index.php?route=manage_owner_FS" class="nav-link <?php echo ($route == 'manage_owner_FS') ? 'active' : ''; ?>">Manage FS Owner</a></li>
        <li class="nav-item"><a href="index.php?route=manage_owner_SD" class="nav-link <?php echo ($route == 'manage_owner_SD') ? 'active' : ''; ?>">Manage SD Owner</a></li>
        <li class="nav-item"><a href="index.php?route=manage_department" class="nav-link <?php echo ($route == 'manage_department') ? 'active' : ''; ?>">Manage Department</a></li>
        <li class="nav-item"><a href="index.php?route=manage_clerk" class="nav-link <?php echo ($route == 'manage_clerk') ? 'active' : ''; ?>">Manage Clerk</a></li>
        <li class="nav-item"><a href="index.php?route=manage_device" class="nav-link <?php echo ($route == 'manage_device') ? 'active' : ''; ?>">Manage Device</a></li>
        <?php
        }?>
      </ul>

      <div class="col-2 text-end">
        <!-- <form action="../logout.php">
          <button type="submit" class="btn btn-outline-primary me-2">Logout</button>
        </form> -->
        <a href="/coupon" class="btn btn-outline-primary me-2">Return</a>
      </div>
    </header>
  </div>