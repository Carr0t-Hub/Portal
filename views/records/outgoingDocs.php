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
  $outgoingDocs = $_POST['referenceNo'];
  $res = getOutgoingDetails($mysqli, $outgoingDocs);
?>

<?php foreach ($res as $key) : ?>
  <?php $res1 = getAttachmentByID($mysqli, $key['attachment']); ?>
  <main id="main" class="main">
  <div class="pagetitle"><!-- Start Page Title -->
  <!-- <form method="POST" action="../process/records/records.php" id="SaveOutgoingSender" enctype="multipart/form-data">-->
    <div class="container">
        <div class="card-header d-flex justify-content-between" style="background-color:rgba(23, 95, 10, 1);">
          <h5 class="m-0 font-weight-bold">
            <i data-toggle="modal" data-target="#modal-register_recieve"><img src="../assets/img/outgoingicon.png" class="img-circle" width="50" height="50"></i>
            <span class="text-light col-form-label"><strong> Outgoing Documents</strong></span>

          </h5>
          <div>
                        <a href="AddOutgoing.php" title="Add Outgoing Document"><i><img src="../assets/img/outgoingmail.png" class="img-circle" width="50" height="50"></i></a>
                      
            <!-- Button trigger modal 
            <button type="button" class="btn btn-primary"  onclick="saveStatusReceivedocs();">
              Receive Document 
            </button>
            <input type="text" class="form-control" name="incomingID" id="incomingID" value="<?php echo $key['documentID']; ?>" readonly>-->
            <!-- Button trigger modal -->
            <a href="history.php?code=O" class="button1 button3" data-bs-toggle="tooltip" data-bs-placement="left" title="Go Back"><i class="fas fa-backward"></i> Back</a>
            <!-- Modal -->
          </div>
        </div>
        <div class="docu-detail">
        <div class="docu-detail">
          <div class="det-br">
            <table class="table table-bordered table-hover table-responsive-sm table-responsive-md display" id="dataTable" width="100%" cellspacing="0">
              <thead class="thread-dark">
                <tr>
                <th style="background-color:rgba(227, 218, 194, 0.81);">Reference Number: </th>
                <td style="background-color:rgba(17, 4, 18, 0.08);"><?php echo strtoupper($key['referenceNo']); ?></td>
                </tr>
                <tr>
                <th style="background-color:rgba(227, 218, 194, 0.81);">Date:</th>
                <td style="background-color:rgba(17, 4, 18, 0.08);"><?php echo strtoupper($key['createdDateTime']); ?></td>
                </tr>
                <tr>
                <th style="background-color:rgba(227, 218, 194, 0.81);">Addressed To:</th>
                <td style="background-color:rgba(17, 4, 18, 0.08);"><?php echo strtoupper($key['sender']);?></td>
                </tr>
                <tr>
                <th style="background-color:rgba(227, 218, 194, 0.81);">Document Type:</th>
                <td style="background-color:rgba(17, 4, 18, 0.08);"><?php echo strtoupper($key['documentType']); ?></td>
                </tr>
                <tr>
                <th style="background-color:rgba(227, 218, 194, 0.81);">Particulars/Subject:</th>
                <td style="background-color:rgba(17, 4, 18, 0.08);"><?php echo strtoupper($key['particulars']); ?></td>
                </tr>
                <tr>
                <th style="background-color:rgba(227, 218, 194, 0.81);">Sender</th>
                <td style="background-color:rgba(17, 4, 18, 0.08);"><?php echo strtoupper($key['person']); ?></td>
                </tr>
                <tr>
                <th style="background-color:rgba(227, 218, 194, 0.81);">Action Taken:</th>
                <td style="background-color:rgba(17, 4, 18, 0.08);"><?php echo strtoupper($key['actionTaken']); ?></td>
                </tr>
                <tr>
                <th style="background-color:rgba(227, 218, 194, 0.81);">Status:</th>
                <td style="background-color:rgba(17, 4, 18, 0.08);"><?php echo strtoupper($key['StatusType']); ?></td>
                </tr>
                <tr>
                <th style="background-color:rgba(227, 218, 194, 0.81);">Attachment:</th>
                <th style="background-color:rgba(17, 4, 18, 0.08);"><button class="btn btn-outline-primary" onclick="window.open('../uploads/records/outgoing/<?php echo $res1['fileName'].'_'.$res1['size'].$res1['id'].'.'. $res1['fileExtension']?>','_blank')" class="btn btn-primary"><i class="bi bi-download"></i> <?php
                 echo $res1['fileName'].'_'.$res1['size'].$res1['id'].'.'. $res1['fileExtension'];?></a>
            <?php endforeach; ?>
                </th>
              </tr>
              </thead>
            </table>
          </div>
       </div>
    </form>
  </div>
</main>
<script type="text/javascript">
        $('#Sender').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: false,
        });
</script>
<script>
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
<?php include('../common/footer.php'); ?>