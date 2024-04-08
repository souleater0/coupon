<div class="container">
    <div class="row mb-2">
        <div class="col">
            <span class="" id="basic-addon1">Search</span>
            <input type="text" class="form-control search w-100" placeholder="Ex. Email or Name"
                id="live_search" autocomplete="off">
        </div>
        <div class="col">
            <div class="float-end mb-2">
                <button type="button" id="addOwnerBtn" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#ownerModal">Add Clerk</button>
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
                    <th scope="col" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody id="clerkTable">

            </tbody>
        </table>
    </div>
        <!-- Modal -->
        <div class="modal fade" id="ownerModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
        <form id="form_owner">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Clerk Details</h1>
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
            <button type="button" id="updateOwner" update-id="" class="btn btn-primary">UPDATE</button>
          </div>
        </form>
        </div>
      </div>
    </div>
  <!-- Modal End -->
</div>