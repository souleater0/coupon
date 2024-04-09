<div class="container">
    <div class="row mb-2">
        <div class="col">
            <span class="" id="basic-addon1">Search</span>
            <input type="text" class="form-control search w-100" placeholder="Ex. Department or Prefix" id="live_search"
                autocomplete="off">
        </div>
        <div class="col">
            <div class="float-end mb-2">
                <button type="button" id="addOwnerBtn" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#ownerModal">Add Clerk</button>
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
</div>