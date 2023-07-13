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

<style type="text/css">
  /* for input validation */
.errorInput {
  position: relative;
  animation: shake .1s linear;
  animation-iteration-count: 3;
  border: 1px solid red;
  outline: none;
}
</style>
<main id="main" class="main">
  <div class="pagetitle"><!-- Start Page Title -->
    <div class="container">
      <div class="card shadow mb-4">
        <form method="POST" action="../process/records/records.php" class="c" id="saveDetails" enctype="multipart/form-data"> 
          <div class="card-header d-flex justify-content-between" style="background-color:rgba(23, 95, 10, 1);">
            <h4 class="m-0 font-weight-bold">
              <i><img src="../assets/img/Stamp.png" class="img-circle" width="50" height="50"></i>
              <span class="text-light col-form-label"><strong> Information</strong></span>
            </h4>
            <div>
              <a href="AddDocument.php" class="button1 button3" data-bs-toggle="tooltip" data-bs-placement="left" title="Go Back"><i class="fas fa-backward"></i> </a>
            </div>
          </div>
          <div class="card-body" style="background-color:rgba(17, 4, 18, 0.08);">
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
                      <!-- <a href="sender.php" class="btn btn-secondary btn-md" id="btnSenderView"><i class="fas fa-eye"></i> View</a> -->
                      <button type="button" name="saveSender" id="saveSender" onclick="checkSender();" class="button button2"><i class="fas fa-save"></i> Save</button>
                    </div>
                  </div>
                </div>
              </div>
               <!-- <div class="tab-pane fade" id="document" role="tabpanel" aria-labelledby="document-tab">
                <div>
                  <div class="row">
                    <div class="col-md-12">
                      <label for="">Document Type <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" name="docuType" id="docuType" required>
                    </div>
                  </div>
                  <div class="mt-3 d-flex justify-content-between">
                    <div>
                      <span class="text-danger">Note: All * are required.</span>
                    </div>
                    <div class="a">
                      <button type="button" onclick="checkDocType();" name="saveDocType" class="button button2" data-bs-toggle="tooltip"  data-bs-placement="left" title="Save Information"><i class="fas fa-save"></i> Save</button>
                    </div>
                  </div>
                </div>
              </div> -->

          
            </div>
          </div>
        </form> 
      </div>
    </div>
  </div>
</main>

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

