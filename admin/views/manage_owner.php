<h2 class="modal-title text-center text-uppercase">List of Employee</h2><br><br>
<div  style="width: 100% !important;">
  <div class="row mb-2">
    <div class="col">
        <span class="" id="basic-addon1">Search</span>
        <input type="text" class="form-control search w-100" placeholder="Ex. Owner ID OR Coupon Code" id="live_search" autocomplete="off" style="border: 1px solid black;">
    </div>
    <div class="col align-self-end">
        <div class="float-right ">
            <button type="button" id="addOwnerBtn" class="btn btn-primary float-end" data-toggle="modal" data-target="#ownerModal">Add Employee</button>
        </div>
    </div>
  </div>
    <div class="table-container">
        <table class="table table-hover">
        <thead>
            <tr>
            <th scope="col">Owner ID</th>
            <th scope="col">Owner Name</th>
            <th scope="col">Department</th>
            <th scope="col" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody id="manageTable">

        </tbody>
        </table>
    </div>
</div>

    <!-- Modal -->
    <div class="modal fade" id="ownerModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
        <form id="form_owner">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Employee Details</h1>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">X</button>
          </div>
          <div class="modal-body mx-2">
            <div class="my-2">
              <select class="form-select" aria-label="Default select example" name="departmentID" id="selectDepartment" onchange="updateCouponPrefix()">
                <option disabled selected>Select Department*</option>
                <?php 
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $sql = "SELECT * FROM department";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                ?>
                <option value="<?php echo $row["id"];?>"><?php echo $row["department_name"];?></option>
                <?php
                }}?>
              </select>
            </div>
             <div class="my-2">
              <label for="exampleFormControlInput1" class="form-label">Owner ID*</label>
              <input type="text" class="form-control" id="ownerID" name="ownerId" placeholder="Ex. 1234" style="border:0.5px solid black;">
            </div>
            <div class="my-2">
              <label for="exampleFormControlInput1" class="form-label">Full Name*</label>
              <input type="text" class="form-control" id="ownerName" name="ownerName" placeholder="Ex. Juan dela cruz"style="border:0.5px solid black;">
            </div>
            <div class="my-2">
              <label for="exampleFormControlInput1" class="form-label">Email</label>
              <input type="text" class="form-control" id="ownerEmail" name="ownerEmail" placeholder="Ex. juandelacruz@gmail.com"style="border:0.5px solid black;">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" id="closeOwner" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" id="addOwner" class="btn btn-primary">ADD</button>
            <button type="button" id="updateEmployee" update-id="" class="btn btn-primary">UPDATE</button>
          </div>
        </form>
        </div>
      </div>
    </div>
  <!-- Modal End -->

  <script>
$(document).ready(function(){
    $('#ownerName, #ownerEmail').on('input', function() {
      // Convert the input value to uppercase
      $(this).val($(this).val().toUpperCase());
    });
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "1000",
        "hideDuration": "1000",
        "timeOut": "2000",
        "extendedTimeOut": "2000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    LoadTable();
    function LoadTable() {
        var search = $('#live_search').val();
        // console.log("Search value:", search);
        if(search=="")
        {
            $.ajax({
                url: "../process/employee_table.php",
                type: "POST",
                cache: false,
                data:{
                    search:search
                    },
                success:function(data){
                    // alert(data);
                    // toastr.success("Record Retrieve Successful");
                    $('#manageTable').html(data);
                }
            });
        }else{
            $.ajax({
                url: "../process/employee_table.php",
                type: "POST",
                cache: false,
                data:{
                    search:search
                    },
                success:function(data){
                    // alert(data);
                    $('#manageTable').html(data);
                }
                });
        }
        }
    $("#live_search").keyup(function(){
        var search = $(this).val();
        LoadTable();
    });

    $('#addOwner').click(function(){
        var formData = $('#form_owner').serialize();
        // alert (formData);
        $.ajax({
            url: "../process/admin_action.php",
            method: "POST",
            data: formData+"&action=addEmployee",
            dataType: "json",
            success: function(response) {
                if(response.success==true){
                    toastr.success(response.message);
                    LoadTable();
                    $("#closeOwner").click();
                }else{
                    toastr.error(response.message);
                }
            }
        });
    });
    $('#updateEmployee').click(function(){
        var recordID = $(this).attr("update-id");
        // alert(recordID);
        var formData = $('#form_owner').serialize();
        $.ajax({
            url: "../process/admin_action.php",
            method: "POST",
            data: formData+"&action=updateEmployee&updateId="+recordID,
            dataType: "json",
            success: function(response) {
                if(response.success==true){
                    toastr.success(response.message);
                    LoadTable();
                    $("#closeOwner").click();
                }else{
                    toastr.error(response.message);
                }
            }
        });
    });
});
  </script>