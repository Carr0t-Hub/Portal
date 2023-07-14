<?php include('../common/header.php'); ?>
<?php include('../common/sidebar.php'); ?>

<body onload="checkThisCategory()">
<div class="page-wrapper">
	<div class="page-content">
    <div class="row">
      <div class="col">
        <form method="POST" action="../process/records/records.php" id="saveMemorandum" enctype="multipart/form-data">
          <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between">
              <div>
                <h6><img src="../assets/images/dts.png" class="img-circle" width="20" height="20"><span><b> Records Management Information System</span></b></h6>
              </div>
              <div>
                <h6><i class="bx bx-calendar-alt"></i><strong> <?= date("l, jS \of F Y "); ?></strong></h6>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="text" class="form-control" name="documentDate" id="documentDate" value="<?= date('F d, Y');?>" readonly>
                    <label for="documentDate"><strong>Date</strong></label>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="text" class="form-control" value="Memorandum" readonly>
                    <input type="text" class="form-control" name="documentCategory" id="documentCategory" value="dts_memorandum" onload="checkThisCategory()" hidden>
                    <label for="documentCategory"><strong>Category</strong></label>
                  </div>
                </div>

                <div class="col-md-4">
                  <?php
                    $year = date('Y');
                    $res = dtsGetReferenceNo($mysqli,$year);
                    $c = count($res) + 1;
                    $succession = sprintf("%03d", $c);
                    $reference = "I-".date("m-Y-") . $succession;
                  ?>
                  <div class="form-floating">
                    <input type="text" class="form-control" name="dtsReferenceNo" id="dtsReferenceNo" value="" readonly >
                    <label for="dtsReferenceNo"><strong>Reference Number</strong></label>
                  </div>
                </div>
              </div>
              <div class="row mt-3 addAttachment">
                <div class="col-sm-12 col-md-12 col-lg-12">
                  <div class="form-floating">
                    <input type="file" accept=".pdf" class="form-control" name="dtsAttachmentsM" id="dtsAttachmentsM" value="" required>
                    <label for="dtsAttachmentsM"><strong>Document </strong><span class="text-danger"> (Attach Document)   <i>PDF file only *</i></span></label>
                  </div>
                </div>
              </div>

              <div class="row mt-3">
                <div class="col-md-12">
                  <div class="form-floating">
                    <textarea name="dtsParticulars" id="dtsParticulars" cols="30" rows="5" class="form-control" value="" placeholder="Particulars / Subject" style="height: 100px;" required></textarea>
                    <label for="dtsParticulars"><strong>Subject</strong><span class="text-danger"> *</span> </label>
                  </div>
                </div>
              </div><br>

              <div class="col-md-5">
                <input type="text" class="form-control" name="dtsaddressedTo" id="dtsaddressedTo" value="To All" hidden>
              </div>

              <!-- S T A R T  D Y N A M I C  F I E L D S --> 
              <?php $counter = 1; ?>
              <div class="dynamicFields mt-3"></div>
              <!-- E N D  D Y N A M I C  F I E L D S -->

              <div class="row mt-3">
                <div class="col-md-12">
                  <div>
                    <button type="submit" name="saveDocuments" class="button button2" data-bs-toggle="tooltip" data-bs-placement="left" title="Save Information"><i class="far fa-save"></i> Save</button>
                    <a href="history.php?code=SO" title="Go Back" class="button1 button3"><i class="fas fa-backward"></i> Back</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script type="text/javascript">
  // for reference number 
  function checkThisCategory(){
    var categoryTable = document.getElementById("documentCategory").value;
    $.ajax({
      url: '../process/records/records.php',
      type: 'post',
      data: {dtscategoryTable:categoryTable},
      dataType: "json",
      success:function(response){
        switch (categoryTable){
          case "dts_memorandum":
          referenceLetter = "M";
          break;
        }
        console.log(referenceLetter);
        var today = new Date();
        var month = String(today.getMonth() + 1).padStart(2, '0');
        var year = today.getFullYear();
        var currentDate = month + "-" + year;
        var number = (response.length + 1).toLocaleString('en-US', {minimumIntegerDigits: 3, useGrouping:false});
        var referenceNo = referenceLetter+"-"+currentDate+"-"+number;
        document.getElementById("dtsReferenceNo").value = referenceNo;
        $(".dynamicFields .dynamicDetails").remove();
        putFields();
      }
    });
  }

  // saving
  function dtsSaveMemorandum(){ 
    var dtsCategory = document.getElementById("documentCategory").value;
    var dtsReferenceNo_ = document.getElementById("dtsReferenceNo").value;
    var dtsParticulars_ = document.getElementById("dtsParticulars").value;
    if(dtsCategory == "dts_memorandum"){
      var dtsDateReceived_ = "blank";
      var dtsRemarks_ = "blank";
      var dtsaddressedTo = document.getElementById("dtsaddressedTo").value;
      var dtsAttachments_ = document.getElementById("dtsAttachmentsM").value;
    }

    if(dtsReferenceNo_ == "" || dtsaddressedTo == "" || dtsDateReceived_== "" || dtsAttachments_== "" || dtsParticulars_== ""  ){
      $(".modal-header #headerMsg").val("Invalid!");
      $(".modal-body #message").val("Missing Field/s");
      $("#negative").modal("show");
    } else {
      document.getElementById('saveMemorandum').submit();
    }
  }

  // C U S T O M  S C R I P T  F O R  S H O W / H I D E  F I E L D S
  $(document).ready(function(){
    $('#documentCategory').on('change', function(){
      if (this.value == 'dts_memorandum') {
      $('#memorandumAttachment').show();
      $('#specialOrderAttachment').hide();
      $('#incomingDateAttachment').hide();
      $('#outgoingAttachment').hide();
      }
    });
  });
  
</script>

<?php include('../common/footer.php'); ?> 