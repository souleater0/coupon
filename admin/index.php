<?php
    require 'process/session_check.php';
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EC Solution - Login</title>
    <style>
        html,
        body {
        height: 100%;
        }
        body{
            display: -ms-flexbox;
            display: -webkit-box;
            display: flex;
            -ms-flex-align: center;
            -ms-flex-pack: center;
            -webkit-box-align: center;
            align-items: center;
            -webkit-box-pack: center;
            justify-content: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }
        .login-form{
            width: 100%;
            max-width: 400px;
            padding: 15px;
            margin: 0 auto;
        }
    </style>
    <link href="../assets/bootstrap533.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/toastr.min.css">
    <script src="../assets/jquery.min.js"></script>
  </head>
  <body>
        <div class="login-form">
            <form id="form_login">
            <h1 class="text-center">Admin Login</h1>
            <input type="text" name="email" class="form-control" id="form_email" placeholder="name@example.com">
            <input type="password" name="password" id="form_password" class="form-control" placeholder="******" aria-describedby="passwordHelpBlock">
            <div id="passwordHelpBlock" class="form-text d-none">
            Your password must be 8-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.
            </div>
            <button type="button" id="submit_login" class="btn btn-primary w-100 mt-2">Submit</button>
            </form>
        </div>
        <script>
        $(document).ready(function(){
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
            $('#form_password').keypress(function (event) {
                if (event.keyCode === 13) {
                    event.preventDefault(); 
                    $('#submit_login').click();
                }
            });
            $('#submit_login').click(function(){
                var formData = $('#form_login').serialize();
                $.ajax({
                    url: "process/adminlogin.php",
                    method: "POST",
                    data: formData+"&action=loginProcess",
                    dataType: "json",
                    success: function(response) {
                        if(response.success==true){
                            
                            toastr.success(response.message);
                            setTimeout(function() {
                                window.location.href = response.redirectUrl;
                            }, 2000);
                            
                        }else{
                            toastr.error(response.message);
                        }
                    }
                });
            });
        });
        </script>
    <script src="../assets/bootstrap.bundle.min.js"></script>
    <script src="../assets/toastr.min.js"></script>
  </body>
</html>