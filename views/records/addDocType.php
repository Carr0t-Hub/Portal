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
</style>

<main id="main" class="main">
  <div class="pagetitle"><!-- Start Page Title -->
    <div class="container">
      <div class="card shadow mb-4">
        <form method="POST" action="../process/records/records.php" class="c" id="saveDocType" enctype="multipart/form-data">
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
                  <div class="row">
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
                      <button type="submit" onclick="checkDocType();" name="saveDocType" class="button button2" data-bs-toggle="tooltip"  data-bs-placement="left" title="Save Information"><i class="fas fa-save"></i> Save</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</main>     

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
function saveThisDocu(docType) 
    var docuType = document.getElementById("docuType").value;
   
    if(docuType == "" ){
        $(".modal-header #headerMsg").val("Invalid!");
        $(".modal-body #message").val("Missing Field/s");
        $("#negative").modal("show");
    } 
    
    else {
      // $(".modal-header #PheaderMsg").val("Success!");
      // $(".modal-body #Pmessage").val("Information Saved!");
      // $("#positive").modal("show");
      // windows.location.href = "../records/history.php?code=I";
      document.getElementById('saveDocType').submit();
    }
  

<?php include('../common/footer.php');?>