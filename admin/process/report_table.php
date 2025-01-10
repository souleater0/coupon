<?php
    include '../../db_connection.php';
    include '../time_zone.php';

    if(!empty($_POST['action']) && $_POST['action'] == 'reportGenerate'){
        if(isset($_POST['start_date']) && !empty($_POST['start_date']) 
        && isset($_POST['end_date']) && !empty($_POST['end_date']) 
        && isset($_POST['department'])
        && isset($_POST['time_In']) 
        && isset($_POST['time_Out'])
        && isset($_POST['personID'])
    ){  
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $departmentID  = $_POST['department'];
            $time_IN = $_POST['time_In'];
            $time_OUT = $_POST['time_Out'];
            $personID = $_POST['personID'];
            //convert to military time
            $m_time_IN = date("H:i:59", strtotime($time_IN));
            $m_time_OUT = date("H:i:59", strtotime($time_OUT));
    
            $startDateTime = $start_date. ' '.$m_time_IN;
            $endDateTime = $end_date.' '.$m_time_OUT;
            // echo $start_date.' '.$end_date;
            $report = "SELECT
            b.coupon_code,
            b.coupon_value,
            c.lname,
            c.fname,
            c.mname,
            d.department_name,
            a.claim_date,
            a.remarks,
            e.display_name
        FROM
            claims AS a
            INNER JOIN coupons AS b ON a.coupon_id = b.coupon_code
            INNER JOIN owners AS c ON a.owner_id = c.staff_id 
            AND b.owner_id = c.staff_id
            INNER JOIN department AS d ON c.owner_department = d.id
            INNER JOIN admins AS e ON a.admin_id = e.id
        WHERE
            ";
            if(empty($_POST['time_In']) && empty($_POST['time_Out'])){
                $report .="DATE(a.claim_date) BETWEEN '$start_date'AND '$end_date'";
            }else{
                $report .="a.claim_date BETWEEN '$startDateTime'AND '$endDateTime'";
            }
            if(!empty($_POST['department'])){
                $report .="AND d.id = '$departmentID'";
            }
            if(!empty($_POST['personID'])){
                $report .="AND e.id = '$personID'";
            }
            $report .="
            ORDER BY a.claim_date ASC";
            $result = $conn->query($report);
        }
        // AND d.id = '$departmentID' AND e.id = '$personID'
        else{
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $time_IN = $_POST['time_In'];
            $time_OUT = $_POST['time_Out'];
    
            //convert to military time
            $m_time_IN = date("H:i:59", strtotime($time_IN));
            $m_time_OUT = date("H:i:59", strtotime($time_OUT));
    
            $startDateTime = $start_date. ' '.$m_time_IN;
            $endDateTime = $end_date.' '.$m_time_OUT;
    
            $report = "SELECT
            b.coupon_code,
            b.coupon_value,
            c.lname,
            c.fname,
            c.mname,
            d.department_name,
            a.claim_date,
            a.remarks,
            e.display_name
        FROM
            claims AS a
            INNER JOIN coupons AS b ON a.coupon_id = b.coupon_code
            INNER JOIN owners AS c ON a.owner_id = c.staff_id 
            AND b.owner_id = c.staff_id
            INNER JOIN department AS d ON c.owner_department = d.id
            INNER JOIN admins AS e ON a.admin_id = e.id
        WHERE
            a.claim_date BETWEEN '$startDateTime'
            AND '$endDateTime'
            ORDER BY a.claim_date ASC";
            $result = $conn->query($report);
        }
    }
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
?>
        <tr>
            <td><?php echo $row["coupon_code"]; ?></td>
            <td><?php echo $row["coupon_value"]; ?></td>
            <td><?php echo $row["lname"]; ?></td>
            <td><?php echo $row["fname"]; ?></td>
            <td><?php echo $row["mname"]; ?></td>
            <td><?php echo $row["department_name"]; ?></td>
            <!-- <td><?php echo $row["claim_date"]; ?></td> -->
            <td><?php echo date("F j, Y h:i A", strtotime($row["claim_date"])); ?></td>
            <td><?php echo $row["remarks"]; ?></td>
            <td><?php echo $row["display_name"]; ?></td>
        </tr>
<?php 
    }
    }else{
        echo "No Result Found";
    }
?>