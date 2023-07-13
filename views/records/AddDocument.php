<?php include('../common/header.php'); ?>
<?php include('../common/sidebar.php'); ?>
<style>
/* Save button */
.button {border-radius: 12px;
  background-color: #4CAF50; /* Green */
  border: none;
  color: white;
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
.button2:hover {border-radius: 12px;
  background-color: #008CBA;
  color: white;
/* Back button */
}
.button1 {border-radius: 12px;
  background-color: #f44336; /* RED */
  border: none;
  color: white;
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
  background-color:#555555;
  color: white;
}
.button4:hover{border-radius: 12px;
  background-color: #008CBA;
  color: white;
}
/* Add Details button */
.button5 {border-radius: 12px;
  background-color: #008CBA; /* RED */
  border: none;
  color: white;
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
.button4:hover{border-radius: 12px;
  background-color:#4CAF50;
  color: white;
}
/* Clock Display button */
.buttonClock {border-radius: 12px;
  background-color: white; 
  color: black; 
  border: 2px solid #008CBA;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
  -webkit-transition-duration: 0.4s; /* Safari */
  transition-duration: 0.4s;
}
.btn:hover{border-radius: 12px;
  background-color: #008CBA;
  color: white;
}
</style>
<body onload="checkThisCategory()">
<main id="main" class="main">
  <div class="pagetitle"><!-- Start Page Title -->
    <form method="POST" action="../process/records/records.php" id="saveIncomingDocument" enctype="multipart/form-data">
      <div class="card shadow mb-4">
          <div class="card-header py-3 d-flex justify-content-between" style="background-color:rgba(23, 95, 10, 1);">
            <div>
              <h4><img src="../assets/img/dts.png" class="img-circle" width="50" height="50"><span class="text-light"><b> Records Management Information System</span></b></h4>         
          </div>
          <div class="form-floating">
              <input type="text" class="form-control" name="documentDate" id="documentDate" value="<?= date('F d, Y');?>" hidden>
              <label for="documentDate" hidden><strong></strong></label>
            </div>
          <div class="col-sm-12 col-lg-6 d-flex justify-content-lg-end">
            <button type="button" class="buttonClock btn" value="" id="" data-bs-toggle="modal"><strong><i class="fas fa-calendar-day"></i> <?= date("l jS \of F Y "); ?></strong></button>
        </div>
    </div>

<div class="card-body" style="background-color:rgba(17, 4, 18, 0.08);">
    <div class="row">
    <div class="col-md-4">
    <div class="form-floating">
      <input type="text" class="form-control" value="Incoming Document" readonly>
      <label><strong>Category</strong></label>
      <input type="text" class="form-control" name="documentCategory" id="documentCategory" value="dts_incoming" onload="checkThisCategory()" hidden>
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
              <!-- <input type="text" class="form-control" name="dtsReferenceNo" id="dtsReferenceNo" value="<?= $reference?>" readonly> -->
              <input type="text" class="form-control" name="dtsReferenceNo" id="dtsReferenceNo" value="" readonly >
              <label for="dtsReferenceNo"><strong>Reference Number</strong></label>
            </div>
          </div>
        
        <div class="col-md-4">
              <div class="form-floating">
                <button type="button" name="addInformation" class="button5 button4" data-bs-toggle="tooltip" data-bs-placement="left" title="Add Document Information" onclick="window.location.href= 'addInfo.php';"><i class="fas fa-plus"></i> Details</button>  
              </div>
            </div>  
        <div id="senderType">
          <div class="row mt-3">
            <div class="col-sm-5 col-md-5 col-lg-5">
              <div class="form-floating">
                <select class="form-select" aria-label="Document Sender" name="dtsDocumentSender" id="dtsDocumentSender" required>
                  <option value="" selected readonly>-- Please Select Sender --</option>
                  <?php $senders = dtsGetSenderList($mysqli);?>
                  <?php foreach ($senders as $key):?>
                    <option value="<?= $key['id'];?>"><?php echo $key['firstName'].' '.$key['lastName'].' - '.$key['division'];?></option>
                  <?php endforeach ?>
                </select>
                <label for="dtsDocumentSender"><strong>Sender</strong><span class="text-danger"> *</span></label>
              </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6">
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
          </div>
      
          <div class="row mt-3">
            <div class="col-sm-5 col-md-5 col-lg-5">
              <div class="form-floating">
                        <input type="text" class="form-control form-control-sm" id="dtsDateReceived" name="dtsDateReceived" 
                        value="<?= date('Y-m-d'); ?>" readonly >
                        <label for="dtsDateReceived"><strong>Date Received</strong><span class="text-danger"> *</span></label>
                    </div>
              </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
              <div class="form-floating">
                <input type="file" accept="application/pdf" class="form-control" name="dtsAttachmentsI" id="dtsAttachmentsI" value="" >
                <label for="dtsAttachmentsI"><strong>Attachments</strong><span class="text-danger">    <i>PDF file only *</i></span></label>
              </div>
            </div>
          </div>
        </div>
            <!--<div id="outgoingAttachment" style="display: none;">
              <div class="row mt-3 addAttachment">
                <div class="col-sm-12 col-md-12 col-lg-12">
                  <div class="form-floating">
                    <input type="file" accept=".pdf" class="form-control" name="dtsAttachmentsO" id="dtsAttachmentsO" value="" required>
                    <label for="dtsAttachmentsO"><strong>Attachments</strong><span class="text-danger"> *</span></label>
                  </div>
                </div>
              </div>
            </div>

            <div id="specialOrderAttachment" style="display: none;">
              <div class="row mt-3 addAttachment">
                <div class="col-sm-12 col-md-12 col-lg-12">
                  <div class="form-floating">
                    <input type="file" accept=".pdf" class="form-control" name="dtsAttachmentsSO" id="dtsAttachmentsSO" value="" required>
                    <label for="dtsAttachmentsSO"><strong>Attachments</strong><span class="text-danger"> *</span></label>
                  </div>
                </div>
              </div>
            </div>
            
            <div id="memorandumAttachment" style="display: none;">
              <div class="row mt-3 addAttachment">
                <div class="col-sm-12 col-md-12 col-lg-12">
                  <div class="form-floating">
                  <input type="file" accept=".pdf" class="form-control" name="dtsAttachmentsM" id="dtsAttachmentsM" value="" required>
                    <label for="dtsAttachmentsM"><strong>Attachments</strong><span class="text-danger"> *</span></label>
                  </div>
                </div>
              </div>
            </div>-->
        
        <div class="row mt-3">
          <div class="col-md-11">
            <div class="form-floating">
              <textarea name="dtsParticulars" id="dtsParticulars" cols="30" rows="5" class="form-control" value="" placeholder="Particulars / Subject" style="height: 100px;" required></textarea>
              <label for="dtsParticulars"><strong>Subject</strong><span class="text-danger"> *</span> </label>
            </div>
          </div>
        </div> 
        <!-- S T A R T  D Y N A M I C  F I E L D S --> 
          <?php $counter = 1; ?>
          <div class="dynamicFields mt-3">
          </div>
        <!-- E N D  D Y N A M I C  F I E L D S -->
        <div class="row mt-3">
          <div class="col-md-12">
            <div>
              <button type="submit" name="saveDocuments" class="button button2" data-bs-toggle="tooltip" data-bs-placement="left" title="Save Information"><i class="far fa-save"></i> Save</button>
              <a href="history.php?code=I" title="Go Back" class="button1 button3"><i class="fas fa-backward"></i> Back</a>
            </div>
          </div>
      </div>
    </form>
  </div>
  </div>
  </div>
  </div>
</div>
</main>
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
          case "dts_incoming":
            referenceLetter = "I";
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
  </script>

<script type="text/javascript">
function checkDetails(){
  var dtsParticulars = document.getElementById("dtsParticulars").value;
  if(dtsParticulars != ""){
    $.ajax({
      url: '../process/records/records.php',
      type: 'post',
      data: {dtsParticulars:dtsParticulars},
      dataType: 'json',
      success:function(response){
        if(response == "yes"){
          document.getElementById("dtsParticulars").value = "";
          $(".modal-header #headerMsg").val("Invalid!");
            $(".modal-body #message").val("Document already in the list.");
            $("#negative").modal("show");
        }
        if(response == "no"){
          dtsSaveDocuments(dtsParticulars);
        }
      }
    });
  }
  else {
    $(".modal-header #headerMsg").val("Invalid!");
    $(".modal-body #message").val("Missing field/s.");
    $("#negative").modal("show");
  }
}

// saving
  function dtsSaveDocuments(){ 
    var dtsCategory = document.getElementById("documentCategory").value;
    var dtsReferenceNo_ = document.getElementById("dtsReferenceNo").value;
    var dtsParticulars_ = document.getElementById("dtsParticulars").value;
    var dtsPersonConcerned_ = "blank";
    
    if(dtsCategory == "dts_incoming"){
      var dtsDateReceived_ = document.getElementById("dtsDateReceived").value;
      var dtsRemarks_ = "blank";
      var dtsDocumentSender_ = document.getElementById("dtsDocumentSender").value;
      var dtsDocumentType_ = document.getElementById("dtsDocumentType").value;
      var dtsAttachments_ = document.getElementById("dtsAttachmentsI").value;
    } 
   // if(dtsCategory == "dts_outgoing"){
    //  var dtsDateReceived_ = "blank";
    //  var dtsRemarks_ = "blank";
     // var dtsDocumentSender_ = document.getElementById("dtsDocumentSender").value;
     // var dtsDocumentType_ = document.getElementById("dtsDocumentType").value;
     // var dtsAttachments_ = document.getElementById("dtsAttachmentsO").value;
    //}

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

    if(dtsReferenceNo_ == "" || dtsDocumentSender_ == "" || dtsDocumentType_ == "" || dtsDateReceived_== "" || dtsAttachments_== "" || dtsParticulars_== ""  ){
        $(".modal-header #headerMsg").val("Invalid!");
        $(".modal-body #message").val("Missing Field/s");
        $("#negative").modal("show");
    } 
    
    else {
      // $(".modal-header #PheaderMsg").val("Success!");
      // $(".modal-body #Pmessage").val("Information Saved!");
      // $("#positive").modal("show");
      // windows.location.href = "../records/history.php?code=I";
      document.getElementById('saveIncomingDocument').submit();
    }
  }

// C U S T O M  S C R I P T  F O R  S H O W / H I D E  F I E L D S
  $(document).ready(function(){
    $('#documentCategory').on('change', function(){
      if (this.value == 'dts_incoming'){
        $('#incomingDateAttachment').show();
        $('#outgoingAttachment').hide();
        $('#specialOrderAttachment').hide();
        $('#memorandumAttachment').hide();
      } 
      //if (this.value == 'dts_outgoing') {
        //$('#outgoingAttachment').show();
        //$('#incomingDateAttachment').hide();
        //$('#specialOrderAttachment').hide();
        //$('#memorandumAttachment').hide();
      //}
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

<?php include('../common/footer.php'); ?> 