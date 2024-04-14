<?php
include 'db_connection.php';
include 'admin/time_zone.php';
session_start();
if (!isset($_SESSION['admin_session_id'])) {
    header("Location: admin/index.php");
    die();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EC FOOD STUB</title>
    <style>
        .salarydeduc-container{
            max-height: 300px;
            overflow-y: scroll;
            width: 100%;
        }
        .table-container {
            max-height: 600px;
            overflow-y: scroll;
            width: 100%;
        }

        .table-container table, .salarydeduc-container table {
            width: 100%;
        }

        .table-container thead,.salarydeduc-container thead {
            position: sticky;
            top: 0px;
            background-color: white;
        }
    </style>
    <link href="assets/bootstrap533.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/toastr.min.css">
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <script src="assets/jquery.min.js"></script>
    <script src="assets/bootstrap.min.js"></script>

</head>

<body>
    
    <!-- -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light px-5">
        <div class="text-black border px-3 py-3">
          <?php echo $day_name.', '.$current_date_format.'<br>'.$current_time_format?>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">   
      <li class="nav-item">
            <a class ="nav-link" href="admin/views/index.php?route=dashboard">Dashboard</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="float-end">Current User: <b><?php echo $_SESSION['admin_session_name']?></b>&nbsp;&nbsp;&nbsp;Location: <b><?php echo $_SESSION['admin_session_location']?></b></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="admin/logout.php">LOG OUT</a>
        </div>
      </li>
    </ul>
  </div>
    </nav>
    <!--  -->
    <div class="container">
        <ul class="nav nav-tabs mt-5">
            <li class="active"><a data-toggle="tab" href="#food_stub">FOOD STUB</a></li>
            <li><a data-toggle="tab" href="#salary_deduction">SALARY DEDUCTION</a></li>
        </ul>

        <div class="tab-content">
            <div id="food_stub" class="tab-pane fade in active">
                <!--  -->
                <div class="container col d-flex justify-content-center" style="margin-bottom: 2%;margin-top: 5%;">
                    <div class="card w-50">
                        <div class="card-body">
                            <h1 class="card-title text-center">EC FOOD STUB</h1>
                            <form id="barcode_scan" class="text-center">
                                <label for="coupon">Scan Coupon Barcode:</label>
                                <input type="text" id="coupon" name="coupon" autofocus
                                    oninput="moveToNextInput(this, 'id')">
                                <br><br>
                                <label for="id">Scan Owner ID Barcode:</label>
                                <input type="text" id="id" name="id">
                                <br><br>
                                <button type="button" style="display: none;" id="addBarcode"
                                    class="btn btn-primary">Submit</button>
                            </form>
                            </br>
                            <input type="text" class="form-control search" id="live_search" autocomplete="off"
                                placeholder="Type Stub Code">
                        </div>
                    </div>
                </div>
                <!-- -->

                <!--  -->
                <div class="container">
                    <!-- <span class="w-50" id="basic-addon1">Search</span> -->
                    <!-- <input type="text" class="form-control search" id="live_search" autocomplete="off"> -->
                    <div class="table-container">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <!-- <th scope="col">#</th> -->
                                    <th scope="col">Full Name</th>
                                    <th scope="col">Department</th>
                                    <th scope="col">Coupon Code</th>
                                    <th scope="col">Coupon Value</th>
                                    <th scope="col">Claimed Date</th>
                                    <th scope="col">Clerk</th>
                                    <th scope="col">Remarks</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <!-- SALARY DEDUCTION TAB -->
            <div id="salary_deduction" class="tab-pane fade">
                <!--  -->
                <div class="container col d-flex justify-content-center" style="margin-bottom: 2%;margin-top: 5%;">
                    <div class="card w-50">
                        <div class="card-body">
                            <h1 class="card-title text-center">SALARY DEDUCTION</h1>
                            <form id="sd_scan" class="text-center">
                                <label for="coupon">SD Barcode:</label>
                                <input type="text" id="sd_coupon" name="sd_coupon" autofocus
                                    oninput="moveToNextInput(this, 'id')">
                                <br><br>
                                <label for="id">ID Barcode:</label>
                                <input type="text" id="s_id" name="s_id">
                                                                <br><br>
                                <label for="id">AMOUNT SD:</label>
                                <input type="text" id="amount_sd" name="amount_sd">
                                                                <br><br>
                                <label for="id">RECEIPT #:</label>
                                <input type="text" id="receipt_no" name="receipt_no">
                                <br><br>
                                <button type="button" id="addDeduction" class="btn btn-primary">Submit</button>
                            </form>
                            </br>
                            <input type="text" class="form-control search" id="live_search2" autocomplete="off"
                                placeholder="Type Stub Code">
                        </div>
                    </div>
                </div>
                <!-- -->
                
<div class="salarydeduc-container">
<table class="table table-hover">
        <thead>
            <tr> 
            <th scope="col">FULL NAME</th>
            <th scope="col">DEPARTMENT</th>
            <th scope="col">SD CODE</th>
            <th scope="col">REMAINING BALANCE</th>
            <th scope="col">ACTION</th>
            </tr>
        </thead>
        <tbody id="tableReport">
        </tbody>
</table>
</div>
<!-- SALARY DEDUCTION END -->
                <!--  -->
            </div>

        </div>
    </div>
                <script>
                    $(document).ready(function () {
                        $("#live_search").keyup(function () {
                            var search = $(this).val();
                        });
                        $("#live_search2").keyup(function () {
                            var search2 = $(this).val();
                            // alert(search2);
                        });
                        toastr.options = {
                            "closeButton": true,
                            "debug": false,
                            "newestOnTop": true,
                            "progressBar": true,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": true,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                        $('#coupon').keypress(function (event) {
                            if (event.keyCode === 13) { // Check if Enter key is pressed
                                event.preventDefault(); // Prevent form submission
                                $('#id').focus(); // Focus on the second input
                            }
                        });
                        $('#id').keypress(function (event) {
                            if (event.keyCode === 13) {
                                event.preventDefault();
                                $('#addBarcode').click();
                            }
                        });
                        $('#addBarcode').click(function () {
                            var formData = $('#barcode_scan').serialize();
                            $.ajax({
                                url: "process_scan.php",
                                method: "POST",
                                data: formData +
                                    "&action=addBarcode&date=<?php echo $current_date;?>",
                                dataType: "json",
                                success: function (response) {
                                    if (response.success == true) {
                                        toastr.success(response.message);
                                        // setTimeout(() => {
                                        //     location.reload();
                                        // }, 2000);
                                        $('#coupon').val("");
                                        $('#id').val("");
                                        $('#coupon').focus();
                                    } else {
                                        toastr.error(response.message);
                                        $('#coupon').val("");
                                        $('#id').val("");
                                        $('#coupon').focus();
                                    }
                                }
                            });
                        });
                        $('#addDeduction').click(function () {
                            // var formData = $('#sd_scan').serialize();
                            alert("test");
                        });
                        autorefresh();

                        function autorefresh() {
                            setInterval(function () {
                                LoadTable();
                                LoadTable2();
                            }, 1000);
                        }

                        function LoadTable() {
                            var search = $('#live_search').val();
                            if (search == "") {
                                $.ajax({
                                    url: "datatable.php",
                                    type: "POST",
                                    cache: false,
                                    data: {
                                        search: search
                                    },
                                    success: function (data) {
                                        // alert(data);
                                        // toastr.success("Record Retrieve Successful");
                                        $('#tableBody').html(data);
                                    }
                                });
                            } else {
                                $.ajax({
                                    url: "datatable.php",
                                    type: "POST",
                                    cache: false,
                                    data: {
                                        search: search
                                    },
                                    success: function (data) {
                                        // alert(data);
                                        $('#tableBody').html(data);
                                    }
                                });
                            }
                        }
                        function LoadTable2() {
                            var search2 = $('#live_search2').val();
                            
                            if (search2 == "") {
                                $.ajax({
                                    url: "admin/process/sd_table.php",
                                    type: "POST",
                                    cache: false,
                                    data: {
                                        search: search2
                                    },
                                    success: function (data) {
                                        // alert(data);
                                        // toastr.success("Record Retrieve Successful");
                                        $('#tableReport').html(data);
                                    }
                                });
                            } else {
                                $.ajax({
                                    url: "admin/process/sd_table.php",
                                    type: "POST",
                                    cache: false,
                                    data: {
                                        search: search2
                                    },
                                    success: function (data) {
                                        // alert(data);
                                        
                                        $('#tableReport').html(data);
                                    }
                                });
                            }
                        }
                    });
                    // document.onkeydown = function (e) {
                    // return false;
                    // }
                </script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
                    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
                    crossorigin="anonymous"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>

</html>