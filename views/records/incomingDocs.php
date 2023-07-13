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

</style>
<?php
if(isset($_POST['referenceNo']))
{
  $incomingDocs = $_POST['referenceNo'];
}
 if(isset($_GET['referenceNo']))
{
  $incomingDocs = $_GET['referenceNo']; 
} 

$res = dtsGetIncomingDoc($mysqli, $incomingDocs);
$docuID = 0;
?>

<?php foreach ($res as $key) :
  $docuID = $key['docuID'];
?>
  <?php foreach ($res as $key) : ?>
    <?php $res1 = getAttachmentByID($mysqli, $key['attachment']); ?>
    <main id="main" class="main">
      
        <br>
        <div class="pagetitle"><!-- Start Page Title -->
          <div class="container">
            <div class="card-header d-flex justify-content-between" style="background-color:rgba(23, 95, 10, 1);">
              <h5 class="m-0 font-weight-bold">
                <i data-toggle="modal" data-target="#modal-register_recieve"><img src="../assets/img/incomingDocs.png" class="img-circle" width="50" height="50"></i>
                <span class="text-light col-form-label"><strong> Incoming Documents</strong></span>
                <div>
                  <!-- <input type="text" class="form-control" name="incomingID" id="incomingID" value="</?php echo $key['status']; ?>" readonly>-->
              </h5>
              <div>
                <a href="AddDocument.php" title="Add Incoming Document"><i><img src="../assets/img/addDoc.png" class="img-circle" width="50" height="50"></i></a>
                <!-- Button trigger modal 
            <button type="button" class="btn btn-primary"  onclick="saveStatusReceivedocs();">
              Receive Document 
            </button>
            <input type="text" class="form-control" name="incomingID" id="incomingID" value="<?php echo $key['documentID']; ?>" readonly>-->
                <!-- Button trigger modal -->
                <a href="history.php?code=I" class="button1 button3" data-bs-toggle="tooltip" data-bs-placement="left" title="Go Back"><i class="fas fa-backward"></i> Back</a>
                <!-- Modal -->
              </div>
            </div>
            <div class="docu-detail">
              <div class="det-br">
                <table class="table table-bordered table-hover table-responsive-sm table-responsive-md display" id="dataTable" width="100%" cellspacing="0">
                  <tr>
                    <th style="background-color:rgba(227, 218, 194, 0.81);">Reference Number: </th>
                    <td style="background-color:rgba(17, 4, 18, 0.08);"><?php echo strtoupper($key['referenceNo']); ?></td>
                  </tr>
                  <tr>
                    <th style="background-color:rgba(227, 218, 194, 0.81);">Date & Time:</th>
                    <td style="background-color:rgba(17, 4, 18, 0.08);"><?php echo strtoupper($key['createdDateTime']); ?></td>
                  </tr>
                  <tr>
                    <th style="background-color:rgba(227, 218, 194, 0.81);">Name of Sender:</th>
                    <td style="background-color:rgba(17, 4, 18, 0.08);"><?php echo strtoupper($key['sender']); ?></td>
                  </tr>
                  <tr>
                    <th style="background-color:rgba(227, 218, 194, 0.81);">Document Type:</th>
                    <td style="background-color:rgba(17, 4, 18, 0.08);"><?php echo strtoupper($key['documentType']); ?></td>
                  </tr>
                  <tr>
                    <th style="background-color:rgba(227, 218, 194, 0.81);">Date Received:</th>
                    <td style="background-color:rgba(17, 4, 18, 0.08);"><?php echo strtoupper($key['dateReceived']); ?></td>
                  </tr>
                  <tr>
                    <th style="background-color:rgba(227, 218, 194, 0.81);">Subject:</th>
                    <td style="background-color:rgba(17, 4, 18, 0.08);"><?php echo strtoupper($key['particulars']); ?></td>
                  </tr>
                  <tr>
                    <th style="background-color:rgba(227, 218, 194, 0.81);">Attachment:</th>
                    <td style="background-color:rgba(17, 4, 18, 0.08);"><button class="btn btn-outline-primary" onclick="window.open('../uploads/records/incoming/<?php echo $res1['fileName'] . '_' . $res1['size'] . $res1['id'] . '.' . $res1['fileExtension'] ?>','_blank')" class="btn btn-primary"><i class="bi bi-download"></i><?php echo $res1['fileName'] . '_' . $res1['size'] . $res1['id'] . '.' . $res1['fileExtension']; ?></a>
                    </td>
                  </tr>
                <?php endforeach; ?>
                </table>


                <!--      //    V   I   E   W     T   A   B   L   E     F   O   R     P   E   R   S   O   N     C   O   N   C   E   R   N   E   D   //     -->

                <div class="card shadow mt-4 mb-4" style="background-color:rgba(227, 218, 194, 0.81);">
                  <div class="table-responsive">
                    <table class="table">
                      <thead class="thead-dark">
                        <tr>
                          <th class="table-secondary">Concerned Division</th>
                          <th class="table-secondary">Concerned Section</th>
                          <th class="table-secondary">Date Done</th>
                          <th class="table-secondary">Action Needed</th>
                          <th class="table-secondary">Remarks</th>
                          <th class="table-secondary">Action Taken</th>
                          <th class="table-secondary">Status</th>
                          <th class="table-secondary">Action</th>
                        </thread>
                      <tbody>
                        <?php
                        $ODincomingTable = getDataIncomingTableOD($mysqli, $docuID);
                        foreach ($ODincomingTable as $od) :
                        ?>
                          <tr>
                            <td><b><?= $od['person'] ?></b></td>
                            <td><b><?= $od['section'] ?></b></td>
                            <td><?= $od['dateDone'] ?></td>
                            <td><?= $od['actionNeeded'] ?></td>
                            <td><?= $od['remarks'] ?></td>
                            <td><?= $od['actionTaken'] ?></td>
                            <td><?= $od['StatusType']?></td>
                            <td><button type="button" class="button5 button4" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $od['docID']?>" data-bs-whatever="@mdo">Take Action</button>
                            <?php endforeach; ?>
                          </tr>
                      </tbody>
                    </table>


                        <!-- //     O  F  F  I  C  E     O  F     D  I  R  E  C  T  O  R     I  N  C  O  M  I  N  G     F  I  E  L  D   //     -->

                    <div class="card-header d-flex justify-content-between" style="background-color:rgba(23, 95, 10, 1);">
                      <h5 class="m-0 font-weight-bold">
                        <i data-toggle="modal" data-target="#modal-register_recieve"><img src="../assets/img/sendIncoming.png" class="img-circle" width="50" height="50"></i>
                        <span class="text-light col-form-label"><strong> Take Action</strong></span>
                        <div>
                          <span class="text-danger">Fill-out by the Office of the Director</span>
                        </div>
                    </div>
                   <form method="POST" action="../process/records/records.php" id="SaveODIncoming" enctype="multipart/form-data">
                      <div class="card-body" style="background-color:rgba(227, 218, 194, 0.81);">
                         <!--<div class="row mt-3">
                          <div class="col-md-15">
                            <label for="Person Concerned" class="text-dark"><strong>Person Concerned</strong> <span class="text-danger">*</span></label>
                            <select class="form-select" id="dtsPersonConcerned" name="dtsPersonConcerned" data-placeholder="Choose Person Concern" multiple="" required>
                             <option value="Office of the Director">Office of the Director</option>
                              <option value="OD-Planning and Monitoring Unit">OD-Planning and Monitoring Unit</option>
                              <option value="OD-Internal Audit">OD-Internal Audit</option>\
                              <option value="Office of the Assistant Director">Office of the Assistant Director</option>
                              <option value="Program Development Division">Program Development Division</option>
                              <option value="Program Monitoring, Evaluation, and Linkaging Division">Program Monitoring, Evaluation, and Linkaging Division</option>
                              <option value="Knowledge Management and Information Systems Division">Knowledge Management and Information Systems Division</option>
                              <option value="Human Resource Management Section">Human Resource Management Section</option>
                              <option value="Procurement Section">Procurement Section</option>
                              <option value="Property and Supply Section">Property and Supply Section</option>
                              <option value="Cash Section">Cash Section</option>
                              <option value="Records Section">Records Section</option>
                              <option value="General Services Section">General Services Section</option>
                              <option value="Accounting Section">Accounting Section</option>
                              <option value="Budget Section">Budget Section</option>
                              <option value="Information Management Section">Information Management Section</option>
                              <option value="Library System Section">Scientific Literature Systems Section</option>
                              <option value="Applied Communication Section">Applied Communication Section</option>
                              </?php $res1 = getAllUsers($mysqli); ?>
                              </?php foreach ($res1 as $key1 => $personConcern) : ?>
                                <option value="</?= $personConcern['userID']; ?>"></?= strtoupper($personConcern['lastName'] . ", " . $personConcern['firstName']); ?></option>
                              </?php endforeach; ?>
                            </select>
                          </div>
                      </div><br>-->

            <div class="row mt-3">
            <div class="col-sm-5 col-md-5 col-lg-5">
              <div class="form-floating">
                            <select class="form-select" aria-label="actionNeeded" name="dtsDocuDivision" id="dtsDocuDivision" required>
                              <option value="">Choose Division</option>
                              <option value="Office of the Director">Office of the Director</option>
                              <option value="Office of the Assistant Director">Office of the Assistant Director</option>
                              <option value="Program Development Division">Program Development Division</option>
                              <option value="Program Monitoring, Evaluation, and Linkaging Division">Program Monitoring, Evaluation, and Linkaging Division</option>
                              <option value="Knowledge Management and Information Systems Division">Knowledge Management and Information Systems Division</option>
                              <option value="Administrative and Finance Division">Administrative and Finance Division</option>
                            </select>
                            <label for="Concerned Section" class="text-dark"><strong>Concerned Division</strong><span class="text-danger">*</span></label>
                          </div>
                      </div><br>

                      <div class="col-md-5">
                            <div class="form-floating" id="docuSection">
                            <select class="form-select" aria-label="actionNeeded" name="dtsDocuSection" id="dtsDocuSection">
                              <option value="">Choose Section</option>
                              <option value="Internal Audit">Internal Audit</option>
                              <option value="Planning and Monitoring Unit">Planning and Monitoring Unit</option>
                              <option value="Project Evaluation and Packaging Section">Project Evaluation and Packaging Section</option>
                              <option value="Technology Management Section">Technology Management Section</option>
                              <option value="Institutional Development Section">Institutional Development Section</option>
                              <option value="Impact Evaluation and Policy Section">Impact Evaluation and Policy Section</option>
                              <option value="Monitoring and Evaluation Section">Monitoring and Evaluation Section</option>
                              <option value="Research Linkages Section">Research Linkages Section</option>
                              <option value="Results Management Section">Results Management Section</option>
                              <option value="International Relations Section">International Relations Section</option>
                              <option value="Applied Communiction Section">Applied Communiction Section</option>
                              <option value="Information Management Section">Information Management Section</option>
                              <option value="Scientific Literature System Section">Scientific Literature System Section</option>
                              <option value="Human Resource Management Section">Human Resource Management Section</option>
                              <option value="Procurement Section">Procurement Section</option>
                              <option value="Property and Supply Section">Property and Supply Section</option>
                              <option value="Cash Section">Cash Section</option>
                              <option value="Records Section">Records Section</option>
                              <option value="General Services Section">General Services Section</option>
                              <option value="Accounting Section">Accounting Section</option>
                              <option value="Budget Section">Budget Section</option>
                            </select>
                            <label for="Concerned Section" class="text-dark"><strong>Concerned Section</strong><span class="text-danger"></span></label>
                              </div>
                              </div>
                              </div><br>
                       <!-- <form method="POST" action="../process/records/records.php" id="SaveODIncoming" enctype="multipart/form-data">
                      <div class="card-body" style="background-color:rgba(227, 218, 194, 0.81);">
                        <div class="row mt-3">
                          <div class="col-md-15">
                            <label for="Person Concerned" class="text-dark"><strong></strong> <span class="text-danger">*</span></label>
                            <select class="form-select" id="dtsPersonConcerned" name="dtsPersonConcerned" data-placeholder="Choose Person Concern" multiple="" required>
                            <option value="Office of the Director">OFFICE OF THE DIRECTOR</option>
                            <option value="Office of the Assistant Director">OFFICE OF THE ASSISTANT DIRECTOR</option>
                            </select>
                          </div>
                        </div><br>-->

                      

                          <div class="row mt-3">
                            <div class="col-md-8">
                              <div class="form-floating" id="docuDateDone">
                                <input type="date" class="form-control" name="dtsDateDone" id="dtsDateDone" value="" required>
                                <label for="dateDone" class="text-dark"><strong>Date Accomplished</strong><span class="text-danger">*</span></label>
                              </div>
                            </div>
                              </div><br>
                            <div class="col-md-8">
                              <div class="form-floating" id="docuActionNeeded">
                                <select class="form-select" aria-label="actionNeeded" name="dtsDocuActionNeeded" id="dtsDocuActionNeeded">
                                  <option value="" selected readonly>-- Action required --</option>
                                  <option value="FOR COMPLIANCE">FOR COMPLIANCE</option>
                                  <option value="FOR APPROPRIATE ACTION">FOR APPROPRIATE ACTION</option>
                                  <option value="FOR YOUR COMMENTS">FOR YOUR COMMENTS</option>
                                  <option value="APPROVED/ DISAPPROVED">APPROVED/ DISAPPROVED</option>
                                  <option value="PLEASE SEE ME">PLEASE SEE ME</option>
                                  <option value="FOR FILING">FOR FILING</option>
                                  <option value="FOR YOUR INFORMATION">FOR YOUR INFORMATION</option>
                                </select>
                                <label for="ActionNeeded" class="text-dark"><strong>Action Required</strong><span class="text-danger"></span></label>
                              </div>
                            </div><br>

                        <div class="row mt-3">
                        <div class="col-md-14">
                          <div class="form-floating">
                            <input type="text" class="form-control" name="dtsActionTaken" id="dtsActionTaken" value="" placeholder="Action Taken" required>
                            <label for="actionTaken" class="text-dark"><strong>Action Taken</strong>
                              <span class="text-danger">*</span></label>
                          </div><br>
                            <div class="col-md-15">
                                <div class="form-floating" id="dtsDocuRemarks">
                                  <input type="text" class="form-control" name="dtsDocuRemarks" id="dtsDocuRemarks" value="" placeholder="Remarks">
                                  <label for="remarks" class="text-dark"><strong>Remarks</strong><span class="text-danger"></span></label>
                                </div>

                                <div class="row mt-3">
                                  <div class="col-md-12">
                                    <div class="d-flex flex-row-reverse">
                                      <input type="text" name="docuID" id="docuID" value=<?= $key['docuID']; ?> hidden>
                                      <button type="submit" name="saveIncomingPC" value="savePersonConcerned" class="button button2" data-bs-toggle="tooltip" data-bs-placement="left" title="Save Information"><i class="far fa-save"></i> Save</button>
                                    </div> <!-- END DIV SUBMIT-->
                                  </div>
                               
                    </form>

    </main>


    <!--  M  U   L   T   I   P   L   E       S   E   L   E   C   T       F  I  E  L  D -->

    <script>
      $('#dtsPersonConcerned').select2({
        theme: "bootstrap-5",
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        closeOnSelect: false,
      });
    </script>
   
    <!--     S   A   V   E     F   O   R     S   T   A   T   U    S   -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script>
      function dtsSaveODIncoming() {
        if (id == "id") {
        
          var dtsDocuActionNeeded = document.getElementById("dtsDocuActionNeeded").value;
          var dtsDocuRemarks = document.getElementById("dtsDocuRemarks").value;
          var dtsActionTaken = document.getElementById("dtsActionTaken").value;
          var dtsDateDone = document.getElementById("dtsDateDone").value;
          var dtsDocuDivision = document.getElementById("dtsDocuDivision").value;
          var dtsDocuSection = document.getElementById("dtsDocuSection").value;
        }
      }


      if (dtsDocuActionNeeded_ == "" || dtsDocuRemarks_ == "" || dtsActionTaken_ == "" || dtsDateDone == "" || dtsDocuDivision =="" || dtsDocuSection == "") {
        $(".modal-header #headMsg").val("invalid!");
        $(".modal-body #message").val("Missing Field/s");
        $("#negative").modal("show");
      } else {
        // $(".modal-header #PheaderMsg").val("Success!");
        // $(".modal-body #Pmessage").val("Information Saved!");
        // $("#positive").modal("show");
        // windows.location.href = "../records/history.php?code=I";
        document.getElementById('SaveODIncoming').submit();
      }
    </script>

<script>
  function dtsUpdateStatus(){
    if (id == "id"){
      var statusType = document.getElementById("statusType").value;
    }
  }
  
  if(statusType_ == ""){
    $(".modal-header #headMsg").val("invalid!");
    $(".modal-body #message").val("Missing Field/s");
    $("#negative").modal("show");
  } else {
    document.getElementById('saveStatusUpdate').submit();
  }
  </script>

  <?php endforeach; ?>
 
  <?php include('../common/footer.php'); ?>