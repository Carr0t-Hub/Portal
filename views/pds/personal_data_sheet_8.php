<?php include('common/header.php'); ?>

<?php include('common/sidebar.php'); ?>
<?php
if (isset($_SESSION['userID'])) {
  $SkillInfo = getSkillInfo($mysqli);
  $RecognitionInfo = getRecognitionInfo($mysqli);
  $OrganizationInfo = getOrganizationInfo($mysqli);
} else {
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
      <form action="../process/updateOther1.php" method="POST" data-toggle="validator" role="form" enctype="multipart/form-data">
        <?php
        $countSkill = 1;
        if ($SkillInfo == false) {
          echo ('<div id="skillFields">');
          echo ('<div class="row p-1" id="removeSKILL">');

          echo ('<div class="col-md-11">');
          echo ('<label for="skill">Special Skills and Hobbies</label>');
          echo ('<input type="text" id="skills" name="skill' . $countSkill . '" class="form-control form-control-sm text-uppercase">');
          echo ('</div>');

          echo ('<div class="col-md-1">');
          echo ('<label for="addSkills">ADD</label>');
          echo ('<input type="button" class="btn btn-warning form-control form-control-sm" name="addSkills" id="addSkills" value="+">');
          echo ('</div>');

          echo ('</div>');
          // echo('</div>');
        } else {
          echo ('<div id="skillFields">');
          foreach ($SkillInfo as $key) {
            $countSkill++;
            echo ('<div class="row p-1" id="removeSKILL">');

            echo ('<div class="col-md-11">');
            echo ('<label for="skill">Special Skills and Hobbies</label>');
            echo ('<input type="text" id="skills" name="skill' . $countSkill . '" class="form-control form-control-sm text-uppercase" value="' . $key['skill'] . '">');
            echo ('</div>');

            if ($key['rank'] == 1) {
              echo ('<div class="col-md-1">');
              echo ('<label for="addSkills">ADD</label>');
              echo ('<input type="button" class="btn btn-warning form-control form-control-sm" name="addSkills" id="addSkills" value="+">');
              echo ('</div>');
            } else {
              echo ('<div class="col-md-1">');
              echo ('<label for="removeME">REMOVE</label>');
              echo ('<input type="button" class="btn btn-danger form-control form-control-sm" name="removeSkills" id="removeSkills" value="-" ">');
              echo ('</div>');
            }

            echo ('</div>'); //END ROW
          }
        } //END ELSE
        echo ('</div>'); // END SKILL FIELDS
        ?>

        <hr>

        <?php
        $countRecognition = 1;
        if ($RecognitionInfo == false) {
          echo ('<div id="recognitionFields">');
          echo ('<div class="row p-1" id="removeRECOG">');

          echo ('<div class="col-md-11">
              <label for="recognition">Non-Academic Distinctions / Recognition</label>
              <input type="text" id="recognition" name="recognition' . $countRecognition . '" class="form-control form-control-sm text-uppercase">
            </div>');

          echo ('<div class="col-md-1">
              <label for="addRecog">ADD</label>
              <input type="button" class="btn btn-warning form-control form-control-sm" name="addRecog" id="addRecog" value="+">
            </div>');

          echo ('</div>');
        } else {
          echo ('<div id="recognitionFields">');
          foreach ($RecognitionInfo as $key) {
            $countRecognition++;

            echo ('<div class="row p-1" id="removeRECOG">');

            echo ('<div class="col-md-11">
              <label for="recognition">Non-Academic Distinctions / Recognition</label>
              <input type="text" id="recognition" name="recognition' . $countRecognition . '" class="form-control form-control-sm text-uppercase" value="' . $key['recognition'] . '">
            </div>');

            if ($key['rank'] == 1) {
              echo ('<div class="col-md-1">
                <label for="addRecog">ADD</label>
                <input type="button" class="btn btn-warning form-control form-control-sm" name="addRecog" id="addRecog" value="+">
              </div>');
            } else {
              echo ('<div class="col-md-1">');
              echo ('<label for="removeRecog">REMOVE</label>');
              echo ('<input type="button" class="btn btn-danger form-control form-control-sm" name="removeRecog" id="removeRecog" value="-" ">');
              echo ('</div>');
            }
            echo ('</div>'); //END ROW
          }
        } //END ELSE
        echo ('</div>'); //END RECOGNITION FIELDS
        ?>

        <hr>

        <?php
        $countOrganization = 1;
        if ($OrganizationInfo == false) {
          echo ('<div id="organizationFields">');
          echo ('<div class="row p-1" id="removeORG">');

          echo ('<div class="col-md-11">
                <label for="membership">Membership in Association / Organization</label>
                <input type="text" id="membership" name="organization' . $countOrganization . '" class="form-control form-control-sm text-uppercase" >
              </div>');

          echo ('<div class="col-md-1">
                <label for="addOrg">ADD</label>
                <input type="button" class="btn btn-warning form-control form-control-sm" name="addOrg" id="addOrg" value="+">
              </div>');

          echo ('</div>'); // END ROW
        } else {
          echo ('<div id="organizationFields">');
          foreach ($OrganizationInfo as $key) {
            $countOrganization++;

            echo ('<div class="row p-1" id="removeORG">');

            echo ('<div class="col-md-11">
                <label for="membership">Membership in Association / Organization</label>
                <input type="text" id="membership" name="organization' . $countOrganization . '" class="form-control form-control-sm text-uppercase" value="' . $key['organization'] . '">
              </div>');

            if ($key['rank'] == 1) {
              echo ('<div class="col-md-1">
                    <label for="addOrg">ADD</label>
                    <input type="button" class="btn btn-warning form-control form-control-sm" name="addOrg" id="addOrg" value="+">
                  </div>');
            } else {
              echo ('<div class="col-md-1">');
              echo ('<label for="removeRecog">REMOVE</label>');
              echo ('<input type="button" class="btn btn-danger form-control form-control-sm" name="removeOrg" id="removeOrg" value="-" ">');
              echo ('</div>');
            }
            echo ('</div>'); // END ROW
          }
        } //END ELSE
        echo ('</div>'); // END ORGANIZATION FIELDS
        ?>

    </div>
    <div class="card-footer">
      <div class="row p-1">
        <div class="col-md-6 g-2 d-flex text-left">
          <a href="personal_data_sheet_7.php" class="form-control form-control-sm btn btn-md btn-secondary">Back</a>
        </div>
        <div class="col-md-6 g-2 d-flex text-right">
          <button type="submit" value="Next" class="form-control form-control-sm btn btn-md btn-success">Next</button>
        </div>
      </div>
    </div>

    </form>

  </div>

</div>
<!-- /.container-fluid -->
<script src="js/ajax//libs/jquery/3.1.1/jquery-3.1.1.min.js"></script>

<!-- Skills -->
<script type="text/javascript">
  countSkill = <?= $countSkill ?>;
  $(document).ready(function() {

    var x = 1;

    $("#addSkills").click(function() {
      if ( countSkill > 50) {
            alert("Maximum number of entries reached");
            // alert(countChild);
            return;
        }
      x++;
      countSkill++;
      $('#skillFields').append('<div class="row p-1" id="removeSKILL"><div class="col-md-11"><label for="skill">Special Skills and Hobbies</label><input type="text" id="skills" name="skill' + countSkill + '" class="form-control form-control-sm text-uppercase" value=""></div><div class="col-md-1"><label for="removeSkills">REMOVE</label><input type="button" class="btn btn-danger form-control form-control-sm" name="removeSkills" id="removeSkills" value="-"></div></div>');
    });

    $('#skillFields').on('click', '#removeSkills', function() {
      $(this).closest('#removeSKILL').remove();
      x--;
    });

  });
</script>

<!-- Recognition -->
<script type="text/javascript">
  countRecognition = <?= $countRecognition ?>;
  $(document).ready(function() {
    var x = 1;
    $("#addRecog").click(function() {
      if ( countRecognition > 50) {
            alert("Maximum number of entries reached");
            // alert(countChild);
            return;
        }
      x++;
      countRecognition++;
      $('#recognitionFields').append('<div class="row p-1" id="removeRECOG"><div class="col-md-11"><label for="recognition">Non-Academic Distinctions / Recognition</label><input type="text" id="recognition" name="recognition' + countRecognition + '" class="form-control form-control-sm text-uppercase"></div><div class="col-md-1"><label for="removeRecog">REMOVE</label><input type="button" class="btn btn-danger form-control form-control-sm" name="removeRecog" id="removeRecog" value="-"></div></div>');
    });

    $('#recognitionFields').on('click', '#removeRecog', function() {
      $(this).closest('#removeRECOG').remove();
      x--;
    });

  });
</script>

<!-- Organization -->
<script type="text/javascript">
  countOrganization = <?= $countOrganization ?>;
  $(document).ready(function() {
    var x = 1;
    $("#addOrg").click(function() {
      if ( countOrganization > 50) {
            alert("Maximum number of entries reached");
            // alert(countChild);
            return;
        }
      x++;
      countOrganization++;
      $('#organizationFields').append('<div class="row p-1" id="removeORG"><div class="col-md-11"><label for="membership">Membership in Association / Organization</label><input type="text" id="membership" name="organization' + countOrganization + '" class="form-control form-control-sm text-uppercase"></div><div class="col-md-1"><label for="removeOrg">REMOVE</label><input type="button" class="btn btn-danger form-control form-control-sm" name="removeOrg" id="removeOrg" value="-"></div></div>');

    });

    $('#organizationFields').on('click', '#removeOrg', function() {
      $(this).closest('#removeORG').remove();
      x--;
    });

  });
</script>

<?php include('common/footer.php'); ?>