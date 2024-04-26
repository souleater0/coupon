<h2 class="modal-title text-center">FOOD STUB OWNER</h2><br><br>
<div  style="width: 100% !important;">
  <div class="row mb-2">
    <div class="col">
    <span class="" id="basic-addon1">Search</span>
    <input type="text" class="form-control search w-100" placeholder="Ex. Owner ID OR Coupon Code" id="live_search" autocomplete="off" style="border: 1px solid black;">
    </div>
 <div class="col align-self-end">
  <div class="float-right ">
         <button type="button" id="addOwnerBtn" class="btn btn-primary float-end" data-toggle="modal" data-target="#ownerModal">Add Food Stub</button>
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
      <th scope="col">Time Base</th>
      <th scope="col" class="text-center">Time Shift</th>
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
            <h3 class="modal-title fs-5" id="exampleModalLabel">Food Stub Owner Details</h3>
            <button type="button" class="btn btn-close" data-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark-large"></i></button>
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
              <input type="text" class="form-control" id="in_ownerId" name="ownerId" placeholder="Ex. 1234" style="border:0.5px solid black;">
            </div>
            <div class="my-2">
              <label for="exampleFormControlInput1" class="form-label">Full Name*</label>
              <input type="text" class="form-control" id="in_ownerName" name="ownerName" placeholder="Ex. Juan dela cruz"style="border:0.5px solid black;" readonly>
            </div>
            <div class="my-2">
              <label for="exampleFormControlInput1" class="form-label">Email</label>
              <input type="text" class="form-control" id="in_ownerEmail" name="ownerEmail" placeholder="Ex. juandelacruz@gmail.com"style="border:0.5px solid black;" readonly>
            </div>
            <div class="my-2">
              <label for="exampleFormControlInput1" class="form-label">Coupon Code*</label>
              <input type="text" class="form-control" id="couponCode" name="ownerCoupon" placeholder="Ex. FNBFS2024001"style="border:0.5px solid black;">
            </div>
            <div class="my-2">
              <label for="exampleFormControlInput1" class="form-label">Coupon Value*</label>
              <input type="text" class="form-control" id="couponValue" name="ownerCouponValue" placeholder="Ex. ₱60" style="border:0.5px solid black;">
            </div>
            <div class="my-2">
              <span class="fw-semibold text-uppercase ">Base Time:*</span>
              <select class="form-select" aria-label="Default select example" name="ownerTimeBase" id="TimeBase" onchange="displayIndividualTime()">
                <option disabled >Select Time Base</option>
                <option value="1" selected>Department Time</option>
                <option value="2">Individual Time</option>
              </select>
            </div>
            <div class="my-2 d-none" id="time_holder">
              <div class="row">
                <div class="col-12 text-center ">
                  <span class="fw-bold text-uppercase ">Individual Time</span>
                </div>
                <div class="col-6">
                  From Time:<input type="time" name="from_Time" id="from_Time" class="w-100 form-control"style="border:0.5px solid black;">
                </div>
                <div class="col-6">
                  To Time:<input type="time" name="to_Time" id="to_Time" class="w-100 form-control"style="border:0.5px solid black;">
                </div>
              </div>
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
  function displayIndividualTime(){
    var timebase_opt = $("#TimeBase").val();
    // alert(timebase_opt);
    if (timebase_opt == "1") {
        $("#time_holder").addClass('d-none'); // Hide individual time fields
    } else {
        $("#time_holder").removeClass('d-none'); // Show individual time fields
    }
  }
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
        success: function(response){
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
            $('#addOwnerBtn').click(function(){
              $("#addOwner").show();
              $("#updateOwner").hide();
              $('#in_ownerId').prop('readonly', false);
              $('#selectDepartment option:eq(0)').prop('selected', true);
              $('#in_ownerId').val("");
              $('#in_ownerName').val("");
              $('#in_ownerEmail').val("");
              $('#couponCode').val("");
              $('#couponValue').val("");
              // $('#TimeBase').val(1);
              $('#TimeBase option:eq(1)').prop('selected', true);
              displayIndividualTime();
              $("#updateOwner").attr("update-id", "");
            });

            $('#in_ownerId').keyup(function(){
              var recordID = $("#in_ownerId").val();
              //alert(recordID);
                $.ajax({
                  url: "../process/admin_action.php",
                  method: "POST",
                  data: {recordID:recordID, action: "fetchEmployee"},
                  dataType: "json",
                  success: function(response) {
                      if(response.success==true){
                          toastr.success(response.message);
                        
                          $('#selectDepartment').val(response.data.dep_id);
                          $('#in_ownerId').val(response.data.staff_id);
                          $('#in_ownerName').val(response.data.owner_name);
                          $('#in_ownerEmail').val(response.data.owner_email);

                          $("#updateOwner").attr("update-id", recordID);
                          updateCouponPrefix();

                      }else{
                          toastr.error(response.message);
                          $('#selectDepartment').val("");
                          $('#in_ownerId').val("");
                          $('#in_ownerName').val("");
                          $('#in_ownerEmail').val("");
                          $('#TimeBase').val("");
                          
                      }
                  }
              });
            });
            
        });
</script>