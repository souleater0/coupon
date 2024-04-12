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

                // Check if the end time is on the next day
                if ($end_time >= $start_time) {
                    // If the end time is on the same day
                    if ($current_time >= $start_time && $current_time <= $end_time) {

                        $response = array(
                            'success' => true,
                            'message' => 'Food Stub has been claimed.',
                            // 'message' => 'Food Stub can be claimed. <br>From '.$start_time.' to '.$end_time.'.<br> Current time is '.$current_time,
                        );
                    } else {
                        $response = array(
                            'success' => false,
                            'message' => 'Food Stub is not yet available at this time!',
                            'message' => 'Food Stub is not yet available at this time! From '.$start_time.' to '.$end_time.'.<br> Current time is '.$current_time,
                        );
                    }
                } else {
                    // If the end time is on the next day
                    if ($current_time >= $start_time || $current_time <= $end_time) {
                        $response = array(
                            'success' => true,
                            'message' => 'Food Stub has been claimed.',
                            // 'message' => 'Food Stub can be claimed. <br>From '.$start_time.' to '.$end_time.'.<br> Current time is '.$current_time,
                        );
                    } else {
                        $response = array(
                            'success' => false,
                            'message' => 'Food Stub is not yet available at this time!',
                            // 'message' => 'Food Stub is not yet available at this time! From '.$start_time.' to '.$end_time.'.<br> Current time is '.$current_time,
                        );
                    }
                }
                }else{ //if Individual
                    //get time from individual
                    // Retrieve start and end times from the database
                    $start_time = $coupon_row['from_time'];
                    $end_time = $coupon_row['to_time'];

                    // Check if the end time is on the next day
                    if ($end_time >= $start_time) {
                        // If the end time is on the same day
                        if ($current_time >= $start_time && $current_time <= $end_time) {
                            $response = array(
                                'success' => true,
                                'message' => 'Food Stub has been claimed.',
                                // 'message' => 'Food Stub has been claimed. <br>From '.$start_time.' to '.$end_time.'.<br> Current time is '.$current_time,
                            );
                        } else {
                            $response = array(
                                'success' => false,
                                'message' => 'Food Stub is not yet available at this time!',
                                // 'message' => 'Food Stub is not yet available at this time! From '.$start_time.' to '.$end_time.'.<br> Current time is '.$current_time,
                            );
                        }
                    } else {
                        // If the end time is on the next day
                        if ($current_time >= $start_time || $current_time <= $end_time) {
                            $response = array(
                                'success' => true,
                                'message' => 'Food Stub has been claimed.',
                                // 'message' => 'Food Stub has been claimed. <br>From '.$start_time.' to '.$end_time.'.<br> Current time is '.$current_time,
                            );
                        } else {
                            $response = array(
                                'success' => false,
                                'message' => 'Food Stub is not yet available at this time!',
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
