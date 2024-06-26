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
        b.id,
        a.staff_id,
        a.owner_name,
        a.owner_email,
        c.department_name,
				b.sd_code,
				b.sd_credits,
        b.created_at
    FROM
        owners a
        INNER JOIN salary_deduction B ON b.owner_id = a.staff_id
        INNER JOIN department c ON c.id = a.owner_department
        AND a.owner_department = c.id
        WHERE a.staff_id LIKE '%{$searchCode}%' OR b.sd_code LIKE '%{$searchCode}%' OR a.owner_name LIKE '%{$searchCode}%'
    ORDER BY
        b.id ASC
        ";
        $result = $conn->query($sql);
    }else{
        $sql = "SELECT
        b.id,
        a.staff_id,
        a.owner_name,
        a.owner_email,
        c.department_name,
        b.sd_code,
        b.sd_credits,
        b.created_at
    FROM
        owners a
        INNER JOIN salary_deduction b ON b.owner_id = a.staff_id
        INNER JOIN department c ON c.id = a.owner_department
        AND a.owner_department = c.id 
    ORDER BY
        b.id ASC
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
      <td><?php echo $row["sd_code"];?></td>
      
      <td class="text-center"><?php echo $row["sd_credits"];?></td>
      <td class="text-center"><button type="button" record-id="<?php echo $row["sd_code"]?>" class="btn btn-primary editOwner me-2">EDIT</button>&nbsp;<button type="button" record-id="<?php echo $row["sd_code"]?>" class="btn btn-danger deleteOwner">DELETE</button></td>
    </tr>
    <br>
    <?php
}}?>
<script>
    $('.editOwner').click(function(){
        $("#updateOwner").show();
        $("#addOwner").hide();
        $('#in_ownerId').prop('readonly', true);
        var recordID = $(this).attr("record-id");
        // alert(recordID);
        $.ajax({
            url: "../process/admin_action.php",
            method: "POST",
            data: {recordID:recordID, action: "fetchSDOwner"},
            dataType: "json",
            success: function(response) {
                if(response.success==true){
                    toastr.success(response.message);
                    $("#ownerModal").modal("show");
                    
                    $('#selectDepartment').val(response.data.dep_id);
                    $('#in_ownerId').val(response.data.staff_id);
                    $('#in_ownerName').val(response.data.owner_name);
                    $('#in_ownerEmail').val(response.data.owner_email);
                    $('#sdCode').val(response.data.sd_code);
                    $('#maxSD').val(response.data.sd_credits);
                    $('#first_day_start').val(response.data.first_cut_start);
                    $('#first_day_end').val(response.data.first_cut_end);
                    $('#second_day_start').val(response.data.second_cut_start);
                    $('#second_day_end').val(response.data.second_cut_end);
                    $("#updateOwner").attr("update-id", recordID);
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
                    data: {recordID:recordID,action: "deleteSDOwner"},
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