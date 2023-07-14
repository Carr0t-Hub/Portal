<?php include('../common/header.php'); ?>
<?php include('../common/sidebar.php'); ?>

<div class="page-wrapper">
	<div class="page-content">
    <div class="row">
      <div class="col">
        <div class="card shadow mb-4">
          <form method="POST" action="../process/records/records.php" class="c" id="saveDocType" enctype="multipart/form-data">
            <div class="card-header d-flex justify-content-between">
              <div>
                <h6><img src="../assets/images/dts.png" class="img-circle" width="20" height="20"><span><b> Records Management Information System</span></b></h6>
              </div>
              <div>
                <a href="AddDocument.php" class="button1 button3" data-bs-toggle="tooltip" data-bs-placement="left" title="Go Back"><i class="bx bx-arrow-back"></i> </a>
              </div>
            </div>
            <div class="card-body">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <a href="addInfo.php" class="nav-link active"><strong>Sender / Addressee</strong></a>
                </li>
                <li class="nav-item" role="presentation">
                  <a href="addDocType.php" class="nav-link active"><strong>Document Type</strong></a>
                </li>
              </ul>
              <div class="row mt-3">
                <div class="col-md-12">
                  <strong><label for="">Document Type <span class="text-danger">*</span></label></strong>
                  <input type="text" class="form-control" name="docuType" id="docuType" required>
                </div>
              </div>
              <div class="mt-3 d-flex justify-content-between">
                <div>
                  <span class="text-danger">Note: All * are required.</span>
                </div>
                <div class="a">
                  <button type="submit" onclick="checkDocType();" name="saveDocType" class="btn btn-primary btn-sm" data-bs-toggle="tooltip"  data-bs-placement="left" title="Save Information"><i class="bx bx-save"></i> Save</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

<!-- FOR CHECKING OF DOCUMENT -->
<script type="text/javascript">
  function checkDocType(){
    var docType = document.getElementById("docuType").value;
    if(docType != ""){
      $.ajax({
        url: '../process/records/records.php',
        type: 'post',
        data: {docuType:docType},
        dataType: 'json',
        success:function(response){
          if(response == "yes"){
            document.getElementById("docuType").value = "";
              // alert("data is duplicated");
            // modal located inside footer
            $(".modal-header #headerMsg").val("Invalid!");
            $(".modal-body #message").val("Document type already in the list.");
            $("#negative").modal("show");
          }
          if(response == "no"){
            saveThisDocu(docType);
          }
        }
      });
    }
    else{
      $(".modal-header #headerMsg").val("Invalid!");
      $(".modal-body #message").val("Missing field/s.");
      $("#negative").modal("show");
    } 
  }

  // saving
  function saveThisDocu(docType) {
    var docuType = document.getElementById("docuType").value;
    if(docuType == "" ){
      $(".modal-header #headerMsg").val("Invalid!");
      $(".modal-body #message").val("Missing Field/s");
      $("#negative").modal("show");
    } else {
      // $(".modal-header #PheaderMsg").val("Success!");
      // $(".modal-body #Pmessage").val("Information Saved!");
      // $("#positive").modal("show");
      // windows.location.href = "../records/history.php?code=I";
      document.getElementById('saveDocType').submit();
    }
  }
</script>

<?php include('../common/footer.php');?>