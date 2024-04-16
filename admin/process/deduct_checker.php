<?php
include '../../db_connection.php';
include '../time_zone.php';

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get the current year and month
$current_year = date('Y');
$current_month = date('m');
$staff_id = "230629406";
$sql_getcutoff = "SELECT
a.sd_credits,a.first_cut_start, a.first_cut_end, a.second_cut_start, a.second_cut_end
FROM salary_deduction a
WHERE a.owner_id = '$staff_id'";
$result_getcutoff = mysqli_query($conn, $sql_getcutoff);

if($result_getcutoff->num_rows > 0) {
  $row = $result_getcutoff->fetch_assoc();
  $first_cut_start = $row["first_cut_start"];
  $first_cut_end = $row["first_cut_end"];
  $second_cut_start = $row["second_cut_start"];
  $second_cut_end = $row["second_cut_end"];
  $max_credits = $row['sd_credits'];

    // Convert day values to specific dates
    $first_cut_start_date = date('Y-m-d', strtotime("$current_year-$current_month-$first_cut_start"));
    $first_cut_end_date = date('Y-m-d', strtotime("$current_year-$current_month-$first_cut_end"));
    // Convert day values to specific dates
    $second_cut_start_date = date('Y-m-d', strtotime("$current_year-$current_month-$second_cut_start"));
    $second_cut_end_date = date('Y-m-d', strtotime("$current_year-$current_month-$second_cut_end"));


    // $sd_owner_logs = "";
    echo "<br>FIRST CUT-OFF<br><br>";
    //Retrieve Transaction First Cut OFF
    $sql_transaction_1st_CUT = "SELECT created_at,amount_sd,receipt_no FROM balance_deducted WHERE created_at BETWEEN '$first_cut_start_date' AND '$first_cut_end_date' AND void = '0'";
    $result_transaction_1st_CUT = $conn->query($sql_transaction_1st_CUT);
    while ($row = $result_transaction_1st_CUT->fetch_assoc()) {
        echo $row["created_at"].' '.$row["amount_sd"].' '.$row["receipt_no"].'<br>';
    }
    //Retrieve Deduction Data within the specified cut-off periods
    $sql = "SELECT amount_sd FROM balance_deducted WHERE created_at BETWEEN '$first_cut_start_date' AND '$first_cut_end_date' AND void = '0'";
    $result = $conn->query($sql);
    $total_deduction_first_cut = 0;
    while ($row = $result->fetch_assoc()) {
        $total_deduction_first_cut += $row['amount_sd'];
    }
    echo "<br>1st Cut-Off Deducted: ".$total_deduction_first_cut;

    echo "<br>Balance Left: ". $max_credits - $total_deduction_first_cut;
    // echo "<br><br>SECOND CUT-OFF<br><br>";
    // //Retrieve Transaction Second Cut OFF
    // $sql_transaction_1st_CUT = "SELECT created_at,amount_sd,receipt_no FROM balance_deducted WHERE created_at BETWEEN '$second_cut_start_date' AND '$second_cut_end_date' AND void = '0'";
    // $result_transaction_1st_CUT = $conn->query($sql_transaction_1st_CUT);
    // while ($row = $result_transaction_1st_CUT->fetch_assoc()) {
    //     echo $row["created_at"].' '.$row["amount_sd"].' '.$row["receipt_no"].'<br>';
    // }
    // //Retrieve Deduction Data within the specified cut-off periods
    // $sql = "SELECT amount_sd FROM balance_deducted WHERE created_at BETWEEN '$second_cut_start_date' AND '$second_cut_end_date' AND void = '0'";
    // $result = $conn->query($sql);
    // $total_deduction_second_cut = 0;
    // while ($row = $result->fetch_assoc()) {
    //     $total_deduction_second_cut += $row['amount_sd'];
    // }
    
    // echo "<br>Total Deduction <br> 2nd Cut-Off ".$total_deduction_second_cut;
}
?>