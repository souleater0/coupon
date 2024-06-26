<h2 class="modal-title text-center text-uppercase ">list of clerk</h2><br><br>
<div style="width: 100% !important;">
    <div class="row mb-2">
        <div class="col">
            <span class="" id="basic-addon1">Search</span>
            <input type="text" class="form-control search w-100" placeholder="Ex. Email or Name"
                id="live_search" autocomplete="off" style="border:0.5px solid black;">
        </div>
        <div class="col align-self-end">
            <div class="float-right">
                <button type="button" id="addClerkBtn" class="btn btn-primary" data-toggle="modal"
                    data-target="#clerkModal">Add Clerk</button>
            </div>
        </div>
    </div>
    <div class="table-container">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Email</th>
                    <th scope="col">Display Name</th>
                    <th scope="col">Location</th>
                    <th scope="col" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody id="clerkTable">

            </tbody>
        </table>
    </div>
        <!-- Modal -->
        <div class="modal fade" id="clerkModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
        <form id="form_clerk">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Clerk Details</h1>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body mx-2">
          <div class="my-2">
              <label for="exampleFormControlInput1" class="form-label">Display Name</label>
              <input type="text" class="form-control" id="ownerName" name="ownerName" placeholder="Ex. juandelacruz" style="border: 1px solid black;" required>
            </div>
            <div class="my-2">
              <label for="exampleFormControlInput1" class="form-label">Username</label>
              <input type="text" class="form-control" id="in_ownerEmail" name="ownerEmail" placeholder="Ex. juandelacruz@gmail.com" style="border: 1px solid black;" required>
            </div>
            <div class="my-2">
              <label for="exampleFormControlInput1" class="form-label">Password</label>
              <div class="d-flex">
              <input type="password" class="form-control" id="in_Password" name="in_Password" placeholder="Ex. *******" style="border: 1px solid black;" required>
              <button type="button" class="btn btn-info" id="show_Pass">SHOW</button>
              </div>
            </div>
            <div class="my-2">
              <label for="exampleFormControlInput1" class="form-label">Confirm Password</label>
              <div class="d-flex">
              <input type="password" class="form-control" id="in_ConPassword" name="in_ConPassword" placeholder="Ex. *******" style="border: 1px solid black;" required>
              <button type="button" class="btn btn-info" id="show_ConPass">SHOW</button>
              </div>
            </div>
             <div class="my-2">
              <label for="exampleFormControlInput1" class="form-label">Location</label>
              <input type="text" class="form-control" id="in_Location" name="in_Location" placeholder="Ex. ESKINA" style="border: 1px solid black;" required>
            </div>
          <div class="modal-footer">
            <button type="button" id="closeClerk" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" id="addClerk" class="btn btn-primary">ADD</button>
            <button type="button" id="updateClerk" update-id="" class="btn btn-primary">UPDATE</button>
          </div>
        </form>
        </div>
      </div>
    </div>
  <!-- Modal End -->
</div>
<script>
$(document).ready(function(){
  $("#show_Pass").click(function (event) {
    event.preventDefault();
    if($("#in_Password").attr("type")=="password")
    {
      $("#show_Pass").text("HIDE");
      $("#in_Password").attr('type','text');
    }else{
      $("#show_Pass").text("SHOW");
      $("#in_Password").attr('type','password');
    }
  });
  $("#show_ConPass").click(function (event) {
    event.preventDefault();
    if($("#in_ConPassword").attr("type")=="password")
    {
      $("#show_ConPass").text("HIDE");
      $("#in_ConPassword").attr('type','text');
    }else{
      $("#show_ConPass").text("SHOW");
      $("#in_ConPassword").attr('type','password');
    }
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
            url: "../process/clerk_table.php",
            type: "POST",
            cache: false,
            data:{
                search:search
                },
            success:function(data){
                // alert(data);
                // toastr.success("Record Retrieve Successful");
                $('#clerkTable').html(data);
            }
            });
    }else{
        $.ajax({
            url: "../process/clerk_table.php",
            type: "POST",
            cache: false,
            data:{
                search:search
                },
            success:function(data){
                // alert(data);
                $('#clerkTable').html(data);
            }
            });
      }
    }
  $("#live_search").keyup(function(){
      var search = $(this).val();
      LoadTable();
  });
  $('#addClerk').click(function(){
    var formData = $('#form_clerk').serialize();
    $.ajax({
        url: "../process/admin_action.php",
        method: "POST",
        data: formData+"&action=addClerk",
        dataType: "json",
        success: function(response) {
            if(response.success==true){
                toastr.success(response.message);
                LoadTable();
                $("#closeClerk").click();
            }else{
                toastr.error(response.message);
            }
        }
    });
  });
  $('#updateClerk').click(function(){
    var recordID = $(this).attr("update-id");
    // alert(recordID);
    var formData = $('#form_clerk').serialize();
    $.ajax({
          url: "../process/admin_action.php",
          method: "POST",
          data: formData+"&action=updateClerk&updateId="+recordID,
          dataType: "json",
          success: function(response) {
              if(response.success==true){
                  toastr.success(response.message);
                  LoadTable();
                  $("#closeClerk").click();
              }else{
                  toastr.error(response.message);
              }
          }
      });
  });

  $('#addClerkBtn').click(function(){
    $("#addClerk").show();
    $("#updateClerk").hide();
    $('#ownerName').val("");
    $('#in_ownerEmail').val("");
    $('#in_Password').val("");
    $('#in_ConPassword').val("");
    $('#in_Location').val("");
    $("#updateClerk").attr("update-id", "");
    LoadTable();
  });
});
</script>