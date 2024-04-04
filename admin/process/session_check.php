<?php 
    session_start();
    if (isset($_SESSION['admin_session_id'])) {
        header("Location: views/index.php?route=dashboard");
        die();
    }
?>