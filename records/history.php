<?php include('../common/header.php'); ?>
<?php include('../common/sidebar.php'); ?>

<div class="page-wrapper">
  <div class="page-content">
    <div class="row">
      <div class="col">
        <div class="card shadow">
          <div class="card-header d-flex justify-content-between">
            <h4 class="m-0 font-weight-bold">
              <i><img src="../assets/images/listView.png" class="img-circle" width="40" height="40"></i>
              <span class="col-form-label"><strong>  List of Documents</strong></span>
            </h4>
            <div>
              <a href="AddDocument.php" title="Add Incoming Document"><i><img src="../assets/images/addDoc.png" class="img-circle" width="50" height="50"></i></a>
              <a href="AddOutgoing.php" title="Add Outgoing Document"><i><img src="../assets/images/outgoingmail.png" class="img-circle" width="50" height="50"></i></a>
              <a href="AddSpecialOrder.php" title="Add Special Order"><i><img src="../assets/images/so.png" class="img-circle" width="50" height="50"></i></a>
              <a href="AddMemorandum.php" title="Add Memorandum"><i><img src="../assets/images/memo.png" class="img-circle" width="50" height="50"></i></a>
              <a href="#" title="View Details"><i><img src="../assets/images/View.png" class="img-circle" width="50" height="50" hidden></i></a>
              <i id="printRecords" title="Print All Incoming Documents"><img src="../assets/images/Print.png" class="img-circle" width="50" height="50"></i>
            </div>
          </div>
          <div class="card-body">
            <div class="a">
              <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                  <button class="nav-link<?php if($_GET['code']=="I"){ echo "active";} ?>" id="nav-incoming-tab" onclick="window.location.href= 'history.php?code=I';" data-bs-toggle="tab" data-bs-target="#nav-incoming" type="button" role="tab" aria-controls="nav-incoming" aria-selected="true"><b>Incoming</b></button>
                  <button class="nav-link<?php if($_GET['code']=="O"){ echo "active";} ?>" id="nav-outgoing-tab" onclick="window.location.href= 'history.php?code=O';" data-bs-toggle="tab" data-bs-target="#nav-outgoing" type="button" role="tab" aria-controls="nav-outgoing" aria-selected="false"><b>Outgoing</b></button>
                  <button class="nav-link<?php if($_GET['code']=="SO"){ echo "active";} ?>" id="nav-specialorder-tab" onclick="window.location.href= 'history.php?code=SO';" data-bs-toggle="tab" data-bs-target="#nav-so" type="button" role="tab" aria-controls="nav-so" aria-selected="false"><b>Special Order</b></button>
                  <button class="nav-link<?php if($_GET['code']=="M"){ echo "active";} ?>" id="nav-specialorder-tab" onclick="window.location.href= 'history.php?code=M';" data-bs-toggle="tab" data-bs-target="#nav-memo" type="button" role="tab" aria-controls="nav-memo" aria-selected="false"><b>Memorandum</b></button>
                </div>
              </nav>
            </div>
            <input id="listofdocs" hidden type="text" value=<?php echo  $_GET['code'];?>> </input>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered table-striped table-hover table-responsive-sm table-responsive-md display" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                      <tr>
                        <th class="text-center align-middle">Reference Number</th>
                        <th class="text-center align-middle">Date Filed</th>
                        <th class="text-center align-middle">Sender</th>
                        <th class="text-center align-middle">Subject</th>
                        <th class="text-center align-middle">Document Type</th>
                        <th class="text-center align-middle">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        switch ($_GET['code']) {
                          case "I":
                            $incoming = "I";
                            $incomingDocus = dtsGetIncomingDocus($mysqli);
                            foreach ($incomingDocus as $v) :
                      ?>
                        <tr>
                          <td><strong><?= $v['referenceNo'] ?></strong></td>
                          <td><strong><?= date_format(date_create($v['createdDateTime']), 'M d, Y - H:i A') ?></strong></td>
                          <td><b><?= $v['sender'] ?></b></td>
                          <td><b><?=$v['subject']?></b></td>
                          <td><b><?= $v['documentType'] ?></b></td>
                          <td width="20%">
                            <form action="incomingDocs.php" method="POST">
                              <input type="text" hidden value="<?= $v['referenceNo']; ?>">
                              <button type="submit" class="btn btn-success"><i class="fas fa-eye"></i> View </button>
                              <a href="generate_pdf.php?referenceNo=<?=  $v['referenceNo']; ?>" class="btn button2"><i class="fa fa-print"></i> Print</a>
                            </form>
                          </td>
                        </tr>
                      <?php 
                          endforeach;
                          break;
                          case "O":
                              $outgoing = "O";
                              $outgoingDocus = dtsGetOutgoingDocus($mysqli);
                              foreach ($outgoingDocus as $o) :
                      ?>
                        <tr>
                          <td><b><?= $o['referenceNo'] ?></b></td>
                          <td><b><?= date_format(date_create($o['createdDateTime']), 'M d, Y - H:i A') ?></b></td>
                          <td><b><?= $o['sender'] ?></b></td>
                          <td><b><?= $o['subject']?></b></td>
                          <td><b><?= $o['documentType'] ?></b></td>
                          <td width="18%">
                            <form action="outgoingDocs.php" method="POST">
                              <input type="text" hidden name="referenceNo" id="referenceNo" value="<?= $o['referenceNo']; ?>">
                              <button type="submit" class="btn btn-success" name="viewOutgoingbtn" id="viewOutgoingbtn"><i class="fas fa-eye"></i> View </button>
                            </form>
                          </td>
                        </tr>
                      <?php 
                          endforeach;
                          break;
                          case "SO":
                              $special = "SO";
                              $specialOrderDocus = dtsGetSpecialOrderDocus($mysqli);
                              foreach ($specialOrderDocus as $so) :
                      ?>
                        <tr>
                          <td><b><?= $so['referenceNo'] ?></b></td>
                          <td><b><?= date_format(date_create($so['createdDateTime']), 'M d, Y - H:i A') ?></b></td>
                          <td><b>Junel B. Soriano, PhD.</b></td>
                          <td><b><?= $so['subject']?></b></td>
                          <td><b>Special Order</b></td>  
                          <td width="10%">
                            <form action="specialOrderDocs.php" method="POST">
                              <input type="text" hidden name="referenceNo" id="referenceNo" value="<?= $so['referenceNo']; ?>">
                              <button type="submit" class="btn btn-success" name="viewSpecialOrderbtn" id="viewSpecialOrderbtn"><i class="fas fa-eye"></i> View </button>
                            </form>
                          </td>
                        </tr>
                      <?php
                          endforeach;
                          break;
                          case "M":
                              $memo = "M";
                              $memorandumDocus = dtsGetMemorandumDocus($mysqli);
                              foreach ($memorandumDocus as $m) :
                      ?>
                        <tr>
                          <td><b><?= $m['referenceNo'] ?></b></td>
                          <td><b><?= date_format(date_create($m['createdDateTime']), 'M d, Y - H:i A') ?></b></td>
                          <td><b>Junel B. Soriano, PhD.</b></td>
                          <td><b><?= $m['subject']?></b></td>
                          <td><b>Memorandum</b></td>
                          <td width="10%">
                            <form action="memorandumDocs.php" method="POST">
                              <input type="text" hidden name="referenceNo" id="referenceNo" value="<?= $m['referenceNo']; ?>">
                              <button type="submit" class="btn btn-success" name="viewIncomingbtn" id="viewIncomingbtn"><i class="fas fa-eye"></i> View </button>
                            </form>
                          </td>
                        </tr>
                      <?php
                        endforeach;
                        break;
                        }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    var table = $('#dataTable').DataTable({
      paging: false,
      searching: true,
      info: false,
    });

    $('#dataTable tbody').on('click', 'tr', function (){ 
      $(this).toggleClass('selected');
      var oData2 = table.rows('.selected').data(); 
      $idz = [];
      for (var i=0; i < oData2.length ;i++){
        $idz.push(oData2[i][0]);
      }   
                    
      if ($idz ==""){
        $( "#printRecords").prop('disable',true);
      } else {
        $("#printRecords").prop('disable',false);
      }
    });

    $( "#printRecords" ).click(function() {
      var list_docs = $("#listofdocs").val();
      var jsonString = JSON.stringify($idz);

      if (list_docs == "I"){
        if ($idz ==""){
        } else {
          $.ajax({
            url: '../records/generate_pdf2.php',
            type: 'GET',
            data: {referenceNo:jsonString},
            content: "html",
            success:function(data){
              var win = window.open("generate_pdf2.php?referenceNo="+jsonString, '_blank');
            },
            error: function(errorMessage){
              console.log(errorMessage);
            }
          });
         }
      }   

      if (list_docs == "O"){
        if ($idz ==""){
        } else {
          $.ajax({
            url: '../records/generate_outgoing_pdf2.php',
            type: 'GET',
            data: {referenceNo:jsonString},
            content: "html",
            success: function(data){
              var win = window.open("generate_outgoing_pdf2.php?referenceNo="+jsonString,'_blank');
            },
            error: function(errorMessage){
              console.log(errorMessage);
            }
          });
        }
      }    
      if (list_docs == "SO"){
        alert("Special Order");
      }    
      if (list_docs == "M"){
        alert("Memorandum");
      }    
    });
  });

  function doThis() {
    var trigger = document.getElementById("incomingFieldDetails");
    if ((trigger.style.display == 'none')) {
      trigger.style.display = 'block';
    } else {
      trigger.style.display = 'none';
    }
  }

  window.onload = function() {
    $("#dataTable").on("click", ".viewDetails", function() {
      var referenceNo = $(this).data('id');
      alert(referenceNo);
    });
  };
</script>

<?php include('../common/footer.php'); ?>