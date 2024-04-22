<?php
session_start();
include '../../db_connection.php';
include '../time_zone.php';
// Add Owner
if(!empty($_POST['action']) && $_POST['action'] == 'addOwner') {
    if(
    !empty($_POST['departmentID']) && isset($_POST['departmentID']) 
    // && !empty($_POST['ownerId']) && isset($_POST['ownerId']) 
    // && !empty($_POST['ownerName']) && isset($_POST['ownerName']) 
    // && isset($_POST['ownerEmail']) 
    // && !empty($_POST['ownerCoupon']) && isset($_POST['ownerCoupon']) 
    // && !empty($_POST['ownerCouponValue']) && isset($_POST['ownerCouponValue'])
    &&!empty($_POST['ownerTimeBase']) && isset($_POST['ownerTimeBase'])
    ){
        $departmentID = mysqli_real_escape_string($conn, $_POST['departmentID']);
        $ownerID = mysqli_real_escape_string($conn, $_POST['ownerId']);
        $ownerName = mysqli_real_escape_string($conn, $_POST['ownerName']);
        $ownerEmail = mysqli_real_escape_string($conn, $_POST['ownerEmail']);
        $ownerCoupon = mysqli_real_escape_string($conn, $_POST['ownerCoupon']);
        $ownerCouponValue = mysqli_real_escape_string($conn, $_POST['ownerCouponValue']);
        $TimeBase = mysqli_real_escape_string($conn, $_POST['ownerTimeBase']);

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
            if($TimeBase === "1"){
                    //insert baset time to owner
                    $sql_owner = "INSERT INTO owners (staff_id,owner_name,base_time,owner_email,owner_department) VALUES ('$ownerID','$ownerName','$TimeBase','$ownerEmail','$departmentID')";
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
            }else{
                if(!empty($_POST['from_Time']) && isset($_POST['from_Time']) && !empty($_POST['to_Time']) && isset($_POST['to_Time'])){
                    $timebaseFROM_TIME = mysqli_real_escape_string($conn, $_POST['from_Time']);
                    $timebaseTO_TIME = mysqli_real_escape_string($conn, $_POST['to_Time']);
                    //insert baset time to owner
                    $sql_owner = "INSERT INTO owners (staff_id,owner_name,base_time,owner_email,owner_department,from_time,to_time) VALUES ('$ownerID','$ownerName','$TimeBase','$ownerEmail','$departmentID','$timebaseFROM_TIME','$timebaseTO_TIME')";
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
            }
        }
    }else{
         $response = array(
            'success' => false,
            'message' => 'Fill up all required data',
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
        coupons.coupon_value,
        owners.base_time,
    CASE
            
            WHEN owners.base_time = 1 THEN
            department.from_time 
            WHEN owners.base_time = 2 THEN
            owners.from_time 
        END AS from_time,
    CASE
            
            WHEN owners.base_time = 1 THEN
            department.to_time 
            WHEN owners.base_time = 2 THEN
            owners.to_time 
        END AS to_time 
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
            $baseTime = mysqli_real_escape_string($conn, $_POST['ownerTimeBase']);
            $from_TIME = mysqli_real_escape_string($conn, $_POST['from_Time']);
            $to_TIME = mysqli_real_escape_string($conn, $_POST['to_Time']);

            // update owner details
            $sql_updateOwner_Details ="UPDATE owners
            SET
            staff_id = '$ownerID',
            owner_name = '$ownerName',
            owner_email = '$ownerEmail',
            owner_department = '$departmentID',
            base_time = '$baseTime'
            ";
            if($baseTime=="2"){
                $sql_updateOwner_Details .=", from_time = '$from_TIME', to_time = '$to_TIME'";
            }
            $sql_updateOwner_Details.="
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
}elseif(!empty($_POST['action']) && $_POST['action'] == 'addSDOwner') { //ADD SD OWNER

    if(empty($_POST['departmentID']) && $_POST['departmentID']){
        $response = array(
            'success' => false,
            'message' => "Select a Department!",
        );
    }
    else if(empty($_POST['ownerId']) && $_POST['ownerId']){
        $response = array(
            'success' => false,
            'message' => "Enter your Owner ID!",
        );
    }
    else if(empty($_POST['ownerName']) && $_POST['ownerName']){
        $response = array(
            'success' => false,
            'message' => "Enter your Full Name!",
        );
    }
    else if(empty($_POST['sdCode']) && $_POST['sdCode']){
        $response = array(
            'success' => false,
            'message' => "Enter a unique SD Code!",
        );
    }
    else if(empty($_POST['maxSD']) && $_POST['maxSD']){
        $response = array(
            'success' => false,
            'message' => "Enter Max Credits per Cut-Off!",
        );
    }
    else if(empty($_POST['first_Start']) && $_POST['first_Start']){
        $response = array(
            'success' => false,
            'message' => "Enter the starting Day of 1st Cut-Off!",
        );
    }
    else if(empty($_POST['first_End']) && $_POST['first_End']){
        $response = array(
            'success' => false,
            'message' => "Enter the ending Day of 1st Cut-Off!",
        );
    }
    else if(empty($_POST['second_Start']) && $_POST['second_Start']){
        $response = array(
            'success' => false,
            'message' => "Enter the starting Day of 2nd Cut-Off!",
        );
    }
    else if(empty($_POST['second_End']) && $_POST['second_End']){
        $response = array(
            'success' => false,
            'message' => "Enter the ending Day of 2nd Cut-Off!",
        );
    }else{
        $depID = mysqli_real_escape_string($conn, $_POST['departmentID']);
        $ownerID = mysqli_real_escape_string($conn, $_POST['ownerId']);
        $ownerName = mysqli_real_escape_string($conn, $_POST['ownerName']);
        $ownerEmail = mysqli_real_escape_string($conn, $_POST['ownerEmail']);
        $sd_Code = mysqli_real_escape_string($conn, $_POST['sdCode']);
        $maxSD = mysqli_real_escape_string($conn, $_POST['maxSD']);
        $first_cut_start = mysqli_real_escape_string($conn, $_POST['first_Start']);
        $first_cut_end = mysqli_real_escape_string($conn, $_POST['first_End']);
        $second_cut_start = mysqli_real_escape_string($conn, $_POST['second_Start']);
        $second_cut_end = mysqli_real_escape_string($conn, $_POST['second_End']);

        $query_Owners = "SELECT 
        a.owner_name,
        b.owner_id,
        b.sd_code
        FROM owners a
        INNER JOIN salary_deduction b ON b.owner_id = a.staff_id
        WHERE owner_id = '$ownerID' OR sd_code = '$sd_Code'";
        $query_Owners = mysqli_query($conn, $query_Owners);

        if(mysqli_num_rows($query_Owners) > 0){
            $response = array(
                'success' => false,
                'message' => "Owner Already Exist!",
            );
        }else{
            //insert owner
            $sql_owner = "INSERT INTO owners (staff_id,owner_name,owner_email,owner_department) VALUES ('$ownerID','$ownerName','$ownerEmail','$depID')";
            mysqli_query($conn, $sql_owner);
            //insert credits and cut off
            $sql_owner_sd_details = "INSERT INTO salary_deduction (sd_code,sd_credits,owner_id,first_cut_start,first_cut_end,second_cut_start,second_cut_end) VALUES ('$sd_Code','$maxSD','$ownerID','$first_cut_start','$first_cut_end','$second_cut_start','$second_cut_end')";
            mysqli_query($conn, $sql_owner_sd_details);
            $response = array(
                'success' => true,
                'message' => "SD Owner has been Added Successfully!",
            );
        }
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}elseif(!empty($_POST['action']) && $_POST['action'] == 'fetchSDOwner'){
    if(!empty($_POST['recordID']) && isset($_POST['recordID'])){
        $fetch_SDOwnerID = $_POST['recordID'];
        $fetch_SDOwner_data = "SELECT
        a.owner_department AS dep_id,
        a.staff_id,
        a.owner_name,
        a.owner_email,
        b.sd_code,
        b.sd_credits,
        b.first_cut_start,
        b.first_cut_end,
        b.second_cut_start,
        b.second_cut_end
        FROM owners a
        INNER JOIN salary_deduction b ON b.owner_id = a.staff_id
        INNER JOIN department c ON c.id = a.owner_department
        WHERE a.id = '$fetch_SDOwnerID'";
        $result_SDOwner_data = mysqli_query($conn, $fetch_SDOwner_data);
        $row_SDOwner_data = mysqli_fetch_assoc($result_SDOwner_data);
        $response = array(
            'success' => true,
            'message' => "Record Retrieved",
            'data' => $row_SDOwner_data,
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}
elseif(!empty($_POST['action']) && $_POST['action'] == 'updateSDOwner'){
    if(empty($_POST['departmentID']) && $_POST['departmentID']){
        $response = array(
            'success' => false,
            'message' => "Select a Department!",
        );
    }
    else if(empty($_POST['ownerId']) && $_POST['ownerId']){
        $response = array(
            'success' => false,
            'message' => "Enter your Owner ID!",
        );
    }
    else if(empty($_POST['ownerName']) && $_POST['ownerName']){
        $response = array(
            'success' => false,
            'message' => "Enter your Full Name!",
        );
    }
    else if(empty($_POST['sdCode']) && $_POST['sdCode']){
        $response = array(
            'success' => false,
            'message' => "Enter a unique SD Code!",
        );
    }
    else if(empty($_POST['maxSD']) && $_POST['maxSD']){
        $response = array(
            'success' => false,
            'message' => "Enter Max Credits per Cut-Off!",
        );
    }
    else if(empty($_POST['first_Start']) && $_POST['first_Start']){
        $response = array(
            'success' => false,
            'message' => "Enter the starting Day of 1st Cut-Off!",
        );
    }
    else if(empty($_POST['first_End']) && $_POST['first_End']){
        $response = array(
            'success' => false,
            'message' => "Enter the ending Day of 1st Cut-Off!",
        );
    }
    else if(empty($_POST['second_Start']) && $_POST['second_Start']){
        $response = array(
            'success' => false,
            'message' => "Enter the starting Day of 2nd Cut-Off!",
        );
    }
    else if(empty($_POST['second_End']) && $_POST['second_End']){
        $response = array(
            'success' => false,
            'message' => "Enter the ending Day of 2nd Cut-Off!",
        );
    }else{
        $updateId = mysqli_real_escape_string($conn, $_POST['updateId']);

        $depID = mysqli_real_escape_string($conn, $_POST['departmentID']);
        $ownerID = mysqli_real_escape_string($conn, $_POST['ownerId']);
        $ownerName = mysqli_real_escape_string($conn, $_POST['ownerName']);
        $ownerEmail = mysqli_real_escape_string($conn, $_POST['ownerEmail']);
        $sd_Code = mysqli_real_escape_string($conn, $_POST['sdCode']);
        $maxSD = mysqli_real_escape_string($conn, $_POST['maxSD']);
        $first_cut_start = mysqli_real_escape_string($conn, $_POST['first_Start']);
        $first_cut_end = mysqli_real_escape_string($conn, $_POST['first_End']);
        $second_cut_start = mysqli_real_escape_string($conn, $_POST['second_Start']);
        $second_cut_end = mysqli_real_escape_string($conn, $_POST['second_End']);

        //update owners
        $sql_SDOWNER = "UPDATE owners
        SET
        staff_id = '$ownerID',
        owner_name = '$ownerName',
        owner_email = '$sd_Code',
        owner_department = '$depID'
        WHERE id = '$updateId'
        ";
        mysqli_query($conn, $sql_SDOWNER);

        //update owner sd details
        $sql_SDOWNER_Details = "UPDATE salary_deduction
        SET
        sd_code = '$sd_Code',
        sd_credits = '$maxSD',
        owner_id = '$ownerID',
        first_cut_start = '$first_cut_start',
        first_cut_end = '$first_cut_end',
        second_cut_start = '$second_cut_start',
        second_cut_end = '$second_cut_end'
        WHERE id = '$updateId'";
        mysqli_query($conn, $sql_SDOWNER_Details);
        $response = array(
            'success' => true,
            'message' => "Record has been Updated!",
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}elseif(!empty($_POST['action']) && $_POST['action'] == 'deleteSDOwner'){
    $deleteOwnerID = $_POST['recordID'];
    $deleteRecord = "DELETE salary_deduction, owners
    FROM salary_deduction
    JOIN owners ON owners.staff_id = salary_deduction.owner_id
    WHERE owners.id = '$deleteOwnerID'";
    mysqli_query($conn, $deleteRecord);
    $response = array(
        'success' => true,
        'message' => "Record has been deleted Successfully",
    );
    header('Content-Type: application/json');
    echo json_encode($response);

}elseif(!empty($_POST['action']) && $_POST['action'] == 'fetchClerk'){
    if(!empty($_POST['recordID']) && isset($_POST['recordID'])){
        $fetchOwnerID = $_POST['recordID'];
        $query_fetch_admin = "SELECT * FROM admins where id = '$fetchOwnerID'";
        $result_fetch_admin = mysqli_query($conn, $query_fetch_admin);
        $row_fetch_admin = mysqli_fetch_assoc($result_fetch_admin);
        $response = array(
            'success' => true,
            'message' => "Record Retrieved",
            'data' => $row_fetch_admin,
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}
elseif(!empty($_POST['action']) && $_POST['action'] == 'addClerk'){

    if(!empty($_POST['ownerName']) && isset($_POST['ownerName'])&&
        !empty($_POST['ownerEmail']) && isset($_POST['ownerEmail'])&&
        !empty($_POST['in_Location']) && isset($_POST['in_Location'])
        ){
            if(!empty($_POST['in_Password']) && isset($_POST['in_Password']) && !empty($_POST['in_ConPassword']) && isset($_POST['in_ConPassword'])){
                $clerkName = $_POST['ownerName'];
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
                        $sql_coupon = "INSERT INTO admins (email, password,display_name, role_id, location) 
                        VALUES ('$clerkEmail','$conpassword','$clerkName','1','$clerkLocation')";
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
            'message' => 'Please Fill up Name, Email or Location!',
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);

}elseif(!empty($_POST['action']) && $_POST['action'] == 'updateClerk'){

    if(empty($_POST['ownerName']) && isset($_POST['ownerName'])){
        $response = array(
            'success' => false,
            'message' => 'Please Fill Up Clerk Name',
           );
    }
    else if(empty($_POST['ownerEmail']) && isset($_POST['ownerEmail'])){
        $response = array(
            'success' => false,
            'message' => 'Please Fill Up Clerk Email',
           );
    }
    else if(empty($_POST['in_Password']) && isset($_POST['in_Password'])){
        $response = array(
            'success' => false,
            'message' => 'Please Fill Up Clerk Password',
           );
    }
    else if(empty($_POST['in_ConPassword']) && isset($_POST['in_ConPassword'])){
        $response = array(
            'success' => false,
            'message' => 'Please Fill Up Clerk Confirm Password',
           );
    }
    else if(empty($_POST['in_Location']) && isset($_POST['in_Location'])){
        $response = array(
            'success' => false,
            'message' => 'Please Fill Up Clerk Location',
           );
    }else{
        $updateId = mysqli_real_escape_string($conn, $_POST['updateId']);
        $owner_PASSWORD = mysqli_real_escape_string($conn, $_POST['in_Password']);
        $owner_CPASSWORD = mysqli_real_escape_string($conn, $_POST['in_ConPassword']);

        if($owner_PASSWORD == $owner_CPASSWORD){
            $owner_NAME = mysqli_real_escape_string($conn, $_POST['ownerName']);
            $owner_EMAIL = mysqli_real_escape_string($conn, $_POST['ownerEmail']);
            $owner_LOCATION = mysqli_real_escape_string($conn, $_POST['in_Location']);
            $sql_Update_Clerk = "UPDATE admins
            SET
            email = '$owner_EMAIL',
            password = '$owner_CPASSWORD',
            display_name = '$owner_NAME',
            location = '$owner_LOCATION'
            WHERE id = '$updateId'";
            mysqli_query($conn, $sql_Update_Clerk);
            $response = array(
                'success' => true,
                'message' => 'Clerk Details has been Updated',
            );
        }else{
            $response = array(
                'success' => false,
                'message' => 'Password and Confirm Password does not Match!',
            );
        }

    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
else if(!empty($_POST['action']) && $_POST['action'] == 'deleteClerk'){
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
else if(!empty($_POST['action']) && $_POST['action'] == 'fetchDepartment'){
        if(!empty($_POST['recordID']) && isset($_POST['recordID'])){
            $fetchDep_ID = $_POST['recordID'];
            $fetchDep_DATA = "SELECT * FROM department WHERE id = '$fetchDep_ID'";
            $result_Dep = mysqli_query($conn, $fetchDep_DATA);
            if(mysqli_num_rows($result_Dep) > 0){
                $data_Department = mysqli_fetch_assoc($result_Dep);
                $response = array(
                    'success' => true,
                    'message' => "Record Retrieved",
                    'data' => $data_Department,
                );
            }else{
                $response = array(
                    'success' => false,
                    'message' => "Failed to retrieve department!",
                );
            }
        header('Content-Type: application/json');
        echo json_encode($response);
        }
}
else if(!empty($_POST['action']) && $_POST['action'] == 'addDepartment'){
    if(empty($_POST['in_Department']) && isset($_POST['in_Department'])){
        $response = array(
            'success' => false,
            'message' => "Please enter a Department!",
        );
    }
    else if(empty($_POST['in_prefix']) && isset($_POST['in_prefix'])){
        $response = array(
            'success' => false,
            'message' => "Please enter Department Prefix!",
        );
    }
    else if(empty($_POST['from_Time']) && isset($_POST['from_Time'])){
        $response = array(
            'success' => false,
            'message' => "Please select Starting Time!",
        );
    }
    else if(empty($_POST['to_Time']) && isset($_POST['to_Time'])){
        $response = array(
            'success' => false,
            'message' => "Please select End Time!",
        );
    }else{
        $departmentName = $_POST['in_Department'];
        $dep_Prefix = $_POST['in_prefix'];
        $From_Time = $_POST['from_Time'];
        $To_Time = $_POST['to_Time'];
        $sql_department ="SELECT * FROM department WHERE department_name = '$departmentName' OR department_prefix = '$dep_Prefix'";
        $result_department = mysqli_query($conn, $sql_department);
        if(mysqli_num_rows($result_department) > 0){
            $response = array(
                'success' => false,
                'message' => "Department already existed!",
            );
        }else{
            $sql_create_Department = "INSERT INTO department (department_name, department_prefix, from_time, to_time) VALUES ('$departmentName','$dep_Prefix', '$From_Time','$To_Time')";
            mysqli_query($conn, $sql_create_Department);
            $response = array(
                'success' => true,
                'message' => "Department has been created!",
            );
        }
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}
else if(!empty($_POST['action']) && $_POST['action'] == 'updateDepartment'){
    if(empty($_POST['in_Department']) && isset($_POST['in_Department'])){
        $response = array(
            'success' => false,
            'message' => "Please enter a Department!",
        );
    }
    else if(empty($_POST['in_prefix']) && isset($_POST['in_prefix'])){
        $response = array(
            'success' => false,
            'message' => "Please enter Department Prefix!",
        );
    }
    else if(empty($_POST['from_Time']) && isset($_POST['from_Time'])){
        $response = array(
            'success' => false,
            'message' => "Please select Starting Time!",
        );
    }
    else if(empty($_POST['to_Time']) && isset($_POST['to_Time'])){
        $response = array(
            'success' => false,
            'message' => "Please select End Time!",
        );
    }else{
        $updateId = mysqli_real_escape_string($conn, $_POST['updateId']);

        $departmentName = $_POST['in_Department'];
        $dep_Prefix = $_POST['in_prefix'];
        $From_Time = $_POST['from_Time'];
        $To_Time = $_POST['to_Time'];

        $sql_Update_Department = "UPDATE department
        SET
        department_name = '$departmentName',
        department_prefix = '$dep_Prefix',
        from_time = '$From_Time',
        to_time = '$To_Time'
        WHERE id = '$updateId'";
        mysqli_query($conn, $sql_Update_Department);
        $response = array(
            'success' => true,
            'message' => 'Department has been Updated',
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}
else if(!empty($_POST['action']) && $_POST['action'] == 'deleteDepartment'){
    if(empty($_POST['recordID']) && isset($_POST['recordID'])){
        $response = array(
            'success' => false,
            'message' => "Department Does not Exist!",
        );
    }else{
        $deleteDEP_ID = $_POST['recordID'];
        $sql_delete_Department = "DELETE FROM department WHERE id = '$deleteDEP_ID'";
        mysqli_query($conn, $sql_delete_Department);
        $response = array(
            'success' => true,
            'message' => "Department has been deleted!",
        );
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
}else if(!empty($_POST['action']) && $_POST['action'] == 'voidTransaction'){
    if(empty($_POST['recordID']) && isset($_POST['recordID']) ){
        $response = array(
            'success' => false,
            'message' => 'No record ID!',
        );
    }else if(empty($_POST['recordID']) && isset($_POST['recordID']))
        $response = array(
            'success' => false,
            'message' => 'Please scan void pin!',
        );
    else{
        $recordID = mysqli_real_escape_string($conn, $_POST['recordID']);
        $voidPin = mysqli_real_escape_string($conn, $_POST['voidPin']);
        //check manager
        $check_Manager ="SELECT * FROM admins WHERE role_id='3' AND pin='$voidPin'";
        $result_Manager = mysqli_query($conn, $check_Manager);
        if(mysqli_num_rows($result_Manager) > 0){
            $voidTransaction = "UPDATE balance_deducted
            SET
            void = '1'
            WHERE id ='$recordID'
            ";
            mysqli_query($conn, $voidTransaction);
            $response = array(
                'success' => true,
                'message' => 'Transaction has been voided.',
            );
        }else{
            $response = array(
                'success' => false,
                'message' => 'Invalid Void Pin!',
            );
        }
    }
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