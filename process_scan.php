<?php
include 'admin/time_zone.php';
include 'db_connection.php';
session_start();
if (!empty($_POST['action']) && $_POST['action'] == 'addBarcode') {
    if (!empty($_SESSION['admin_session_id']) && $_SESSION['admin_session_id'] != 0) {
        $coupon = strtoupper($_POST['coupon']);
        $owner_id = strtoupper($_POST['id']);

        // Check if coupon exists 
        $coupon_query = "SELECT
            a.staff_id,
            c.id AS dep_id,
            c.department_name,
            b.id AS coupon_id,
            b.coupon_code,
            b.coupon_value,
            a.base_time,
            CASE
                WHEN a.base_time = 1 THEN c.from_time
                WHEN a.base_time = 2 THEN a.from_time
            END AS from_time,
            CASE
                WHEN a.base_time = 1 THEN c.to_time
                WHEN a.base_time = 2 THEN a.to_time
            END AS to_time
        FROM
            owners a
            INNER JOIN coupons b ON b.owner_id = a.staff_id
            INNER JOIN department c ON c.id = a.owner_department
        WHERE b.coupon_code = '$coupon'
        ORDER BY a.id ASC";

        $coupon_result = mysqli_query($conn, $coupon_query);

        if (mysqli_num_rows($coupon_result) > 0) {
            $coupon_row = mysqli_fetch_assoc($coupon_result);

            if (strtoupper($coupon_row['staff_id']) == $owner_id) {
                // Time handling
                $start_time = $coupon_row['from_time'];
                $end_time = $coupon_row['to_time'];
                $current_time = date('H:i');
                $current_date = date('Y-m-d');
                $next_day_date = date("Y-m-d", strtotime($current_date . '+1 day'));

                $claim_start_time = $current_time >= $start_time || $current_time <= $end_time;

                // Check for existing claims
                $check_exist = "SELECT * FROM claims WHERE NOW() BETWEEN claim_date AND claim_end_date AND owner_id = '$owner_id'";
                $result_check_exist = mysqli_query($conn, $check_exist);

                if ($result_check_exist->num_rows > 0) {
                    $response = ['success' => false, 'message' => 'Food Stub already Claimed!'];
                } else {
                    if ( ($start_time < $end_time && $current_time >= $start_time && $current_time <= $end_time) || 
                        ($start_time > $end_time && $claim_start_time) ) {
                        
                        // Record the claim
                        $end_datetime = ($current_time > $end_time) ? $next_day_date : $current_date;
                        $end_datetime .= ' ' . $end_time;

                        $claim_query = "INSERT INTO claims (owner_id, coupon_id, admin_id, claim_end_date, remarks) 
                                        VALUES ('$owner_id', '$coupon', '" . $_SESSION['admin_session_id'] . "', '$end_datetime', 'claimed')";
                        mysqli_query($conn, $claim_query);
                        $response = ['success' => true, 'message' => 'Food Stub has been claimed. From ' . $start_time . ' to ' . $end_time . '.'];
                    } else {
                        $response = ['success' => false, 'message' => 'Food Stub is not available at this time! From ' . $start_time . ' to ' . $end_time . '.'];
                    }
                }
            } else {
                $response = ['success' => false, 'message' => 'Owner Does Not Match!'];
            }
        } else {
            $response = ['success' => false, 'message' => 'Coupon Does Not Exist!'];
        }
    } else {
        $response = ['success' => false, 'message' => 'Unauthorized access.'];
    }

    echo json_encode($response);
}
// if(!empty($_POST['action']) && $_POST['action'] == 'addBarcode') {

//     if(!empty($_SESSION['admin_session_id']) && $_SESSION['admin_session_id'] != 0){
//         if(isset($_POST['coupon']) && !empty($_POST['coupon']) && isset($_POST['id']) && !empty($_POST['id'])) {
//             $coupon = strtoupper($_POST['coupon']);
//             $owner_id = strtoupper($_POST['id']);
//             // Check if coupon exists 
//             $coupon_query = "SELECT
//             a.staff_id,
//             a.owner_name,
//             a.owner_email,
//             c.id AS dep_id,
//             c.department_name,
//             b.id AS coupon_id,
//             b.coupon_code,
//             b.coupon_value,
//             a.base_time,
//             b.created_at,
//         CASE
//             WHEN a.base_time = 1 THEN c.from_time
//             WHEN a.base_time = 2 THEN a.from_time
//         END AS from_time,
//         CASE
//             WHEN a.base_time = 1 THEN c.to_time
//             WHEN a.base_time = 2 THEN a.to_time
//         END AS to_time
        
//         FROM
//             owners a
//             INNER JOIN coupons b ON b.owner_id = a.staff_id
//             INNER JOIN department c ON c.id = a.owner_department
//             AND a.owner_department = c.id
//                     WHERE b.coupon_code = '$coupon'
//         ORDER BY
//             a.id ASC";
//             $coupon_result = mysqli_query($conn, $coupon_query);
//             if(mysqli_num_rows($coupon_result) > 0) {
//                 // Check if owner ID matches
//                 $coupon_row = mysqli_fetch_assoc($coupon_result);
//                 if(strtoupper($coupon_row['staff_id']) == $owner_id) {
//                     //Owner Match
    
//                     // Retrieve start and end times from the database
//                     $start_time = $coupon_row['from_time'];
//                     $end_time = $coupon_row['to_time'];
    
//                     $current_date_next_day = date("Y-m-d", strtotime($current_date . '+1 day'));
//                     $end_datetime = $current_date . ' ' . $end_time;
//                     $end_datetime_plus = $current_date_next_day . ' ' . $end_time;
                    
//                     //if Department
//                     if($coupon_row['base_time'] === "1"){ 
    
//                         if ($end_time > $start_time) {
//                             // Start time and end time are within the same day
//                             if ($start_time <= $current_time && $current_time <= $end_time) {
//                                 //record claim
//                                 $check_exist ="SELECT
//                                 * 
//                                 FROM
//                                     claims AS a
//                                     INNER JOIN owners AS b ON b.staff_id = a.owner_id
//                                 WHERE
//                                     NOW() BETWEEN a.claim_date 
//                                     AND a.claim_end_date 
//                                     AND b.staff_id = '$owner_id';
//                                     ";
//                                 $result_check_exist = mysqli_query($conn, $check_exist);
//                                 if($result_check_exist->num_rows>0){
//                                     $response = array(
//                                         'success' => false,
//                                         'message' => 'Food Stub already Claimed!',
//                                     );
//                                 }else{
//                                     $claim_query = "INSERT INTO claims (owner_id, coupon_id,admin_id,claim_end_date,remarks) VALUES ('$owner_id','$coupon','".$_SESSION['admin_session_id']."','".$end_datetime."', 'claimed')";
//                                     mysqli_query($conn, $claim_query);
//                                     $response = array(
//                                         'success' => true,
//                                         // 'message' => 'Food Stub has been claimed.',
//                                         'message' => 'Food Stub has been claimed. today<br>From '.$start_time.' to '.$end_time.'.<br> Current time is '.$current_time,
//                                     );
//                                 }
//                                 //claim end
//                             }else {
//                                 $response = array(
//                                     'success' => false,
//                                     // 'message' => 'Food Stub is not yet available at this time!',
//                                     'message' => 'Food Stub is not yet available at this time! From '.$start_time.' to '.$end_time.'.<br> Current time is '.$current_time,
//                                 );
//                             }
                        
//                         //if start time and end time has another day
//                         } else {
//                             //check if claim is within start time and end time
//                             if ($current_time >= $start_time || $current_time <= $end_time) {
//                                 //check if claim before midnight or after midnight
//                                 if ($current_time >= $midnight_time) {
//                                     //before midnight
//                                     $check_exist ="SELECT
//                                     * 
//                                     FROM
//                                         claims AS a
//                                         INNER JOIN owners AS b ON a.owner_id = b.staff_id 
//                                     WHERE
//                                         NOW() BETWEEN a.claim_date 
//                                         AND a.claim_end_date 
//                                         AND b.staff_id = '$owner_id';
//                                         ";
//                                         $result_check_exist = mysqli_query($conn, $check_exist);
//                                         if($result_check_exist->num_rows>0){
//                                             $response = array(
//                                                 'success' => false,
//                                                 'message' => 'Food Stub already Claimed!',
//                                             );
//                                         }else{
//                                             $claim_query = "INSERT INTO claims (owner_id, coupon_id,admin_id,claim_end_date,remarks) VALUES ('$owner_id','$coupon','".$_SESSION['admin_session_id']."','".$end_datetime_plus."', 'claimed')";
//                                             mysqli_query($conn, $claim_query);
//                                             $response = array(
//                                                 'success' => true,
//                                                 // 'message' => 'Food Stub has been claimed.',
//                                                 'message' => 'Food Stub has been claimed. <br>From '.$start_time.' to '.$end_time.'.<br> Current time is '.$current_time,
//                                             );
//                                         }
//                                 }else{
//                                     //after midnight
//                                     $check_exist ="SELECT
//                                     * 
//                                     FROM
//                                         claims AS a
//                                         INNER JOIN owners AS b ON a.owner_id = b.staff_id 
//                                     WHERE
//                                         NOW() BETWEEN a.claim_date 
//                                         AND a.claim_end_date 
//                                         AND b.staff_id = '$owner_id';
//                                         ";
//                                         $result_check_exist = mysqli_query($conn, $check_exist);
//                                         if($result_check_exist->num_rows>0){
//                                             $response = array(
//                                                 'success' => false,
//                                                 'message' => 'Food Stub already Claimed!',
//                                             );
//                                         }else{
//                                             $claim_query = "INSERT INTO claims (owner_id, coupon_id,admin_id,claim_end_date,remarks) VALUES ('$owner_id','$coupon','".$_SESSION['admin_session_id']."','".$end_datetime."', 'claimed')";
//                                             mysqli_query($conn, $claim_query);
//                                             $response = array(
//                                                 'success' => true,
//                                                 // 'message' => 'Food Stub has been claimed.',
//                                                 'message' => 'Food Stub has been claimed. <br>From '.$start_time.' to '.$end_time.'.<br> Current time is '.$current_time,
//                                             );
//                                         }
//                                 }
//                             }else{
//                                 //not available
//                                 $response = array(
//                                     'success' => false,
//                                     // 'message' => 'Food Stub is not yet available at this time!',
//                                     'message' => 'Food Stub is not yet available at this time! From '.$start_time.' to '.$end_time.'.<br> Current time is '.$current_time,
//                                 );
//                             }
//                         }             
//                     //if Individual
//                     }else{ 
//                         if ($end_time > $start_time) {
//                             // Start time and end time are within the same day
//                             if ($start_time <= $current_time && $current_time <= $end_time) {
//                                 //record claim
//                                 $check_exist ="SELECT
//                                 * 
//                                 FROM
//                                     claims AS a
//                                     INNER JOIN owners AS b ON b.staff_id = a.owner_id
//                                 WHERE
//                                     NOW() BETWEEN a.claim_date 
//                                     AND a.claim_end_date 
//                                     AND b.staff_id = '$owner_id';
//                                     ";
//                                 $result_check_exist = mysqli_query($conn, $check_exist);
//                                 if($result_check_exist->num_rows>0){
//                                     $response = array(
//                                         'success' => false,
//                                         'message' => 'Food Stub already Claimed!',
//                                     );
//                                 }else{
//                                     $claim_query = "INSERT INTO claims (owner_id, coupon_id,admin_id,claim_end_date,remarks) VALUES ('$owner_id','$coupon','".$_SESSION['admin_session_id']."','".$end_datetime."', 'claimed')";
//                                     mysqli_query($conn, $claim_query);
//                                     $response = array(
//                                         'success' => true,
//                                         // 'message' => 'Food Stub has been claimed.',
//                                         'message' => 'Food Stub has been claimed. today<br>From '.$start_time.' to '.$end_time.'.<br> Current time is '.$current_time,
//                                     );
//                                 }
//                                 //claim end
//                             }else {
//                                 $response = array(
//                                     'success' => false,
//                                     // 'message' => 'Food Stub is not yet available at this time!',
//                                     'message' => 'Food Stub is not yet available at this time! From '.$start_time.' to '.$end_time.'.<br> Current time is '.$current_time,
//                                 );
//                             }
                        
//                         //if start time and end time has another day
//                         } else {
//                             //check if claim is within start time and end time
//                             if ($current_time >= $start_time || $current_time <= $end_time) {
//                                 //check if claim before midnight or after midnight
//                                 if ($current_time >= $midnight_time) {
//                                     //before midnight
//                                     $check_exist ="SELECT
//                                     * 
//                                     FROM
//                                         claims AS a
//                                         INNER JOIN owners AS b ON a.owner_id = b.staff_id 
//                                     WHERE
//                                         NOW() BETWEEN a.claim_date 
//                                         AND a.claim_end_date 
//                                         AND b.staff_id = '$owner_id';
//                                         ";
//                                         $result_check_exist = mysqli_query($conn, $check_exist);
//                                         if($result_check_exist->num_rows>0){
//                                             $response = array(
//                                                 'success' => false,
//                                                 'message' => 'Food Stub already Claimed!',
//                                             );
//                                         }else{
//                                             $claim_query = "INSERT INTO claims (owner_id, coupon_id,admin_id,claim_end_date,remarks) VALUES ('$owner_id','$coupon','".$_SESSION['admin_session_id']."','".$end_datetime_plus."', 'claimed')";
//                                             mysqli_query($conn, $claim_query);
//                                             $response = array(
//                                                 'success' => true,
//                                                 // 'message' => 'Food Stub has been claimed.',
//                                                 'message' => 'Food Stub has been claimed. <br>From '.$start_time.' to '.$end_time.'.<br> Current time is '.$current_time,
//                                             );
//                                         }
//                                 }else{
//                                     //after midnight
//                                     $check_exist ="SELECT
//                                     * 
//                                     FROM
//                                         claims AS a
//                                         INNER JOIN owners AS b ON a.owner_id = b.staff_id 
//                                     WHERE
//                                         NOW() BETWEEN a.claim_date 
//                                         AND a.claim_end_date 
//                                         AND b.staff_id = '$owner_id';
//                                         ";
//                                         $result_check_exist = mysqli_query($conn, $check_exist);
//                                         if($result_check_exist->num_rows>0){
//                                             $response = array(
//                                                 'success' => false,
//                                                 'message' => 'Food Stub already Claimed!',
//                                             );
//                                         }else{
//                                             $claim_query = "INSERT INTO claims (owner_id, coupon_id,admin_id,claim_end_date,remarks) VALUES ('$owner_id','$coupon','".$_SESSION['admin_session_id']."','".$end_datetime."', 'claimed')";
//                                             mysqli_query($conn, $claim_query);
//                                             $response = array(
//                                                 'success' => true,
//                                                 // 'message' => 'Food Stub has been claimed.',
//                                                 'message' => 'Food Stub has been claimed. <br>From '.$start_time.' to '.$end_time.'.<br> Current time is '.$current_time,
//                                             );
//                                         }
//                                 }
//                             }else{
//                                 //not available
//                                 $response = array(
//                                     'success' => false,
//                                     // 'message' => 'Food Stub is not yet available at this time!',
//                                     'message' => 'Food Stub is not yet available at this time! From '.$start_time.' to '.$end_time.'.<br> Current time is '.$current_time,
//                                 );
//                             }
//                         } 
//                     }
//                 } else {
//                     $response = array(
//                         'success' => false,
//                         'message' => 'Owner Does Not Match!',
//                     );
//                 }
//             } else {
//                 $response = array(
//                     'success' => false,
//                     'message' => 'Coupon Does not Exist!',
//                 );
//             }
//         } else {
//             $response = array(
//                 'success' => false,
//                 'message' => 'Please Enter All Data First!',
//             );
//         }
//     }else{
//         $response = array(
//             'success' => false,
//             'message' => 'Please Logout and Login Again!',
//         );
//     }

//     header('Content-Type: application/json');
//     echo json_encode($response);
// }
if(!empty($_POST['action']) && $_POST['action'] == 'addDeduction') {
    if(isset($_POST['sd_coupon']) && empty($_POST['sd_coupon'])){
        $response = array(
            'success' => false,
            'message' => 'Please Enter SD Code!',
        );
    }else if (isset($_POST['s_id']) && empty($_POST['s_id'])){
        $response = array(
            'success' => false,
            'message' => 'Please Enter Owner ID!',
        );
    }else if (isset($_POST['amount_sd']) && empty($_POST['amount_sd'])){
        $response = array(
            'success' => false,
            'message' => 'Please Enter Amount!',
        );
    }else if (isset($_POST['receipt_no']) && empty($_POST['receipt_no'])){
        $response = array(
            'success' => false,
            'message' => 'Please Enter Receipt No. !',
        );
    }else{
        if(!empty($_SESSION['admin_session_id']) && $_SESSION['admin_session_id'] != 0){
            $sd_Coupon = strtoupper($_POST['sd_coupon']);
            $ownerID = strtoupper($_POST['s_id']);
            $amount = $_POST['amount_sd'];
            $receiptNo = $_POST['receipt_no'];
    
            if(strlen($receiptNo) >= 5){
                        //check receipt if exist
            $check_receipt = "SELECT * FROM balance_deducted
            WHERE receipt_no = '$receiptNo'";
            $result_check_receipt = mysqli_query($conn, $check_receipt);
    
            if (mysqli_num_rows($result_check_receipt) > 0) {
                $response = array(
                    'success' => false,
                    'message' => 'Receipt No. Already Exist!',
                );
            }else{
                $check_Owner = "SELECT a.staff_id
                FROM owners a 
                INNER JOIN salary_deduction b ON b.owner_id = a.staff_id
                WHERE b.sd_code = '$sd_Coupon'";
        
                $result_check_Owner = mysqli_query($conn, $check_Owner);
                if($result_check_Owner->num_rows > 0){
                    $sd_row = mysqli_fetch_assoc($result_check_Owner);
                    //check owner if id matches
                    if(strtoupper($sd_row["staff_id"]) == $ownerID){
                        //continue then validate amount
                        $query_sd_owners = "SELECT
                        a.staff_id,
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
                                    WHEN DAY(CURRENT_DATE()) BETWEEN c.first_cut_start AND c.first_cut_end THEN
                                        DAY(d.created_at) BETWEEN c.first_cut_start AND c.first_cut_end 
                                    WHEN DAY(CURRENT_DATE()) BETWEEN c.second_cut_start AND c.second_cut_end THEN
                                        DAY(d.created_at) BETWEEN c.second_cut_start AND c.second_cut_end 
                                END
                            )
                            AND MONTH(d.created_at) = MONTH(CURRENT_DATE()) 
                            AND YEAR(d.created_at) = YEAR(CURRENT_DATE()) 
                            AND d.void = '0'
                        WHERE c.sd_code = '$sd_Coupon'
                    GROUP BY
                        a.owner_name,
                        b.department_name,
                        c.sd_code
                        ";
                    $result_owners = mysqli_query($conn, $query_sd_owners);
                        if($result_owners->num_rows > 0){
                            $owner_ROW = mysqli_fetch_assoc($result_owners);
                            //check amount
                            $remaining_BALANCE = $owner_ROW["remaining_balance"];
                            if($amount<=$remaining_BALANCE){
                                $insert_Deduction = "INSERT INTO balance_deducted (amount_sd,receipt_no,sd_code,owner_id,admin_id)
                                VALUES ('$amount','$receiptNo','$sd_Coupon','$ownerID','".$_SESSION['admin_session_id']."')";
                                mysqli_query($conn, $insert_Deduction);
                                $response = array(
                                    'success' => true,
                                    'message' => 'Balance has been deducted!',
                                ); 
                            }else{
                                $response = array(
                                    'success' => false,
                                    'message' => 'Not enough balance to deduct',
                                );
                            }
                        }
                    }else{
                        $response = array(
                            'success' => false,
                            'message' => 'Owner does not match!',
                        );
                    }
                }else{
                    $response = array(
                        'success' => false,
                        'message' => 'SD code does not exist!',
                    );
                }
            }
            }else{
                $response = array(
                    'success' => false,
                    'message' => 'Receipt No. must be 5 characters and above!',
                );
            }
        }
        else{
            $response = array(
                'success' => false,
                'message' => 'Please Logout and Login Again!',
            );
        }

    }
header('Content-Type: application/json');
echo json_encode($response);
}
?>
