<?php include('common/header.php'); ?>

<?php include('common/sidebar.php'); ?>
<?php
if (isset($_SESSION['userID'])) {
$TrainingInfo = getTrainingInfo($mysqli);
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
    <div class="card-header bg-dark text-light">Learning and Development (L&D) Interventions</div>
    <form action="../process/updateTraining.php" method="POST" data-toggle="validator" role="form" enctype="multipart/form-data">
      <?php
      $countTraining = 1;
      if ($TrainingInfo == false) {
        echo ('<div class="card-body" id="moreFields">');
        echo ('<div class="card-title">Training & Programs Attended</div>');

        echo ('<div class="row p-1" id="removeHere">');
        echo ('<div class="col-md-6">');
        echo ('<label for="trainingPrograms">Title of Learning and Development / Training Programs</label>');
        echo ('<textarea class="form-control form-control-sm text-uppercase" name="title' . $countTraining . '" id="trainingPrograms" cols="20" rows="2"></textarea>');
        echo ('</div>');
        echo ('<div class="col-md-3">');
        echo ('<label for="inclusiveTrainingFrom">Inclusive Dates From</label>');
        echo ('<input type="date" class="form-control form-control-sm" id="inclusiveTrainingFrom" name="startDate' . $countTraining . '">');
        echo ('</div>');
        echo ('<div class="col-md-3">');
        echo ('<label for="inclusiveTrainingTo">Inclusive Dates To</label>');
        echo ('<input type="date" class="form-control form-control-sm" id="inclusiveTrainingTo" name="endDate' . $countTraining . '">');
        echo ('</div>');
        // echo('</div>');

        // echo('<div class="row p-1">');
        echo ('<div class="col-md-1">');
        echo ('<label for="noOfHrsTraining"># Hours</label>');
        echo ('<input class="form-control form-control-sm" id="noOfHrsTraining" name="numberOfHours' . $countTraining . '">');
        echo ('</div>');

        echo ('<div class="col-md-5">');
        echo ('<label for="typeOfLD">Type of LD (Managerial/Supervisor/Technical/etc.)</label>');
        echo ('<input type="text" class="form-control form-control-sm text-uppercase" id="typeOfLD" name="IDType' . $countTraining . '">');
        echo ('</div>');

        echo ('<div class="col-md-5">');
        echo ('<label for="sponsoredBy">Conducted / Sponsored By</label>');
        echo ('<input type="text" class="form-control form-control-sm text-uppercase" id="sponsoredBy" name="sponsoredBy' . $countTraining . '">');
        echo ('</div>');

        echo ('<div class="col-md-1">');
        echo ('<label for="addMore">ADD</label>');
        echo ('<input type="button" class="btn btn-sm btn-warning form-control form-control-sm" name="addMore" id="addMore" value="+">');
        echo ('</div>');
        echo ('</div>');
      } else {
        echo ('<div class="card-body" id="moreFields">');
        echo ('<div class="card-title">Training & Programs Attended</div>');
        foreach ($TrainingInfo as $key) {
          $countTraining++;

          echo ('<div class="row p-1" id="removeHere">');

          echo ('<div class="col-md-6">');
          echo ('<label for="trainingPrograms">Title of Learning and Development / Training Programs</label>');
          echo ('<textarea class="form-control form-control-sm text-uppercase" name="title' . $countTraining . '" id="trainingPrograms" cols="20" rows="2">' . $key['title'] . '</textarea>');
          echo ('</div>');

          echo ('<div class="col-md-3">');
          echo ('<label for="inclusiveTrainingFrom">Inclusive Dates From</label>');
          echo ('<input type="date" class="form-control form-control-sm" id="inclusiveTrainingFrom" name="startDate' . $countTraining . '" value="' . $key['startDate'] . '">');
          echo ('</div>');

          echo ('<div class="col-md-3">');
          echo ('<label for="inclusiveTrainingTo">Inclusive Dates To</label>');
          echo ('<input type="date" class="form-control form-control-sm" id="inclusiveTrainingTo" name="endDate' . $countTraining . '" value="' . $key['endDate'] . '">');
          echo ('</div>');
          // echo('</div>');

          // echo('<div class="row p-1">');
          echo ('<div class="col-md-1">');
          echo ('<label for="noOfHrsTraining"># Hours</label>');
          echo ('<input class="form-control form-control-sm" id="noOfHrsTraining" name="numberOfHours' . $countTraining . '" value="' . $key['numberOfHours'] . '">');
          echo ('</div>');

          echo ('<div class="col-md-5">');
          echo ('<label for="typeOfLD">Type of LD (Managerial/Supervisor/Technical/etc.)</label>');
          echo ('<input type="text" class="form-control form-control-sm text-uppercase" id="typeOfLD" name="IDType' . $countTraining . '" value="' . $key['IDType'] . '">');
          echo ('</div>');

          echo ('<div class="col-md-5">');
          echo ('<label for="sponsoredBy">Conducted / Sponsored By</label>');
          echo ('<input type="text" class="form-control form-control-sm" id="sponsoredBy" name="sponsoredBy' . $countTraining . '" value="' . $key['sponsoredBy'] . '">');
          echo ('</div>');



          if ($key['rank'] == 1) {
            echo ('<div class="col-md-1">');
            echo ('<label for="addMore">ADD</label>');
            echo ('<input type="button" class="btn btn-sm btn-warning form-control form-control-sm" name="addMore" id="addMore" value="+">');
            echo ('</div>');
          } else {
            echo ('<div class="col-md-1">');
            echo ('<label for="removeME">REMOVE</label>');
            echo ('<input type="button" class="btn btn-sm btn-danger form-control form-control-sm" name="removeMe" id="removeMe" value="-" ">');
            echo ('</div>');
          }

          echo ('</div>');  //END ROW
        } //END FOR EACH
      }  // END ELSE
      echo ('</div>'); // END MORE FIELDS
      ?>
      <div class="card-footer">
        <div class="row p-1">
          <div class="col-md-6 g-2 d-flex text-left">
            <a href="personal_data_sheet_6.php" class="form-control form-control-sm btn btn-md btn-secondary">Back</a>
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
<script type="text/javascript">
  countTraining = <?= $countTraining ?>;
  $(document).ready(function() {
    // var max = 5;
    var x = 1;
    $("#addMore").click(function() {
      if ( countTraining > 50) {
            alert("Maximum number of entries reached");
            // alert(countChild);
            return;
        }
      x++;
      countTraining++;

      $('#moreFields').append('<div class="row p-1" id="removeHere"><div class="col-md-6"><label for="title">Title of Learning and Development / Training Programs</label><textarea class="form-control form-control-sm text-uppercase" name="title' + countTraining + '" id="trainingPrograms" cols="20" rows="2"></textarea></div><div class="col-md-3"><label for="startDate">Inclusive Dates From</label><input type="date" class="form-control form-control-sm" id="inclusiveTrainingFrom" name="startDate' + countTraining + '"></div><div class="col-md-3"><label for="endDate">Inclusive Dates To</label><input type="date" class="form-control form-control-sm" id="inclusiveTrainingTo" name="endDate' + countTraining + '"></div><div class="col-md-1"><label for="numberOfHours"># Hours</label><input class="form-control form-control-sm" id="noOfHrsTraining" name="numberOfHours' + countTraining + '"></div><div class="col-md-5"><label for="IDType">Type of LD (Managerial/Supervisor/Technical/etc.)</label><input type="text" class="form-control form-control-sm text-uppercase" id="typeOfLD" name="IDType' + countTraining + '"></div><div class="col-md-5"><label for="sponsoredBy">Conducted / Sponsored By</label><input type="text" class="form-control form-control-sm text-uppercase" id="sponsoredBy" name="sponsoredBy' + countTraining + '"></div><div class="col-md-1"><label for="removeMe">REMOVE</label><input type="button" class="btn btn-sm btn-danger form-control form-control-sm" name="removeMe" id="removeMe" value="-"></div></div>');
    });

    $('#moreFields').on('click', '#removeMe', function() {
      $(this).closest('#removeHere').remove();
      x--;
    });

  });
</script>
<?php include('common/footer.php'); ?>