
<?php
include '../../db_connection.php';
include 'time_zone.php';
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" />
    
  </head>
  <body>
  <div class="container">
    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
      <div class="text-start">
        <div class="text-black border p-3">
          <?php echo $day_name.', '.$current_date_format?>
        </div>
      </div>
      <ul class="nav nav-pills col-12 col-md-auto mb-2 justify-content-center mb-md-0" id="headerNav">
        <?php
        $route = $_GET['route'] ?? 'home';
        ?>
        <li class="nav-item"><a href="index.php?route=dashboard" class="nav-link <?php echo ($route == 'dashboard') ? 'active' : ''; ?>">Dashboard</a></li>
        <li class="nav-item"><a href="index.php?route=manage_owner" class="nav-link <?php echo ($route == 'manage_owner') ? 'active' : ''; ?>">Manage Owner</a></li>
        <li class="nav-item"><a href="index.php?route=manage_department" class="nav-link <?php echo ($route == 'manage_department') ? 'active' : ''; ?>">Manage Department</a></li>
      </ul>

      <div class="col-md-3 text-end">
        <form action="../logout.php">
          <button type="submit" class="btn btn-outline-primary me-2">Logout</button>
        </form>
      </div>
    </header>
  </div>