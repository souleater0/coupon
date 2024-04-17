<?php
include 'admin/time_zone.php';
include 'db_connection.php';
session_start();
if(!empty($_POST['action']) && $_POST['action'] == 'addBarcode') {
    if(isset($_POST['coupon']) && !empty($_POST['coupon']) && isset($_POST['id']) && !empty($_POST['id'])) {
        $coupon = $_POST['coupon'];
        $owner_id = $_POST['id'];
        // Check if coupon exists 
        $coupon_query = "SELECT
        a.id,
        a.staff_id,
        a.owner_name,
        a.owner_email,
        c.id AS dep_id,
        c.department_name,
        b.id AS coupon_id,
        b.coupon_code,
        b.coupon_value,
        a.base_time,
        b.created_at,
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
        INNER JOIN coupons b ON b.owner_id = a.id
        INNER JOIN department c ON c.id = a.owner_department
        AND a.owner_department = c.id
				WHERE b.coupon_code = '$coupon'
    ORDER BY
        a.id ASC";
        $coupon_result = mysqli_query($conn, $coupon_query);
        if(mysqli_num_rows($coupon_result) > 0) {
            // Check if owner ID matches
            $coupon_row = mysqli_fetch_assoc($coupon_result);
            if($coupon_row['staff_id'] == $owner_id) {
                //Owner Match
                if($coupon_row['base_time'] === "1"){ //if Department
                // Retrieve start and end times from the database
                $start_time = $coupon_row['from_time'];
                $end_time = $coupon_row['to_time'];

                $current_date_next_day = date("Y-m-d", strtotime($current_date . '+1 day'));
                $start_datetime = $current_date . ' ' . $start_time;
                $end_datetime = $current_date . ' ' . $end_time;
                $end_datetime_plus = $current_date_next_day . ' ' . $end_time;

                
                if ($end_time >= $start_time) {
                    // If the end time is on the same day
                    if ($current_time >= $start_time && $current_time <= $end_time) {
                    //Check if record is exist
                    // Record claim
                    $check_exist ="SELECT
                    * 
                FROM
                    claims AS a
                    INNER JOIN owners AS b ON a.owner_id = b.id 
                WHERE
                    NOW() BETWEEN a.claim_date 
                    AND a.claim_end_date 
                    AND b.staff_id = '$owner_id';
                    ";
                    $result_check_exist = mysqli_query($conn, $check_exist);
                    if($result_check_exist->num_rows>0){
                        $response = array(
                            'success' => false,
                            'message' => 'Food Stub already Claimed!',
                        );
                    }else{
                        $claim_query = "INSERT INTO claims (owner_id, coupon_id,admin_id,claim_end_date,remarks) VALUES (".$coupon_row['id'].",".$coupon_row['coupon_id'].",".$_SESSION['admin_session_id'].",'".$end_datetime."', 'claimed')";
                        mysqli_query($conn, $claim_query);
                        $response = array(
                            'success' => true,
                            // 'message' => 'Food Stub has been claimed.',
                            'message' => 'Food Stub has been claimed. <br>From '.$start_time.' to '.$end_time.'.<br> Current time is '.$current_time,
                        );
                    }
                    } else {
                        $response = array(
                            'success' => false,
                            // 'message' => 'Food Stub is not yet available at this time!',
                            'message' => 'Food Stub is not yet available at this time! From '.$start_time.' to '.$end_time.'.<br> Current time is '.$current_time,
                        );
                    }
                } else {
                    // If the end time is on the next day
                    if ($current_time >= $start_time || $current_time <= $end_time) {
                    // Record claim
                        $check_exist ="SELECT
                        * 
                    FROM
                        claims AS a
                        INNER JOIN owners AS b ON a.owner_id = b.id 
                    WHERE
                        NOW() BETWEEN a.claim_date 
                        AND a.claim_end_date 
                        AND b.staff_id = '$owner_id';
                        ";
                        $result_check_exist = mysqli_query($conn, $check_exist);
                        if($result_check_exist->num_rows>0){
                            $response = array(
                                'success' => false,
                                'message' => 'Food Stub already Claimed!',
                            );
                        }else{
                            $claim_query = "INSERT INTO claims (owner_id, coupon_id,admin_id,claim_end_date,remarks) VALUES (".$coupon_row['id'].",".$coupon_row['coupon_id'].",".$_SESSION['admin_session_id'].",'".$end_datetime_plus."', 'claimed')";
                            mysqli_query($conn, $claim_query);
                            $response = array(
                                'success' => true,
                                // 'message' => 'Food Stub has been claimed.',
                                'message' => 'Food Stub has been claimed. <br>From '.$start_time.' to '.$end_time.'.<br> Current time is '.$current_time,
                            );
                        }
                    } else {
                        $response = array(
                            'success' => false,
                            // 'message' => 'Food Stub is not yet available at this time!',
                            'message' => 'Food Stub is not yet available at this time! From '.$start_time.' to '.$end_time.'.<br> Current time is '.$current_time,
                        );
                    }
                }
                }else{ //if Individual
                    //get time from individual
                    // Retrieve start and end times from the database
                    $start_time = $coupon_row['from_time'];
                    $end_time = $coupon_row['to_time'];
    
                    $current_date_next_day = date("Y-m-d", strtotime($current_date . '+1 day'));
                    $start_datetime = $current_date . ' ' . $start_time;
                    $end_datetime = $current_date . ' ' . $end_time;
                    $end_datetime_plus = $current_date_next_day . ' ' . $end_time;

                    if ($end_time >= $start_time) {
                            // If the end time is on the same day
                        if ($current_time >= $start_time && $current_time <= $end_time) {
                                // Record claim
                                $check_exist ="SELECT
                                * 
                            FROM
                                claims AS a
                                INNER JOIN owners AS b ON a.owner_id = b.id 
                            WHERE
                                NOW() BETWEEN a.claim_date 
                                AND a.claim_end_date 
                                AND b.staff_id = '$owner_id';
                                ";
                                $result_check_exist = mysqli_query($conn, $check_exist);
                                if($result_check_exist->num_rows>0){
                                    $response = array(
                                        'success' => false,
                                        'message' => 'Food Stub already Claimed!',
                                    );
                                }else{
                                    $claim_query = "INSERT INTO claims (owner_id, coupon_id,admin_id,claim_end_date,remarks) VALUES (".$coupon_row['id'].",".$coupon_row['coupon_id'].",".$_SESSION['admin_session_id'].",'".$end_datetime."', 'claimed')";
                                    mysqli_query($conn, $claim_query);
                                    $response = array(
                                        'success' => true,
                                        // 'message' => 'Food Stub has been claimed.',
                                        'message' => 'Food Stub has been claimed. <br>From '.$start_time.' to '.$end_time.'.<br> Current time is '.$current_time,
                                    );
                                }
                        } else {
                            $response = array(
                                'success' => false,
                                // 'message' => 'Food Stub is not yet available at this time!',
                                // 'message' => 'Food Stub is not yet available at this time! From '.$start_time.' to '.$end_time.'.<br> Current time is '.$current_time,
                            );
                        }
                    } else {
                        // If the end time is on the next day
                        if ($current_time >= $start_time || $current_time <= $end_time) {

                            // Record claim
                            $check_exist ="SELECT
                                * 
                            FROM
                                claims AS a
                                INNER JOIN owners AS b ON a.owner_id = b.id 
                            WHERE
                                NOW() BETWEEN a.claim_date 
                                AND a.claim_end_date 
                                AND b.staff_id = '$owner_id';
                                ";
                                $result_check_exist = mysqli_query($conn, $check_exist);
                                if($result_check_exist->num_rows>0){
                                    $response = array(
                                        'success' => false,
                                        'message' => 'Food Stub already Claimed!',
                                    );
                                }else{
                                    $claim_query = "INSERT INTO claims (owner_id, coupon_id,admin_id,claim_end_date,remarks) VALUES (".$coupon_row['id'].",".$coupon_row['coupon_id'].",".$_SESSION['admin_session_id'].",'".$end_datetime_plus."', 'claimed')";
                                    mysqli_query($conn, $claim_query);
                                    $response = array(
                                        'success' => true,
                                        // 'message' => 'Food Stub has been claimed.',
                                        'message' => 'Food Stub has been claimed. <br>From '.$start_time.' to '.$end_time.'.<br> Current time is '.$current_time,
                                    );
                                } 
                        } else {
                            $response = array(
                                'success' => false,
                                // 'message' => 'Food Stub is not yet available at this time!',
                                // 'message' => 'Food Stub is not yet available at this time! From '.$start_time.' to '.$end_time.'.<br> Current time is '.$current_time,
                            );
                        }
                    }
                }

            } else {
                $response = array(
                    'success' => false,
                    'message' => 'Owner Does Not Match!',
                );
            }
        } else {
            $response = array(
                'success' => false,
                'message' => 'Coupon Does not Exist!',
            );
        }
    } else {
        $response = array(
            'success' => false,
            'message' => 'Please Enter All Data First!',
        );
    }
}
header('Content-Type: application/json');
echo json_encode($response);
?>
