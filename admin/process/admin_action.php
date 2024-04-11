<?php
session_start();
include '../../db_connection.php';
include '../time_zone.php';
// Add Owner
if(!empty($_POST['action']) && $_POST['action'] == 'addOwner' && !empty($_POST['departmentID']) && isset($_POST['departmentID']) && !empty($_POST['ownerId']) && isset($_POST['ownerId']) && !empty($_POST['ownerName']) && isset($_POST['ownerName']) && isset($_POST['ownerEmail']) && !empty($_POST['ownerCoupon']) && isset($_POST['ownerCoupon'])&& !empty($_POST['ownerCouponValue']) && isset($_POST['ownerCouponValue'])) {
    $departmentID = mysqli_real_escape_string($conn, $_POST['departmentID']);
    $ownerID = mysqli_real_escape_string($conn, $_POST['ownerId']);
    $ownerName = mysqli_real_escape_string($conn, $_POST['ownerName']);
    $ownerEmail = mysqli_real_escape_string($conn, $_POST['ownerEmail']);
    $ownerCoupon = mysqli_real_escape_string($conn, $_POST['ownerCoupon']);
    $ownerCouponValue = mysqli_real_escape_string($conn, $_POST['ownerCouponValue']);
    
    $queryOwners = "SELECT
	owners.id, 
	owners.staff_id, 
	owners.owner_name, 
	owners.owner_email, 
	department.department_name, 
	coupons.coupon_code, 
	coupons.coupon_value
    FROM
	owners
	INNER JOIN
	coupons
	ON 
		owners.id = coupons.owner_id
	INNER JOIN
	department
	ON 
		owners.owner_department = department.id
    WHERE owners.staff_id = '$ownerID' OR coupons.coupon_code='$ownerCoupon'";
    $result = mysqli_query($conn, $queryOwners);
    if(mysqli_num_rows($result) > 0){
        $response = array(
            'success' => false,
            'message' => 'Owner Already Exist',
        );
    }else{

        $sql_owner = "INSERT INTO owners (staff_id,owner_name,owner_email,owner_department) VALUES ('$ownerID','$ownerName','$ownerEmail','$departmentID')";
        $query_sql_owner = mysqli_query($conn, $sql_owner);
        //get owner_ID
        $searchOwner = "SELECT * FROM owners where staff_id = '$ownerID'";
        $query_searchOwner = mysqli_query($conn, $searchOwner);
        $rowOwner = mysqli_fetch_assoc($query_searchOwner);
        $ownerSID = $rowOwner['id'];
        // INSERT COUPOn
        $sql_coupon = "INSERT INTO coupons (coupon_code, coupon_value,owner_id) 
        VALUES ('$ownerCoupon','$ownerCouponValue','$ownerSID')";
        mysqli_query($conn, $sql_coupon);

        $response = array(
            'success' => true,
            'message' => 'Added Owner Successful',
        );
    }


    header('Content-Type: application/json');
    echo json_encode($response);
}
else if(!empty($_POST['action']) && $_POST['action'] == 'deleteOwner' && !empty($_POST['recordID']) && isset($_POST['recordID'])){

    $deleteOwnerID = $_POST['recordID'];
    $deleteRecord = "DELETE coupons, owners
    FROM coupons
    JOIN owners ON coupons.owner_id = owners.id
    WHERE owners.id = '$deleteOwnerID'";
    mysqli_query($conn, $deleteRecord);
    $response = array(
        'success' => true,
        'message' => "Record has been deleted Successfully",
    );
    header('Content-Type: application/json');
    echo json_encode($response);
}
else if(!empty($_POST['action']) && $_POST['action'] == 'fetchOwner'){
    if(!empty($_POST['recordID']) && isset($_POST['recordID'])){
        $fetchOwnerID = $_POST['recordID'];
        $fetchOwnerData = "SELECT
            owners.id,
            owners.staff_id,
            owners.owner_name,
            owners.owner_email,
            department.id AS dep_id,
            department.department_name,
            coupons.coupon_code,
            coupons.coupon_value 
        FROM
            owners
            INNER JOIN coupons ON owners.id = coupons.owner_id
            INNER JOIN department ON owners.owner_department = department.id 
        WHERE
	        owners.id = '$fetchOwnerID'
        ";
        $query_fetchOwnerData = mysqli_query($conn, $fetchOwnerData);
        $row_fetchOwnerData = mysqli_fetch_assoc($query_fetchOwnerData);
        $response = array(
            'success' => true,
            'message' => "Record Retrieved",
            'data' => $row_fetchOwnerData,
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}
else if(!empty($_POST['action']) && $_POST['action'] == 'updateOwner')
{
    if(
        !empty($_POST['updateId']) && isset($_POST['updateId']) 
        // && !empty($_POST['departmentID']) && isset($_POST['departmentID']) && !empty($_POST['ownerId']) && isset($_POST['ownerId']) && !empty($_POST['ownerName']) && isset($_POST['ownerName']) && isset($_POST['ownerEmail']) && !empty($_POST['ownerCoupon']) && isset($_POST['ownerCoupon'])&& !empty($_POST['ownerCouponValue']) && isset($_POST['ownerCouponValue'])
        ){
            $updateId = mysqli_real_escape_string($conn, $_POST['updateId']);
            $departmentID = mysqli_real_escape_string($conn, $_POST['departmentID']);
            $ownerID = mysqli_real_escape_string($conn, $_POST['ownerId']);
            $ownerName = mysqli_real_escape_string($conn, $_POST['ownerName']);
            $ownerEmail = mysqli_real_escape_string($conn, $_POST['ownerEmail']);
            $ownerCoupon = mysqli_real_escape_string($conn, $_POST['ownerCoupon']);
            $ownerCouponValue = mysqli_real_escape_string($conn, $_POST['ownerCouponValue']);

            // update owner details
            $sql_updateOwner_Details ="UPDATE owners
            SET
            staff_id = '$ownerID',
            owner_name = '$ownerName',
            owner_email = '$ownerEmail',
            owner_department = '$departmentID'
            WHERE id = $updateId";
            mysqli_query($conn, $sql_updateOwner_Details);
            
            // update coupon code
            $sql_updateOwner_Coupon = "UPDATE coupons
            SET
            coupon_code = '$ownerCoupon',
            coupon_value = '$ownerCouponValue'
            WHERE owner_id = $updateId";
            mysqli_query($conn,$sql_updateOwner_Coupon);
            
        $response = array(
            'success' => true,
            'message' => "Record has been Updated!",
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}elseif(!empty($_POST['action']) && $_POST['action'] == 'addClerk'){

    if(
        !empty($_POST['ownerEmail']) && isset($_POST['ownerEmail'])&&
        !empty($_POST['in_Location']) && isset($_POST['in_Location'])
        ){
            if(!empty($_POST['in_Password']) && isset($_POST['in_Password']) && !empty($_POST['in_ConPassword']) && isset($_POST['in_ConPassword'])){
                $clerkEmail = $_POST['ownerEmail'];
                $clerkLocation = $_POST['in_Location'];
                $password = $_POST['in_Password'];
                $conpassword = $_POST['in_ConPassword'];
                if($password == $conpassword){
                    $clerk_check= "SELECT * FROM admins WHERE email = '$clerkEmail'";
                    $result = mysqli_query($conn, $clerk_check);
                    if(mysqli_num_rows($result) > 0){
                        $response = array(
                            'success' => false,
                            'message' => 'Account Already Existed!',
                        );
                    }else{
                        $sql_coupon = "INSERT INTO admins (email, password, role_id, location) 
                        VALUES ('$clerkEmail','$conpassword','1','$clerkLocation')";
                        mysqli_query($conn, $sql_coupon);
                        $response = array(
                            'success' => true,
                            'message' => 'Clerk has been added!',
                        );
                    }
                }else{
                    $response = array(
                        'success' => false,
                        'message' => 'Password does not match!',
                    );
                }
            }
    }else{
        $response = array(
            'success' => false,
            'message' => 'Please Fill up Email or Location!',
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}else if(!empty($_POST['action']) && $_POST['action'] == 'deleteClerk'){
    $deleteClerkID = $_POST['recordID'];
    $deleteRecord = "DELETE
    FROM admins
    WHERE id = '$deleteClerkID'";
    mysqli_query($conn, $deleteRecord);
    $response = array(
        'success' => true,
        'message' => "Record has been deleted Successfully",
    );
    header('Content-Type: application/json');
    echo json_encode($response);
}
else{
    $response = array(
        'success' => false,
        'message' => 'Fill up all data first!',
    );
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>