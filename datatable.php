<?php
include 'db_connection.php';
include 'admin/time_zone.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['search']) && !empty($_POST['search'])){
    $searchCode = $_POST['search'];
    $sql = "SELECT a.id, b.owner_name, d.department_name, c.coupon_code, c.coupon_value, a.claim_date, a.remarks
    FROM claims a
    INNER JOIN owners b ON a.owner_id = b.id
    INNER JOIN coupons c ON a.coupon_id = c.id
    INNER JOIN department d ON b.owner_department = d.id
    WHERE DATE(a.claim_date) = '$current_date' AND c.coupon_code LIKE '%{$searchCode}%' OR b.owner_name LIKE '%{$searchCode}%'  ORDER BY a.claim_date DESC";

    $result = $conn->query($sql);
}else{
    
    $sql = "SELECT a.id, b.owner_name, d.department_name, c.coupon_code, c.coupon_value, a.claim_date, a.remarks
    FROM claims a
    INNER JOIN owners b ON a.owner_id = b.id
    INNER JOIN coupons c ON a.coupon_id = c.id
    INNER JOIN department d ON b.owner_department = d.id
    WHERE DATE(a.claim_date) = '$current_date' ORDER BY a.claim_date DESC";

    $result = $conn->query($sql);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>
        <tr>
            <th scope="row"><?php echo $row["id"] ?></th>
            <td><?php echo $row["owner_name"]; ?></td>
            <td><?php echo $row["department_name"]; ?></td>
            <td><?php echo $row["coupon_code"]; ?></td>
            <td><?php echo $row["coupon_value"]; ?></td>
            <td><?php echo $row["claim_date"]; ?></td>
            <td><?php echo $row["remarks"]; ?></td>
        </tr>
<?php
    }
}else{
    echo "No Result Found";
}
?>
