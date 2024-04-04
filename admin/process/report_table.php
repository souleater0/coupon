<?php
    include '../../db_connection.php';
    include '../time_zone.php';

    if(isset($_POST['start_date']) && !empty($_POST['start_date']) && isset($_POST['end_date']) && !empty($_POST['end_date']) && isset($_POST['department']) && !empty($_POST['department'])){
        
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $departmentID  = $_POST['department'];
        // echo $start_date.' '.$end_date;
        $report = "SELECT
        b.coupon_code,
        b.coupon_value,
        c.owner_name,
        d.department_name,
        a.claim_date,
        a.remarks 
        FROM
        claims AS a
        INNER JOIN coupons AS b ON a.coupon_id = b.id
        INNER JOIN owners AS c ON a.owner_id = c.id 
        AND b.owner_id = c.id
        INNER JOIN department AS d ON c.owner_department = d.id
        WHERE DATE (a.claim_date) BETWEEN '$start_date' AND '$end_date' AND d.id = '$departmentID'";
        $result = $conn->query($report);
    }
    else{
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $report = "SELECT
        b.coupon_code,
        b.coupon_value,
        c.owner_name,
        d.department_name,
        a.claim_date,
        a.remarks 
        FROM
        claims AS a
        INNER JOIN coupons AS b ON a.coupon_id = b.id
        INNER JOIN owners AS c ON a.owner_id = c.id 
        AND b.owner_id = c.id
        INNER JOIN department AS d ON c.owner_department = d.id
        WHERE DATE (a.claim_date) BETWEEN '$start_date' AND '$end_date'";
        $result = $conn->query($report);
    }
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
?>

        <tr>
            <td><?php echo $row["coupon_code"]; ?></td>
            <td><?php echo $row["coupon_value"]; ?></td>
            <td><?php echo $row["owner_name"]; ?></td>
            <td><?php echo $row["department_name"]; ?></td>
            <td><?php echo $row["claim_date"]; ?></td>
            <td><?php echo $row["remarks"]; ?></td>
        </tr>
<?php 
    }
}else{
    echo "No Result Found";
}
?>