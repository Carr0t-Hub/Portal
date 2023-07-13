<?php include('common/header.php'); ?>

<?php include('common/sidebar.php'); ?>
<?php
if (isset($_SESSION['userID'])) {
  $OtherInfo = getOtherInfo($mysqli);
  if ($OtherInfo == false) {
    $n34_a =  "";
    $n34_b = "";
    $n34_b_details = "";
    $n35_a = "";
    $n35_a_details = "";
    $n35_b =  "";
    $n35_b_dateFiled =  "";
    $n35_b_status = "";
    $n36 = "";
    $n36_details = "";
    $n37 = "";
    $n37_details = "";
    $n38_a = "";
    $n38_a_details = "";
    $n38_b = "";
    $n38_b_details = "";
    $n39 = "";
    $n39_details = "";
    $n40_a = "";
    $n40_a_details = "";
    $n40_b = "";
    $n40_b_details = "";
    $n40_c = "";
    $n40_c_details = "";
  } else {
    foreach ($OtherInfo as $key) {
      $n34_a =  htmlentities($key['num34_a']);
      $n34_b =  htmlentities($key['num34_b']);
      $n34_b_details =  htmlentities($key['num34_b_details']);
      $n35_a =  htmlentities($key['num35_a']);
      $n35_a_details =  htmlentities($key['num35_a_details']);
      $n35_b =  htmlentities($key['num35_b']);
      $n35_b_dateFiled =  htmlentities($key['num35_b_dateFiled']);
      $n35_b_status = htmlentities($key['num35_b_status']);
      $n36 = htmlentities($key['num36']);
      $n36_details = htmlentities($key['num36_details']);
      $n37 = htmlentities($key['num37']);
      $n37_details = htmlentities($key['num37_details']);
      $n38_a = htmlentities($key['num38_a']);
      $n38_a_details = htmlentities($key['num38_a_details']);
      $n38_b = htmlentities($key['num38_b']);
      $n38_b_details = htmlentities($key['num38_b_details']);
      $n39 = htmlentities($key['num39']);
      $n39_details = htmlentities($key['num39_details']);
      $n40_a = htmlentities($key['num40_a']);
      $n40_a_details = htmlentities($key['num40_a_details']);
      $n40_b = htmlentities($key['num40_b']);
      $n40_b_details = htmlentities($key['num40_b_details']);
      $n40_c = htmlentities($key['num40_c']);
      $n40_c_details = htmlentities($key['num40_c_details']);
    }
  }

  $ReferenceInfo = getReferenceInfo($mysqli);

  $IssuedIDInfo = getIssuedIDInfo($mysqli);
  if ($IssuedIDInfo == false) {
    $INa =  "";
    $INo =  "";
    $DPI =  "";
  }
  foreach ($IssuedIDInfo as $key) {
    $INa =  htmlentities($key['IDName']);
    $INo =  htmlentities($key['IDNo']);
    $DPI =  htmlentities($key['datePlaceIssuance']);
  }
}
else{
  echo "<script> alert('Invalid'); window.location.href='/'</script> ";
}
?>

<!-- Begin Page Content -->
<main id="main" class="main">
  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800">Personal Data Sheet</h1>
  <div class="card">
    <div class="card-header bg-dark text-light">Other Information</div>
    <div class="card-body">
      <form action="../process/updateOther2.php" method="POST" data-toggle="validator" role="form" enctype="multipart/form-data">
        <div class="row p-1">
          <div class="col-md-12">
            <p class="card-text">Are you related by consanguinity or affinity to the appointing or recommending authority, or to the chief of bureau or office or to the person who has immediate supervision over you in the Office, Bureau of Department where you will be appointed,</p>
            <label for="n34_a">within the third degree?</label>
            <select class="form-control form-control-sm text-uppercase" id="n34_a" name="num34_a">
              <option value="">Choose...</option>
              <option value="YES" <?php
                                  if ($n34_a == 'YES') {
                                    echo "selected";
                                  }
                                  ?>>Yes</option>

              <option value="NO" <?php
                                  if ($n34_a == 'NO') {
                                    echo "selected";
                                  }
                                  ?>>No</option>
            </select>
          </div>
        </div>

        <div class="row p-1">
          <div class="col-md-6">
            <label for="num34_b">within the fourth degree (for LGU - Career Employees)?</label>
            <select class="form-control form-control-sm text-uppercase" id="34b" name="num34_b">
              <option value="">Choose...</option>
              <option value="YES" <?php
                                  if ($n34_b == 'YES') {
                                    echo "selected";
                                  }
                                  ?>>Yes</option>
              <option value="NO" <?php
                                  if ($n34_b == 'NO') {
                                    echo "selected";
                                  }
                                  ?>>No</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="34b">If YES, Give Details</label>
            <input type="text" id="34bDetails" name="num34_b_details" class="form-control form-control-sm text-uppercase" value="<?php echo $n34_b_details; ?>" readonly>
          </div>
        </div>

        <hr>

        <div class="row p-1">
          <div class="col-md-6">
            <label for="num35_a">Have you ever been found guilty of any administrative offense?</label>
            <select class="form-control form-control-sm text-uppercase" id="35a" name="num35_a">
              <option value="">Choose...</option>
              <option value="YES" <?php
                                  if ($n35_a == 'YES') {
                                    echo "selected";
                                  }
                                  ?>>Yes</option>
              <option value="NO" <?php
                                  if ($n35_a == 'NO') {
                                    echo "selected";
                                  }
                                  ?>>No</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="35aDetails">If YES, Give Details</label>
            <input type="text" id="35aDetails" name="num35_a_details" class="form-control form-control-sm text-uppercase" value="<?php echo $n35_a_details; ?>" readonly>
          </div>
        </div>

        <div class="row p-1">
          <div class="col-md-6">
            <label for="num35_b">Have you been criminally charged before any court?</label>
            <select class="form-control form-control-sm text-uppercase" id="35b" name="num35_b">
              <option value="">Choose...</option>
              <option value="YES" <?php
                                  if ($n35_b == 'YES') {
                                    echo "selected";
                                  }
                                  ?>>Yes</option>
              <option value="NO" <?php
                                  if ($n35_b == 'NO') {
                                    echo "selected";
                                  }
                                  ?>>No</option>
            </select>
          </div>
          <div class="col-md-3">
            <label for="num35_b_dateFiled">If YES, Give Details (Date Filed)</label>
            <input type="date" id="35bDetails1" name="num35_b_dateFiled" class="form-control form-control-sm" value="<?php echo $n35_b_dateFiled; ?>" readonly>
          </div>
          <div class="col-md-3">
            <label for="num35_b_status">Status of Case</label>
            <input type="text" id="35bDetails2" name="num35_b_status" class="form-control form-control-sm text-uppercase" value="<?php echo $n35_b_status; ?>" readonly>
          </div>
        </div>

        <hr>

        <div class="row p-1">
          <div class="col-md-6">
            <label for="num36">Have you ever been convicted of any crime or violation of any law, decree, ordinance or regulation by any court or tribunal?</label>
            <select class="form-control form-control-sm text-uppercase" id="36a" name="num36">
              <option value="">Choose...</option>
              <option value="YES" <?php
                                  if ($n36 == 'YES') {
                                    echo "selected";
                                  }
                                  ?>>Yes</option>
              <option value="NO" <?php
                                  if ($n36 == 'NO') {
                                    echo "selected";
                                  }
                                  ?>>No</option>
            </select>
          </div>
          <div class="col-md-6 mt-3">
            <label for="num36_details">If YES, Give Details</label>
            <input type="text" id="36aDetails" name="num36_details" class="form-control form-control-sm text-uppercase" value="<?php echo $n36_details; ?>" readonly>
          </div>
        </div>

        <hr>

        <div class="row p-1">
          <div class="col-md-6">
            <label for="num37">Have you ever been separated from the service in any of the following modes: resignation, retirement, dropped from the rolls, dismissal, termination, end of term, finished contract or phased out (abolition) in the public or private sector?</label>
            <select class="form-control form-control-sm text-uppercase" id="37a" name="num37">
              <option value="">Choose...</option>
              <option value="YES" <?php
                                  if ($n37 == 'YES') {
                                    echo "selected";
                                  }
                                  ?>>Yes</option>
              <option value="NO" <?php
                                  if ($n37 == 'NO') {
                                    echo "selected";
                                  }
                                  ?>>No</option>
            </select>
          </div>
          <div class="col-md-6 mt-5">
            <label for="num37_details">If YES, Give Details</label>
            <input type="text" id="37aDetails" name="num37_details" class="form-control form-control-sm text-uppercase" value="<?php echo $n37_details; ?>" readonly>
          </div>
        </div>

        <hr>

        <div class="row p-1">
          <div class="col-md-6">
            <label for="num38_a">Have you ever been a candidate in a national or local election held within the last year (except Barangay election)?</label>
            <select class="form-control form-control-sm text-uppercase" id="38a" name="num38_a">
              <option value="">Choose...</option>
              <option value="YES" <?php
                                  if ($n38_a == 'YES') {
                                    echo "selected";
                                  }
                                  ?>>Yes</option>
              <option value="NO" <?php
                                  if ($n38_a == 'NO') {
                                    echo "selected";
                                  }
                                  ?>>No</option>
            </select>
          </div>
          <div class="col-md-6 mt-3">
            <label for="num38_a_details">If YES, Give Details</label>
            <input type="text" id="38aDetails" name="num38_a_details" class="form-control form-control-sm text-uppercase" value="<?php echo $n38_a_details; ?>" readonly>
          </div>
        </div>

        <div class="row p-1">
          <div class="col-md-6">
            <label for="num38_b">Have you resigned from the government service during the three (3)-month period before the last election to promote/actively campaign for a national or local candidate?</label>
            <select class="form-control form-control-sm text-uppercase" id="38b" name="num38_b">
              <option value="">Choose...</option>
              <option value="YES" <?php
                                  if ($n38_b == 'YES') {
                                    echo "selected";
                                  }
                                  ?>>Yes</option>
              <option value="NO" <?php
                                  if ($n38_b == 'NO') {
                                    echo "selected";
                                  }
                                  ?>>No</option>
            </select>
          </div>
          <div class="col-md-6 mt-4">
            <label for="num38_b_details">If YES, Give Details</label>
            <input type="text" id="38bDetails" name="num38_b_details" class="form-control form-control-sm text-uppercase" value="<?php echo $n38_b_details; ?>" readonly>
          </div>
        </div>

        <hr>

        <div class="row p-1">
          <div class="col-md-6">
            <label for="num39">Have you acquired the status of an immigrant or permanent resident of another country?</label>
            <select class="form-control form-control-sm text-uppercase" id="39a" name="num39">
              <option value="">Choose...</option>
              <option value="YES" <?php
                                  if ($n39 == 'YES') {
                                    echo "selected";
                                  }
                                  ?>>Yes</option>
              <option value="NO" <?php
                                  if ($n39 == 'NO') {
                                    echo "selected";
                                  }
                                  ?>>No</option>
            </select>
          </div>
          <div class="col-md-6 mt-2">
            <label for="num39_details">If YES, Give Details (Country)</label>
            <input type="text" id="39aDetails" name="num39_details" class="form-control form-control-sm text-uppercase" value="<?php echo $n39_details; ?>" readonly>
          </div>
        </div>

        <hr>

        <div class="row p-1">
          <p class="card-text">Pursuant to:(a) Indigenous People's Act (RA8371); (b) Magna Carta for Disabled Persons (RA7277); and (c) Solo Parents Welfare Act of 2000 (RA8972), Please answer for the following items:</p>
          <div class="col-md-6">
            <label for="num40_a">Are you a member of any indigenous group?</label>
            <select class="form-control form-control-sm text-uppercase" id="40a" name="num40_a">
              <option value="">Choose...</option>
              <option value="YES" <?php
                                  if ($n40_a == 'YES') {
                                    echo "selected";
                                  }
                                  ?>>Yes</option>
              <option value="NO" <?php
                                  if ($n40_a == 'NO') {
                                    echo "selected";
                                  }
                                  ?>>No</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="num40_a_details">If YES, Give Specify</label>
            <input type="text" id="40aDetails" name="num40_a_details" class="form-control form-control-sm text-uppercase" value="<?php echo $n40_a_details; ?>" readonly>
          </div>
        </div>

        <div class="row p-1">
          <div class="col-md-6">
            <label for="num40_b">Are you a person with disability?</label>
            <select class="form-control form-control-sm text-uppercase" id="40b" name="num40_b">
              <option value="">Choose...</option>
              <option value="YES" <?php
                                  if ($n40_b == 'YES') {
                                    echo "selected";
                                  }
                                  ?>>Yes</option>
              <option value="NO" <?php
                                  if ($n40_b == 'NO') {
                                    echo "selected";
                                  }
                                  ?>>No</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="num40_b_details">If YES, Give Specify ID No.</label>
            <input type="text" id="40bDetails" name="num40_b_details" class="form-control form-control-sm text-uppercase" value="<?php echo $n40_b_details; ?>" readonly>
          </div>
        </div>

        <div class="row p-1">
          <div class="col-md-6">
            <label for="num40_c">Are you a solo parent?</label>
            <select class="form-control form-control-sm text-uppercase" id="40c" name="num40_c">
              <option value="">Choose...</option>
              <option value="YES" <?php
                                  if ($n40_c == 'YES') {
                                    echo "selected";
                                  }
                                  ?>>Yes</option>
              <option value="NO" <?php
                                  if ($n40_c == 'NO') {
                                    echo "selected";
                                  }
                                  ?>>No</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="num40_c_details">If YES, Give Specify ID No.</label>
            <input type="text" id="40cDetails" name="num40_c_details" class="form-control form-control-sm text-uppercase" value="<?php echo $n40_c_details; ?>" readonly>
          </div>
        </div>

        <hr>

        <div class="card-title">REFERENCES</div>
        <?php
        $countReference = 1;
        if ($ReferenceInfo == false) {
          echo ('<div id="referenceField">');
          echo ('<div class="row p-1" id="removeRefer">');
          echo ('<div class="col-md-3">
                <label for="referenceName">Name</label>
                <input type="text" id="refName" name="referenceName' . $countReference . '" class="form-control form-control-sm text-uppercase">
              </div>');
          echo ('<div class="col-md-6">
                <label for="address">Address</label>
                <input type="text" id="refAdd" name="address' . $countReference . '" class="form-control form-control-sm text-uppercase">
              </div>');
          echo ('<div class="col-md-2">
                <label for="phoneNumber">Telephone No.</label>
                <input type="text" id="refTel" name="phoneNumber' . $countReference . '" class="form-control form-control-sm" maxlength="11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
              </div>');
          echo ('<div class="col-md-1">
                <label for="addRef">ADD</label>
                <input type="button" class="btn btn-warning form-control form-control-sm" name="addRef" id="addRef" value="+">
              </div>');
          echo ('</div>'); //END ROW
        } else {
          echo ('<div id="referenceField">');
          foreach ($ReferenceInfo as $key) {
            $countReference++;
            echo ('<div class="row p-1" id="removeRefer">');
            echo ('<div class="col-md-3">
                <label for="referenceName">Name</label>
                <input type="text" id="refName" name="referenceName' . $countReference . '" class="form-control form-control-sm text-uppercase" value="' . $key['referenceName'] . '">
              </div>');
            echo ('<div class="col-md-6">
                <label for="address">Address</label>
                <input type="text" id="refAdd" name="address' . $countReference . '" class="form-control form-control-sm text-uppercase" value="' . $key['address'] . '">
              </div>');
            echo ('<div class="col-md-2">
                <label for="phoneNumber">Telephone No.</label>
                <input type="text" id="refTel" name="phoneNumber' . $countReference . '" class="form-control form-control-sm" maxlength="11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="' . $key['phoneNumber'] . '">
              </div>');
            if ($key['rank'] == 1) {
              echo ('<div class="col-md-1">
                  <label for="addRef">ADD</label>
                  <input type="button" class="btn btn-warning form-control form-control-sm" name="addRef" id="addRef" value="+">
                </div>');
            } else {
              echo ('<div class="col-md-1">');
              echo ('<label for="removeRef">REMOVE</label>');
              echo ('<input type="button" class="btn btn-danger form-control form-control-sm" name="removeRef" id="removeRef" value="-" ">');
              echo ('</div>');
            }

            echo ('</div>'); //END ROW
          }
        } // END ELSE
        echo ('</div>'); //END REFERENCE FIELD
        ?>


        <hr>

        <div class="card-title">Government Issued ID</div>
        <div class="row p-1">
          <div class="col-md-4">
            <label for="IDName">Government ID</label>
            <select class="form-control form-control-sm text-uppercase" id="govID" name="IDName">
              <option value="">Choose...</option>
              <option value="DRIVER`S LICENSE" <?php
                                                if ($INa == "DRIVER`S LICENSE") {
                                                  echo "selected";
                                                }
                                                ?>>Driver's License</option>
              <option value="OFW ID" <?php
                                      if ($INa == "OFW ID") {
                                        echo "selected";
                                      }
                                      ?>>OFW ID</option>
              <option value="PASSPORT" <?php
                                        if ($INa == "PASSPORT") {
                                          echo "selected";
                                        }
                                        ?>>Passport</option>
              <option value="PHILHEALTH ID" <?php
                                            if ($INa == "PHILHEALTH ID") {
                                              echo "selected";
                                            }
                                            ?>>PhilHealth ID</option>
              <option value="POSTAL ID" <?php
                                        if ($INa == "POSTAL ID") {
                                          echo "selected";
                                        }
                                        ?>>Postal ID</option>
              <option value="PRC ID" <?php
                                      if ($INa == "PRC ID") {
                                        echo "selected";
                                      }
                                      ?>>PRC ID</option>
              <option value="SENIOR CITIZEN ID" <?php
                                                if ($INa == "SENIOR CITIZEN ID") {
                                                  echo "selected";
                                                }
                                                ?>>Senior Citizen ID</option>
              <option value="TIN ID" <?php
                                      if ($INa == "TIN ID") {
                                        echo "selected";
                                      }
                                      ?>>TIN ID</option>
              <option value="UMID" <?php
                                    if ($INa == "UMID") {
                                      echo "selected";
                                    }
                                    ?>>UMID</option>
              <option value="VOTER`S ID" <?php
                                          if ($INa == "VOTER`S ID") {
                                            echo "selected";
                                          }
                                          ?>>Voter's ID</option>
            </select>
          </div>
          <div class="col-md-5">
            <label for="IDNo">ID No.</label>
            <input type="text" id="govIDNo" name="IDNo" class="form-control form-control-sm text-uppercase" value="<?php echo $INo; ?>">
          </div>
          <div class="col-md-3">
            <label for="datePlaceIssuance">Date and Place of Issuance</label>
            <input type="text" id="dateIssued" name="datePlaceIssuance" class="form-control form-control-sm text-uppercase" value="<?php echo $DPI; ?>">
          </div>
        </div>
    </div>
    <!-- END CARD DIV -->

    <div class="card-footer">
      <div class="row p-1">
        <div class="col-md-6 g-2 d-flex text-left">
          <a href="personal_data_sheet_8.php" class="form-control form-control-sm btn btn-md btn-secondary">Back</a>
        </div>
        <div class="col-md-6 g-2 d-flex text-right">
          <button type="submit" value="Finish" class="form-control form-control-sm btn btn-md btn-success">Finish</button>
        </div>
      </div>
    </div>

    </form>
  </div>

  <!-- </div> -->

</div>
<!-- /.container-fluid -->
<script src="js/ajax//libs/jquery/3.1.1/jquery-3.1.1.min.js"></script>
<!-- Reference -->
<script type="text/javascript">
  countReference = <?= $countReference ?>;
  $(document).ready(function() {
    var max = 3;
    var x = 1;

    $("#addRef").click(function() {
      countReference++;
      if (x < max) {
        $('#referenceField').append('<div class="row p-1" id="removeRefer"><div class="col-md-3"><label for="referenceName">Name</label><input type="text" id="refName" name="referenceName' + countReference + '" class="form-control form-control-sm text-uppercase"></div><div class="col-md-6"><label for="address">Address</label><input type="text" id="refAdd" name="address' + countReference + '" class="form-control form-control-sm text-uppercase"></div><div class="col-md-2"><label for="phoneNumber">Telephone No.</label><input type="text" id="refTel" name="phoneNumber' + countReference + '" class="form-control form-control-sm" maxlength="11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"></div><div class="col-md-1"><label for="removeRef">Remove</label><input type="button" class="btn btn-danger form-control form-control-sm" name="removeRef" id="removeRef" value="-"></div></div>');
        x++;
      }
    });

    $('#referenceField').on('click', '#removeRef', function() {
      $(this).closest('#removeRefer').remove();
      x--;
    });

  });
</script>
<?php include('common/footer.php'); ?>