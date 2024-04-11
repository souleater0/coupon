<?php
    include '../../db_connection.php';
    include '../time_zone.php';

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if(isset($_GET['search']) && !empty($_GET['search'])){
        $searchCode = $_GET['search'];
        $sql = "SELECT a.id,a.email, a.display_name, a.location 
        FROM admins a
        WHERE a.role_id !='2'
        ";
        $result = $conn->query($sql);
    }else{
        $sql = "SELECT a.id,a.email, a.display_name, a.location 
        FROM admins a
        WHERE a.role_id !='2'
        ";
        $result = $conn->query($sql);
    }

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
?>
    <tr>
      <th scope="row"><?php echo $row["id"]?></th>
      <td><?php echo $row["email"];?></td>
      <td><?php echo $row["display_name"];?></td>
      <td><?php echo $row["location"];?></td>
      <td class="text-center"><button type="button" record-id="<?php echo $row["id"]?>" class="btn btn-primary editClerk me-2">EDIT</button><button type="button" record-id="<?php echo $row["id"]?>" class="btn btn-danger deleteClerk">DELETE</button></td>
    </tr>
<?php
}}?>

<script>
    $('.editClerk').click(function(){
        // $("#updateOwner").show();
        // $("#addOwner").hide();
        // var recordID = $(this).attr("record-id");
        $("#clerkModal").modal("show");
    });
    $('.deleteClerk').click(function(){
        var recordID = $(this).attr("record-id");
        if(confirm("Are you sure you want to delete this record?")) {
                $.ajax({
                    url: "../process/admin_action.php",
                    method: "POST",
                    data: {recordID:recordID,action: "deleteClerk"},
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