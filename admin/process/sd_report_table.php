<?php
    include '../../db_connection.php';
    include '../time_zone.php';

    if(!empty($_POST['action']) && $_POST['action'] == 'reportGenerate'){
        if(isset($_POST['MonthYear']) && !empty($_POST['MonthYear'])
        && isset($_POST['dep_ID'])
        && isset($_POST['selectType']) 
        && isset($_POST['selectCutoff'])
        && isset($_POST['clerk_ID'])
        ){  
            $MonthYear = $_POST['MonthYear'];

            $selected_Month = date('m', strtotime($current_MONTH_YEAR));
            $selected_Year = date('Y', strtotime($current_MONTH_YEAR));
            $dep_ID = $_POST['dep_ID'];
            $selectCutOFF = $_POST['selectCutoff'];
            $selectedTYPE  = $_POST['selectType'];
            $clerk_ID = $_POST['clerk_ID'];

            //summary
            if($selectedTYPE=="s_type1"){
                $query_summary ="SELECT
                a.owner_name,
                b.department_name,
                c.sd_code,
                CONCAT('$selected_Month', ' ', '$selected_Year') AS cut_off_month_year,
                ";
                
                if($selectCutOFF== "1"){
                    $query_summary .= "CONCAT('1st Cut-Off: ', c.first_cut_start, ' to ', c.first_cut_end) AS cut_off,";
                }else if($selectCutOFF== "2"){
                    $query_summary .= "CONCAT('2nd Cut-Off: ', c.second_cut_start, ' to ', c.second_cut_end) AS cut_off,";
                }

                $query_summary.="
                IFNULL(SUM(d.amount_sd), 0) AS total_deducted_amount
                FROM
                    owners a
                    INNER JOIN department b ON b.id = a.owner_department
                    INNER JOIN salary_deduction c ON c.owner_id = a.staff_id
                    LEFT JOIN balance_deducted d ON d.sd_code = c.sd_code 
                        AND d.owner_id = a.staff_id 
                        ";
                if($selectCutOFF== "1"){
                    $query_summary.= "AND DAY(d.created_at) BETWEEN c.first_cut_start AND c.first_cut_end";
                }else if($selectCutOFF== "2"){
                    $query_summary.= "AND DAY(d.created_at) BETWEEN c.second_cut_start AND c.second_cut_end";
                }
                $query_summary.= "
                        AND MONTH(d.created_at) = '$selected_Month'
                        AND YEAR(d.created_at) = '$selected_Year'
                        AND d.void = '0'
                GROUP BY
                    a.owner_name,
                    b.department_name,
                    c.sd_code
                ";
                $result_summary = mysqli_query($conn,$query_summary);

                if($result_summary->num_rows > 0){
                    while($row_summary = mysqli_fetch_assoc($result_summary)){
                    ?>
                     <tr>
                    <td><?php echo $row_summary["owner_name"];?></td>
                    <td><?php echo $row_summary["department_name"];?></td>
                    <td><?php echo $row_summary["sd_code"];?></td>
                    <td><?php echo $row_summary["total_deducted_amount"];?></td>
                    <td><?php echo $row_summary["cut_off"];?></td>
                    <td><?php echo $row_summary["cut_off_month_year"];?></td>
                     </tr>
                    <?php
                    }
                }
            }
            //break-down
            if($selectedTYPE== "s_type2"){
            }
    
    // }else{
    //     echo "No Result Found";
    // }
       }
}
?>