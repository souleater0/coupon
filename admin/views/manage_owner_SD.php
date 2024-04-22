<h2 class="modal-title text-center">SALARY DEDUCTION OWNER</h2><br><br>
<div  style="width: 100% !important;">
  <div class="row mb-2">
    <div class="col">
    <span class="" id="basic-addon1">Search</span>
    <input type="text" class="form-control search w-100" placeholder="Ex. Owner ID OR Coupon Code" id="live_search" autocomplete="off" style="border:1px solid black;">
    </div>
    <div class="col align-self-end">
  <div class="float-right">
        <button type="button" id="addOwnerBtn" class="btn btn-primary" data-toggle="modal" data-target="#ownerModal">Add Owner</button>
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
      <th scope="col">Department</th>
      <th scope="col">SD Code</th>
      <th scope="col">Max SD Credits Per Cut-Off</th>
      <!-- <th scope="col" class="text-center">Time Shift</th> -->
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
            <h1 class="modal-title fs-5" id="exampleModalLabel">SD Owner Details</h1>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body mx-2" style="height: 80vh; overflow-y:auto;">
            <div class="my-2">
              <select class="form-select" aria-label="Default select example" name="departmentID" id="selectDepartment">
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
              <input type="text" class="form-control" id="in_ownerId" name="ownerId" placeholder="Ex. 1234"style="border:0.5px solid black;">
            </div>
            <div class="my-2">
              <label for="exampleFormControlInput1" class="form-label">Full Name*</label>
              <input type="text" class="form-control" id="in_ownerName" name="ownerName" placeholder="Ex. Juan dela cruz"style="border:0.5px solid black;">
            </div>
            <div class="my-2">
              <label for="exampleFormControlInput1" class="form-label">Email</label>
              <input type="text" class="form-control" id="in_ownerEmail" name="ownerEmail" placeholder="Ex. juandelacruz@gmail.com"style="border:0.5px solid black;">
            </div>
            <div class="my-2">
              <label for="exampleFormControlInput1" class="form-label">SD Code*</label>
              <input type="text" class="form-control" id="sdCode" name="sdCode" placeholder="Ex. FNBSD1234"style="border:0.5px solid black;">
            </div>
            <div class="my-2">
              <label for="exampleFormControlInput1" class="form-label">Max SD Credits Per Cut-off*</label>
              <input type="text" class="form-control" id="maxSD" name="maxSD" placeholder="Ex. ₱1000"style="border:0.5px solid black;">
            </div>
            <div class="my-2">
                <span class="fw-bold text-uppercase text-dark">1ST Cut-Off Day*</span>
              </div>
            <div class="my-2">
              <span class="fw-semibold">Day Start*</span>
              <input type="text" class="form-control" id="first_day_start" name="first_Start" placeholder="Ex. 1234"style="border:0.5px solid black;">
            </div>
            <div class="my-2">
            <span class="fw-semibold">Day End*</span>
              <input type="text" class="form-control" id="first_day_end" name="first_End" placeholder="Ex. 1234"style="border:0.5px solid black;">
            </div>
            <div class="my-2">
              <span class="fw-bolder text-uppercase text-dark">2nd Cut-Off Day*</span>
            </div>
            <div class="my-2">
              <span class="fw-semibold">Day Start*</span>
              <input type="text" class="form-control" id="second_day_start" name="second_Start" placeholder="Ex. 1234"style="border:0.5px solid black;">
            </div>
            <div class="my-2">
            <span class="fw-semibold">Day End*</span>
              <input type="text" class="form-control" id="second_day_end" name="second_End" placeholder="Ex. 1234"style="border:0.5px solid black;">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" id="closeOwner" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" id="addOwner" class="btn btn-primary">ADD</button>
            <button type="button" id="updateOwner" update-id="" class="btn btn-primary">UPDATE</button>
          </div>
        </form>
        </div>
      </div>
    </div>
  <!-- Modal End -->
  
<script>
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
        //     autorefresh();
        // function autorefresh(){
        //  setInterval(function(){
        //   LoadTable();
        // },1000);
        // }
        LoadTable();
        function LoadTable() {
          var search = $('#live_search').val();
          // console.log("Search value:", search);
          if(search=="")
          {
              $.ajax({
                  url: "../process/sd_owner_table.php",
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
                  url: "../process/sd_owner_table.php",
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
                    data: formData+"&action=addSDOwner",
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
            $('#updateOwner').click(function(){
              var recordID = $(this).attr("update-id");
              // alert(recordID);
              var formData = $('#form_owner').serialize();
              $.ajax({
                    url: "../process/admin_action.php",
                    method: "POST",
                    data: formData+"&action=updateSDOwner&updateId="+recordID,
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
            $('#addOwnerBtn').click(function(){
              $("#addOwner").show();
              $("#updateOwner").hide();
              $('#selectDepartment option:eq(0)').prop('selected', true);
              $('#in_ownerId').val("");
              $('#in_ownerName').val("");
              $('#in_ownerEmail').val("");
              $('#couponCode').val("");
              $('#couponValue').val("");
              $("#updateOwner").attr("update-id", "");
            });
            
        });
</script>