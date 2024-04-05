<?php
include 'db_connection.php';
include 'admin/time_zone.php';
require 'admin/process/session_restrict.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EC FOOD STUB</title>
    <style>
              .table-container{
        max-height: 600px;
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
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
    <h1>Barcode Scanner</h1>
    <form id="barcode_scan">
        <label for="coupon">Scan Coupon Barcode:</label>
        <input type="text" id="coupon" name="coupon" autofocus>
        <br><br>
        <label for="id">Scan Owner ID Barcode:</label>
        <input type="text" id="id" name="id">
        <br><br>
        <button type="button" style="display: none;" id="addBarcode" class="btn btn-primary">Submit</button>
    </form>
    <div class="container">
<span class="w-50" id="basic-addon1">Search</span>
<input type="text" class="form-control search" id="live_search" autocomplete="off">
<div class="table-container">
<table class="table table-hover">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Full Name</th>
            <th scope="col">Department</th>
            <th scope="col">Coupon Code</th>
            <th scope="col">Coupon Value</th>
            <th scope="col">Claimed Date</th>
            <th scope="col">Remarks</th>
            </tr>
        </thead>
        <tbody id="tableBody">

        </tbody>
        </table>
</div>

    </div>
    <script>
          $(document).ready(function(){
            $("#live_search").keyup(function(){
            var search = $(this).val();
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
            $('#coupon').keypress(function(event){
                if (event.keyCode === 13) { // Check if Enter key is pressed
                    event.preventDefault(); // Prevent form submission
                    $('#id').focus(); // Focus on the second input
                }
            });
            $('#id').keypress(function(event){
                if (event.keyCode === 13) {
                    event.preventDefault();
                    $('#addBarcode').click();
                }
            });
            $('#addBarcode').click(function(){
                var formData = $('#barcode_scan').serialize();
                $.ajax({
                    url: "process_scan.php",
                    method: "POST",
                    data: formData+"&action=addBarcode&date=<?php echo $current_date;?>",
                    dataType: "json",
                    success: function(response) {
                        if(response.success==true){
                            toastr.success(response.message);
                            // setTimeout(() => {
                            //     location.reload();
                            // }, 2000);
                        }else{
                            toastr.error(response.message);
                            $('#coupon').val("");
                            $('#id').val("");
                            $('#coupon').focus();
                        }
                    }
                });
            });
                    autorefresh();
        function autorefresh(){
         setInterval(function(){
          LoadTable();
        },1000);
        }
        function LoadTable() {
            var search = $('#live_search').val();
            if(search=="")
        {
            $.ajax({
                url: "datatable.php",
                type: "POST",
                cache: false,
                data:{
                    search:search
                    },
                success:function(data){
                    // alert(data);
                    // toastr.success("Record Retrieve Successful");
                    $('#tableBody').html(data);
                }
                });
        }else{
            $.ajax({
                url: "datatable.php",
                type: "POST",
                cache: false,
                data:{
                    search:search
                    },
                success:function(data){
                    // alert(data);
                    $('#tableBody').html(data);
                }
                });
            }
        }
        });
        // document.onkeydown = function (e) {
        // return false;
        // }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>
</html>
