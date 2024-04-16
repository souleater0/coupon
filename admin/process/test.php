<?php
include '../../db_connection.php';
include '../time_zone.php';

    // $staff_id = "230629406";

    if(isset($_GET['sd_code']) && !empty($_GET['sd_code'])){
        $staff_id = $_GET['sd_code'];
       
        // Get the current year and month
        $current_year = date('Y');
        $current_month = date('m');
        $staff_id = "230629406";
        $sql_getcutoff = "SELECT
        a.sd_credits,
        a.first_cut_start,
        a.first_cut_end,
        a.second_cut_start,
        a.second_cut_end,
        b.owner_name,
        c.department_name
    FROM
        salary_deduction a
    INNER JOIN owners b ON b.staff_id = a.owner_id
    INNER JOIN department c ON c.id = b.owner_department
    WHERE
        a.owner_id = '$staff_id'";
        $result_getcutoff = mysqli_query($conn, $sql_getcutoff);

        if($result_getcutoff->num_rows > 0) {
            $row_Details = $result_getcutoff->fetch_assoc();
            $first_cut_start = $row_Details["first_cut_start"];
            $first_cut_end = $row_Details["first_cut_end"];
            $second_cut_start = $row_Details["second_cut_start"];
            $second_cut_end = $row_Details["second_cut_end"];
            $max_credits = $row_Details['sd_credits'];

            // Convert day values to specific dates
            $first_cut_start_date = date('Y-m-d', strtotime("$current_year-$current_month-$first_cut_start"));
            $first_cut_end_date = date('Y-m-d', strtotime("$current_year-$current_month-$first_cut_end"));
            // Convert day values to specific dates
            $second_cut_start_date = date('Y-m-d', strtotime("$current_year-$current_month-$second_cut_start"));
            $second_cut_end_date = date('Y-m-d', strtotime("$current_year-$current_month-$second_cut_end"));

            $query_Cut_Off_LOGS = "SELECT DISTINCT DATE(created_at) AS transaction_date FROM balance_deducted
            WHERE owner_id= '$staff_id' AND created_at BETWEEN '$first_cut_start_date' AND '$first_cut_end_date'";
            $result_Cut_Off_LOGS = mysqli_query($conn, $query_Cut_Off_LOGS);
            if($result_Cut_Off_LOGS->num_rows >0){
                while($row_Transaction = $result_Cut_Off_LOGS->fetch_assoc()){
                    echo $row_Transaction['transaction_date'].' <br>';
                }
                
            }else{
                echo "No Transaction Found";
            }

            
        }        

    }else{
        echo "No Result Found";
    }
?>