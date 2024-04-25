<?php
    include '../../db_connection.php';
    include '../time_zone.php';

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if(isset($_POST['search']) && !empty($_POST['search'])){
        $searchCode = $_POST['search'];
        $query_Employee = "SELECT
        a.id,
        a.staff_id,
        a.owner_name,
        a.owner_email,
        b.department_name
        FROM owners a
        INNER JOIN department b ON b.id = a.owner_department
        WHERE a.owner_name LIKE '%$searchCode%' OR a.staff_id LIKE '%$searchCode%'
        ORDER BY owner_name ASC";
        $result_Employee = mysqli_query($conn, $query_Employee);
    }else{
        $query_Employee = "SELECT
        a.id,
        a.staff_id,
        a.owner_name,
        a.owner_email,
        b.department_name
        FROM owners a
        INNER JOIN department b ON b.id = a.owner_department
        ORDER BY owner_name ASC";
        $result_Employee = mysqli_query($conn, $query_Employee);
    }
    if(mysqli_num_rows($result_Employee) > 0){
        while($row_Employee = mysqli_fetch_assoc($result_Employee)) {
    ?>
            <tr>
            <td><?php echo $row_Employee["staff_id"];?></td>
            <td><?php echo $row_Employee["owner_name"];?></td>
            <td><?php echo $row_Employee["department_name"];?></td>
            <td class="text-center"><button type="button" record-id="<?php echo $row_Employee["staff_id"]?>" class="btn btn-primary editEmployee me-2">EDIT</button>&nbsp;<button type="button" record-id="<?php echo $row_Employee["staff_id"]?>" class="btn btn-danger deleteEmployee">DELETE</button></td>
            </tr>
     <?php
        }
    }else{
        echo "No Result";
    }
?>

<script>
    $('.editEmployee').click(function(){
        $("#updateEmployee").show();
        $("#addOwner").hide();
        var recordID = $(this).attr("record-id");
        //alert(recordID);
        $.ajax({
            url: "../process/admin_action.php",
            method: "POST",
            data: {recordID:recordID, action: "fetchEmployee"},
            dataType: "json",
            success: function(response) {
                if(response.success==true){
                    toastr.success(response.message);
                    $("#ownerModal").modal("show");
                    
                    $('#selectDepartment').val(response.data.dep_id);
                    $('#ownerID').val(response.data.staff_id);
                    $('#ownerName').val(response.data.owner_name);
                    $('#ownerEmail').val(response.data.owner_email);

                    $("#updateEmployee").attr("update-id", recordID);
                }else{
                    toastr.error(response.message);
                }
            }
        });
    });
    $('.deleteEmployee').click(function(){
              var recordID = $(this).attr("record-id");
              // alert($(this).attr("id"));
              if(confirm("Are you sure you want to delete this record?")) {
                $.ajax({
                    url: "../process/admin_action.php",
                    method: "POST",
                    data: {recordID:recordID,action: "deleteEmployee"},
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