<?php
include 'db_connection.php';

$records_query = "SELECT owners.owner_name, coupons.coupon_code, claims.claim_date FROM claims 
    JOIN owners ON claims.owner_id = owners.id
    JOIN coupons ON claims.coupon_id = coupons.id";

$records_result = mysqli_query($conn, $records_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recorded Claims</title>
</head>
<body>
    <h1>Recorded Claims</h1>
    <table border="1">
        <tr>
            <th>Owner Name</th>
            <th>Coupon Code</th>
            <th>Claim Date</th>
        </tr>
        <?php
        while($row = mysqli_fetch_assoc($records_result)) {
            echo "<tr>";
            echo "<td>".$row['owner_name']."</td>";
            echo "<td>".$row['coupon_code']."</td>";
            echo "<td>".$row['claim_date']."</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>
