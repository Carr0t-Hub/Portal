<?php include('../common/header.php'); ?>
<?php include('../common/sidebar.php'); ?>
<style>
/* VIEW BUTTON */
.button {border-radius: 12px;
  background-color: #4CAF50; /* GREEN*/
  border: none;
  color: white;
  padding: 10px 20px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
  -webkit-transition-duration: 0.4s; /* Safari */
  transition-duration: 0.4s;
}
.button1:hover{
  background-color: #008CBA; /* BLUE */
  color: white;
}

/* PRINT BUTTON */
.btn {border-radius: 12px;
  background-color: #555555; /* BLACK */
  border: none;
  color: white;
  padding: 10px 20px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
  -webkit-transition-duration: 0.4s; /* Safari */
  transition-duration: 0.4s;
}
.button2:hover{
  background-color: #008CBA; /* BLUE */
  color: white;
}


</style>

<main id="main" class="main">
    <div class="pagetitle"><!-- Start Page Title -->
        <div class="container">
            <div class="card shadow mt-4 mb-4">
                <div class="card-header d-flex justify-content-between"  style="background-color:rgba(23, 95, 10, 1);">
                    <h4 class="m-0 font-weight-bold">
                        <i><img src="../assets/img/listView.png" class="img-circle" width="40" height="40"></i>
                        <span class="text-light col-form-label"><strong>  List of Documents</strong></span>
                    </h4>
                        <!--
                        <a href="receiveIncomingdocs.php" data-bs-toggle="tooltip" data-bs-placement="left" title="Receive Incoming Documents"><img src="../assets/img/incomingdetails.png" class="img-circle" width="40" height="40"></a>
                        <a href="receiveOutgoingdocs.php" data-bs-toggle="tooltip" data-bs-placement="left" title="Receive Outgoing Documents"><img src="../assets/img/outgoingdet.png" class="img-circle" width="40" height="40"></a>

-->
                    <div>
                        <a href="AddDocument.php" title="Add Incoming Document"><i><img src="../assets/img/addDoc.png" class="img-circle" width="50" height="50"></i></a>
                        <a href="AddOutgoing.php" title="Add Outgoing Document"><i><img src="../assets/img/outgoingmail.png" class="img-circle" width="50" height="50"></i></a>
                        <a href="AddSpecialOrder.php" title="Add Special Order"><i><img src="../assets/img/so.png" class="img-circle" width="50" height="50"></i></a>
                        <a href="AddMemorandum.php" title="Add Memorandum"><i><img src="../assets/img/memo.png" class="img-circle" width="50" height="50"></i></a>
                        <a href="#" title="View Details"><i><img src="../assets/img/View.png" class="img-circle" width="50" height="50" hidden></i></a>
                        <i id="printRecords" title="Print All Incoming Documents"><img src="../assets/img/Print.png" class="img-circle" width="50" height="50"></i>
                        
                </div>
                </div>
                <div class="card-body" style="background-color:rgba(17, 4, 18, 0.08);">
                <div class="a">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist" style="background-color:rgba(89, 173, 89, 1);">
                            <button class="nav-link<?php if($_GET['code']=="I"){ echo "active";} ?>" id="nav-incoming-tab" data-bs-toggle="tooltip" data-bs-placement="left" title="View Information" onclick="window.location.href= 'history.php?code=I';" type="button" role="tab" aria-controls="nav-incoming" aria-selected="true"><b>Incoming</b></button>
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
                        
                                    <th class="text-center" align ="left" style="background-color:rgba(222, 234, 166, 0.8);">Reference Number</th>
                                    <th class="text-center" style="background-color:rgba(222, 234, 166, 0.8);">Date Filed</th>
                                    <th class="text-center" style="background-color:rgba(222, 234, 166, 0.8);">Sender</th>
                                    <th class="text-center" style="background-color:rgba(222, 234, 166, 0.8);">Subject</th>
                                    <th class="text-center" style="background-color:rgba(222, 234, 166, 0.8);">Document Type</th>
                                    <th class="text-center" style="background-color:rgba(222, 234, 166, 0.8);">Action</th>
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
                                                        <input type="text" hidden name="referenceNo" id="referenceNo" value="<?= $v['referenceNo']; ?>">
                                                        <button type="submit" class="btn btn-success" name="viewIncomingbtn" id="viewIncomingbtn"><i class="fas fa-eye"></i> View </button>
                                                        <!-- <a href="Incomingroutingslip.php?referenceNo=<?=  $v['referenceNo']; ?>" class="btn btn-primary"><i class="fa fa-print"></i> Print</a> -->
                                                        <a href="generate_pdf.php?referenceNo=<?=  $v['referenceNo']; ?>" class="btn button2"><i class="fa fa-print"></i> Print</a>
                                                        <!-- <a href="Incomingroutingslip.php?id" class="btn btn-primary"><i class="fa fa-print"></i> Print</a> -->
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach;
                                        break;
                                    case "O":
                                        $outgoing = "O";
                                        $outgoingDocus = dtsGetOutgoingDocus($mysqli);
                                        foreach ($outgoingDocus as $o) :
                                        ?><tr>
                                              
                                                <td><b><?= $o['referenceNo'] ?></b></td>
                                                <td><b><?= date_format(date_create($o['createdDateTime']), 'M d, Y - H:i A') ?></b></td>
                                                <td><b><?= $o['sender'] ?></b></td>
                                                <td><b><?= $o['subject']?></b></td>
                                                <td><b><?= $o['documentType'] ?></b></td>
                                                <td width="18%">
                                                    <form action="outgoingDocs.php" method="POST">
                                                        <input type="text" hidden name="referenceNo" id="referenceNo" value="<?= $o['referenceNo']; ?>">
                                                        <button type="submit" class="btn btn-success" name="viewOutgoingbtn" id="viewOutgoingbtn"><i class="fas fa-eye"></i> View </button>
                                                        <!-- <a href="generate_outgoing_pdf.php?referenceNo=<?=  $o['referenceNo']; ?>" class="btn button2"><i class="fa fa-print"></i> Print</a> -->
                                                    </form>

                                                </td>

                                            </tr>
                                        <?php endforeach;

                                        break;
                                    case "SO":
                                        $special = "SO";
                                        $specialOrderDocus = dtsGetSpecialOrderDocus($mysqli);
                                        foreach ($specialOrderDocus as $so) :
                                        ?><tr>
                                             
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

                                            </tr><?php
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
                            <tbody>
                        </table>
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
					// data2 = oData2[i][1]
					    $idz = [];
					        for (var i=0; i < oData2.length ;i++){
					         $idz.push(oData2[i][0]);
					    }
                    
                    if ($idz ==""){
                        $( "#printRecords").prop('disable',true);
                    }
                    else{
                        $("#printRecords").prop('disable',false);
                    }
                    });
                    // $("#printRecords").click()
                    $( "#printRecords" ).click(function() {
                        var list_docs = $("#listofdocs").val();
                        var jsonString = JSON.stringify($idz);

                    if (list_docs == "I"){
                      
                       if ($idz ==""){
                        
                       }
                       else{
                        $.ajax({
                      url: '../records/generate_pdf2.php',
                      type: 'GET',
                      data: {referenceNo:jsonString},
                      content: "html",
                      success:function(data){
                            // var url = "../records/generate_pdf2.php?referenceNo=".jsonString;
                            // window.location = url;
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
                      }
                      else{
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
                    // $.ajax({
                    //   url: '../process/records/records.php',
                    //   type: 'post',
                    //   data: {referenceNo_:referenceNo},
                    //   dataType: "json",
                    //   success:function(response){
                    //     for( var i = 0; i<response.length; i++){
                    //       var referenceNum = response[i]['referenceNo'];
                    //       // var category = response[i]['category'];
                    //     }

                    //     $(".modal-body #referenceNo").val(referenceNum);

                    //     $("#viewDocu").modal("show");
                    //   }
                    // });
                });
            };
        </script>

<?php include('../common/footer.php'); ?>