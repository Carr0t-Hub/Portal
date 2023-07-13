<?php include('../common/header.php'); ?>
<?php include('../common/sidebar.php'); ?>
<?php
  if (isset($_SESSION['userID'])) {
    $res = get_user_info($mysqli);
    if ($res == false) {
      $Lna = "";
      $Fna = "";
      $Mna = "";
      $Ena = "";
      $Bda = "";

      $Bpl = "";
      $Gen = "";
      $Sta = "";
      $Hei = "";
      $Wei = "";
      $Blo = "";

      $Gsi = "";
      $Pag = "";
      $Phi = "";
      $Sss = "";
      $Tin = "";
      $Aen = "";

      $Cit = "";
      $Cib = "";
      $DCo = "";

      $RBl = "";
      $RSt = "";
      $RVi = "";
      $RBr = "";
      $RCi = "";
      $RPr = "";
      $RZi = "";

      $PBl = "";
      $PSt = "";
      $PVi = "";
      $PBr = "";
      $PCi = "";
      $PPr = "";
      $PZi = "";

      $Tel = "";
      $Mob = "";
      $Ema = "";
    } else {
      foreach ($res as $key) {
        $Lna =  htmlentities($key['lastName']);
        $Fna =  htmlentities($key['firstName']);
        $Mna =  htmlentities($key['middleName']);
        $Ena =  htmlentities($key['extensionName']);
        $Bda =  htmlentities($key['birthdate']);

        $Bpl =  htmlentities($key['birthplace']);
        $Gen = htmlentities($key['gender']);
        $Sta = htmlentities($key['civilStatus']);
        $Hei = htmlentities($key['height']);
        $Wei = htmlentities($key['weight']);
        $Blo = htmlentities($key['bloodType']);

        $Gsi = htmlentities($key['gsis']);
        $Pag = htmlentities($key['pagibig']);
        $Phi = htmlentities($key['philhealth']);
        $Sss = htmlentities($key['sss']);
        $Tin = htmlentities($key['tin']);
        $Aen = htmlentities($key['agencyEmployeeNum']);

        $Cit = htmlentities($key['citizenship']);
        $Cib = htmlentities($key['citizenBy']);
        $DCo = htmlentities($key['dualCitizenshipCountry']);

        $RBl = htmlentities($key['Res_block']);
        $RSt = htmlentities($key['Res_street']);
        $RVi = htmlentities($key['Res_village']);
        $RBr = htmlentities($key['Res_brgy']);
        $RCi = htmlentities($key['Res_city']);
        $RPr = htmlentities($key['Res_province']);
        $RZi = htmlentities($key['Res_zip']);

        $PBl = htmlentities($key['Per_block']);
        $PSt = htmlentities($key['Per_street']);
        $PVi = htmlentities($key['Per_village']);
        $PBr = htmlentities($key['Per_brgy']);
        $PCi = htmlentities($key['Per_city']);
        $PPr = htmlentities($key['Per_province']);
        $PZi = htmlentities($key['Per_zip']);

        $Tel = htmlentities($key['telephone']);
        $Mob = htmlentities($key['mobile']);
        $Ema = htmlentities($key['email']);
      }
    }
  } else {
    echo "<script> alert('Invalid'); window.location.href='../views/dashboard.php'</script> ";
  }
?>


<!-- Begin Page Content -->
<main id="main" class="main">
  <!-- Page Heading -->

  <div class="alert alert-primary alert-dismissible fade show" role="alert">
    <h5 class="alert-heading"><strong><i class="fas fa-envelope"></i> Welcome!</strong></h5>
    <p>This is your PDS page, you can update it every now and then or fully accomplish it in one-sitting! Your progress will be saved each time you press the <i>next</i> button found at the bottom of each page so don't forget to click it.</p>
    <button type="button" class="btn-close btn-sm shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <div class="card">
    <div class="card-header bg-dark text-light"><i class="fas fa-user"></i> Personal Information</div>
    <div class="card-body">
      <form action="../process/updatePersonalInfo.php" method="POST" data-toggle="validator" role="form" enctype="multipart/form-data">
        <div class="row p-1">
          <div class="col-md-4">
            <label for="firstName">First Name</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="firstName" name="firstName" value="<?php echo $Fna; ?>" required>
          </div>
          <div class="col-md-3">
            <label for="middleInitial">Middle Name</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="middleInitial" name="middleName" value="<?php echo $Mna; ?>">
          </div>
          <div class="col-md-3">
            <label for="lastName">Last Name</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="lastName" name="lastName" value="<?php echo $Lna; ?>" required>
          </div>
          <div class="col-md-2">
            <label for="extensionName">Extension Name</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="nameExtension" name="extensionName" value="<?php echo $Ena; ?>">
          </div>
        </div>

        <div class="row p-1">
          <div class="col-md-3">
            <label for="birthdate">Date of Birth</label>
            <input type="date" class="form-control form-control-sm" id="dob" name="birthdate" value="<?php echo $Bda; ?>" >
          </div>
          <div class="col-md-5">
            <label for="birthplace">Place of Birth</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="pob" name="birthplace" value="<?php echo $Bpl; ?>" >
          </div>
          <div class="col-md-2">
            <label for="gender">Sex</label>
            <select  class="form-control form-control-sm text-uppercase" id="sex" name="gender" required >
              <option value="">Choose...</option>
              <option value="MALE" <?php
                                    if ($Gen == 'MALE') {
                                      echo "selected";
                                    }
                                    ?>>Male</option>
              <option value="FEMALE" <?php
                                      if ($Gen == 'FEMALE') {
                                        echo "selected";
                                      }
                                      ?>>Female
              </option>
            </select>
          </div>
          <div class="col-md-2">
            <label for="civilStatus">Civil Status</label>
            <select class="form-control form-control-sm text-uppercase" id="civilStatus" name="civilStatus" required>
              <option value="">Choose...</option>
              <option value="SINGLE" <?php
                                      if ($Sta == 'SINGLE') {
                                        echo "selected";
                                      }
                                      ?>>Single</option>
              <option value="MARRIED" <?php
                                      if ($Sta == 'MARRIED') {
                                        echo "selected";
                                      }
                                      ?>>Married</option>
              <option value="ANNULLED" <?php
                                        if ($Sta == 'ANNULLED') {
                                          echo "selected";
                                        }
                                        ?>>Annulled</option>
              <option value="SEPARATED" <?php
                                        if ($Sta == 'SEPARATED') {
                                          echo "selected";
                                        }
                                        ?>>Separated</option>
              <option value="WIDOWED" <?php
                                      if ($Sta == 'WIDOWED') {
                                        echo "selected";
                                      }
                                      ?>>Widowed</option>
              <option value="DIVORCED" <?php
                                        if ($Sta == 'DIVORCED') {
                                          echo "selected";
                                        }
                                        ?>>Divorced</option>
            </select>
          </div>
        </div>

        <div class="row p-1">
          <div class="col-sm-2 md-2 lg-2">
            <label for="height">Height</label>
            <input class="form-control form-control-sm" id="height" name="height" placeholder="FT/CM" maxlength="6" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="<?= $Hei ?>" >
          </div>
          <div class="col-sm-2 md-2 lg-2">
            <label for="weight">Weight</label>
            <input class="form-control form-control-sm" id="weight" name="weight" placeholder="KGS/LBS" maxlength="6" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="<?= $Wei ?>" >
          </div>
          <div class="col-md-2">
            <label for="bloodType">Blood Type</label>
            <select class="form-control form-control-sm" id="bloodType" name="bloodType">
              <option value="">Choose...</option>
              <option value="A+" <?php
                                  if ($Blo == 'A+') {
                                    echo "selected";
                                  }
                                  ?>>A+</option>
              <option value="A-" <?php
                                  if ($Blo == 'A-') {
                                    echo "selected";
                                  }
                                  ?>>A-</option>

              <option value="B+" <?php
                                  if ($Blo == 'B+') {
                                    echo "selected";
                                  }
                                  ?>>B+</option>
              <option value="B-" <?php
                                  if ($Blo == 'B-') {
                                    echo "selected";
                                  }
                                  ?>>B-</option>

              <option value="AB+" <?php
                                  if ($Blo == 'AB+') {
                                    echo "selected";
                                  }
                                  ?>>AB+</option>
              <option value="AB-" <?php
                                  if ($Blo == 'AB-') {
                                    echo "selected";
                                  }
                                  ?>>AB-</option>

              <option value="O+" <?php
                                  if ($Blo == 'O+') {
                                    echo "selected";
                                  }
                                  ?>>O+</option>
              <option value="O-" <?php
                                  if ($Blo == 'O-') {
                                    echo "selected";
                                  }
                                  ?>>O-</option>
            </select>
          </div>
          <div class="col-md-2">
            <label for="telephone">Telephone</label>
            <input class="form-control form-control-sm" id="telNo" name="telephone" placeholder="8XXXXXXX" maxlength="9" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="<?= $Tel ?>">
          </div>
          <div class="col-md-2">
            <label for="mobile">Mobile No.</label>
            <input class="form-control form-control-sm" id="mobileNo" name="mobile" placeholder="09XXXXXXXXX" maxlength="11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="<?= $Mob ?>">
          </div>
          <div class="col-md-2">
            <label for="email">Email</label>
            <input type="email" class="form-control form-control-sm text-uppercase" id="emailAdd" name="email" value="<?= $Ema ?>">
          </div>
        </div>

        <div class="row p-1">
          <div class="col-md-4">
            <label for="citizenship">Citizenship</label>
            <select class="form-control form-control-sm text-uppercase" id="citizen" name="citizenship" >
              <option value="">Choose...</option>
              <option value="FILIPINO" <?php
                                        if ($Cit == 'FILIPINO') {
                                          echo "selected";
                                        }
                                        ?>>Filipino</option>
              <option value="DUAL" <?php
                                    if ($Cit == 'DUAL') {
                                      echo "selected";
                                    }
                                    ?>>Dual Citizenship</option>
            </select>
          </div>
          <div class="col-md-4">
            <label for="citizenBy">Dual Citizenship</label>
            <select class="form-control form-control-sm text-uppercase" id="citizenOther" name="citizenBy" <?php if ($Cib == "") {
                                                                                                              echo "disabled";
                                                                                                            } ?>>

              <option value="N/A" selected>Choose...</option>

              if($_POST['citizenship'] == 'Dual'){
              <option value="BY BIRTH" <?php
                                        if ($Cib == "BY BIRTH") {
                                          echo "selected";
                                        }
                                        ?>>By Birth</option>
              <option value="BY NATURALIZATION" <?php
                                                if ($Cib == "BY NATURALIZATION") {
                                                  echo "selected";
                                                }
                                                ?>>By Naturalization</option>

            </select>
          </div>
          <div class="col-md-4">
            <label for="dualCitizenshipCountry">Indicate Country</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="country" name="dualCitizenshipCountry" value="<?= $DCo ?>" <?php if ($Cib == "") {
                                                                                                                                                    echo "disabled";
                                                                                                                                                  } ?>>
          </div>
        </div>

        <div class="row p-1">
          <div class="col-md-2">
            <label for="gsis">GSIS ID No.</label>
            <input class="form-control form-control-sm" id="GSIS" name="gsis" value="<?= $Gsi ?>">
          </div>
          <div class="col-md-2">
            <label for="pagibig">PAGIBIG ID No.</label>
            <input class="form-control form-control-sm" id="PAGIBIG" name="pagibig" value="<?= $Pag ?>">
          </div>
          <div class="col-md-2">
            <label for="philhealth">PHILHEALTH No.</label>
            <input class="form-control form-control-sm" id="PHILHEALTH" name="philhealth" value="<?= $Phi ?>">
          </div>
          <div class="col-md-2">
            <label for="sss">SSS No.</label>
            <input class="form-control form-control-sm" id="SSS" name="sss" value="<?= $Sss ?>">
          </div>
          <div class="col-md-2">
            <label for="tin">TIN No.</label>
            <input class="form-control form-control-sm" id="TIN" name="tin" value="<?= $Tin ?>">
          </div>
          <div class="col-md-2">
            <label for="agencyEmployeeNum">Agency Emp. No.</label>
            <input class="form-control form-control-sm" id="empNo" name="agencyEmployeeNum" value="<?= $Aen ?>">
          </div>
        </div>

        <div class="row p-1">
          <div class="card-title text-dark">Residential Address</div>
          <div class="col-md-4">
            <label for="resBlock">House / Block / Lot No.</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="houseBlk" name="resBlock" value="<?= $RBl ?>">
          </div>
          <div class="col-md-4">
            <label for="resStreet">Street</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="street" name="resStreet" value="<?= $RSt ?>">
          </div>
          <div class="col-md-4">
            <label for="resVillage">Subdivision / Village</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="subVill" name="resVillage" value="<?= $RVi ?>">
          </div>
          <div class="col-md-4">
            <label for="resBrgy">Barangay</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="brgy" name="resBrgy" value="<?= $RBr ?>">
          </div>
          <div class="col-md-4">
            <label for="resCity">City / Municipality</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="cityMun" name="resCity" value="<?= $RCi ?>" >
          </div>
          <div class="col-sm-2 md-2 lg-2">
            <label for="resProvince">Province</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="province" name="resProvince" value="<?= $RPr ?>">
          </div>
          <div class="col-sm-2 md-2 lg-2">
            <label for="resZip">Zip</label>
            <input class="form-control form-control-sm" id="zipCode" name="resZip" maxlength="4" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="<?= $RZi ?>">
          </div>
        </div>

        <div class="row p-1">
          <div class="card-title text-dark">
            Permanent Address
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="permaAddress" value="permaAddress" onchange="CopyAdd();">
              <label class="form-check-label" for="permaAddress">
                (Same as Above)
              </label>
            </div>
          </div>

          <div class="col-md-4">
            <label for="perBlock">House / Block / Lot No.</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="permahouseBlk" name="perBlock" value="<?= $PBl ?>">
          </div>
          <div class="col-md-4">
            <label for="perStreet">Street</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="permaStreet" name="perStreet" value="<?= $PSt ?>">
          </div>
          <div class="col-md-4">
            <label for="perVillage">Subdivision / Village</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="permaSubVill" name="perVillage" value="<?= $PVi ?>">
          </div>
          <div class="col-md-4">
            <label for="perBrgy">Barangay</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="permaBrgy" name="perBrgy" value="<?= $PBr ?>">
          </div>
          <div class="col-md-4">
            <label for="perCity">City / Municipality</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="permaCityMun" name="perCity" value="<?= $PCi ?>">
          </div>
          <div class="col-sm-2 md-2 lg-2">
            <label for="perProvince">Province</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="permaProvince" name="perProvince" value="<?= $PPr ?>">
          </div>
          <div class="col-sm-2 md-2 lg-2">
            <label for="perZip">Zip</label>
            <input type="numer" class="form-control form-control-sm" id="permaZipCode" name="perZip" maxlength="4" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="<?= $PZi ?>">
          </div>
        </div>
    </div>

    <div class="card-footer">
      <div class="row p-1">
        <div class="col-md-12 g-2">
          <button type="submit" value="Next" class="form-control form-control-sm btn btn-md btn-success shadow-none">Next</button>
        </div>
      </div>
      </form>
    </div>

  </div>

</div>

<!-- /.container-fluid -->

<?php include('../common/footer.php'); ?>

<script type="text/javascript">
  // function under development for auto update every 30 seconds
  $(function() {
    setInterval(function() {
      var x = "<?php updatePersonalInfo($userID, $Lna, $Fna, $Mna, $Ena, $Bda, $Bpl, $Gen, $Sta, $Hei, $Wei, $Blo, $Gsi, $Pag, $Phi, $Sss, $Tin, $Aen, $Cit, $Cib, $DCo, $RBl, $RSt, $RVi, $RBr, $RCi, $RPr, $RZi, $PBl, $PSt, $PVi, $PBr, $PCi, $PPr, $PZi, $Tel, $Mob, $Ema, $mysqli); ?>";
      // alert(x);
    }, 30000);
  });
</script>