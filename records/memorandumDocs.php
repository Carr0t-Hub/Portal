<?php include('../common/header.php'); ?>
<?php include('../common/sidebar.php'); ?>

<?php
  $memorandumDocs = $_POST['referenceNo'];
  $res = getMemorandumDetails($mysqli, $memorandumDocs);
?>
<?php foreach($res as $key) : ?>
  <?php $res1 = getAttachmentByID($mysqli, $key['attachment']); ?>

<div class="page-wrapper">
	<div class="page-content">
    <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-header d-flex justify-content-between">
            <h6 class="font-weight-bold">
              <i><img src="../assets/images/memo1.png" class="img-circle" width="30" height="30"></i>
              <span><strong> Memorandum</strong></span>
            </h6>
            <div>
              <a href="AddMemorandum.php" title="Add Memorandum"><i><img src="../assets/images/memo.png" class="img-circle" width="50" height="50"></i></a>
              <a href="history.php?code=M" class="button1 button3" data-bs-toggle="tooltip" data-bs-placement="left" title="Go Back"><i class="bx bx-arrow-back"></i> Back</a>
            </div>
          </div>
          <div class="docu-detail">
            <div class="det-br">
              <table class="table table-bordered table-hover table-responsive-sm table-responsive-md display" id="dataTable" width="100%" cellspacing="0">
                <tr>
                  <th>Reference Number:</th>
                  <td><?php echo strtoupper($key['referenceNo']); ?></td>
                </tr>
                <tr>
                  <th>Date:</th>
                  <td><?php echo strtoupper($key['createdDateTime']); ?></td>
                </tr>
                <tr>
                  <th>Particulars/ Subject:</th>
                  <td><?php echo strtoupper($key['particulars']); ?></td>
                </tr>
                <tr>
                  <th>Attachment:</th>
                  <td><button class="btn btn-outline-primary" onclick="window.open('../uploads/records/memorandum/<?php echo $res1['fileName'].'_'.$res1['size'].$res1['id'].'.'. $res1['fileExtension']?>','_blank')" class="btn btn-primary"><i class="bi bi-download"></i> <?php echo $res1['fileName'].'_'.$res1['size'].$res1['id'].'.'. $res1['fileExtension'];?></button>
                  </td>
                </tr>
                <tr>
                  <th>Person Concerned:</th>
                  <td><?php echo strtoupper($key['person']);?></td>
                </tr>
                <tr>
                  <th>Status:</th>
                  <td><?php echo strtoupper($key['StatusType']);?></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php endforeach; ?>

<?php include('../common/footer.php');