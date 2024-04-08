<?php
include 'admin/time_zone.php';
include 'db_connection.php';
session_start();
if(!empty($_POST['action']) && $_POST['action'] == 'addBarcode') {
    if(isset($_POST['coupon']) && !empty($_POST['coupon']) && isset($_POST['id']) && !empty($_POST['id'])) {
        $coupon = $_POST['coupon'];
        $owner_id = $_POST['id'];
        $dateNow = $current_date;
        // Check if coupon exists 
        $coupon_query = "SELECT
        a.id,
        b.id AS owner_id,
        a.coupon_code, 
        a.coupon_value, 
        b.staff_id, 
        b.owner_name, 
        b.owner_email, 
        b.owner_department
    FROM
        coupons AS a
        INNER JOIN
        owners AS b ON a.owner_id = b.id
        WHERE coupon_code = '$coupon'";
        $coupon_result = mysqli_query($conn, $coupon_query);
        if(mysqli_num_rows($coupon_result) > 0) {
            // Check if owner ID matches
            $coupon_row = mysqli_fetch_assoc($coupon_result);
            if($coupon_row['staff_id'] == $owner_id) {
                // Get owner details
            $owner_query = "SELECT
                *
            FROM
                claims AS a
                INNER JOIN
                owners AS b
                ON 
                    a.owner_id = b.id
            WHERE
                Date(a.claim_date) = '$dateNow' AND b.staff_id = '$owner_id'";
                $checkOwner = mysqli_query($conn, $owner_query);
                if(mysqli_num_rows($checkOwner) > 0){
                     $response = array(
                        'success' => false,
                        'message' => 'Data already Exist.',
                    );
                }
                else{
                    
                    // Record claim
                    $claim_query = "INSERT INTO claims (owner_id, coupon_id,admin_id,remarks) VALUES (".$coupon_row['owner_id'].",".$coupon_row['id'].",".$_SESSION['admin_session_id'].", 'claimed')";
                    mysqli_query($conn, $claim_query);
                    $response = array(
                        'success' => true,
                        'message' => 'Data has been Recorded.',
                    );
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
