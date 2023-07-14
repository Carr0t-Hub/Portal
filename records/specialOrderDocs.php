<?php include('../common/header.php'); ?>
<?php include('../common/sidebar.php'); ?>

<?php
  $specialOrderDocs = $_POST['referenceNo'];
  $res = getSpecialOrderDetails($mysqli, $specialOrderDocs);
?>

<?php foreach ($res as $key) : ?>
  <?php $res1 = getAttachmentByID($mysqli, $key['attachment']); ?>
  <div class="page-wrapper">
    <div class="page-content">
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-header d-flex justify-content-between">
              <h6 class="m-0 font-weight-bold">
                <i><img src="../assets/images/so.png" class="img-circle" width="30" height="30"></i>
                <span><strong> Special Order</strong></span>
              </h6>
              <div>
                <a href="AddSpecialOrder.php" title="Add Special Order"><i><img src="../assets/images/so.png" class="img-circle" width="30" height="30"></i></a>
                <a href="history.php?code=SO" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="left" title="Go Back"><i class="bx bx-arrow-back"></i> Back</a>
              </div>
            </div>
            <div class="card-body">
              <div class="docu-detail">
                <div class="det-br">
                  <table class="table table-bordered table-hover table-responsive-sm table-responsive-md display" id="dataTable" width="100%" cellspacing="0">
                    <tr>
                      <th style="background-color:rgba(227, 218, 194, 0.81);">Reference Number:</th>
                      <td style="background-color:rgba(17, 4, 18, 0.08);"><?php echo strtoupper($key['referenceNo']); ?></td>
                    </tr>
                    <tr>
                      <th style="background-color:rgba(227, 218, 194, 0.81);">Date:</th>
                      <td style="background-color:rgba(17, 4, 18, 0.08);"><?php echo strtoupper($key['createdDateTime']); ?></td>
                    </tr>
                    <tr>
                      <th style="background-color:rgba(227, 218, 194, 0.81);">Particulars/ Subject:</th>
                      <td style="background-color:rgba(17, 4, 18, 0.08);"><?php echo strtoupper($key['particulars']); ?></td>
                    </tr>
                    <tr>
                      <th style="background-color:rgba(227, 218, 194, 0.81);">Attachment:</th>
                      <td style="background-color:rgba(17, 4, 18, 0.08);"><button class="btn btn-outline-primary" onclick="window.open('../uploads/records/specialorder/<?php echo $res1['fileName'].'_'.$res1['size'].$res1['id'].'.'. $res1['fileExtension']?>','_blank')" class="btn btn-primary"><i class="bi bi-download"></i> <?php echo $res1['fileName'].'_'.$res1['size'].$res1['id'].'.'. $res1['fileExtension'];?></button></th>
                    </tr>
                    <tr>
                      <th style="background-color:rgba(227, 218, 194, 0.81);">Status:</td>
                      <td style="background-color:rgba(17, 4, 18, 0.08);"><?php echo strtoupper($key['StatusType']); ?></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>

<?php include('../common/footer.php');?>