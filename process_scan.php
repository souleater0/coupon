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
                    //get time from department
                    $ownerDateTime=$current_date.' '.$coupon_row['from_time'];
                    if($current_datetime>=$ownerDateTime){
                        
                        $response = array(
                            'success' => true,
                            'message' => 'It is Valid '.$ownerDateTime ,
                        );
                    }else{
                        $response = array(
                            'success' => true,
                            'message' => 'It is not Valid '.$ownerDateTime ,
                        );
                    }

                }else{ //if Individual
                    //get time from individual
                    $ownerStartDateTime=$current_date.' '.$coupon_row['from_time'];
                    $ownerEndDateTime=$current_date.' '.$coupon_row['to_time'];
                    if($current_datetime >= $ownerStartDateTime && $current_datetime<= $ownerEndDateTime){
                        
                        $response = array(
                            'success' => true,
                            'message' => 'It is Valid '.$ownerStartDateTime,
                        );
                    }else{
                        $response = array(
                            'success' => true,
                            'message' => 'It is not Valid '.$ownerStartDateTime,
                        );
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
