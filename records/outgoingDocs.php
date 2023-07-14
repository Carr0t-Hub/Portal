<?php include('../common/header.php'); ?>
<?php include('../common/sidebar.php'); ?>

<?php
  $outgoingDocs = $_POST['referenceNo'];
  $res = getOutgoingDetails($mysqli, $outgoingDocs);
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
                <i data-toggle="modal" data-target="#modal-register_recieve"><img src="../assets/img/outgoingicon.png" class="img-circle" width="50" height="50"></i>
                <span><strong> Outgoing Documents</strong></span>
              </h6>
              <div>
                <a href="AddOutgoing.php" title="Add Outgoing Document"><i><img src="../assets/images/outgoingmail.png" class="img-circle" width="30" height="30"></i></a>
                <a href="history.php?code=O" class="button1 button3" data-bs-toggle="tooltip" data-bs-placement="left" title="Go Back"><i class="bx bx-arrow-back"></i> Back</a>
              </div>
            </div>
            <div class="card-body">
              <div class="docu-detail">
                <div class="det-br">
                  <table class="table table-bordered table-hover table-responsive-sm table-responsive-md display" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thread-dark">
                      <tr>
                        <th>Reference Number: </th>
                        <td><?php echo strtoupper($key['referenceNo']); ?></td>
                      </tr>
                      <tr>
                        <th>Date:</th>
                        <td><?php echo strtoupper($key['createdDateTime']); ?></td>
                      </tr>
                      <tr>
                        <th>Addressed To:</th>
                        <td><?php echo strtoupper($key['sender']);?></td>
                      </tr>
                      <tr>
                        <th>Document Type:</th>
                        <td><?php echo strtoupper($key['documentType']); ?></td>
                      </tr>
                      <tr>
                        <th>Particulars/Subject:</th>
                        <td><?php echo strtoupper($key['particulars']); ?></td>
                      </tr>
                      <tr>
                        <th>Sender</th>
                        <td><?php echo strtoupper($key['person']); ?></td>
                      </tr>
                      <tr>
                        <th>Action Taken:</th>
                        <td><?php echo strtoupper($key['actionTaken']); ?></td>
                      </tr>
                      <tr>
                        <th>Status:</th>
                        <td><?php echo strtoupper($key['StatusType']); ?></td>
                      </tr>
                      <tr>
                        <th>Attachment:</th>
                        <th><button class="btn btn-outline-primary" onclick="window.open('../uploads/records/outgoing/<?php echo $res1['fileName'].'_'.$res1['size'].$res1['id'].'.'. $res1['fileExtension']?>','_blank')" class="btn btn-primary"><i class="bi bi-download"></i> <?php
                        echo $res1['fileName'].'_'.$res1['size'].$res1['id'].'.'. $res1['fileExtension'];?></a>
                      </tr>
                    </thead>
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

<script type="text/javascript">
  $('#Sender').select2({
    theme: "bootstrap-5",
    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
    placeholder: $(this).data('placeholder'),
    closeOnSelect: false,
  });

  function dtsSaveOutgoingSender(){
    if (id == "id"){
      var Sender = document.getElementById("Sender").value;
      var dtsActionTaken = document.getElementById("dtsActionTaken").value;
    }
  }

  if(Sender_ == "" || dtsActionTaken_ == ""){
    $(".modal-header #headMsg").val("invalid!");
    $(".modal-body #message").val("Missing Field/s");
    $("#negative").modal("show");
  } else {
    document.getElementById('SaveOutgoingSender').submit();
  }
</script>

<?php include('../common/footer.php'); ?>