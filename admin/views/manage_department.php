<h2 class="modal-title text-center">SALARY DEDUCTION OWNER</h2><br><br>
<div  style="width: 100% !important;">
    <div class="row mb-2">
        <div class="col">
            <span class="" id="basic-addon1">Search</span>
            <input type="text" class="form-control search w-100" placeholder="Ex. Department or Prefix" id="live_search" autocomplete="off"style="border:0.5px solid black;">
        </div>
        <div class="col align-self-end">
            <div class="float-right">
                <button type="button" id="addDepartmentBtn" class="btn btn-primary" data-toggle="modal"
                    data-target="#depModal">Add Department</button>
            </div>
        </div>
    </div>
    <div class="report-container">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Department Name</th>
                    <th scope="col">Department Prefix</th>
                    <th scope="col">From Time</th>
                    <th scope="col">To Time</th>
                    <th scope="col" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody id="departmentTable">
            </tbody>
        </table>
    </div>
</div>
    <!-- Modal -->
    <div class="modal fade" id="depModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
        <form id="form_department">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Department Details</h1>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body mx-2">
             <div class="my-2">
              <label for="in_Department" class="form-label">Department Name</label>
              <input type="text" class="form-control" id="in_Department" name="in_Department" placeholder="Ex. Marketing"style="border:0.5px solid black;">
            </div>
            <div class="my-2">
              <label for="in_Department" class="form-label">Department Prefix</label>
              <input type="text" class="form-control" id="in_Department_prefix" name="in_prefix" placeholder="Ex. Marketing"style="border:0.5px solid black;">
            </div>
            <div class="my-2">
              <div class="row">
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
            <button type="button" id="closeDep" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" id="addDep" class="btn btn-primary">ADD</button>
            <button type="button" id="updateDep" update-id="" class="btn btn-primary">UPDATE</button>
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
        $("#live_search").keyup(function(){
            var search = $(this).val();
            LoadTable();
        });
        LoadTable();
        function LoadTable() {
          var search = $('#live_search').val();
          if(search=="")
          {
              $.ajax({
                  url: "../process/department_table.php",
                  type: "POST",
                  cache: false,
                  data:{
                      search:search
                      },
                  success:function(data){
                      $('#departmentTable').html(data);
                  }
              });
          }else{
              $.ajax({
                  url: "../process/department_table.php",
                  type: "POST",
                  cache: false,
                  data:{
                      search:search
                      },
                  success:function(data){
                      $('#departmentTable').html(data);
                  }
                  });
            }
         }
         $('#addDep').click(function(){
              var formData = $('#form_department').serialize();
              // alert (formData);
              $.ajax({
                  url: "../process/admin_action.php",
                  method: "POST",
                  data: formData+"&action=addDepartment",
                  dataType: "json",
                  success: function(response) {
                      if(response.success==true){
                        $("#closeDep").click();
                        toastr.success(response.message);
                        LoadTable();
                      }else{
                          toastr.error(response.message);
                      }
                  }
              });
            
          });
          $('#updateDep').click(function(){
              var recordID = $(this).attr("update-id");
              // alert(recordID);
              var formData = $('#form_department').serialize();
              $.ajax({
                    url: "../process/admin_action.php",
                    method: "POST",
                    data: formData+"&action=updateDepartment&updateId="+recordID,
                    dataType: "json",
                    success: function(response) {
                        if(response.success==true){
                            toastr.success(response.message);
                            LoadTable();
                            $("#closeDep").click();
                        }else{
                            toastr.error(response.message);
                        }
                    }
                });
            });
        $('#addDepartmentBtn').click(function(){
            $("#addDep").show();
            $("#updateDep").hide();

            $('#in_Department').val("");
            $('#in_Department_prefix').val("");
            $('#from_Time').val("");
            $('#to_Time').val("");
        });
    });
</script>