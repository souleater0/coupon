<?php
    include '../../db_connection.php';
    include '../time_zone.php';

if(!empty($_POST['action']) && $_POST['action'] == 'reportGenerate'){
    if(isset($_POST['filter_opt'])){
        $filter_opt = $_POST['filter_opt'];
        if($filter_opt = "1"){
            if(
               isset($_POST['start_date'])
            && isset($_POST['end_date'])
            && isset($_POST['time_In'])
            && isset($_POST['time_Out'])
            && isset($_POST['dep_ID'])
            && isset($_POST['selectCutoff'])
            && isset($_POST['selectType'])
            && isset($_POST['selectSD']) 
            && isset($_POST['clerk_ID'])){
                $start_date = $_POST['start_date'];
                $end_date = $_POST['end_date'];
                $time_IN = $_POST['time_In'];
                $time_OUT = $_POST['time_Out'];
                $dep_ID = $_POST['dep_ID'];
                $selectCutOFF = $_POST['selectCutoff'];
                $selectedTYPE  = $_POST['selectType'];
                $selectedSD  = $_POST['selectSD'];
                $clerk_ID = $_POST['clerk_ID'];
                //convert to military time
                $m_time_IN = date("H:i:59", strtotime($time_IN));
                $m_time_OUT = date("H:i:59", strtotime($time_OUT));

                $startDateTime = $start_date. ' '.$m_time_IN;
                $endDateTime = $end_date.' '.$m_time_OUT;

                $breakdown_sd_range = "SELECT
                a.owner_name AS full_name,
                b.department_name AS department,
                c.sd_code AS sd_code,
                CASE
                    WHEN d.amount_sd IS NOT NULL THEN d.amount_sd
                    ELSE 'NO SD'
                END AS amount_sd,
                d.receipt_no AS receipt_no,
                CASE 
                    WHEN DAY(d.created_at) BETWEEN c.first_cut_start AND c.first_cut_end THEN CONCAT('1st Cut-Off: ', c.first_cut_start, ' to ', c.first_cut_end)
                    WHEN DAY(d.created_at) BETWEEN c.second_cut_start AND c.second_cut_end THEN CONCAT('2nd Cut-Off: ', c.second_cut_start, ' to ', c.second_cut_end)
                END AS cut_off,
                DATE_FORMAT(d.created_at, '%Y-%m-%d %h:%i %p') AS date_created,
                e.display_name AS clerk_name
                FROM
                    owners a
                    INNER JOIN department b ON b.id = a.owner_department
                    INNER JOIN salary_deduction c ON c.owner_id = a.staff_id
                    INNER JOIN admins e
                    LEFT JOIN balance_deducted d ON d.sd_code = c.sd_code AND d.admin_id = e.id AND c.owner_id = a.staff_id
                WHERE
                    d.void = '0'
                    AND (DATE(d.created_at) BETWEEN '$start_date' AND '$end_date')
                    ";
                    if(!empty($_POST['time_In']) && !empty($_POST['time_Out']) ){
                        $breakdown_sd_range .= "AND (TIME(d.created_at) BETWEEN '$m_time_IN' AND '$m_time_OUT')";
                    }
                    if(!empty($_POST['department'])){
                        $breakdown_sd_range .= "AND b.id = '$dep_ID'";
                    }
                    if(!empty($_POST['clerk_ID'])){
                        $breakdown_sd_range .= "AND d.admin_id = '$clerk_ID'";
                    }
                    $result_breakdown_range = mysqli_query($conn,$breakdown_sd_range);
                    if($result_breakdown_range->num_rows > 0){
                        while($row_breakdown_range = mysqli_fetch_assoc($result_breakdown_range)){
                            ?>
                                                     <tr>
                        <td><?php echo $row_breakdown_range["full_name"];?></td>
                        <td><?php echo $row_breakdown_range["department"];?></td>
                        <td><?php echo $row_breakdown_range["sd_code"];?></td>
                        <td><?php echo $row_breakdown_range["amount_sd"];?></td>
                        <td><?php echo $row_breakdown_range["receipt_no"];?></td>
                        <td><?php echo $row_breakdown_range["cut_off"];?></td>
                        <td><?php echo $row_breakdown_range["date_created"];?></td>
                        <td><?php echo $row_breakdown_range["clerk_name"];?></td>
                         </tr>
                            <?php
                        }
                    }
            }
        }
        
        if($filter_opt = "2"){
            if(isset($_POST['MonthYear']) && !empty($_POST['MonthYear'])
            && isset($_POST['dep_ID'])
            && isset($_POST['selectType'])
            && isset($_POST['selectSD']) 
            && isset($_POST['selectCutoff'])
            && isset($_POST['clerk_ID'])
            ){  
                $MonthYear = $_POST['MonthYear'];
    
                $selected_Month = date('m', strtotime($MonthYear));
                $selected_Year = date('Y', strtotime($MonthYear));
                $dep_ID = $_POST['dep_ID'];
                $selectCutOFF = $_POST['selectCutoff'];
                $selectedTYPE  = $_POST['selectType'];
                $selectedSD  = $_POST['selectSD'];
                $clerk_ID = $_POST['clerk_ID'];
    
                //summary
                if($selectedTYPE=="s_type1"){
                    $query_summary ="SELECT
                    a.owner_name,
                    b.department_name,
                    c.sd_code,
                    CONCAT(CASE '$selected_Month'
                WHEN 1 THEN 'January'
                WHEN 2 THEN 'February'
                WHEN 3 THEN 'March'
                WHEN 4 THEN 'April'
                WHEN 5 THEN 'May'
                WHEN 6 THEN 'June'
                WHEN 7 THEN 'July'
                WHEN 8 THEN 'August'
                WHEN 9 THEN 'September'
                WHEN 10 THEN 'October'
                WHEN 11 THEN 'November'
                WHEN 12 THEN 'December'
                ELSE 'Invalid Month'
            END, ' ', '$selected_Year') AS cut_off_month_year,
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
                    if($selectedSD=="1"){
                        $query_summary.=" WHERE MONTH(d.created_at) = '$selected_Month' ";
                        if(!empty($dep_ID)){
                            $query_summary .= " AND b.id = '$dep_ID'";
                        }
                    }
                    $query_summary.= "
                            AND YEAR(d.created_at) = '$selected_Year'
                            AND d.void = '0'";
                    if($selectedSD=="2"){
                        $query_summary.=" AND MONTH(d.created_at) = '$selected_Month'";
                        if(!empty($dep_ID)){
                            $query_summary .= "WHERE b.id = '$dep_ID'";
                        }
                    }
                    $query_summary.="
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
                    }else{
                        echo "No Result Found";
                    }
                }
                //break-down
                if($selectedTYPE== "s_type2"){
                    $query_breakdown ="SELECT
                    a.owner_name AS full_name,
                    b.department_name AS department,
                    c.sd_code AS sd_code,
                    CASE
                    WHEN d.amount_sd IS NOT NULL THEN d.amount_sd
                    ELSE 'NO SD'
                    END AS amount_sd,
                    d.receipt_no AS receipt_no,
                    ";
                    if($selectCutOFF== "1"){
                        $query_breakdown .= "CONCAT('1st Cut-Off: ', c.first_cut_start, ' to ', c.first_cut_end) AS cut_off,";
                    }else if($selectCutOFF== "2"){
                        $query_breakdown .= "CONCAT('2nd Cut-Off: ', c.second_cut_start, ' to ', c.second_cut_end) AS cut_off,";
                    }
                    $query_breakdown .="
                    d.created_at AS DATE,
                    e.display_name AS clerk_id
                    
                    FROM owners a
                    INNER JOIN department b ON b.id = a.owner_department
                    INNER JOIN salary_deduction c ON c.owner_id = a.staff_id
                    INNER JOIN admins e
                    LEFT JOIN balance_deducted d ON d.sd_code = c.sd_code AND d.admin_id = e.id
                    AND c.owner_id = a.staff_id";
                    if($selectCutOFF== "1"){
                        $query_breakdown.= " AND DAY(d.created_at) BETWEEN c.first_cut_start AND c.first_cut_end";
                    }else if($selectCutOFF== "2"){
                        $query_breakdown.= " AND DAY(d.created_at) BETWEEN c.second_cut_start AND c.second_cut_end";
                    }
                    $query_breakdown.=" WHERE MONTH(d.created_at) = '$selected_Month' ";
                    if(!empty($dep_ID)){
                        $query_breakdown .= " AND b.id = '$dep_ID'";
                    }
                    if(!empty($clerk_ID)){
                        $query_breakdown .= " AND d.admin_id = '$clerk_ID'";
                    }
                    $query_breakdown.= "
                            AND YEAR(d.created_at) = '$selected_Year'
                            AND d.void = '0'";
                $result_breakdown = mysqli_query($conn, $query_breakdown);
                if($result_breakdown->num_rows>0){
                    while ($row_breakdown = mysqli_fetch_assoc($result_breakdown))
                    {
                    ?>
                         <tr>
                        <td><?php echo $row_breakdown["full_name"];?></td>
                        <td><?php echo $row_breakdown["department"];?></td>
                        <td><?php echo $row_breakdown["sd_code"];?></td>
                        <td><?php echo $row_breakdown["amount_sd"];?></td>
                        <td><?php echo $row_breakdown["receipt_no"];?></td>
                        <td><?php echo $row_breakdown["cut_off"];?></td>
                        <td><?php echo $row_breakdown["DATE"];?></td>
                        <td><?php echo $row_breakdown["clerk_id"];?></td>
                         </tr>
                    <?php
                    }
                }
                
                }
           }
        }
    }
}
if(!empty($_POST['action']) && $_POST['action'] == 'csvGenerate'){
    if(isset($_POST['MonthYear']) && !empty($_POST['MonthYear'])
    && isset($_POST['dep_ID'])
    && isset($_POST['selectType'])
    && isset($_POST['selectSD']) 
    && isset($_POST['selectCutoff'])
    && isset($_POST['clerk_ID'])
    ){  
        $MonthYear = $_POST['MonthYear'];
        $selected_Month = date('m', strtotime($MonthYear));
        $selected_Year = date('Y', strtotime($MonthYear));
        $dep_ID = $_POST['dep_ID'];
        $selectCutOFF = $_POST['selectCutoff'];
        $selectedTYPE  = $_POST['selectType'];
        $selectedSD  = $_POST['selectSD'];
        $clerk_ID = $_POST['clerk_ID'];

        $filename = "SalaryDeduction_".$current_date.".csv";
        $delimiter = ",";
        $f = fopen('php://memory','w');

        if($selectedTYPE=="s_type1"){
            $fields = array('Full Name','Department','SD Code', 'Total Deducted','CUT-OFF','DATE');
            fputcsv($f, $fields, $delimiter);
            $query_summary ="SELECT
            a.owner_name,
            b.department_name,
            c.sd_code,
            CONCAT(CASE '$selected_Month'
        WHEN 1 THEN 'January'
        WHEN 2 THEN 'February'
        WHEN 3 THEN 'March'
        WHEN 4 THEN 'April'
        WHEN 5 THEN 'May'
        WHEN 6 THEN 'June'
        WHEN 7 THEN 'July'
        WHEN 8 THEN 'August'
        WHEN 9 THEN 'September'
        WHEN 10 THEN 'October'
        WHEN 11 THEN 'November'
        WHEN 12 THEN 'December'
        ELSE 'Invalid Month'
    END, ' ', '$selected_Year') AS cut_off_month_year,
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
            if($selectedSD=="1"){
                $query_summary.=" WHERE MONTH(d.created_at) = '$selected_Month' ";
                if(!empty($dep_ID)){
                    $query_summary .= " AND b.id = '$dep_ID'";
                }
            }
            $query_summary.= "
                    AND YEAR(d.created_at) = '$selected_Year'
                    AND d.void = '0'";
            if($selectedSD=="2"){
                $query_summary.=" AND MONTH(d.created_at) = '$selected_Month'";
                if(!empty($dep_ID)){
                    $query_summary .= "WHERE b.id = '$dep_ID'";
                }
            }
            $query_summary.="
            GROUP BY
                a.owner_name,
                b.department_name,
                c.sd_code
            ";
            $result_summary = mysqli_query($conn,$query_summary);

            if($result_summary->num_rows > 0){
                while($row_summary = mysqli_fetch_assoc($result_summary)){
                    $lineData = array($row_summary["owner_name"],$row_summary["department_name"],$row_summary["sd_code"],$row_summary["total_deducted_amount"],$row_summary["cut_off"],$row_summary["cut_off_month_year"]);
                    fputcsv($f, $lineData, $delimiter);
                }
                fseek($f,0);
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="'.$filename.'";');
                fpassthru($f);
                exit();
            }else{
                echo "No Result Found";
            }
        }
        if($selectedTYPE== "s_type2"){
            $fields = array('Full Name','Department','SD Code', 'SD Amount','Receipt No.','Cut-Off','Date','Clerk');
            fputcsv($f, $fields, $delimiter);
            $query_breakdown ="SELECT
            a.owner_name AS full_name,
            b.department_name AS department,
            c.sd_code AS sd_code,
            CASE
            WHEN d.amount_sd IS NOT NULL THEN d.amount_sd
            ELSE 'NO SD'
            END AS amount_sd,
            d.receipt_no AS receipt_no,
            ";
            if($selectCutOFF== "1"){
                $query_breakdown .= "CONCAT('1st Cut-Off: ', c.first_cut_start, ' to ', c.first_cut_end) AS cut_off,";
            }else if($selectCutOFF== "2"){
                $query_breakdown .= "CONCAT('2nd Cut-Off: ', c.second_cut_start, ' to ', c.second_cut_end) AS cut_off,";
            }
            $query_breakdown .="
            d.created_at AS DATE,
            e.display_name AS clerk_id
            
            FROM owners a
            INNER JOIN department b ON b.id = a.owner_department
            INNER JOIN salary_deduction c ON c.owner_id = a.staff_id
            INNER JOIN admins e
            LEFT JOIN balance_deducted d ON d.sd_code = c.sd_code AND d.admin_id = e.id
            AND c.owner_id = a.staff_id";
            if($selectCutOFF== "1"){
                $query_breakdown.= " AND DAY(d.created_at) BETWEEN c.first_cut_start AND c.first_cut_end";
            }else if($selectCutOFF== "2"){
                $query_breakdown.= " AND DAY(d.created_at) BETWEEN c.second_cut_start AND c.second_cut_end";
            }
            $query_breakdown.=" WHERE MONTH(d.created_at) = '$selected_Month' ";
            if(!empty($dep_ID)){
                $query_breakdown .= " AND b.id = '$dep_ID'";
            }
            if(!empty($clerk_ID)){
                $query_breakdown .= " AND d.admin_id = '$clerk_ID'";
            }
            $query_breakdown.= "
                    AND YEAR(d.created_at) = '$selected_Year'
                    AND d.void = '0'";
        $result_breakdown = mysqli_query($conn, $query_breakdown);
        if($result_breakdown->num_rows>0){
            while ($row_breakdown = mysqli_fetch_assoc($result_breakdown))
            {
                $lineData = array($row_breakdown["full_name"],$row_breakdown["department"],$row_breakdown["sd_code"],$row_breakdown["amount_sd"],$row_breakdown["receipt_no"],$row_breakdown["cut_off"],$row_breakdown["DATE"],$row_breakdown["clerk_id"]);
                fputcsv($f, $lineData, $delimiter);
            }
            fseek($f,0);
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="'.$filename.'";');
            fpassthru($f);
            exit();
        }else{
            echo "No Result Found";
        }
        }



    }



}
?>