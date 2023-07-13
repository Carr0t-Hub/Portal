<?php 
    include('../common/header.php');
    include('../common/sidebar.php');
?>
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
    <form method="POST" action="../process/records/records.php" id="saveMemorandum" enctype="multipart/form-data">
    <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between" style="background-color:rgba(23, 95, 10, 1);">
    <div>
    <h4><img src="../assets/img/dts.png" class="img-circle" width="50" height="50"><span class="text-light"><b> Records Management Information System</span></b></h4>
    </div>
    <div class="col-sm-12 col-lg-6 d-flex justify-content-lg-end">
            <button type="button" class="buttonClock btn" value="" id="" data-bs-toggle="modal"><strong><i class="fas fa-calendar-day"></i> <?= date("l jS \of F Y "); ?></strong></button>
        </div>
    </div>

<div class="card-body" style="background-color:rgba(17, 4, 18, 0.08);">
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
              <!-- <input type="text" class="form-control" name="dtsReferenceNo" id="dtsReferenceNo" value="<?= $reference?>" readonly> -->
              <input type="text" class="form-control" name="dtsReferenceNo" id="dtsReferenceNo" value="" readonly >
              <label for="dtsReferenceNo"><strong>Reference Number</strong></label>
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
<div class="dynamicFields mt-3">
</div>
<!-- E N D  D Y N A M I C  F I E L D S -->
<div class="row mt-3">
<div class="col-md-12">
<div>
    <button type="submit" name="saveDocuments" class="button button2" data-bs-toggle="tooltip" data-bs-placement="left" title="Save Information"><i class="far fa-save"></i> Save</button>
    <a href="history.php?code=SO" title="Go Back" class="button1 button3"><i class="fas fa-backward"></i> Back</a>

</form>
</div>
</div>
</div>
</body>
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

    
    //if(dtsCategory == "dts_incoming"){
      //var dtsDateReceived_ = document.getElementById("dtsDateReceived").value;
      //var dtsRemarks_ = "blank";
      //var dtsDocumentSender_ = document.getElementById("dtsDocumentSender").value;
      //var dtsDocumentType_ = document.getElementById("dtsDocumentType").value;
      //var dtsAttachments_ = document.getElementById("dtsAttachmentsI").value;
    //} 
    if(dtsCategory == "dts_memorandum"){
     var dtsDateReceived_ = "blank";
     var dtsRemarks_ = "blank";
     var dtsaddressedTo = document.getElementById("dtsaddressedTo").value;
     var dtsAttachments_ = document.getElementById("dtsAttachmentsM").value;
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

    if(dtsReferenceNo_ == "" || dtsaddressedTo == "" || dtsDateReceived_== "" || dtsAttachments_== "" || dtsParticulars_== ""  ){
        $(".modal-header #headerMsg").val("Invalid!");
        $(".modal-body #message").val("Missing Field/s");
        $("#negative").modal("show");
    } 
    
    else {
      // $(".modal-header #PheaderMsg").val("Success!");
      // $(".modal-body #Pmessage").val("Information Saved!");
      // $("#positive").modal("show");
      // windows.location.href = "../records/history.php?code=I";
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