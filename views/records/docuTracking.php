<?php include('../common/header.php'); ?>
<?php include('../common/sidebar.php'); ?>

<main id="main" class="main">
  <div class="pagetitle"><!-- Start Page Title -->
    <div class="container">
      <div class="card shadow mb-4">
        <form method="POST" action="../process/records/records.php" class="c" id="saveIncomingDocument" enctype="multipart/form-data">
          <div class="card-header py-3 d-flex justify-content-between">
            <div>
              <h4><img src="../assets/img/dts.png" class="img-circle" width="50" height="50"><span class="text-dark"><b>Document Tracking System</span></b></h4> 
              <h6 class="text-danger">Note: All <span class="text-danger">*</span> are required.</h6>
            </div>
            <div>
              <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#saveDocumentTracking">
                <i class="fa-solid fa-floppy-disk"></i> Save Documents
              </button>
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
                  <select class="form-select" id="documentCategory" name="documentCategory" aria-label="documentCategory" onchange="checkThisCategory()">
                    <option disabled selected>-- Please Choose --</option>
                    <option value="dts_incoming">Incoming</option>
                    <option value="dts_outgoing">Outgoing</option>
                    <option value="dts_specialorder">Special Order</option>
                    <option value="dts_memorandum">Memorandum</option>
                  </select>
                  <label for="documentCategory"><strong>Category</strong><span class="text-danger"> *</span></label>
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
                  <input type="text" class="form-control" name="dtsReferenceNo" id="dtsReferenceNo" value="" readonly>
                  <label for="dtsReferenceNo"><strong>Reference Number</strong></label>
                </div>
              </div>
            </div>
            <div id="senderType">
              <div class="row mt-3">
                <div class="col-sm-4 col-md-4 col-lg-4">
                  <div class="form-floating">
                    <select class="form-select" aria-label="Document Sender" name="dtsDocumentSender" id="dtsDocumentSender" required>
                      <option value="" selected readonly>-- Please Select Sender --</option>
                      <?php $senders = dtsGetSenderList($mysqli);?>
                      <?php foreach ($senders as $key):?>
                        <option value="<?= $key['id'];?>"><?php echo $key['firstName'].' '.$key['lastName'];?></option>
                      <?php endforeach ?>
                    </select>
                    <label for="dtsDocumentSender"><strong>Sender</strong><span class="text-danger"> *</span></label>
                  </div>
                </div>
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
                <button type="button" name="addInformation" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" title="Add Document Information" onclick="window.location.href= 'addInfo.php';"><i class="fas fa-plus"></i> Details</button>  
              </div>
            </div>  
            
            <div id="incomingDateAttachment" style="display: none;">
              <div class="row mt-3 addAttachment">
                <div class="col-sm-12 col-md-6 col-lg-6">
                  <div class="form-floating">
                    <input type="date" class="form-control" name="dtsDateReceived" id="dtsDateReceived" value="" >
                    <label for="dtsDateReceived"><strong>Date Received</strong><span class="text-danger"> *</span></label>
                  </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                  <div class="form-floating">
                    <input type="file" accept="application/pdf" class="form-control" name="dtsAttachmentsI" id="dtsAttachmentsI" value="" >
                    <label for="dtsAttachmentsI"><strong>Attachments</strong><span class="text-danger"> *</span></label>
                  </div>
                </div>
              </div>
            </div>

            <div id="outgoingAttachment" style="display: none;">
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
            </div>

            <div class="row mt-3">
              <div class="col-md-12">
                <div class="form-floating">
                  <textarea name="dtsParticulars" id="dtsParticulars" cols="30" rows="5" class="form-control" value="" placeholder="Particulars / Subject" style="height: 100px;" required></textarea>
                  <label for="dtsParticulars"><strong>Particulars / Subject</strong><span class="text-danger"> *</span> </label>
                </div>
              </div>
            </div>
            <!-- S T A R T  D Y N A M I C  F I E L D S --> 
              <?php $counter = 1; ?>
              <div class="dynamicFields mt-3">
                
              </div>
            <!-- E N D  D Y N A M I C  F I E L D S -->
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

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
          case "dts_outgoing":
            referenceLetter = "O";
            break;
          case "dts_specialorder":
            referenceLetter ="SO";
            break;
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

// for fields
  function putFields(){ 
    counter = <?= $counter ?>;
      var categoryTable = document.getElementById("documentCategory").value;
  
      switch (categoryTable){
        case "dts_incoming":
          var senderType = document.getElementById("senderType");
          if (senderType.style.display == "none"){
            senderType.style.display = "block";
          }
          var fields = '<div class="dynamicDetails"><div class="row mt-3"><div class="col-md-5"><div class="form-floating"><?php $personConcerned = "dtsPersonConcerned".$counter; ?><select class="form-select" aria-label="Person Concerned" name="<?=$personConcerned?>" id="<?=$personConcerned?>"><option value="" selected readonly>-- Person Concerned --</option><?php $concerned = getAllUsers($mysqli);?><?php foreach ($concerned as $key):?><option value="<?= $key['userID'];?>"><?php echo strtoupper($key['firstName'].' '.$key['lastName']);?></option><?php endforeach ?></select><label for="<?php echo ''.$counter;?>" class="text-dark"><strong>Person Concerned</strong> <span class="text-danger">*</span></label></div></div><div class="col-md-5"><div class="form-floating"><?php $actionTaken = "dtsActionTaken".$counter; ?><input type="text" class="form-control" name="<?=$actionTaken?>" id="<?=$actionTaken?>" value="" placeholder="Action Taken" required><label for="actionTaken" class="text-dark"><strong>Action Taken</strong><span class="text-danger">*</span></label></div></div><div class="col-md-2 mt-1"><button type="button" class="add-row btn btn-success btn-md btn-block mt-2" data-bs-toggle="tooltip" data-bs-placement="left" title="Add More Personnel" id="addMore"><i class="fas fa-plus"></i></button></div></div class="addFields"><div></div><div class="row mt-3"><div class="col-md-3"><div class="form-floating" id="docuDateDone"><?php $dateDone = "dtsDateDone".$counter; ?><input type="date" class="form-control" name="<?=$dateDone?>" id="<?=$dateDone?>" value=""><label for="dateDone" class="text-dark"><strong>Date Done</strong></label></div></div><div class="col-md-6"><div class="form-floating" id="docuActionNeeded"><?php $actionNeeded = "dtsActionNeeded".$counter; ?><select class="form-select" aria-label="actionNeeded" name="<?=$actionNeeded?>" id="<?=$actionNeeded?>"><option value="" selected readonly>-- Action Needed --</option><option value="For Compliance">For Compliance</option><option value="For Appropriate Action">For Appropriate Action</option><option value="For your comments">For your Comments</option><option value="Approved/Disapproved">Approved/Disapproved</option><option value="Please see me">Please see Me</option><option value="For your files">For you Files</option><option value="For your information">For your information</option></select><label for="ActionNeeded" class="text-dark"><strong>Action Needed</strong><span class="text-danger">*</span></label></div></div><div class="col-md-6"><br><div class="col-md-8"><div class="form-floating" id="docuRemarks"><?php $remarks = "dtsRemarks".$counter; ?><input type="text" class="form-control" name="<?=$remarks?>" id="<?=$remarks?>" value="" placeholder="Remarks" required><label for="remarks" class="text-dark"><strong>Remarks</strong> <span class="text-danger">*</span></label></div></div></div></div>';
          $('.dynamicFields').append(fields);

          $(".dynamicFields .dynamicDetails #addMore").click(function() {
            if(counter > 9){
              $(".modal-header #headerMsg").val("Invalid!");
              $(".modal-body #message").val("Maximum of 10 fields have reached.");
              $("#negative").modal("show");
            } else {
              counter++;
              $('.dynamicFields').append('<div class="dynamicDetails"><hr class="bg-dark"><div class="row mt-3" ><div class="col-md-5"><div class="form-floating"><select class="form-select" aria-label="Person Concerned" name="dtsPersonConcerned'+counter+'" id="dtsPersonConcerned'+counter+'"><option readonly>-- Person Concerned --</option><?php $concerned = getAllUsers($mysqli);?><?php foreach ($concerned as $key):?><option value="<?= $key['userID'];?>"><?php echo strtoupper($key['firstName'].' '.$key['lastName']);?></option><?php endforeach ?></select><label for="personConcerned">Person Concerned <span class="text-danger">*</span></label></div></div><div class="col-md-5"><div class="form-floating"><input type="text" class="form-control" name="dtsActionTaken'+counter+'" id="dtsActionTaken'+counter+'" placeholder="Action Taken"><label for="actionTaken">Action Taken</label></div></div><div class="col-md-2 mt-1"><button type="button" class="add-row btn btn-danger btn-block mt-1" id="removeRow"><i class="fas fa-minus"></i></button></div></div><div class="row mt-3"><div class="col-md-3"><div class="form-floating"><input type="date" class="form-control" name="dtsDateDone'+counter+'" id="dtsDateDone'+counter+'"><label for="dateDone">Date Done</label></div></div><div class="col-md-6"><div class="form-floating"><select class="form-select" aria-label="actionNeeded" name="dtsActionNeeded'+counter+'" id="dtsActionNeeded'+counter+'"><option value="" selected readonly>-- Action Needed --</option><option value="For Compliance">For Compliance</option><option value="For Appropriate Action">For Appropriate Action</option><option value="For your comments">For your Comments</option><option value="Approved/Disapproved">Approved/Disapproved</option><option value="Please see me">Please see Me</option><option value="For your files">For you Files</option><option value="For your information">For your information</option></select><label for="actionNeeded'+counter+'">Action Needed</label></div></div><div class="col-md-3"><div class="form-floating"><input type="text" class="form-control" name="dtsRemarks'+counter+'" id="dtsRemarks'+counter+'" placeholder="Remarks"><label for="remarks'+counter+'">Remarks <span class="text-danger">*</span></label></div></div></div></div>');
            }
          });

          $('.dynamicFields').on('click', '#removeRow', function() {
            counter--;
            $(this).closest('.dynamicDetails').remove();
          });
          break;

        case "dts_outgoing":
          var senderType = document.getElementById("senderType");
          if (senderType.style.display == "none"){
            senderType.style.display = "block";
          }
          
          var fields = '<div class="dynamicDetails"><div class="row mt-3"><div class="col-md-5"><div class="form-floating"><select class="form-select" aria-label="Addressed To" name="dtsPersonConcerned'+counter+'" id="dtsPersonConcerned'+counter+'"><option value="" selected readonly>-- Addressed To --</option><?php $concerned = dtsGetPersonConList($mysqli);?><?php foreach ($concerned as $key):?><option value="<?= $key['id'];?>"><?php echo $key['firstName'].' '.$key['lastName'];?></option><?php endforeach ?></select><label for="" class="text-dark"><strong>Addressed To</strong> <span class="text-danger">*</span></label></div></div><div class="col-md-5"><div class="form-floating"><input type="text" class="form-control" name="dtsActionTaken'+counter+'" id="dtsActionTaken'+counter+'" value="" placeholder="Action Taken"><label for="dtsActionTaken" class="text-dark"><strong>Action Taken</strong></label></div></div><div class="col-md-2 mt-1"><button type="button" class="add-row btn btn-success btn-md btn-block mt-2" data-bs-toggle="tooltip" data-bs-placement="left" title="Add More Personnel" id="addMore"><i class="fas fa-plus"></i></button></div></div></div>';
          $('.dynamicFields').append(fields);

          $(".dynamicFields .dynamicDetails #addMore").click(function() {
            if(counter > 9){
              $(".modal-header #headerMsg").val("Invalid!");
              $(".modal-body #message").val("Maximum of 10 fields have reached.");
              $("#negative").modal("show");
            } else {
              counter++;
              $('.dynamicFields').append('<div class="dynamicDetails"><hr class="bg-dark"><div class="row mt-3"><div class="col-md-5"><div class="form-floating"><select class="form-select" aria-label="Addressed To" name="dtsPersonConcerned'+counter+'" id="dtsPersonConcerned'+counter+'"><option value="" selected readonly>-- Addressed To --</option><?php $concerned = dtsGetPersonConList($mysqli);?><?php foreach ($concerned as $key):?><option value="<?= $key['id'];?>"><?php echo $key['firstName'].' '.$key['lastName'];?></option><?php endforeach ?></select><label for="" class="text-dark"><strong>Addressed To</strong> <span class="text-danger">*</span></label></div></div><div class="col-md-5"><div class="form-floating"><input type="text" class="form-control" name="dtsActionTaken'+counter+'" id="dtsActionTaken'+counter+'" value="" placeholder="Action Taken"><label for="dtsActionTaken" class="text-dark"><strong>Action Taken</strong></label></div></div><div class="col-md-2 mt-1"><button type="button" class="add-row btn btn-danger btn-block mt-1" id="removeRow"><i class="fas fa-minus"></i></button></div></div></div>');
            }
          });

          $('.dynamicFields').on('click', '#removeRow', function() {
            counter--;
            $(this).closest('.dynamicDetails').remove();
          });
          break;

        case "dts_specialorder":
          var senderType = document.getElementById("senderType");
          if (senderType.style.display == "none"){
            senderType.style.display = "block";
          }
          var fields = '<div class="dynamicDetails"><div class="row mt-3"><div class="col-md-10"><div class="form-floating"><select class="form-select" aria-label="Addressed To" name="dtsPersonConcerned'+counter+'" id="dtsPersonConcerned'+counter+'"><option value="" selected readonly>-- Addressed To --</option><?php $concerned = dtsGetPersonConList($mysqli);?><?php foreach ($concerned as $key):?><option value="<?= $key['id'];?>"><?php echo $key['firstName'].' '.$key['lastName'];?></option><?php endforeach ?></select><label for="" class="text-dark"><strong>Addressed To</strong> <span class="text-danger">*</span></label></div></div><div class="col-md-2 mt-1"><button type="button" class="add-row btn btn-success btn-md btn-block mt-2" data-bs-toggle="tooltip" data-bs-placement="left" title="Add More Personnel" id="addMore"><i class="fas fa-plus"></i></button></div></div></div>';
          $('.dynamicFields').append(fields);
          
          $(".dynamicFields .dynamicDetails #addMore").click(function() {
            if(counter > 9){
              $(".modal-header #headerMsg").val("Invalid!");
              $(".modal-body #message").val("Maximum of 10 fields have reached.");
              $("#negative").modal("show");
            } else {
            counter++;
            $('.dynamicFields').append('<div class="dynamicDetails"><hr class="bg-dark"><div class="row mt-3"><div class="col-md-10"><div class="form-floating"><select class="form-select" aria-label="Addressed To" name="dtsPersonConcerned'+counter+'" id="dtsPersonConcerned'+counter+'"><option value="" selected readonly>-- Addressed To --</option><?php $concerned = dtsGetPersonConList($mysqli);?><?php foreach ($concerned as $key):?><option value="<?= $key['id'];?>"><?php echo $key['firstName'].' '.$key['lastName'];?></option><?php endforeach ?></select><label for="<?php echo 'dtsPersonConcerned'.$counter;?>" class="text-dark"><strong>Addressed To</strong> <span class="text-danger">*</span></label></div></div><div class="col-md-2 mt-1"><button type="button" class="add-row btn btn-danger btn-block mt-1" id="removeRow"><i class="fas fa-minus"></i></button></div></div></div>');
            }
          });

          $('.dynamicFields').on('click', '#removeRow', function() {
            counter--;
            $(this).closest('.dynamicDetails').remove();
          });
          break;
          
        case "dts_memorandum":
          var senderType = document.getElementById("senderType");
          if (senderType.style.display == "none"){
            senderType.style.display = "block";
          }
          var fields = '<div class="dynamicDetails"><div class="row mt-3"><div class="col-md-10"><div class="form-floating"><select class="form-select" aria-label="Addressed To" name="dtsPersonConcerned'+counter+'" id="dtsPersonConcerned'+counter+'"><option value="" selected readonly>-- Addressed To --</option><?php $concerned = dtsGetPersonConList($mysqli);?><?php foreach ($concerned as $key):?><option value="<?= $key['id'];?>"><?php echo $key['firstName'].' '.$key['lastName'];?></option><?php endforeach ?></select><label for="" class="text-dark"><strong>Addressed To</strong> <span class="text-danger">*</span></label></div></div><div class="col-md-2 mt-1"><button type="button" class="add-row btn btn-success btn-md btn-block mt-2" data-bs-toggle="tooltip" data-bs-placement="left" title="Add More Personnel" id="addMore"><i class="fas fa-plus"></i></button></div></div></div>';
          $('.dynamicFields').append(fields);

          $(".dynamicFields .dynamicDetails #addMore").click(function() {
            
            if(counter > 9){
              $(".modal-header #headerMsg").val("Invalid!");
              $(".modal-body #message").val("Maximum of 10 fields have reached.");
              $("#negative").modal("show");
            } else {
              counter++;
              $('.dynamicFields').append('<div class="dynamicDetails"><hr class="bg-dark"><div class="row mt-3"><div class="col-md-10"><div class="form-floating"><select class="form-select" aria-label="Addressed To" name="dtsPersonConcerned'+counter+'" id="dtsPersonConcerned'+counter+'"><option value="" selected readonly>-- Addressed To --</option><?php $concerned = dtsGetPersonConList($mysqli);?><?php foreach ($concerned as $key):?><option value="<?= $key['id'];?>"><?php echo $key['firstName'].' '.$key['lastName'];?></option><?php endforeach ?></select><label for="" class="text-dark"><strong>Addressed To</strong> <span class="text-danger">*</span></label></div></div><div class="col-md-2 mt-1"><button type="button" class="add-row btn btn-danger btn-block mt-1" id="removeRow"><i class="fas fa-minus"></i></button></div></div></div>');
            }
          });

          $('.dynamicFields').on('click', '#removeRow', function() {
            counter--;
            $(this).closest('.dynamicDetails').remove();
          });
          break;
        } 
  }
  
// saving
  function dtsSaveDocuments(){ 
    var dtsCategory = document.getElementById("documentCategory").value;
    var dtsReferenceNo_ = document.getElementById("dtsReferenceNo").value;
    var dtsParticulars_ = document.getElementById("dtsParticulars").value;
    var dtsPersonConcerned_ = document.getElementById("dtsPersonConcerned1").value;
    
    if(dtsCategory == "dts_incoming"){
      var dtsDateReceived_ = document.getElementById("dtsDateReceived").value;
      var dtsRemarks_ = document.getElementById("dtsRemarks1").value;
      var dtsDocumentSender_ = document.getElementById("dtsDocumentSender").value;
      var dtsDocumentType_ = document.getElementById("dtsDocumentType").value;
      var dtsAttachments_ = document.getElementById("dtsAttachmentsI").value;
    }
    
    if(dtsCategory == "dts_outgoing"){
      var dtsDateReceived_ = "blank";
      var dtsRemarks_ = "blank";
      var dtsDocumentSender_ = document.getElementById("dtsDocumentSender").value;
      var dtsDocumentType_ = document.getElementById("dtsDocumentType").value;
      var dtsAttachments_ = document.getElementById("dtsAttachmentsO").value;
    }

    if(dtsCategory == "dts_specialorder"){
      var dtsDateReceived_ = "blank";
      var dtsRemarks_ = "blank";
      var dtsDocumentSender_ = "blank";
      var dtsDocumentType_ = "blank";
      var dtsAttachments_ = document.getElementById("dtsAttachmentsSO").value
    }

    if(dtsCategory == "dts_memorandum"){
      var dtsDateReceived_ = "blank";
      var dtsRemarks_ = "blank";
      var dtsDocumentSender_ = "blank";
      var dtsDocumentType_ = "blank";
      var dtsAttachments_ = document.getElementById("dtsAttachmentsM").value
    }

    if(dtsReferenceNo_ == "" || dtsDocumentSender_ == "" || dtsDocumentType_ == "" || dtsDateReceived_== "" || dtsAttachments_== "" || dtsParticulars_== "" || dtsPersonConcerned_=="" || dtsRemarks_==""){
        $(".modal-header #headerMsg").val("Invalid!");
        $(".modal-body #message").val("Missing Field/s");
        $("#negative").modal("show");
    } 
    else {
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
      if (this.value == 'dts_outgoing') {
        $('#outgoingAttachment').show();
        $('#incomingDateAttachment').hide();
        $('#specialOrderAttachment').hide();
        $('#memorandumAttachment').hide();
      }
      if (this.value == 'dts_specialorder') {
        $('#specialOrderAttachment').show();
        $('#incomingDateAttachment').hide();
        $('#outgoingAttachment').hide();
        $('#memorandumAttachment').hide();
      }
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