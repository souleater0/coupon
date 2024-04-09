<div class="container">
<h1 class="text-uppercase text-center">Whitelist Devices</h1>
<?php

// // Whitelisted private IPv4 addresses
// $whitelisted_ips = array(
//     '192.168.1.100',  // Example private IPv4 address 1
//     // '192.168.1.101',  // Example private IPv4 address 2
// );

// // Function to get the client's private IPv4 address
// function getClientIp() {
//     $ipaddress = '';

//     // Get the client's IP address
//     if (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR'])) {
//         $ipaddress = $_SERVER['REMOTE_ADDR'];
//     } else {
//         $ipaddress = 'UNKNOWN';
//     }

//     // Remove any IPv6 prefix (if present)
//     $ipaddress = preg_replace('/^(?:.*[\[:])?([^\]]+)/', '$1', $ipaddress);

//     return $ipaddress;
// }

// // Function to check if IPv4 address is whitelisted
// function isWhitelisted($ip) {
//     global $whitelisted_ips;
//     return in_array($ip, $whitelisted_ips);
// }

// // Get the client's private IPv4 address
// $client_ip = getClientIp();

// // Check if the client's private IPv4 address is whitelisted
// if (isWhitelisted($client_ip)) {
//     // Allow access
//     echo "Access granted. Your private IPv4 address ($client_ip) is whitelisted.";
// } else {
//     // Deny access
//     echo "Access denied. Your private IPv4 address ($client_ip) is not whitelisted.";
// }


////private ip
// Whitelisted IP addresses
// $whitelisted_ips = array(
//     '122.3.79.98',  // Example IP address 1
//     // '1'   // Example IP address 2
// );
// function getClientIp() {
//     $ipaddress = '';

//     // Check if the client is behind a proxy or VPN
//     if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
//         $ip_list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
//         $ipaddress = trim($ip_list[0]);
//     } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
//         $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
//     } elseif (isset($_SERVER['HTTP_X_REAL_IP']) && !empty($_SERVER['HTTP_X_REAL_IP'])) {
//         $ipaddress = $_SERVER['HTTP_X_REAL_IP'];
//     } elseif (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR'])) {
//         $ipaddress = $_SERVER['REMOTE_ADDR'];
//     } else {
//         $ipaddress = 'UNKNOWN';
//     }

//     // Remove any IPv6 prefix (if present)
//     $ipaddress = preg_replace('/^(?:.*[\\[:])?([^\\]]+)/', '$1', $ipaddress);

//     return $ipaddress;
// }

// // Function to check if IP address is whitelisted
// function isWhitelisted($ip) {
//     global $whitelisted_ips;
//     return in_array($ip, $whitelisted_ips);
// }

// // Get the client's IP address
// $client_ip = getClientIp();

// // Check if the client's IP address is whitelisted
// if (isWhitelisted($client_ip)) {
//     // Allow access
//     echo "Access granted. Your IP address ($client_ip) is whitelisted.";
// } else {
//     // Deny access
//     echo "Access denied. Your IP address ($client_ip) is not whitelisted.";
// }

// Function to get the client IP address
// function getClientIp() {
//     $ipaddress = '';
//     if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
//         $ip_list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
//         $ipaddress = trim($ip_list[0]);
//     } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
//         $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
//     } elseif (isset($_SERVER['HTTP_X_REAL_IP']) && !empty($_SERVER['HTTP_X_REAL_IP'])) {
//         $ipaddress = $_SERVER['HTTP_X_REAL_IP'];
//     } elseif (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR'])) {
//         $ipaddress = $_SERVER['REMOTE_ADDR'];
//     } else {
//         $ipaddress = 'UNKNOWN';
//     }
//     return $ipaddress;
// }

// // Get the client's IP address
// $client_ip = getClientIp();

// // Print the IP address
// echo "Your IP address is: " . $client_ip;

// function mac(){
//     ob_start();
//     system('ipconfig/all');
//     $mycom = ob_get_contents();
//     ob_clean();

//     $findme="Physical";
//     $pmac = strpos($mycom, $findme);
//     $mac = substr($mycom, ($pmac+36),17);
//     echo "Mac Address : {$mac}";
// }
// mac();

?>
</div>

<script>
    $(document).ready(function() {
    // Get user agent and screen resolution
    var userAgent = navigator.userAgent;
    var screenWidth = window.screen.width;
    var screenHeight = window.screen.height;

    // Send data to server using AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "save_client_info.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    var data = JSON.stringify({
        userAgent: userAgent,
        screenWidth: screenWidth,
        screenHeight: screenHeight
    });

    xhr.send(data);
    alert(data);
});
</script>
<script>

</script>

<!-- // Start or resume the session
session_start();

// Function to set the branch in the user's session
function setBranchInSession($branchId) {
    $_SESSION['branch_id'] = $branchId;
}

// Function to get the branch from the user's session
function getBranchFromSession() {
    return isset($_SESSION['branch_id']) ? $_SESSION['branch_id'] : null;
}

// Example usage when a user logs in or switches branches
$selectedBranchId = $_POST['branch_id']; // Assuming branch selection comes from a form
setBranchInSession($selectedBranchId);

// Example usage when determining branch for a user action
$currentBranchId = getBranchFromSession();

// Customize user experience or access control based on the current branch
if ($currentBranchId === 'branchA') {
    // Branch A specific customization or access control
} elseif ($currentBranchId === 'branchB') {
    // Branch B specific customization or access control
} else {
    // Handle unrecognized branch or default behavior
} -->
