<?php
$route = $_GET['route'] ?? 'home';
switch ($route){
    case "dashboard":
        require 'views/admin_dashboard.php';
        break;
    case "manage_owner":
        // require -__DIR__ ."views/manage_owner.php";
        require 'views/manage_owner.php';
        break;
    case "manage_department":
            // require -__DIR__ ."views/manage_owner.php";
            require 'views/manage_department.php';
            break;
    default:
        // require 'home.php';
        
}
?>