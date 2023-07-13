<?php include('common/header.php'); ?>
<?php include('common/sidebar.php'); ?>
<?php
if (isset($_SESSION['userID'])) {
  $familyBackground = getSpouseInfo($mysqli);
  if ($familyBackground == false) {
    $Lna = "";
    $Fna = "";
    $Mna = "";
    $Ena = "";

    $Ocu = "";
    $Emp = "";
    $BAd = "";
    $Tel = "";
  } else {
    foreach ($familyBackground as $key) {
      $Lna =  htmlentities($key['lastName']);
      $Fna =  htmlentities($key['firstName']);
      $Mna =  htmlentities($key['middleName']);
      $Ena =  htmlentities($key['extensionName']);

      $Ocu = htmlentities($key['occupation']);
      $Emp = htmlentities($key['employer']);
      $BAd = htmlentities($key['businessAddress']);
      $Tel = htmlentities($key['telephone']);
    }
  }


  $Children = getChildInfo($mysqli);
  $ParentsInfo = getParentsInfo($mysqli);

  if ($ParentsInfo == false) {
    $FLa = "";
    $FFi = "";
    $FEx = "";
    $FMi = "";
    $MLa = "";
    $MFi = "";
    $MMi = "";
  } else {
    foreach ($ParentsInfo as $key) {
      $FLa =  htmlentities($key['FatherLastName']);
      $FFi =  htmlentities($key['FatherFirstName']);
      $FEx =  htmlentities($key['FatherExtensionName']);
      $FMi =  htmlentities($key['FatherMiddleName']);
      $MLa =  htmlentities($key['MotherMaidenLastName']);
      $MFi =  htmlentities($key['MotherFirstName']);
      $MMi =  htmlentities($key['MotherMiddleName']);
    }
  }
} else {
  echo "<script> alert('Invalid'); window.location.href='/'</script> ";
}
?>


<!-- Begin Page Content -->
<main id="main" class="main">
  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800">Personal Data Sheet</h1>
  <div class="card">
    <div class="card-header bg-dark text-light"><i class="fas fa-users"></i> Family Background</div>
    <div class="card-body">
      <form action="../process/updateFamilyBackground.php" method="POST" data-toggle="validator" role="form" enctype="multipart/form-data">
        <div class="row p-1">
          <div class="col-md-4">
            <label for="lastName">Spouse's Last Name</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="lastName" name="lastName" value="<?php echo $Lna; ?>">
          </div>
          <div class="col-md-4">
            <label for="firstName">First Name</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="firstName" name="firstName" value="<?php echo $Fna; ?>">
          </div>
          <div class="col-md-2">
            <label for="middleInitial">Middle Name</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="middleInitial" name="middleName" value="<?php echo $Mna; ?>">
          </div>
          <div class="col-md-2">
            <label for="nameExtension">Extension Name</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="nameExtension" name="extensionName" value="<?php echo $Ena; ?>">
          </div>
        </div>

        <div class="row p-1">
          <div class="col-md-4">
            <label for="businessName">Employee / Business Name</label>
            <textarea name="employer-business" id="businessName" cols="2" rows="2" class="form-control form-control-sm text-uppercase"><?= $Emp ?></textarea>
          </div>
          <div class="col-md-4">
            <label for="businessAdd">Business Address</label>
            <textarea name="businessAddress" id="businessAdd" cols="2" rows="2" class="form-control form-control-sm text-uppercase"><?= $BAd ?></textarea>
          </div>
          <div class="col-md-2">
            <label for="occupation">Occupation</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="occupation" name="occupation" value="<?= $Ocu ?>">
          </div>
          <div class="col-md-2">
            <label for="businessTel">Telephone No.</label>
            <input class="form-control form-control-sm" id="businessTel" name="telephone" value="<?= $Tel ?>">
          </div>
        </div>

        <div class="row p-1">
          <div class="col-md-4">
            <label for="fatherLName">Father's Last Name</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="fatherLName" name="FatherLastName" value="<?= $FLa ?>">
          </div>
          <div class="col-md-4">
            <label for="fatherFName">First Name</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="fatherFName" name="FatherFirstName" value="<?= $FFi ?>">
          </div>
          <div class="col-md-2">
            <label for="fatherMName">Middle Name</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="fatherMName" name="FatherMiddleName" value="<?= $FMi ?>">
          </div>
          <div class="col-md-2">
            <label for="fatherExtName">Extension Name</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="fatherExtName" name="FatherExtensionName" value="<?= $FEx ?>">
          </div>
        </div>

        <div class="row p-1">
          <div class="col-md-3">
            <label for="motherMaiName">Mother's Maiden Name</label>
            <!-- <input type="text" class="form-control form-control-sm" id="motherMaiName" name="motherMaiName"> -->
          </div>
          <div class="col-md-3">
            <label for="motherLName">Last Name</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="motherLName" name="MotherMaidenLastName" value="<?= $MLa ?>">
          </div>
          <div class="col-md-3">
            <label for="motherFName">First Name</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="motherFName" name="MotherFirstName" value="<?= $MFi ?>">
          </div>
          <div class="col-md-3">
            <label for="motherMName">Middle Name</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="motherMName" name="MotherMiddleName" value="<?= $MMi ?>">
          </div>
        </div>

        <div class="row p-1">
          <?php
          $countChild = 1;
          if ($Children == false) {
            echo ('<div class="card-body" id="moreFields">');
            echo ('<div class="row p-1" >');
            echo ('<div class="col-md-7">');
            echo ('<label for="childName">Name of Child</label>');
            echo ('<input type="text" class="form-control form-control-sm text-uppercase" id="eligibility" name="childName' . $countChild . '">
                  </div>');
            echo ('<div class="col-md-3">');
            echo ('<label for="birthdate">Date of Birth</label>');
            echo ('<input type="date" class="form-control form-control-sm" id="examinationDate" name="birthdate' . $countChild . '">
                  </div>');
            echo ('<div class="col-md-1">');
            echo ('<label for="addMore">ADD</label>');
            echo ('<input type="button" class="btn btn-sm btn-warning form-control form-control-sm" name="addMore" id="addMore" value="+">
                  </div>
                  </div>');
          } else {
            echo ('<div class="card-body" id="moreFields">');
            foreach ($Children as $key) {

              $countChild++;
              echo ('<div class="row p-1" id="removeHere">');
              echo ('<div class="col-md-7">');
              echo ('<label for="childName">Name of Child</label>');
              echo ('<input type="text" class="form-control form-control-sm text-uppercase" id="child' . $countChild . '" name="childName' . $countChild . '" value="' . $key['childName'] . '">
                    </div>');
              echo ('<div class="col-md-3">');
              echo ('<label for="birthdate">Date of Birth</label>');
              echo ('<input type="date" class="form-control form-control-sm" id="birthdate" name="birthdate' . $countChild . '" value="' . htmlentities($key['birthdate']) . '">
                    </div>');
              if ($key['rank'] == 1) {
                echo ('<div class="col-md-1">');
                echo ('<label for="addMore">ADD</label>');
                echo ('<input type="button" class="btn btn-sm btn-warning form-control form-control-sm" name="addMore" id="addMore" value="+">
                      </div>');
                echo ('</div>');
              } else {
                echo ('<div class="col-md-1">');
                echo ('<label for="removeME">REMOVE</label>');
                echo ('<input type="button" class="btn btn-danger form-control form-control-sm" name="removeMe" id="removeMe" value="-" ">
                      </div>');
                echo ('</div>');
              }
            } // END FOR EACH
          }
          echo ('</div>');
          echo ('<input type="hidden" name="countChild" value="<?= $countChild ?>">');
          ?>
        </div>
        <!-- END CHILDREN -->
    </div>
    <div class="card-footer">
      <div class="row p-1">
        <div class="col-md-6 g-2 d-flex text-left">
          <!-- <button type="submit" value="Next" class="form-control form-control-sm btn btn-md btn-success">Next</button> -->
          <a href="personal_data_sheet.php" class="form-control form-control-sm btn btn-md btn-secondary">Back</a>
        </div>
        <div class="col-md-6 g-2 d-flex text-right">
          <button type="submit" value="Next" class="form-control form-control-sm btn btn-md btn-success">Next</button>
        </div>
      </div>
    </div>
    </form>
  </div>
  <!-- /.container-fluid -->


  <script src="js/ajax//libs/jquery/3.1.1/jquery-3.1.1.min.js"></script>
  <script type="text/javascript">
    countChild = <?= $countChild ?>;
    $(document).ready(function() {

      var x = 1;

      $("#addMore").click(function() {
        if ( countChild > 20) {
            alert("Maximum number of entries reached");
            // alert(countChild);
            return;
        }
        x++;
        countChild++;
        $('#moreFields').append('<div class="row p-1" id="removeHere"><div class="col-md-7"><label for="eligibility">Name of Child </label><input type="text" class="form-control form-control-sm text-uppercase" id="eligibility" name="childName' + countChild + '" ></div><div class="col-md-3"><label for="examinationDate">Date of Birth</label><input type="date" class="form-control form-control-sm" id="examinationDate" name="birthdate' + countChild + '" value=""></div><div class="col-md-1"><label for="removeMe">REMOVE</label><input type="button" class="btn btn-danger form-control form-control-sm" name="removeMe" id="removeMe" value="-"></div></div>');

      });

      $('#moreFields').on('click', '#removeMe', function() {
        $(this).closest('#removeHere').remove();
        x--;
      });

    });
  </script>

  <?php include('common/footer.php'); ?>