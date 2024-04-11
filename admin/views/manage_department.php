<div class="container">
    <div class="row mb-2">
        <div class="col">
            <span class="" id="basic-addon1">Search</span>
            <input type="text" class="form-control search w-100" placeholder="Ex. Department or Prefix" id="live_search"
                autocomplete="off">
        </div>
        <div class="col">
            <div class="float-end mb-2">
                <button type="button" id="addDepartmentBtn" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#depModal">Add Department</button>
            </div>
        </div>
    </div>
    <div class="report-container">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Department Name</th>
                    <th scope="col">Department Prefix</th>
                    <th scope="col">Action</th>
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
        <form id="form_owner">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Department</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body mx-2">
             <div class="my-2">
              <label for="exampleFormControlInput1" class="form-label">Department</label>
              <input type="text" class="form-control" id="in_Department" name="in_Department" placeholder="Ex. Marketing">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" id="closeDep" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

        $('#addDepartmentBtn').click(function(){
            $("#addDep").show();
            $("#updateDep").hide();  
        });
    });
</script>