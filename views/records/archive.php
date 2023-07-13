<?php 
include('../common/header.php');
include('../common/sidebar.php');
?>

<main id="main" class="main">
  <div class="pagetitle"><!-- Start Page Title -->
  <div class="container">
    <div class="card shadow mb-4">
    <form method="POST" action="../process/records/records.php#" class="c" id="saveIncomingDocument" enctype="multipart/form-data">
      <div class="card-header py-3 d-flex justify-content-between" style="background-color: rgba(245, 40, 145, 0.02);color:white;">
        <div>
        <h4> <i data-toggle="modal" data-target="#modal-register_recieve"><img src="../assets/img/archive.png" class="img-circle" width="60" height="60"></i><span class="text-dark"><b> Document Archiving System</span></b></h4> 
        </div>
    </div>
    <div class="card-body">
        <div class="row-group">
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover table-responsive-sm table-responsive-md" width="100%" cellspacing="0" id="dataTable">
                <thead class="bg-light text-dark">
                  <td><b>Reference Number</td>
                  <td><b>Date</td>
                  <td><b>Sender</td>
                  <td><b>Document Type</td>
                  <td><b>Action</td>
                </thead>
                <tbody>
                  <td>DATA</td>
                  <td>DATA</td>
                  <td>DATA</td>
                  <td>DATA</td>
                  <td><button class="btn btn-info btn-md btn-block"><i class="fa fa-eye"></i> View</button></td>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
</div>
<?php include('../common/footer.php');?>