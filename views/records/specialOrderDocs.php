<?php 
  include('../common/header.php');
  include('../common/sidebar.php');
?>
<style>
/* Back button */
.button1 {border-radius: 12px;
  background-color: white;
  border: 2px solid #f44336;
  color: black;
  padding: 10px 25px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
  -webkit-transition-duration: 0.4s; /* Safari */
  transition-duration: 0.4s;
}
.button3:hover{border-radius: 12px;
  background-color:#f44336;
  color: white;
} 
</style>
<?php
  $specialOrderDocs = $_POST['referenceNo'];
  $res = getSpecialOrderDetails($mysqli, $specialOrderDocs);
?>

<?php foreach ($res as $key) : ?>
  <?php $res1 = getAttachmentByID($mysqli, $key['attachment']); ?>
  <main id="main" class="main">
    <div class="pagetitle"><!-- Start Page Title -->
      <div class="container">
      <div class="card-header d-flex justify-content-between" style="background-color:rgba(23, 95, 10, 1);">
            <h5 class="m-0 font-weight-bold">
              <i><img src="../assets/img/so.png" class="img-circle" width="50" height="50"></i>
              <span class="text-light col-form-label"><strong> Special Order</strong></span>
            </h5>
            <div>
            <a href="AddSpecialOrder.php" title="Add Special Order"><i><img src="../assets/img/so.png" class="img-circle" width="50" height="50"></i></a>
              <a href="history.php?code=SO" class="button1 button3" data-bs-toggle="tooltip" data-bs-placement="left" title="Go Back"><i class="fas fa-backward"></i> Back</a>
            </div>
          </div>
            <div class="docu-detail">
              <div class="det-br">
                <table class="table table-bordered table-hover table-responsive-sm table-responsive-md display" id="dataTable" width="100%" cellspacing="0">
                  <tr>
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
                  </tr>
                </table>
              </div>
            </div>
  </main>
<?php endforeach; ?>

<?php include('../common/footer.php');?>