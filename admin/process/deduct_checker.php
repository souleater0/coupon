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
a.first_cut_start, a.first_cut_end, a.second_cut_start, a.second_cut_end
FROM salary_deduction a
WHERE a.owner_id = '$staff_id'";
$result_getcutoff = mysqli_query($conn, $sql_getcutoff);

if($result_getcutoff->num_rows > 0) {
  $row = $result_getcutoff->fetch_assoc();
  $first_cut_start = $row["first_cut_start"];
  $first_cut_end = $row["first_cut_end"];
  $second_cut_start = $row["second_cut_start"];
  $second_cut_end = $row["second_cut_end"];

  // Convert day values to specific dates
  $first_cut_start_date = date('Y-m-d', strtotime("$current_year-$current_month-$first_cut_start"));
  $first_cut_end_date = date('Y-m-d', strtotime("$current_year-$current_month-$first_cut_end"));
  // Convert day values to specific dates
  $second_cut_start_date = date('Y-m-d', strtotime("$current_year-$current_month-$second_cut_start"));
  $second_cut_end_date = date('Y-m-d', strtotime("$current_year-$current_month-$second_cut_end"));

    //Retrieve Deduction Data within the specified cut-off periods
    $sql = "SELECT amount_sd FROM balance_deducted WHERE created_at BETWEEN '$first_cut_start_date' AND '$first_cut_end_date' AND void = '0'";
    $result = $conn->query($sql);
    $total_deduction_first_cut = 0;
    while ($row = $result->fetch_assoc()) {
        $total_deduction_first_cut += $row['amount_sd'];
    }

    //Retrieve Deduction Data within the specified cut-off periods
    $sql = "SELECT amount_sd FROM balance_deducted WHERE created_at BETWEEN '$second_cut_start_date' AND '$second_cut_end_date' AND void = '0'";
    $result = $conn->query($sql);
    $total_deduction_second_cut = 0;
    while ($row = $result->fetch_assoc()) {
        $total_deduction_second_cut += $row['amount_sd'];
    }
    echo "Total Deduction <br> 1st Cut-Off ".$total_deduction_first_cut;
    echo "<br>Total Deduction <br> 2nd Cut-Off ".$total_deduction_second_cut;
}
?>