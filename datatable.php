<?php
include 'db_connection.php';
include 'admin/time_zone.php';

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
    a.claim_date BETWEEN '2024-04-26 10:00:00' AND '2024-04-27 04:00:00' 
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
    echo "No Result Found";
}
?>
