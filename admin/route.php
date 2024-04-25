<?php
$route = $_GET['route'] ?? 'home';
switch ($route){
    case "dashboard":
        require 'views/admin_dashboard.php';
        break;
    case "report_SD":
        require 'salarydeduction_report.php';
        break;
    case "manage_owner_FS":
        require 'views/manage_owner_FS.php';
        break;
    case "manage_owner_SD":
            require 'views/manage_owner_SD.php';
            break;
    case "manage_department":
            require 'views/manage_department.php';
            break;
    case "manage_clerk":
        require 'views/manage_clerk.php';
        break;
    case "manage_owner":
        require 'views/manage_owner.php';
        break;
    default:
        // require 'home.php';        
}
?>