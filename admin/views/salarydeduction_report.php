<?php
    require("../time_zone.php");
?>
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
<h2 class="modal-title text-center text-uppercase">salary deduction reports</h2><br><br>
<div style="width:100% !important;">
<div class="input-daterange">
      <div class="row">
        <div class="col-9">
            <div class="row">
                <div class="col-3">
                    <div>
                    <label for="start">Month Year:</label>
                        <div class="w-full">
                        <input type="month" id="startMonth" name="start" min="2024-01" value="<?php echo $current_MONTH_YEAR?>"/>
                        </div>
                    </div>
                    <div>
                    <label for="departmentID">Department:</label>
                        <div class="w-full">
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
                    </div>
                </div>
                <div class="col-6">
                    <label for="departmentID">Cut-Off:</label>
                    <div class="w-full">
                    <select name="departmentID" id="select_cutOFF">
                        <option value="1">1ST Cut-Off</option>
                        <option value="2">2ND Cut-Off</option>
                    </select>
                    <select id="select_TYPE">
                        <option value="s_type1">Summary</option>
                        <option value="s_type2">Break-Down</option>
                    </select>
                    </div>
                    <div>
                    <label for="selectPerson">Clerk:</label>
                    <div class="w-full">
                    <select class="form-select" id="selectPerson" name="selectPerson">
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
            </div>
        </div>
        <div class="col-3 align-self-end">
            <div class="float-right">
                <button type="button" id="generateReport" class="btn btn-primary px-2">Generate</button>
                <button type="button" id="generateCSV" class="btn btn-success px-2">Export CSV</button>
            </div>
        </div>
      </div>
</div>
</br></br>
<div class="report-container">
<table class="table table-hover" id="SD_Table">
        <thead id="s_type1">
            <tr> 
            <th scope="col" class="text-uppercase">Full Name</th>
            <th scope="col" class="text-uppercase">Department</th>
            <th scope="col" class="text-uppercase">SD CODE</th>
            <th scope="col" class="text-uppercase">Total Deducted</th>
            <th scope="col" class="text-uppercase">CUT-OFF</th>
            <th scope="col" class="text-uppercase">Date</th>
            </tr>
        </thead>
        <thead id="s_type2">
            <tr> 
            <th scope="col" class="text-uppercase">Full Name</th>
            <th scope="col" class="text-uppercase">Department</th>
            <th scope="col" class="text-uppercase">SD CODE</th>
            <th scope="col" class="text-uppercase">SD Amount</th>
            <th scope="col" class="text-uppercase">Receipt No.</th>
            <th scope="col" class="text-uppercase">CUT-OFF</th>
            <th scope="col" class="text-uppercase">DATE</th>
            <th scope="col" class="text-uppercase">Clerk</th>
            </tr>
        </thead>
        <tbody id="sdReport">
                
        </tbody>
</table>
</div>
</div>
<script>
$(document).ready(function(){
    $(document).ready(function(){
        $('#s_type2').hide();
            // Handle dropdown change event
            $('#select_TYPE').change(function(){
                var selectedThead = $(this).val();
                // Hide all thead sections first
                $('thead').hide();
                // Show the selected thead section
                $('#' + selectedThead).show();
            });
    });
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
        var MonthYear = $("#startMonth").val();
        var dep_ID = $("#selectDepartment").val();
        var selectCutoff = $("#select_cutOFF").val();
        var selectType = $("#select_TYPE").val();
        var clerk_ID = $("#selectPerson").val();

        $.ajax({
                url: "../process/sd_report_table.php",
                type: "POST",
                cache: false,
                data:{
                    MonthYear:MonthYear,
                    dep_ID: dep_ID,
                    selectCutoff: selectCutoff,
                    selectType:selectType,
                    clerk_ID:clerk_ID,
                    action: 'reportGenerate'
                    },
                success:function(data){
                    if (data.trim().startsWith("No Result Found")) {
                        toastr.error("No Results Found!");
                    }else{
                        toastr.success("Report Generated Successfully");
                        
                    }
                    $('#sdReport').html(data);
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
