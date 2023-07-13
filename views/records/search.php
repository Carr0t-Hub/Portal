<?php include('../common/header.php'); ?>
<?php include('../common/sidebar.php'); ?>

<main id="main" class="main">
    <div class="pagetitle"><!-- Start Page Title -->
      <h1> <i class="bi bi-files"></i><b> Records</b></h1>
  <div class="container">
    <div class="card shadow mb-4">
    <form action="../process/records/records.php" method="POST">
      <div class="card-header py-3 d-flex justify-content-between bg-dark text-light">
        <h5 class="m-2">
          <i class="fas fa-search"></i>
          <span><strong> Search Documents</strong></span>
        </h5>
        <div>
          <button id="btnSearchPrint" name="btnSearchPrint" class="btn btn-primary btn-md" data-bs-toggle="tooltip" data-bs-placement="left" title="Print Search Data"><i class="fas fa-print"></i> Print</button>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover table-responsive-sm table-responsive-md" width="100%" cellspacing="0" id="dataTable">
            <thead class="bg-dark text-light">
              <td>Date Received</td>
              <td>Reference Number</td>
              <td>Sender</td>
              <td>Document Type</td>
              <td>Action</td>
            </thead>
            <tbody>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td width="11%"><button id="btnViewSearch" name="btnViewSearch" class="btn btn-info btn-md" data-bs-toggle="tooltip" data-bs-placement="left" title="More Details"><i class="fas fa-eye"></i> View</button></td>
            </tbody>
          </table>
        </div>
      </div>
    </form>
    </div>
  </div>

  <?php include('../common/footer.php'); ?>
