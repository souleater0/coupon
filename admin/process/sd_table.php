<?php
    include '../../db_connection.php';
    include '../time_zone.php';

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if(isset($_POST['search']) && !empty($_POST['search'])){
        $searchCode = $_POST['search'];
        $query_salary_deduction = "SELECT
        a.owner_name,
        b.department_name,
        c.sd_code,
        MAX(c.sd_credits) - IFNULL(SUM(d.amount_sd), 0) AS remaining_balance 
    FROM
        owners a
        INNER JOIN department b ON b.id = a.owner_department
        INNER JOIN salary_deduction c ON c.owner_id = a.staff_id
        LEFT JOIN balance_deducted d ON d.sd_code = c.sd_code 
            AND d.owner_id = a.staff_id 
            AND (
                CASE 
                    WHEN DAY(CURRENT_DATE()) BETWEEN 1 AND 15 THEN
                        DAY(d.created_at) BETWEEN c.first_cut_start AND c.first_cut_end 
                    WHEN DAY(CURRENT_DATE()) BETWEEN 16 AND 31 THEN
                        DAY(d.created_at) BETWEEN c.second_cut_start AND c.second_cut_end 
                END
            )
            AND MONTH(d.created_at) = MONTH(CURRENT_DATE()) 
            AND YEAR(d.created_at) = YEAR(CURRENT_DATE()) 
            AND d.void = '0'
            WHERE a.owner_name LIKE '%{$searchCode}%'
    GROUP BY
        a.owner_name,
        b.department_name,
        c.sd_code";
            $result_salary_deduction = mysqli_query($conn, $query_salary_deduction);
    }else{
        $query_salary_deduction = "SELECT
        a.owner_name,
        b.department_name,
        c.sd_code,
        MAX(c.sd_credits) - IFNULL(SUM(d.amount_sd), 0) AS remaining_balance 
    FROM
        owners a
        INNER JOIN department b ON b.id = a.owner_department
        INNER JOIN salary_deduction c ON c.owner_id = a.staff_id
        LEFT JOIN balance_deducted d ON d.sd_code = c.sd_code 
            AND d.owner_id = a.staff_id 
            AND (
                CASE 
                    WHEN DAY(CURRENT_DATE()) BETWEEN 1 AND 15 THEN
                        DAY(d.created_at) BETWEEN c.first_cut_start AND c.first_cut_end 
                    WHEN DAY(CURRENT_DATE()) BETWEEN 16 AND 31 THEN
                        DAY(d.created_at) BETWEEN c.second_cut_start AND c.second_cut_end 
                END
            )
            AND MONTH(d.created_at) = MONTH(CURRENT_DATE()) 
            AND YEAR(d.created_at) = YEAR(CURRENT_DATE()) 
            AND d.void = '0'
    GROUP BY
        a.owner_name,
        b.department_name,
        c.sd_code";
            $result_salary_deduction = mysqli_query($conn, $query_salary_deduction);
        }
    if ($result_salary_deduction->num_rows > 0) {
        while ($row = $result_salary_deduction->fetch_assoc()) {
            ?>
            <tr>
            <td><?php echo $row["owner_name"];?></td>
            <td><?php echo $row["department_name"];?></td>
            <td><?php echo $row["sd_code"];?></td>
            <td><?php echo $row["remaining_balance"];?></td>
            <td><a class="btn btn-primary" href="sdowner_view.php?sd_code=<?php echo $row["sd_code"];?>">view</a></td>
            </tr>
    <?php
    }
    }else{
        echo "No Result Found";
    }
?>