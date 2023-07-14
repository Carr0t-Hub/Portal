<?php include('../common/header.php'); ?>
<?php include('../common/sidebar.php'); ?>

<body onload="checkThisCategory()">
<div class="page-wrapper">
	<div class="page-content">
    <div class="row">
      <div class="col">
        <form method="POST" action="../process/records/records.php" id="saveOutgoingDocument" enctype="multipart/form-data">
          <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between">
              <div>
                <h6><img src="../assets/images/dts.png" class="img-circle" width="30" height="30"><span><b> Records Management Information System</span></b></h6>         
              </div>
              <div class="form-floating">
                <input type="text" class="form-control" name="documentDate" id="documentDate" value="<?= date('F d, Y');?>" hidden>
              </div>
              <div>
                <h6><i class="bx bx-calendar-alt"></i><strong> <?= date("l, jS \of F Y "); ?></strong></h6>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="text" class="form-control" value="Outgoing Document" readonly>
                      <label><strong>Category</strong></label>
                    <input type="text" class="form-control" name="documentCategory" id="documentCategory" value="dts_outgoing" onload="checkThisCategory()" hidden>
                  </div>
                </div>
                <div class="col-md-4">
                  <?php
                    $year = date('Y');
                    $month = date('m');
                    $res = dtsGetReferenceNo($mysqli, $month,$year);
                    $c = count($res) + 1;
                    $succession = sprintf("%03d", $c);
                    $reference = "I-".date("m-Y-") . $succession;
                  ?>
                  <div class="form-floating">
                    <input type="text" class="form-control" name="dtsReferenceNo" id="dtsReferenceNo" value="" readonly >
                    <label for="dtsReferenceNo"><strong>Reference Number</strong></label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-floating">
                    <button type="button" name="addInformation" class="button5 button4" data-bs-toggle="tooltip" data-bs-placement="left" title="Add Document Information" onclick="window.location.href= 'addInfo.php';"><i class="fas fa-plus"></i> Details</button>  
                  </div>
                </div>  
              </div>
              <div id="senderType">
                <div class="row mt-3">
                  <div class="col-sm-4 col-md-4 col-lg-4">
                    <div class="form-floating">
                      <select class="form-select" aria-label="Document Type" name="dtsDocumentType" id="dtsDocumentType" required>
                        <option value="" selected readonly>-- Please Select Type --</option>
                        <?php $docs = dtsGetDocuTypeList($mysqli);?>
                        <?php foreach ($docs as $key2):?>
                        <option value="<?= $key2['id'];?>"><?php echo $key2['documentType'];?></option>
                        <?php endforeach ?>
                      </select>            
                      <label for="dtsDocumentType"><strong>Document Type</strong><span class="text-danger"> *</span></label>
                    </div>
                  </div>
                  <div class="col-sm-4 col-md-4 col-lg-4">
                    <div class="form-floating">
                      <select class="form-select" aria-label="Document Sender" name="dtsDocumentSender" id="dtsDocumentSender" required>
                        <option value="" selected readonly>-- Please Select Receiver--</option>
                        <?php $senders = dtsGetSenderList($mysqli);?>
                        <?php foreach ($senders as $key):?>
                        <option value="<?= $key['id'];?>"><?php echo $key['firstName'].' '.$key['lastName'];?></option>
                        <?php endforeach ?>
                      </select>
                      <label for="dtsDocumentSender"><strong>Addressed To:</strong><span class="text-danger"> *</span></label>
                    </div>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-md-4">
                    <div class="form-floating">
                      <input type="text" class="form-control" name="dtsPersonConcerned" id="dtsPersonConcerned" value="Junel B. Soriano, PhD" readonly>
                      <label><strong>Sender</strong></label>
                    </div>
                  </div>
                </div>  
                <div class="row mt-3 ">
                  <div class="col-md-10">
                    <div class="form-floating">
                      <input type="file" accept=".pdf" class="form-control" name="dtsAttachmentsO" id="dtsAttachmentsO" value="" required>
                      <label for="dtsAttachmentsO"><strong>Attachments</strong><span class="text-danger">    <i>PDF file only *</i></span></label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mt-3">
                <div class="col-md-10">
                  <div class="form-floating">
                    <textarea name="dtsParticulars" id="dtsParticulars" cols="30" rows="5" class="form-control" value="" placeholder="Particulars / Subject" style="height: 100px;" required></textarea>
                    <label for="dtsParticulars"><strong>Subject</strong><span class="text-danger"> *</span> </label>
                  </div>
                </div>
              </div> 
              <div class="row mt-3">
                <div class="col-md-3">
                  <div class="form-floating">
                    <select class="form-select" aria-label="actionNeeded" name="dtsActionTaken" id="dtsActionTaken">
                      <option value="" selected readonly>-- Action Taken --</option>
                      <option value="Mailed">Mailed</option>
                      <option value="Delivered">Delivered</option>
                    </select>
                    <label for="ActionTaken" class="text-dark"><strong>Action Taken</strong><span class="text-danger"></span></label>
                  </div>
                </div>
              </div><br>
                    
              <!-- S T A R T  D Y N A M I C  F I E L D S --> 
                <?php $counter = 1; ?>
                <div class="dynamicFields mt-3">
                </div>
              <!-- E N D  D Y N A M I C  F I E L D S -->

              <div class="row mt-3">
                <div class="col-md-12">
                  <div>
                    <button type="submit" name="saveDocuments" class="button button2" data-bs-toggle="tooltip" data-bs-placement="left" title="Save Information"><i class="far fa-save"></i> Save</button>
                    <a href="history.php?code=O" title="Go Back" class="button1 button3"><i class="fas fa-backward"></i> Back</a>
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

 <!--<script type="text/javascript">
        $('#dtsPersonConcerned').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: false,
        });
</script>--> 
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
          case "dts_outgoing":
            referenceLetter = "O";
            break;
          //case "dts_outgoing":
            //referenceLetter = "O";
            //break;
          //case "dts_specialorder":
            //referenceLetter ="SO";
            //break;
          //case "dts_memorandum":
            //referenceLetter = "M";
            //break;
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
  function dtsSaveDocuments(){ 
    var dtsCategory = document.getElementById("documentCategory").value;
    var dtsReferenceNo_ = document.getElementById("dtsReferenceNo").value;
    var dtsParticulars_ = document.getElementById("dtsParticulars").value;
    var dtsActionTaken_ = document.getElementById("dtsActionTaken").value;
    var dtsPersonConcerned_ = document.getElementById("dtsPersonConcerned").value;
    
    //if(dtsCategory == "dts_incoming"){
      //var dtsDateReceived_ = document.getElementById("dtsDateReceived").value;
      //var dtsRemarks_ = "blank";
      //var dtsDocumentSender_ = document.getElementById("dtsDocumentSender").value;
      //var dtsDocumentType_ = document.getElementById("dtsDocumentType").value;
      //var dtsAttachments_ = document.getElementById("dtsAttachmentsI").value;
    //} 
    if(dtsCategory == "dts_outgoing"){
     var dtsDateReceived_ = "blank";
     var dtsRemarks_ = "blank";
     var dtsSender_ = document.getElementById("dtsSender").value;
     var dtsDocumentType_ = document.getElementById("dtsDocumentType").value;
     var dtsAttachments_ = document.getElementById("dtsAttachmentsO").value;
    }

    //if(dtsCategory == "dts_specialorder"){
      //var dtsDateReceived_ = "blank";
      //var dtsRemarks_ = "blank";
      //var dtsDocumentSender_ = "blank";
      //var dtsDocumentType_ = "blank";
      //var dtsAttachments_ = document.getElementById("dtsAttachmentsSO").value
    //}

    //if(dtsCategory == "dts_memorandum"){
      //var dtsDateReceived_ = "blank";
      //var dtsRemarks_ = "blank";
      //var dtsDocumentSender_ = "blank";
      //var dtsDocumentType_ = "blank";
      //var dtsAttachments_ = document.getElementById("dtsAttachmentsM").value
    //}

    if(dtsReferenceNo_ == "" || dtsSender_ == "" || dtsDocumentType_ == "" || dtsDateReceived_== "" || dtsAttachments_== "" || dtsParticulars_== ""  ){
        $(".modal-header #headerMsg").val("Invalid!");
        $(".modal-body #message").val("Missing Field/s");
        $("#negative").modal("show");
    } 
    
    else {
      // $(".modal-header #PheaderMsg").val("Success!");
      // $(".modal-body #Pmessage").val("Information Saved!");
      // $("#positive").modal("show");
      // windows.location.href = "../records/history.php?code=I";
      document.getElementById('saveOutgoingDocument').submit();
    }
  }

// C U S T O M  S C R I P T  F O R  S H O W / H I D E  F I E L D S
  $(document).ready(function(){
    $('#documentCategory').on('change', function(){
      //if (this.value == 'dts_incoming'){
        //$('#incomingDateAttachment').show();
        //$('#outgoingAttachment').hide();
        //$('#specialOrderAttachment').hide();
        //$('#memorandumAttachment').hide();
      //} 
      if (this.value == 'dts_outgoing') {
        $('#outgoingAttachment').show();
        $('#incomingDateAttachment').hide();
        $('#specialOrderAttachment').hide();
        $('#memorandumAttachment').hide();
      }
      //if (this.value == 'dts_specialorder') {
        //$('#specialOrderAttachment').show();
        //$('#incomingDateAttachment').hide();
        //$('#outgoingAttachment').hide();
        //$('#memorandumAttachment').hide();
      //}
      //if (this.value == 'dts_memorandum') {
        //$('#memorandumAttachment').show();
        //$('#specialOrderAttachment').hide();
        //$('#incomingDateAttachment').hide();
        //$('#outgoingAttachment').hide();
      //}
    });
  });

</script>
<?php include('../common/footer.php');?>