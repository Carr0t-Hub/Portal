<?php include('../common/header.php'); ?>
<?php include('../common/sidebar.php'); ?>

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

<?php foreach ($res as $key) : $docuID = $key['docuID']; ?>

<div class="page-wrapper">
	<div class="page-content">
    <div class="row">
      <div class="col">
        <div class="card-header d-flex justify-content-between">
          <div>
            <h6><img src="../assets/images/dts.png" class="img-circle" width="20" height="20"><span><b> Records Management Information System</span></b></h6>
          </div>
          <div>
            <a href="AddDocument.php" title="Add Incoming Document"><i><img src="../assets/img/addDoc.png" class="img-circle" width="50" height="50"></i></a>
            <!-- Button trigger modal -->
            <a href="history.php?code=I" class="button1 button3" data-bs-toggle="tooltip" data-bs-placement="left" title="Go Back"><i class="fas fa-backward"></i> Back</a>
            <!-- Modal -->
          </div>
        </div>
        <div class="docu-detail">
          <div class="det-br">
            <?php foreach ($res as $key) : ?>
              <?php $res1 = getAttachmentByID($mysqli, $key['attachment']); ?>
              <table class="table table-bordered table-hover table-responsive-sm table-responsive-md display" id="dataTable" width="100%" cellspacing="0">
                <tr>
                  <th>Reference Number: </th>
                  <td><?php echo strtoupper($key['referenceNo']); ?></td>
                </tr>
                <tr>
                  <th>Date & Time:</th>
                  <td><?php echo strtoupper($key['createdDateTime']); ?></td>
                </tr>
                <tr>
                  <th>Name of Sender:</th>
                  <td><?php echo strtoupper($key['sender']); ?></td>
                </tr>
                <tr>
                  <th>Document Type:</th>
                  <td><?php echo strtoupper($key['documentType']); ?></td>
                </tr>
                <tr>
                  <th>Date Received:</th>
                  <td><?php echo strtoupper($key['dateReceived']); ?></td>
                </tr>
                <tr>
                  <th>Subject:</th>
                  <td><?php echo strtoupper($key['particulars']); ?></td>
                </tr>
                <tr>
                  <th>Attachment:</th>
                  <td><button class="btn btn-outline-primary" onclick="window.open('../uploads/records/incoming/<?php echo $res1['fileName'] . '_' . $res1['size'] . $res1['id'] . '.' . $res1['fileExtension'] ?>','_blank')" class="btn btn-primary"><i class="bi bi-download"></i><?php echo $res1['fileName'] . '_' . $res1['size'] . $res1['id'] . '.' . $res1['fileExtension']; ?></a>
                  </td>
                </tr>
              </table>
            <?php endforeach; ?>
            <!--  V I E W   T A B L E   F O R   P E R S O N   C O N C E R N E D  -->
            <div class="card shadow mt-4 mb-4">
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
                    </tr>
                  </thead>
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
              </div>

              <!--  O F F I C E   O F   D I R E C T O R   I N C O M I N G   F I E L D   -->

              <div class="card-header d-flex justify-content-between">
                <h6 class="font-weight-bold">
                  <i data-toggle="modal" data-target="#modal-register_recieve"><img src="../assets/images/sendIncoming.png" class="img-circle" width="50" height="50"></i>
                  <span><strong> Take Action</strong></span>
                </h6>
                <div>
                  <span class="text-danger">Fill-out by the Office of the Director</span>
                </div>
              </div>
              <form method="POST" action="../process/records/records.php" id="SaveODIncoming" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="row mt-3">
                    <div class="col-md-6">
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
                    <div class="col-md-6">
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
                  <div class="row mt-3">
                    <div class="col-md-6">
                      <div class="form-floating" id="docuDateDone">
                        <input type="date" class="form-control" name="dtsDateDone" id="dtsDateDone" value="" required>
                        <label for="dateDone" class="text-dark"><strong>Date Accomplished</strong><span class="text-danger">*</span></label>
                      </div>
                    </div>
                    <div class="col-md-6">
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
                    </div>
                  </div><br>
                  <div class="row mt-3">
                    <div class="col-md-12">
                      <div class="form-floating">
                        <input type="text" class="form-control" name="dtsActionTaken" id="dtsActionTaken" value="" placeholder="Action Taken" required>
                        <label for="actionTaken" class="text-dark"><strong>Action Taken</strong>
                          <span class="text-danger">*</span></label>
                      </div><br>
                    </div>
                  </div>
                  <div class="row mt-3">
                    <div class="col-md-12">
                      <div class="form-floating" id="dtsDocuRemarks">
                        <input type="text" class="form-control" name="dtsDocuRemarks" id="dtsDocuRemarks" value="" placeholder="Remarks">
                        <label for="remarks" class="text-dark"><strong>Remarks</strong><span class="text-danger"></span></label>
                      </div>
                    </div>
                  </div>
                  <div class="row mt-3">
                    <div class="col-md-12">
                      <div class="d-flex flex-row-reverse">
                        <input type="text" name="docuID" id="docuID" value=<?= $key['docuID']; ?> hidden>
                        <button type="submit" name="saveIncomingPC" value="savePersonConcerned" class="button button2" data-bs-toggle="tooltip" data-bs-placement="left" title="Save Information"><i class="far fa-save"></i> Save</button>
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
  </div>
</div>

<?php endforeach; ?>


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

  <!--  M  U   L   T   I   P   L   E       S   E   L   E   C   T       F  I  E  L  D -->
  <script type="text/javascript">
    $('#dtsPersonConcerned').select2({
      theme: "bootstrap-5",
      width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
      placeholder: $(this).data('placeholder'),
      closeOnSelect: false,
    });
  </script>

  <!--     S   A   V   E     F   O   R     S   T   A   T   U    S   -->
  <script type="text/javascript">
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
      document.getElementById('SaveODIncoming').submit();
    }
  </script>

  <script type="text/javascript">
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

  <?php include('../common/footer.php'); ?>