<?php 
    session_start();
    if (!isset($_SESSION['admin_session_id'])) {
        // header("Location:../index.php");
        header("Location:../views/404.php");
        die();
    }
?>