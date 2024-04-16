<?php
session_start();
include '../../db_connection.php';
include '../time_zone.php';

if(!empty($_POST['action']) && $_POST['action'] == 'fetchdate_Transaction') {
    if(!empty($_POST['date']) && isset($_POST['date']) && !empty($_POST['sd_code']) && isset($_POST['sd_code'])){

        $date = $_POST['date'];
        $sd_code = $_POST['sd_code'];

        $query_DateTransacted ="SELECT
        a.created_at,
        a.amount_sd,
        a.receipt_no
        FROM balance_deducted a WHERE DATE(created_at) = '$date' AND sd_code = '$sd_code'";
        $result_DateTransacted = mysqli_query($conn, $query_DateTransacted);
        $total_deduction = 0;
        if($result_DateTransacted->num_rows>0){
            while($row_DateTransacted = $result_DateTransacted->fetch_assoc())
            {
                // Increment total sum of deductions
                $total_deduction += $row_DateTransacted["amount_sd"];
                ?>
                <tr>
                <td><?php echo $row_DateTransacted["created_at"];?></td>
                <td><?php echo $row_DateTransacted["amount_sd"];?></td>
                <td><?php echo $row_DateTransacted["receipt_no"];?></td>
                <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#void_inputmdl">VOID</button></td>
                </tr>
                <?php
            }
            // Return total sum of deductions
        echo '<tr><td colspan="3" class="fw-bold">Total Deduction:</td><td><strong>'.$total_deduction.'<strong></td></tr>';
        }else{
        }
    }
}
?>