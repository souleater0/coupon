<?php
    include 'admin/time_zone.php';
    include 'db_connection.php';

    $dateNow = $current_date;
    $owner_query = "SELECT * FROM claims where claim_date = $dateNow";
    $checkOwner = mysqli_query($conn, $owner_query);
    echo $dateNow;
    // if(mysqli_num_rows($checkOwner) > 0){
    //     echo "exist";
    // }
?>