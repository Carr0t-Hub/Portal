<?php include('common/header.php'); ?>

<?php include('common/sidebar.php'); ?>
<?php
if (isset($_SESSION['userID'])) {
$CSE = getCSEInfo($mysqli);
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
    <div class="card-header bg-dark text-light">Civil Service Eligibility</div>


    <form action="../process/updateCSE.php" method="POST" data-toggle="validator" role="form" enctype="multipart/form-data" id="add_test">

      <?php
      $countCSE = 1;
      if ($CSE == false) {
        echo ('<div class="card-body" id="moreFields">');

        echo ('<div class="row p-1">');
        echo ('<div class="col-md-5">');
        echo ("<label for='cse'>CAREER SERVICE/ RA 1080 (BOARD/ BAR) UNDER SPECIAL LAWS/ CES/ CSEE BARANGAY ELIGIBILITY / DRIVER'S LICENSE</label>");
        echo ('<input type="text" class="form-control form-control-sm text-uppercase" id="eligibility" name="cse' . $countCSE . '">
            </div>');

        echo ('<div class="col-md-2">');
        echo ('<label for="rate">RATING</label>');
        echo ('<input type="text" class="form-control form-control-sm text-uppercase" id="examinationDate" name="rate' . $countCSE . '">
            </div>');

        echo ('<div class="col-md-2">');
        echo ('<label for="examDate">DATE OF EXAMINATION</label>');
        echo ('<input type="date" class="form-control form-control-sm" id="examinationDate" name="examDate' . $countCSE . '">
            </div>');

        echo ('<div class="col-md-2">');
        echo ('<label for="examPlace">PLACE OF EXAMINATION</label>');
        echo ('<input type="text" class="form-control form-control-sm text-uppercase" id="examinationDate" name="examPlace' . $countCSE . '">
            </div>');

        echo ('<div class="col-md-1">');
        echo ('<label for="addMore">ADD</label>');
        echo ('<input type="button" class="btn btn-sm btn-warning form-control form-control-sm" name="addMore" id="addMore" value="+">
            </div>
          </div>');
      }
      //
      else {
        echo ('<div class="card-body" id="moreFields">');
        foreach ($CSE as $key) {

          $countCSE++;
          echo ('<div class="row p-1" id="removeHere">');
          echo ('<div class="col-md-5">');
          echo ("<label for='cse'>CAREER SERVICE/RA 1080 (BOARD/BAR) UNDER SPECIAL LAWS/CES/CSEE BRGY ELIG./DRIVER'S LICENSE</label>");
          echo ('<input type="text" class="form-control form-control-sm text-uppercase" id="cse' . $countCSE . '" name="cse' . $countCSE . '" value="' . $key['cse'] . '">
            </div>');
          echo ('<div class="col-md-2">');
          echo ('<label for="rate">RATING</label>');
          echo ('<input type="text" class="form-control form-control-sm text-uppercase" id="rate" name="rate' . $countCSE . '" value="' . htmlentities($key['rate']) . '">
            </div>');
          echo ('<div class="col-md-2">');
          echo ('<label for="examDate">DATE OF EXAMINATION</label>');
          echo ('<input type="date" class="form-control form-control-sm" id="examDate" name="examDate' . $countCSE . '" value="' . htmlentities($key['examDate']) . '">
            </div>');
          echo ('<div class="col-md-2">');
          echo ('<label for="examPlace">PLACE OF EXAMINATION</label>');
          echo ('<input type="text" class="form-control form-control-sm text-uppercase" id="examPlace" name="examPlace' . $countCSE . '" value="' . htmlentities($key['examPlace']) . '">
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
      echo ('</div>'); // END MORE FIELDS
      ?>

      <div class="card-footer">
        <div class="row p-1">
          <div class="col-md-6 g-2 d-flex text-left">
            <!-- <button type="submit" value="Next" class="form-control form-control-sm btn btn-md btn-success">Next</button> -->
            <a href="personal_data_sheet_3.php" class="form-control form-control-sm btn btn-md btn-secondary">Back</a>
          </div>
          <div class="col-md-6 g-2 d-flex text-right">
            <!-- <input type="submit" class="btn btn-md btn-success form-control form-control-sm" value="Save" name="save" id="save"> -->
            <!-- <a href="personal_data_sheet_5.php" class="form-control form-control-sm btn btn-md btn-success">Next</a> -->
            <button type="submit" value="Next" class="form-control form-control-sm btn btn-md btn-success">Next</button>
          </div>
        </div>
    </form>
  </div>
</div>

</div>
<!-- /.container-fluid -->
<script src="js/ajax//libs/jquery/3.1.1/jquery-3.1.1.min.js"></script>
<script type="text/javascript">
  countCSE = <?= $countCSE ?>;
  $(document).ready(function() {

    var x = 1;

    $("#addMore").click(function() {
      if ( countCSE > 10) {
            alert("Maximum number of entries reached");
            // alert(countChild);
            return;
        }
      x++;
      countCSE++;
      $('#moreFields').append("<div class='row p-1' id='removeHere'><div class='col-md-5'><label for='cse'> CAREER SERVICE/ RA 1080 (BOARD/ BAR) UNDER SPECIAL LAWS/ CES/ CSEE BARANGAY ELIGIBILITY / DRIVER'S LICENSE </label><input type='text' class='form-control form-control-sm text-uppercase' id='eligibility' name='cse" + countCSE + "'></div><div class='col-md-2'><label for='rate'>RATING</label><input type='text' class='form-control form-control-sm text-uppercase' id='rate' name='rate" + countCSE + "' value=''></div><div class='col-md-2'><label for='examDate'>DATE OF EXAMINATION</label><input type='date' class='form-control form-control-sm' id='examDate' name='examDate" + countCSE + "' value=''></div><div class='col-md-2'><label for='examPlace'>PLACE OF EXAMINATION</label><input type='text' class='form-control form-control-sm text-uppercase' id='examPlace' name='examPlace" + countCSE + "' value=''></div>    <div class='col-md-1'><label for='removeMe'>REMOVE</label><input type='button' class='btn btn-danger form-control form-control-sm' name='removeMe' id='removeMe' value='-''></div></div>");

    });

    $('#moreFields').on('click', '#removeMe', function() {
      $(this).closest('#removeHere').remove();
      x--;
    });

  });
</script>
<?php include('common/footer.php'); ?>