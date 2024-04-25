<?php
include 'db_connection.php';
include 'admin/time_zone.php';

// Get current timestamp
$current_time = time();

// Set start time to 9 AM today
$start_time = strtotime('today 8:00');

// Check if current time is before 4 AM
if (date('G', $current_time) < 4) {
    // End time is 4 AM today
    $end_time = strtotime('tomorrow 4:00');
} else {
    // End time is 4 AM tomorrow
    $end_time = strtotime('tomorrow 4:00');
}

// Format start and end times
$start_time_formatted = date('Y-m-d H:i:s', $start_time);
$end_time_formatted = date('Y-m-d H:i:s', $end_time);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['search']) && !empty($_POST['search'])){
    $searchCode = $_POST['search'];
    $sql = "SELECT a.id, b.owner_name, d.department_name, c.coupon_code, c.coupon_value, a.claim_date,e.display_name, a.remarks
    FROM claims a
    INNER JOIN owners b ON b.staff_id = a.owner_id
    INNER JOIN coupons c ON c.coupon_code = a.coupon_id
    INNER JOIN department d ON b.owner_department = d.id
    INNER JOIN admins e ON a.admin_id = e.id
    WHERE DATE(a.claim_date) = '$current_date' 
    AND c.coupon_code LIKE '%{$searchCode}%' 
    OR DATE(a.claim_date) = '$current_date' AND b.owner_name LIKE '%{$searchCode}%'  
    ORDER BY a.claim_date DESC";

    $result = $conn->query($sql);
}else{
    
    $sql = "SELECT 
    a.id, 
    b.owner_name, 
    d.department_name, 
    c.coupon_code, 
    c.coupon_value, 
    DATE_FORMAT(a.claim_date, '%Y-%m-%d %h:%i %p') AS formatted_claim_date,
    e.display_name, 
    a.remarks
FROM 
    claims a
INNER JOIN 
    owners b ON b.staff_id = a.owner_id
INNER JOIN 
    coupons c ON c.coupon_code = a.coupon_id
INNER JOIN 
    department d ON b.owner_department = d.id
INNER JOIN 
    admins e ON a.admin_id = e.id
WHERE 
    a.claim_date BETWEEN '$start_time_formatted' AND '$end_time_formatted' 
ORDER BY 
    a.claim_date DESC";
    $result = $conn->query($sql);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>
        <tr>
            
            <td><?php echo $row["owner_name"]; ?></td>
            <td><?php echo $row["department_name"]; ?></td>
            <td><?php echo $row["coupon_code"]; ?></td>
            <td><?php echo $row["coupon_value"]; ?></td>
            <td><?php echo $row["formatted_claim_date"]; ?></td>
            <td><?php echo $row["display_name"]; ?></td>
            <td><?php echo $row["remarks"]; ?></td>
        </tr>
<?php
    }
}else{
    echo "No Display Available";
}
?>
