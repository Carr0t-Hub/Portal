<?php include('../common/header.php'); ?>
<?php include('../common/sidebar.php'); ?>

<div class="page-wrapper">
	<div class="page-content">
    <div class="row">
      <div class="col">
        <div class="card shadow mb-4">
          <form method="POST" action="../process/records/records.php" class="c" id="saveDetails" enctype="multipart/form-data"> 
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
                  <button class="nav-link active" id="sender-tab" data-bs-toggle="tab" data-bs-target="#sender" type="button" role="tab" aria-controls="sender" aria-selected="true"><strong>Sender / Addressee</strong></button>
                </li>
                <li class="nav-item" role="presentation">
                  <a href="addDocType.php" class="nav-link active"><strong>Document Type</strong></a>
                </li>
              </ul>
              <div class="tab-content mt-3" id="myTabContent">
                <div class="tab-pane fade show active" id="sender" role="tabpanel" aria-labelledby="sender-tab">
                  <div>
                    <div class="row">
                      <div class="col-md-6">
                        <label for="">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="dtsFirstName" id="dtsFirstName" maxlength="50" required>
                      </div>
                      <div class="col-md-6">
                        <label for="">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="dtsLastName" id="dtsLastName" maxlength="50" required>
                      </div>
                    </div>
                    <div class="row mt-3">
                      <div class="col-md-12">
                        <label for=""> Agency <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="dtsDivAgency" id="dtsDivAgency" maxlength="100" required>
                      </div>
                    </div>
                    <div class="row mt-3 errorField">
                      <div class="col-md-12">
                        <label for="">Email Address</label>
                        <input type="email" class="form-control" name="dtsEmail" id="dtsEmail" maxlength="50" >
                      </div>
                    </div>
                    <div class="mt-3 d-flex justify-content-between">
                      <div>
                        <span class="text-danger">Note: All * are required.</span>
                      </div>
                      <div>
                        <button type="button" name="saveSender" id="saveSender" onclick="checkSender();" class="btn btn-primary"><i class="bx bx-save"></i> Save</button>
                      </div>
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
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  
<!-- FOR CHECKING OF SENDER -->
<script type="text/javascript">
  function checkSender(){
    var FName = document.getElementById("dtsFirstName").value;
    var LName = document.getElementById("dtsLastName").value;
    var Email = document.getElementById("dtsEmail").value;
    var DivAgency = document.getElementById("dtsDivAgency").value;

    if(FName != "" || LName != "" || Email != "" || DivAgency != ""){
      $.ajax({
        url: '../process/records/records.php',
        type: 'post',
        data: {dtsFirstName:FName,dtsLastName:LName,dtsEmail:Email,dtsDivAgency:DivAgency},
        dataType: 'json',
        success:function(response){
          if(response == "yes"){
            document.getElementById("dtsFirstName").value = "";
            document.getElementById("dtsLastName").value = "";
            document.getElementById("dtsEmail").value = "blank";
            document.getElementById("dtsDivAgency").value = "";

            // alert("Invalid!");
            $(".modal-header #headerMsg").val("Invalid!");
            $(".modal-boyd #message").val("Sender already in the list.");
            $("#negative").modal("show");
          }
          if(response == "no"){
            saveThis(FName,LName,Email,DivAgency);
          }
        }
      });
    } else{
      $(".modal-header #headerMsg").val("Invalid!");
      $(".modal-body #message").val("Missing field/s.");
      $("#negative").modal("show");
    }
  }

  function saveThis(FName,LName,Email,DivAgency){
    var FName = document.getElementById("dtsFirstName").value;
    var LName = document.getElementById("dtsLastName").value;
    var Email = document.getElementById("dtsEmail").value;
    var DivAgency = document.getElementById("dtsDivAgency").value;

      if(FName != "" || LName != "" || Email != "" || DivAgency != ""){
      $.ajax({
        url: '../process/records/records.php',
        type: 'post',
        data: {dtsFirstName:FName,dtsLastName:LName,dtsEmail:Email,dtsDivAgency:DivAgency},
        dataType: 'json',
        success:function(response){
          // modal located inside footer
          // alert("Sender successfully saved");
          // $('#dtsFirstName').val("");
          // $('#dtsLastName').val("");
          // $('#dtsEmail').val("");
          // $('#dtsDivAgency').val("");
          $(".modal-header #PheaderMsg").val("Success!");
          $(".modal-body #Pmessage").val("Sender successfully saved.");
          $("#positive").modal("show");
        }
      });
    } else {
      $(".modal-header #headerMsg").val("Invalid!");
      $(".modal-body #message").val("Missing field/s.");
      $("#negative").modal("show");
    }
  }
</script>

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

  function saveThisDocu(docType){
    var docType = document.getElementById("docuType").value;
    if(docType != "") {
      $.ajax({
        url: '../process/records/records.php',
        type: 'post',
        data: {saveDocType:docType},
        dataType: 'json',
        success:function(response){
          if(response == "saved"){
            // modal located inside footer
            // alert("Document type successfully saved");
            $("#docuType").val("");
            $(".modal-header #PheaderMsg").val("Success!");
            $(".modal-body #Pmessage").val("Document type successfully saved.");
            $("#positive").modal("show");
          }
        }
      });
    } else {
      $(".modal-header #headerMsg").val("Invalid!");
      $(".modal-body #message").val("Missing field/s.");
      $("#negative").modal("show");
    }
  }
</script>

<?php include('../common/footer.php');?>

