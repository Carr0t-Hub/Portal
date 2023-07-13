<?php include('common/header.php'); ?>

<?php include('common/sidebar.php'); ?>

<?php
if (isset($_SESSION['userID'])) {
$Work = getWorkInfo($mysqli);
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
  <div class="card-header bg-dark text-light">Work Experience</div>

  <form action="../process/updateWork.php" method="POST" data-toggle="validator" role="form" enctype="multipart/form-data">
    <?php
    $countWork = 1;
    if ($Work == false) {
      echo ('<div class="card-body" id="moreFields">');
      echo ('<div class="card-title">Inclusive Dates</div>');
      // echo('<div class="row p-1" id="removeHere">');
      echo ('<div class="row p-1">');

      echo ('<div class="col-md-2">');
      echo ('<label for="startDate">From</label>');
      echo ('<input type="date" class="form-control form-control-sm" id="inclusiveFrom" name="startDate' . $countWork . '">');
      echo ('</div>');

      echo ('<div class="col-md-2">');
      echo ('<label for="endDate">To</label>');
      echo ('<input type="text" class="form-control form-control-sm" id="inclusiveTo" name="endDate' . $countWork . '"></input>');
      echo ('</div>');

      echo ('<div class="col-md-4">');
      echo ('<label for="workPosition">Position Title</label>');
      echo ('<input type="text" class="form-control form-control-sm text-uppercase" id="postTitle" name="workPosition' . $countWork . '">');
      echo ('</div>');

      echo ('<div class="col-md-4">');
      echo ('<label for="company">Department / Agency / Office / Company</label>');
      echo ('<input type="text" class="form-control form-control-sm text-uppercase" id="office" name="company' . $countWork . '">');
      echo ('</div>');
      // echo('</div>');

      // echo('<div class="row">');
      echo ('<div class="col-md-2">');
      echo ('<label for="salary">Monthly Salary</label>');
      echo ('<input class="form-control form-control-sm" id="salary" name="salary' . $countWork . '">');
      echo ('</div>');

      echo ('<div class="col-md-2">');
      echo ('<label for="salaryGrade">Salary / Job Pay Grade</label>');
      echo ('<input class="form-control form-control-sm" id="jobPayGrade" name="salaryGrade' . $countWork . '">');
      echo ('</div>');

      echo ('<div class="col-md-5">');
      echo ('<label for="statusAppointment">Status of Appointment</label>');
      echo ('<input type="text" class="form-control form-control-sm text-uppercase" id="appointment" name="statusAppointment' . $countWork . '">');
      echo ('</div>');

      echo ('<div class="col-md-2">');
      echo ('<label for="governmentService">Government Service</label>');
      echo ('<select class="form-control form-control-sm" onChange="check();" id="governmentService' . $countWork . '" name="governmentService' . $countWork . '">');
      echo ('<option selected value="">Choose...</option>');
      echo ('<option value="Yes">Yes</option>');
      echo ('<option value="No">No</option>');
      echo ('</select>');
      echo ('</div>');

      echo ('<div class="col-md-1">');
      echo ('<label for="addMore">ADD</label>');
      echo ('<input type="button" class="btn btn-sm btn-warning form-control form-control-sm" name="addMore" id="addMore" value="+">');
      echo ('</div>');
      echo ('</div>'); //END ROW
    } // END IF WORK IS FALSE

    else {
      echo ('<div class="card-body" id="moreFields">');
      echo ('<div class="card-title">Inclusive Dates</div>');
      foreach ($Work as $key) {
        $countWork++;
        echo ('<div class="row p-1" id="removeHere">');

        echo ('<div class="col-md-2">');
        echo ('<label for="startDate">From</label>');
        echo ('<input type="date" class="form-control form-control-sm" id="inclusiveFrom" name="startDate' . $countWork . '" value="' . $key['startDate'] . '">');
        echo ('</div>');

        echo ('<div class="col-md-2">');
        echo ('<label for="endDate">To</label>');
        echo ('<input type="text" class="form-control form-control-sm" id="inclusiveTo" name="endDate' . $countWork . '" value="' . $key['endDate'] . '">');
        echo ('</div>');

        echo ('<div class="col-md-4">');
        echo ('<label for="workPosition">Position Title</label>');
        echo ('<input type="text" class="form-control form-control-sm text-uppercase" id="postTitle" name="workPosition' . $countWork . '"  value="' . $key['workPosition'] . '">');
        echo ('</div>');

        echo ('<div class="col-md-4">');
        echo ('<label for="company">Department / Agency / Office / Company</label>');
        echo ('<input type="text" class="form-control form-control-sm text-uppercase" id="office" name="company' . $countWork . '" value="' . $key['company'] . '">');
        echo ('</div>');
        // echo('</div>');

        // echo('<div class="row">');
        echo ('<div class="col-md-2">');
        echo ('<label for="salary">Monthly Salary</label>');
        echo ('<input class="form-control form-control-sm" id="salary" name="salary' . $countWork . '" value="' . $key['salary'] . '">');
        echo ('</div>');

        echo ('<div class="col-md-2">');
        echo ('<label for="salaryGrade">Salary / Job Pay Grade</label>');
        echo ('<input class="form-control form-control-sm" id="jobPayGrade" name="salaryGrade' . $countWork . '" value="' . $key['salaryGrade'] . '" >');
        echo ('</div>');

        echo ('<div class="col-md-5">');
        echo ('<label for="statusAppointment">Status of Appointment</label>');
        echo ('<input type="text" class="form-control form-control-sm text-uppercase" id="appointment" name="statusAppointment' . $countWork . '" value="' . $key['statusAppointment'] . '">');
        echo ('</div>');

        echo ('<div class="col-md-2">');
        echo ('<label for="governmentService">Government Service</label>');
        echo ('<select class="form-control form-control-sm" id="governmentService' . $countWork . '" name="governmentService' . $countWork . '">');
        echo ('<option selected value="">Choose...</option>');
        echo ('<option value="Yes"');

        if ($key['governmentService'] == "Yes") {
          echo "selected";
        }

        echo ('>Yes</option>');
        echo ('<option value="No"');

        if ($key['governmentService'] == "No") {
          echo "selected";
        }

        echo ('>No</option>');
        echo ('</select>');
        echo ('</div>');

        if ($key['rank'] == 1) {
          echo ('<div class="col-md-1">');
          echo ('<label for="addMore">ADD</label>');
          echo ('<input type="button" class="btn sm btn-warning form-control form-control-sm" name="addMore" id="addMore" value="+">');
          echo ('</div>');
          // echo('</div>'); // END INCLUSIVE Dates
        } else {
          echo ('<div class="col-md-1">');
          echo ('<label for="removeME">REMOVE</label>');
          echo ('<input type="button" class="btn sm btn-danger form-control form-control-sm" name="removeMe" id="removeMe" value="-" ">');
          echo ('</div>');
          // echo('</div>');// END INCLUSIVE DATES
        }

        echo ('</div>'); // END ROW

        // if ($key['governmentService'] == "Yes") {
        //   echo('<center>');      
        //   echo('<div class="card mt-2" style="width: 60rem;" id="serviceRecord" visible="false" style="visibility: hidden;">');
        //   echo('<div class="card-header bg-dark text-light">Other Information</div>');
        //   echo('<div class="card-body">');
        //   echo('<div class="row">');
        //   echo('<div class="col-md-6">');
        //   echo('<label for="" class="d-flex justify-content-start">Station</label>');
        //   echo('<input type="text" class="form-control" name="serviceStation" id="serviceStation" required>');
        //   echo('</div>');
        //   echo('<div class="col-md-6">');
        //   echo('<label for="" class="d-flex justify-content-start">Branch</label>');
        //   echo('<input type="text" class="form-control" name="serviceBranch" id="serviceBranch" required>');
        //   echo('</div>');
        //   echo('</div>');
        //   echo('<div class="row mt-3">');
        //   echo('<div class="col-md-6">');
        //   echo('<label for="" class="d-flex justify-content-start">Leave or Absent w/o Pay</label>');
        //   echo('<div class="row">');
        //   echo('<div class="col-md-6">');
        //   echo('<label for="">From</label>');
        //   echo('<input type="date" class="form-control" name="leaveFrom" id="leaveFrom">');
        //   echo('</div>');
        //   echo('<div class="col-md-6">');
        //   echo('<label for="">To</label>');
        //   echo('<input type="date" class="form-control" name="leaveTo" id="leaveTo">');
        //   echo('</div>');
        //   echo('</div>');
        //   echo('</div>');
        //   echo('<div class="col-md-6">');
        //   echo('<label for="" class="d-flex justify-content-start">Separation</label>');
        //   echo('<div class="row">');
        //   echo('<div class="col-md-6">');
        //   echo('<label for="">Date</label>');
        //   echo('<input type="date" class="form-control" name="separationDate" id="separationDate">');
        //   echo('</div>');
        //   echo('<div class="col-md-6">');
        //   echo('<label for="">Cause</label>');
        //   echo('<input type="text" class="form-control" name="separationCause" id="separationCause">');
        //   echo('</div>');
        //   echo('</div>');
        //   echo('</div>');
        //   echo('</div>');
        //   echo('</div>');
        //   echo('</center>');
        // }

      }  //END FOR EACH
    } // END ELSE
    echo ('</div>'); // END MORE FIELDS
    ?>
    

    <div class="card-footer">
      <div class="row p-1">
        <div class="col-md-6 g-2 d-flex text-left">
          <a href="personal_data_sheet_4.php" class="form-control form-control-sm btn btn-md btn-secondary">Back</a>
        </div>
        <div class="col-md-6 g-2 d-flex text-right">
          <button type="submit" value="Next" class="form-control form-control-sm btn btn-md btn-success">Next</button>
        </div>
      </div>
    </div>
  </form>

  </div> 
  <!-- END CARD -->
</div> <!-- /.container-fluid -->

<script src="js/ajax//libs/jquery/3.1.1/jquery-3.1.1.min.js"></script>
<script type="text/javascript">
  countWork = <?= $countWork ?>;
  $(document).ready(function() {

    // var max = 5;
    var x = 1;

    $("#addMore").click(function() {
      if ( countWork > 50) {
            alert("Maximum number of entries reached");
            // alert(countChild);
            return;
        }
      x++;
      countWork++;
      $('#moreFields').append('<div class="row p-1" id="removeHere"><div class="col-md-2"><label for="startDate">From</label><input type="date" class="form-control form-control-sm" id="inclusiveFrom" name="startDate' + countWork + '" value=""></div> <div class="col-md-2"><label for="endDate">To</label><input type="text" class="form-control form-control-sm" id="inclusiveTo" name="endDate' + countWork + '" value="" ></div>  <div class="col-md-4"><label for="workPosition">Position Title</label><input type="text" class="form-control form-control-sm text-uppercase" id="postTitle" name="workPosition' + countWork + '" value="" ></div> <div class="col-md-4"><label for="company">Department / Agency / Office / Company</label><input type="text" class="form-control form-control-sm text-uppercase" id="office" name="company' + countWork + '" value="" ></div><div class="col-md-2"><label for="salary">Monthly Salary</label><input class="form-control form-control-sm" id="salary" name="salary' + countWork + '" value="" ></div> <div class="col-md-2"><label for="salaryGrade">Salary / Job Pay Grade</label><input class="form-control form-control-sm" id="jobPayGrade" name="salaryGrade' + countWork + '" value="" ></div> <div class="col-md-5"><label for="statusAppointment">Status of Appointment</label><input type="text"  class="form-control form-control-sm text-uppercase" id="appointment" name="statusAppointment' + countWork + '" value=""></div><div class="col-md-2"><label for="governmentService">Government Service</label><select  class="form-control form-control-sm" id="governmentService' + countWork + '" name="governmentService' + countWork + '"><option selected value="">Choose...</option><option value="Yes">Yes</option><option value="No">No</option></select></div><div class="col-md-1"><label for="removeMe">REMOVE</label><input type="button" class="btn btn-sm btn-danger form-control form-control-sm" name="removeMe" id="removeMe" value="-"></div> </div>');

    });

    $('#moreFields').on('click', '#removeMe', function() {
      $(this).closest('#removeHere').remove();
      x--;
    });


  });
</script>

<?php include('common/footer.php'); ?>