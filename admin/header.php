<?php
include '../../db_connection.php';
include 'time_zone.php';
require 'process/session_restrict.php';
?>
<!doctype html>
<html lang="en">
  <head>
  	<title></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
		
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="../../assetlibrary/css/style.css">
        <script src="../../assets/jquery.min.js"></script>
        <script src="../../assetlibrary/js/bootstrap.min.js"></script>
    <script src="../../assetlibrary/js/main.js"></script>
    <link rel="stylesheet" href="../../assets/toastr.min.css">
    <script src="../../assets/toastr.min.js"></script>
<style>
      .table-container{
        max-height: 700px;
        overflow-y: scroll;
        width: auto !important;
      }
      .table-container table {
        width: 100% !important;
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
  </head>
  <body>

		  <div class="wrapper d-flex align-items-stretch">
			<nav id="sidebar">
      
				<div class="custom-menu">
					<button type="button" id="sidebarCollapse" class="btn btn-primary">
	        </button>
        </div>
	  		<div class="img bg-wrap text-center py-4" style="background-image: url(images/bg_1.jpg);">
	  			<div class="user-logo">
	  				<div class="img" style="background-image: url(images/logo.jpg);"></div>
	  				<h3>EC SOLUTIONS</h3>
	  			</div>
	  		</div>
	  		<?php
        $route = $_GET['route'] ?? 'home';
        ?>
        <ul class="list-unstyled components mb-5">
          <li class="active">
            <a href="index.php?route=dashboard" <?php echo ($route == 'dashboard') ? 'active' : ''; ?>><span class="fa fa-gift mr-3"></span> food stub reports</a>
          </li>
          <li>
              <a href="index.php?route=report_SD"  <?php echo ($route == 'report_SD') ? 'active' : ''; ?>>Salary Deduction Reports</a>
          </li>
          
          <li>
            <a href="index.php?route=manage_owner_FS"  <?php echo ($route == 'manage_owner_FS') ? 'active' : ''; ?>>Food Stub Owner</a>
          </li>
          <li>
            <a href="index.php?route=manage_owner_SD" <?php echo ($route == 'manage_owner_SD') ? 'active' : ''; ?>>Salary Deduct Owner</a>
          </li>
          <!-- sseision role -->
          <?php
        if($_SESSION['admin_session_role']==2 && !empty($_SESSION['admin_session_role'])){
        ?>
          <li>
            <a href="index.php?route=manage_department" <?php echo ($route == 'manage_department') ? 'active' : ''; ?>>Manage Department</a>
          </li>
          <li>
           <a href="index.php?route=manage_device" class="dropdown-item <?php echo ($route == 'manage_device') ? 'active' : ''; ?>">Manage Device</a>
          </li>
          <?php
        }?>
          <li>
            <a class="nav-link" href="/coupon">back to dashboard <span class="sr-only">(current)</span></a>
          </li>
        </ul>

    	</nav>
       <div id="content" class="p-4 p-md-5 pt-5">
        <h2 class="mb-4"></h2>
        <?php 
          require '../route.php';
?>
      </div>

</div>
        <!-- Page Content  -->
      


 
 