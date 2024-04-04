<?php
include '../../db_connection.php';
if (isset($_GET['id'])) {
    $department_id = $_GET['id'];
    
    // Fetch department details from the database
    $sql = "SELECT * FROM department WHERE id = $department_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $department_prefix = $row['department_prefix'];
        
        // coupon counter
        $sql_count = "SELECT COUNT(*) AS coupon_count FROM coupons";
        $result_count = $conn->query($sql_count);
        $row_count = $result_count->fetch_assoc();
        $increment = $row_count["coupon_count"]+1;

        $year = date("Y");
        // . sprintf("%03d", $increment);
        $coupon_code = $department_prefix.$year; // Assuming you always want 5 digits for the incrementing value
        $response = array(
            'success' => true,
            'value' => $coupon_code,
        );
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        echo "Department not found";
    }
} else {
    echo "Invalid request";
}
?>