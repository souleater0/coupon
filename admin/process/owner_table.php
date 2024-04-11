<?php
    include '../../db_connection.php';
    include '../time_zone.php';

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if(isset($_POST['search']) && !empty($_POST['search'])){
        $searchCode = $_POST['search'];
        $sql = "SELECT
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
        WHERE a.staff_id LIKE '%{$searchCode}%' OR b.coupon_code LIKE '%{$searchCode}%' OR a.owner_name LIKE '%{$searchCode}%'
    ORDER BY
        a.id ASC
        ";
        $result = $conn->query($sql);
    }else{
        $sql = "SELECT
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
    ORDER BY
        a.id ASC
        ";
        $result = $conn->query($sql);
    }
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
?>
    <tr>
      <th scope="row"><?php echo $row["id"]?></th>
      <td><?php echo $row["staff_id"];?></td>
      <td><?php echo $row["owner_name"];?></td>
      <!-- <td><?php echo $row["owner_email"];?></td> -->
      <td><?php echo $row["department_name"];?></td>
      <td><?php echo $row["coupon_code"];?></td>
      <td class="text-center"><?php echo $row["coupon_value"];?></td>
      <td><?php
      if($row["base_time"] === "1"){
        echo "Department";
      }else{
        echo "Individual";
      }
      ?></td>
      <td><?php echo date("h:i A", strtotime($row["from_time"])).' - '.date("h:i A", strtotime($row["to_time"]));?></td>
      <td class="text-center"><button type="button" record-id="<?php echo $row["id"]?>" class="btn btn-primary editOwner me-2">EDIT</button>&nbsp;<button type="button" record-id="<?php echo $row["id"]?>" class="btn btn-danger deleteOwner">DELETE</button></td>
    </tr>
    <?php
}}?>
<script>
    $('.editOwner').click(function(){
        $("#updateOwner").show();
        $("#addOwner").hide();
        var recordID = $(this).attr("record-id");
        // alert(recordID);
        $.ajax({
            url: "../process/admin_action.php",
            method: "POST",
            data: {recordID:recordID, action: "fetchOwner"},
            dataType: "json",
            success: function(response) {
                if(response.success==true){
                    toastr.success(response.message);
                    $("#ownerModal").modal("show");
                    
                    $('#selectDepartment').val(response.data.dep_id);
                    $('#in_ownerId').val(response.data.staff_id);
                    $('#in_ownerName').val(response.data.owner_name);
                    $('#in_ownerEmail').val(response.data.owner_email);
                    $('#couponCode').val(response.data.coupon_code);
                    $('#couponValue').val(response.data.coupon_value);
                    $('#TimeBase').val(response.data.base_time);
                    if(response.data.base_time==2){
                        $('#from_Time').val(response.data.from_time);
                        $('#to_Time').val(response.data.to_time);
                    }
                    $("#updateClerk").attr("update-id", recordID);
                    
                    displayIndividualTime();
                }else{
                    toastr.error(response.message);
                }
            }
        });
    });
    $('.deleteOwner').click(function(){
              var recordID = $(this).attr("record-id");
              // alert($(this).attr("id"));
              if(confirm("Are you sure you want to delete this record?")) {
                $.ajax({
                    url: "../process/admin_action.php",
                    method: "POST",
                    data: {recordID:recordID,action: "deleteOwner"},
                    dataType: "json",
                    success: function(response) {
                        if(response.success==true){
                            toastr.success(response.message);
                            // $("#closeOwner").click();
                        }else{
                            toastr.error(response.message);
                        }
                    }
                });
              } else {
                  return false;
              }
    });
</script>