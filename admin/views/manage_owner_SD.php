<div class="container">
  <div class="row mb-2">
    <div class="col">
    <span class="" id="basic-addon1">Search</span>
    <input type="text" class="form-control search w-100" placeholder="Ex. Owner ID OR Coupon Code" id="live_search" autocomplete="off">
    </div>
    <div class="col">
  <div class="float-end mb-2">
        <button type="button" id="addOwnerBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ownerModal">Add Owner</button>
  </div>
    </div>
  </div>
<div class="table-container">
<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Owner ID</th>
      <th scope="col">Owner Name</th>
      <!-- <th scope="col">Email</th> -->
      <th scope="col">Department</th>
      <th scope="col">Coupon Code</th>
      <th scope="col">Coupon Value</th>
      <th scope="col">Date Created</th>
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
            <h1 class="modal-title fs-5" id="exampleModalLabel">Owner Details</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body mx-2">
            <div class="my-2">
              <select class="form-select" aria-label="Default select example" name="departmentID" id="selectDepartment" onchange="updateCouponPrefix()">
                <option disabled selected>Select Department</option>
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
              <label for="exampleFormControlInput1" class="form-label">Owner ID</label>
              <input type="text" class="form-control" id="in_ownerId" name="ownerId" placeholder="Ex. 1234">
            </div>
            <div class="my-2">
              <label for="exampleFormControlInput1" class="form-label">Full Name</label>
              <input type="text" class="form-control" id="in_ownerName" name="ownerName" placeholder="Ex. Juan dela cruz">
            </div>
            <div class="my-2">
              <label for="exampleFormControlInput1" class="form-label">Email</label>
              <input type="text" class="form-control" id="in_ownerEmail" name="ownerEmail" placeholder="Ex. juandelacruz@gmail.com">
            </div>
            <div class="my-2">
              <label for="exampleFormControlInput1" class="form-label">Coupon Code</label>
              <input type="text" class="form-control" id="couponCode" name="ownerCoupon" placeholder="Ex. FNBFS2024001">
            </div>
            <div class="my-2">
              <label for="exampleFormControlInput1" class="form-label">Coupon Value</label>
              <input type="text" class="form-control" id="couponValue" name="ownerCouponValue" placeholder="Ex. ₱60">
            </div>
            
          </div>
          <div class="modal-footer">
            <button type="button" id="closeOwner" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" id="addOwner" class="btn btn-primary">ADD</button>
            <button type="button" id="updateOwner" class="btn btn-primary">UPDATE</button>
          </div>
        </form>
        </div>
      </div>
    </div>
  <!-- Modal End -->
  
<script>
  function updateCouponPrefix(){
    var departmentSelect = document.getElementById("selectDepartment");
    var selectedOptionIndex = departmentSelect.selectedIndex;
    var selectedOptionValue = departmentSelect.value;
    // var selectedOption = departmentSelect.options[departmentSelect.selectedIndex];
        // AJAX request
        $.ajax({
        url: "../process/coupon_generator.php",
        type: "GET",
        data: { id: selectedOptionValue },
        success: function(response) {
            $("#couponCode").val(response.value);
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
        }
    });
  }
  $(document).ready(function(){
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
                  url: "../process/owner_table.php",
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
                  url: "../process/owner_table.php",
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
            $("#updateOwner").hide();
              var formData = $('#form_owner').serialize();
              // alert (formData);
              $.ajax({
                  url: "../process/admin_action.php",
                  method: "POST",
                  data: formData+"&action=addOwner",
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
            // $('.editOwner').click(function(){
            //   var recordID = $(this).attr("record-id");
            //   alert(recordID);
              // $.ajax({
              //       url: "../process/admin_action.php",
              //       method: "POST",
              //       data: {recordID:recordID, action: "fetchOwner"},
              //       dataType: "json",
              //       success: function(response) {
              //           if(response.success==true){
              //               toastr.success(response.message);
              //               $("#ownerModal").modal("show");
                            
              //               $('#selectDepartment').val(response.data.dep_id);
              //               $('#in_ownerId').val(response.data.staff_id);
              //               $('#in_ownerName').val(response.data.owner_name);
              //               $('#in_ownerEmail').val(response.data.owner_email);
              //               $('#couponCode').val(response.data.coupon_code);
              //               $('#couponValue').val(response.data.coupon_value);
              //               $("#updateOwner").attr("update-id", recordID);
              //           }else{
              //               toastr.error(response.message);
              //           }
              //       }
              //   });
            // });
            $('#updateOwner').click(function(){
              var recordID = $(this).attr("update-id");
              // alert(recordID);
              var formData = $('#form_owner').serialize();
              $.ajax({
                    url: "../process/admin_action.php",
                    method: "POST",
                    data: formData+"&action=updateOwner&updateId="+recordID,
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