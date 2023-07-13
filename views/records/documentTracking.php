<?php include('../common/header.php'); ?>
<?php include('../common/sidebar.php'); ?>

<main id="main" class="main">
    <div class="pagetitle"><!-- Start Page Title -->
      <h1><img src="../assets/img/dts.png" class="img-circle" width="50" height="50"><b>Document Tracking System</b></h1>
    </div>
    <div class="a">
      <button type="button" name="viewIncoming" class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="left" title="Create Document" onclick="window.location.href= 'docuTracking.php';"><i class="bi bi-folder-plus"></i> </button>
      <button type="button" name="viewIncoming" class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="left" title="View Information" onclick="window.location.href= 'history.php';"><i class="fas fa-history"></i>History</button>
    </div>
      </div>
    </div><!-- End Page Title -->
    <div class="content-wrapper">
    <div class="container-fluid">
     <br>
      <div class="card mb-6">
        <div class="card-header">
          <i class="fa fa-table" ></i> Documents in all offices
          
        </div>
     </div>
<!-- Start Table-->
     <div class="card-body">
        <?php  
                 $connect = mysqli_connect("localhost", "root", "", "villa_dblgu");  
                $query = "SELECT * FROM tbl_documents ORDER BY Date_created";  
                 $result = mysqli_query($connect, $query);  
                 ?> 
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th style="background:#8c8c8c;color:white;">Reference Number</th>
                  <th style="background:#8c8c8c;color:white;">Category</th>
                  <th style="background:#8c8c8c;color:white;">Document Type</th>
                  <th style="background:#8c8c8c;color:white;">Status</th>
                  <th style="background:#8c8c8c;color:white;">Sender</th>
                  <th style="background:#8c8c8c;color:white;">Date Filed</th>
                  <th style="background:#8c8c8c;color:white;">Receiver</th>
                
                </tr>
              </thead>
              <tbody>
              <?php 
                $incoming = "I";
                $outgoing = "O";
                $special = "SO";
                $memo = "M";
              ?>
              <?php 
                $incomingDocus = dtsGetIncomingDocus($mysqli);
                $outgoingDocus = dtsGetOutgoingDocus($mysqli);
                $specialOrderDocus = dtsGetSpecialOrderDocus($mysqli);
                $memorandumDocus = dtsGetMemorandumDocus($mysqli);
              ?>
              <?php foreach($incomingDocus as $v):?>
              <tr>
                  <td><?= $v['referenceNo'] ?></td>
                  <td><?= $v['category']?></td>
                  <td><?= $v['documentType']?></td>
                  <td>NULL</td>
                  <td><?= $v['sender']?></td>
                  <td><?= date_format(date_create($v['createdDateTime']),'M d, Y - H:i A')?></td>
                  <td>NULL</td>
              </tr>
              <?php endforeach ?>
              <?php foreach($outgoingDocus as $o):?>
              <tr>
                  <td><?= $o['referenceNo']?></td>
                  <td><?= $o['category'] ?></td>
                  <td><?= $o['documentType']?></td>
                  <td>NULL</td>
                  <td><?= $o['sender']?></td>
                  <td><?= date_format(date_create($o['createdDateTime']),'M d, Y - H:i A')?></td>
                  <td>NULL</td>   
              </tr>
              <?php endforeach ?>
              <?php foreach($specialOrderDocus as $so):?>
              <tr>
                  <td><?= $so['referenceNo']?></td>
                  <td><?= $so['category'] ?></td>
                  <td></td>
                  <td>NULL</td>
                  <td></td>
                  <td><?= date_format(date_create($so['createdDateTime']),'M d, Y - H:i A')?></td>
                  <td>NULL</td>
              </tr>
              <?php endforeach ?>
              <?php foreach($memorandumDocus as $m):?>
              <tr>
                  <td><?= $m['referenceNo']?></td>
                  <td><?= $m['category'] ?></td>
                  <td></td>
                  <td>NULL</td>
                  <td></td>
                  <td><?= date_format(date_create($m['createdDateTime']),'M d, Y - H:i A')?></td>
                  <td>NULL</td>
              </tr>
              <?php endforeach ?>
            </tbody>
            <tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <?php include('../common/footer.php'); ?>
            