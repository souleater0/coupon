<?php
    include '../../db_connection.php';
    include '../time_zone.php';

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if(isset($_POST['search']) && !empty($_POST['search'])){
        $searchCode = $_POST['search'];
        $sql_query_department = "SELECT * FROM department
        WHERE department_name LIKE '%{$searchCode}%' OR department_prefix LIKE '%{$searchCode}%'
        ORDER BY department_name ASC
        ";
        $result = mysqli_query($conn, $sql_query_department);
    }else{
        $sql_query_department = "SELECT * FROM department
        ORDER BY department_name ASC
        ";
        $result = mysqli_query($conn, $sql_query_department);
    }

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
?> 
    <tr>
    <td><?php echo $row["department_name"];?></td>
    <td><?php echo $row["department_prefix"];?></td>
    <td><?php echo $row["from_time"];?></td>
    <td><?php echo $row["to_time"];?></td>
    <td class="text-center"><button type="button" record-id="<?php echo $row["id"]?>" class="btn btn-primary editDepartment me-2">EDIT</button>&nbsp;<button type="button" record-id="<?php echo $row["id"]?>" class="btn btn-danger deleteDepartment">DELETE</button></td>
    </tr>
<?php 
        }
    }else{
        echo "No Result Found";
    }
?>
<script>
    $('.editDepartment').click(function(){
        $("#updateDep").show();
        $("#addDep").hide();
        var recordID = $(this).attr("record-id");
        // alert(recordID);
        $.ajax({
            url: "../process/admin_action.php",
            method: "POST",
            data: {recordID:recordID, action: "fetchDepartment"},
            dataType: "json",
            success: function(response) {
                if(response.success==true){
                    toastr.success(response.message);
                    $("#depModal").modal("show");
                    
                    $('#in_Department').val(response.data.department_name);
                    $('#in_Department_prefix').val(response.data.department_prefix);
                    $('#from_Time').val(response.data.from_time);
                    $('#to_Time').val(response.data.to_time);

                    $("#updateDep").attr("update-id", recordID);
                    LoadTable();
                }
                else{
                    toastr.error(response.message);
                }
            }
        });
    });
    $('.deleteDepartment').click(function(){
          var recordID = $(this).attr("record-id");
          // alert($(this).attr("id"));
          if(confirm("Are you sure you want to delete this record?")) {
            $.ajax({
                url: "../process/admin_action.php",
                method: "POST",
                data: {recordID:recordID,action: "deleteDepartment"},
                dataType: "json",
                success: function(response) {
                    if(response.success==true){
                        toastr.success(response.message);
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