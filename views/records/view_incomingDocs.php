<?php
    include('../common/header.php');
    include('../common/sidebar.php');
?>


<main id="main" class="main">
  <div class="pagetitle"><!-- Start Page Title -->
    <div class="container">
    <div class="card-header d-flex justify-content-between" style="background-color:rgba(23, 95, 10, 1);">
    <h5 class="m-0 font-weight-bold">
        <i data-toggle="modal" data-target="#modal-register_recieve"><img src="../assets/img/incomingDocs.png" class="img-circle" width="50" height="50"></i>
        <span class="text-light col-form-label"><strong> Incoming Documents</strong></span>
    </h5>
    <div>
        <a href="AddDocument.php" title="Add Incoming Document"><i><img src="../assets/img/addDoc.png" class="img-circle" width="50" height="50"></i></a>
        <a href="AddOutgoing.php" title="Add Outgoing Document"><i><img src="../assets/img/outgoingmail.png" class="img-circle" width="50" height="50"></i></a>
        <a href="AddSpecialOrder.php" title="Add Special Order"><i><img src="../assets/img/so.png" class="img-circle" width="50" height="50"></i></a>
        <a href="AddMemorandum.php" title="Add Memorandum"><i><img src="../assets/img/memo.png" class="img-circle" width="50" height="50"></i></a>
        <a href="history.php?code=I" class="btn btn-danger btn-md" data-bs-toggle="tooltip" data-bs-placement="left" title="Go Back"><i class="fas fa-backward"></i> Back</a>
    </div>
</div>

<!--   T   A   B   L   E    -->

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-responsive-sm table-responsive-md display" id="dataTable" width="100%" cellspacing="0">
          <thead class="thead-dark">
                <tr>
                <th class="table-secondary">Reference No.</th>
                <th class="table-secondary">Date Received</th>
                <th class="table-secondary">Sender</th>
                <th class="table-secondary">Particulars/ Subject</th>
                <th class="table-secondary">Attachment</th>
                <th class="table-secondary">Person Concerned</th>
                <th class="table-secondary">Action Needed</th>
                <th class="table-secondary">Remarks</th>
                <th class="table-secondary">Status</th>
                <th class="table-secondary">Action</th>
       </tr>
              </thead>
              <?php foreach($res as $key) : ?>
              <tbody>
                <tr>
                    <td><?php echo strtoupper($key['referenceNo']); ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <?php endforeach;?>
                </tr>
              </tbody>
        </table>
    </div>
    </div>
</div>
      



<?php include('../common/footer.php');?>








