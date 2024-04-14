<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>sdowner</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="description" content="" />
  <link rel="icon" href="favicon.png">
   <link href="assets/bootstrap533.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/toastr.min.css">
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <script src="assets/jquery.min.js"></script>
    <script src="assets/bootstrap.min.js"></script>

</head>
<body>
  <div class="container">
        <h1 class="text-center">SD CREDIT LOGS</h1><br><br>
        <div class="row">
            <div class="col-md-12">
                <ul class="list-group">
                    <li class="list-group-item">SD CODE: <span class="text-muted">[738173812]</span></li>
                    <li class="list-group-item">OWNER NAME:<span class="text-muted">[PONCIO PILATO]</span></li>
                    <li class="list-group-item">DEPARTMENT: <span class="text-muted">[ESKINA]</span></li>
                    <li class="list-group-item">CREDIT BALANCE: <span class="text-muted">[999]</span></li>
                </ul>
            </div>
        </div>
    </div>
    <!--  --><br><br>
    <div class="container">
        <h1 class="text-left">TRASACTION DATE</h1>
        <div class="row">
            <div class="col-md-12">
                <div class="report-container">
<table class="table table-hover">
        <thead>
            <tr> 
            <th scope="col">DATE</th>
            <th scope="col">ACTION</th>
            </tr>
        </thead>
        <tbody id="tableReport">
          <tr>
          <td>04 / 12 / 2024</td>
                  <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#view_transactionbtn">VIEW</button></td>
          </tr>
          <td>04 / 13 / 2024</td>
                  <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#view_transactionbtn">VIEW</button></td>
          </tr>
        </tbody>
</table>
</div>
            </div>
        </div>
    </div>

<!-- Modal -->
<div class="modal fade" id="view_transactionbtn" tabindex="-1" role="dialog" aria-labelledby="viewmdl_title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewmdl_title">TRANSACTIONS</h5>
        <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <!-- -->
        
        <div class="row">
            <div class="col-md-12">
                <div class="report-container">
<table class="table table-hover">
        <thead>
            <tr> 
            <th scope="col">DATE /TIME</th>
            <th scope="col">SD AMOUNT</th>
            <th scope="col">RECEIPT</th>
            <th scope="col">ACTION</th>
            </tr>
        </thead>
        <tbody id="tableReport">
          
                  <td>04 / 12 / 2024 11:59:00</td>
                  <td>100</td>
                  <td>ESKPS0001</td> 
                  <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#void_inputmdl">
VOID
</button></td>
<!-- PANG POP UP SA VOID PIN -->
<div class="modal fade" id="void_inputmdl" tabindex="-1" role="dialog" aria-labelledby="voidpn_ttl" aria-hidden="true" style="overflow-y: hidden;">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="voidpn_ttl">Scan Void Pin</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <input type="password" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!--  -->
                  <tr>
                    <td>00 / 00 / 00 10:59:00</td>
                  <td>100</td>
                  <td>ESKPS0002</td> 
                  <td>VOID APPROVED</td> 
                  </tr>  

        </tbody>
</table>
</div>
            </div>
        </div>
 
      <!--  -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
       
      </div>
    </div>
  </div>
</div>
</body>
</html>
