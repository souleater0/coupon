<style>
      .report-container{
        max-height: 500px;
        overflow-y: scroll;
        width: 100%;
      }
      .report-container table {
        width: 100%;
      }

      .report-container thead {
        position: sticky;
        top: 0px;
        background-color: white;
      }
</style>
<h2 class="modal-title text-center">FOOD STUB REPORTS</h2><br><br>
<div style="width:100% !important;">
<div class="input-daterange row align-items-end">
   <div class="col-3">
    <div class="col">
        <div class="col">
        From<input type="date" id="startDatePicker" name="fromDate" class="form-control" value="<?php echo $current_date ?>" style="border: 1px solid black;" />
        </div>
        <div class="col">
        To<input type="date" id="endDatePicker" name="toDate" class="form-control" value="<?php echo $current_date ?>" style="border: 1px solid black;"/>
        </div>
    </div>
   </div>
   <div class="col-3">
    <div class="col">
        <div class="col">
        From Time:<input type="time" id="timeIn" class="w-100 form-control" style="border: 1px solid black;">
        </div>
        <div class="col">
        To Time:<input type="time" id="timeOut" class="w-100 form-control" style="border: 1px solid black;">
        </div>
    </div>
   </div>
   <div class="col-3">
    <div class="col">
        <div class="col">
            Department
            <select class="form-select" aria-label="Default select example" name="departmentID" id="selectDepartment" onchange="updateCouponPrefix()">
            <option value="" selected>All</option>
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
        <div class="col">
            Person
            <select class="form-select" id="selectPerson">
                <option value="" selected>All</option>
                <?php 
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $sql = "SELECT * FROM admins where role_id = '1'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
            ?>
            <option value="<?php echo $row["id"];?>"><?php echo $row["display_name"].' - '.$row["location"];?></option>
            <?php
            }}?>
            </select>
        </div>
    </div>
   </div>
   <div class="col-3">
   <button type="button" id="generateReport" class="btn btn-primary px-2">Generate</button>
   <button type="button" id="generateCSV" class="btn btn-success px-2">Export CSV</button>
   </div>
</div>
</br></br>
<div class="report-container">
<table class="table table-hover">
        <thead>
            <tr> 
            <th scope="col">Coupon Code</th>
            <th scope="col">Coupon Value</th>
            <th scope="col">Full Name</th>
            <th scope="col">Department Name</th>
            <th scope="col">Claimed Date</th>
            <th scope="col">Remarks</th>
            <th scope="col">Clerk</th>
            </tr>
        </thead>
        <tbody id="tableReport">
                
        </tbody>
</table>
</div>
<div class="sticky-top top-0 ">
    <span class="text-dark text-uppercase font-weight-bold">Record: </span><span class="text-dark text-uppercase font-weight-bold" id="recordCount">0</span>
    <span class="ml-5 text-dark text-uppercase font-weight-bold">Total Value: </span><span class="text-dark text-uppercase font-weight-bold" id="totalCouponValue">0</span>
</div>

<script>
$(document).ready(function(){
    function calculateTotalCouponValue() {
        var total = 0;
        $('#tableReport tr').each(function() {
            var couponValue = parseFloat($(this).find('td:eq(1)').text());
            if (!isNaN(couponValue)) {
                total += couponValue;
            }
        });
        $('#totalCouponValue').text(total.toFixed(2)); // Assuming coupon value is a decimal
    }
    toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
    $('#generateReport').click(function(){
        var start_date = $("#startDatePicker").val();
        var end_date = $("#endDatePicker").val();
        var department = $("#selectDepartment").val();
        var time_In = $("#timeIn").val();
        var time_Out = $("#timeOut").val();
        var personID = $("#selectPerson").val();
        
        $.ajax({
                url: "../process/report_table.php",
                type: "POST",
                cache: false,
                data:{
                    start_date:start_date,
                    end_date: end_date,
                    department: department,
                    time_In:time_In,
                    time_Out:time_Out,
                    personID: personID,
                    action: 'reportGenerate'
                    },
                success:function(data){
                    // alert(data);
                    if (data.trim().startsWith("No Result Found")) {
                        toastr.error("No Results Found!");
                    }else{
                        toastr.success("Report Generated Successfully");
                    }
                    $('#tableReport').html(data);
                    var tbody = document.getElementById('tableReport');
                    var tdElements = tbody.querySelectorAll('tr');
                    var numberOfTd = tdElements.length;
                    $("#recordCount").text(numberOfTd);
                    calculateTotalCouponValue();
                }
        });
    });
    $('#generateCSV').click(function(){
    var start_date = $("#startDatePicker").val();
    var end_date = $("#endDatePicker").val();
    var department = $("#selectDepartment").val();
    var time_In = $("#timeIn").val();
    var time_Out = $("#timeOut").val();
    var personID = $("#selectPerson").val();
    
    $.ajax({
        url: "../process/csv_table.php",
        type: "POST",
        cache: false,
        data: {
            start_date: start_date,
            end_date: end_date,
            department: department,
            time_In: time_In,
            time_Out: time_Out,
            personID: personID,
            action: 'csvGenerate'
        },
        success: function(data){
            if (data.trim().startsWith("No Result Found")) {
                toastr.error("No data available for the selected criteria.");
            } else {
            // Parse the CSV data
            var csvData = new Blob([data], { type: 'text/csv' });
            var csvUrl = window.URL.createObjectURL(csvData);

            // Create a temporary link element
            var link = document.createElement('a');
            link.href = csvUrl;
            link.setAttribute('download', 'ECSFOODSTAB_' + new Date().toISOString().slice(0, 10).replace(/:/g, '-') + '.csv');
            document.body.appendChild(link);

            // Trigger the download
            link.click();

            // Cleanup
            document.body.removeChild(link);
            window.URL.revokeObjectURL(csvUrl);

            toastr.success("CSV has been Regenerated!");
            }

        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            toastr.error("Failed to generate CSV!");
        }
    });
});

    
});
    
</script>
