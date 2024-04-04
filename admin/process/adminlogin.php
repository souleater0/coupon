<?php
session_start();
include '../../db_connection.php';
if(!empty($_POST['action']) && $_POST['action'] == 'loginProcess') {
    if(isset($_POST['email']) && isset($_POST['password'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $sql = "SELECT * FROM admin WHERE email='{$email}' AND password='{$password}'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0){
            $_SESSION['admin_session_email'] = $email;
            $row = mysqli_fetch_assoc($result);
            $_SESSION['admin_session_id']= $row['id'];
            $response = array(
                'success' => true,
                'message' => 'Login successful.',
                'redirectUrl' => 'views/index.php?route=dashboard'
            );
        }else{
            $response = array(
                'success' => false,
                'message' => 'Invalid Credentials!'
            );
        }
    }
}
header('Content-Type: application/json');
echo json_encode($response);
?>