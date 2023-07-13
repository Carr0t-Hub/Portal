<?php include('common/header.php'); ?>

<?php include('common/sidebar.php'); ?>
<?php
if (isset($_SESSION['userID'])) {
    $res = getEducationalInfo($mysqli);
    if ($res == false) {
      $ESc = "";
      $EDe = "";
      $EFr = "";
      $ETo = "";
      $EUn = "";
      $EGr = "";
      $ESr = "";
      $SSc = "";
      $SDe = "";
      $SFr = "";
      $STo = "";
      $SUn = "";
      $SGr = "";
      $SSr = "";

      $VSc = "";
      $VDe = "";
      $VFr = "";
      $VTo = "";
      $VUn = "";
      $VGr = "";
      $VSr = "";

      $CSc = "";
      $CDe = "";
      $CFr = "";
      $CTo = "";
      $CUn = "";
      $CGr = "";
      $CSr = "";

      $GSc = "";
      $GDe = "";
      $GFr = "";
      $GTo = "";
      $GUn = "";
      $GGr = "";
      $GSr = "";
    } else {
      foreach ($res as $key) {
        $ESc =  htmlentities($key['elemSchoolName']);
        $EDe =  htmlentities($key['elemDegree']);
        $EFr =  htmlentities($key['elemPeriodFrom']);
        $ETo =  htmlentities($key['elemPeriodTo']);
        $EUn = htmlentities($key['elemUnitsEarned']);
        $EGr = htmlentities($key['elemYearGraduate']);
        $ESr = htmlentities($key['elemScholarship']);

        $SSc =  htmlentities($key['secondarySchoolName']);
        $SDe =  htmlentities($key['secondaryDegree']);
        $SFr =  htmlentities($key['secondaryPeriodFrom']);
        $STo =  htmlentities($key['secondaryPeriodTo']);
        $SUn = htmlentities($key['secondaryUnitsEarned']);
        $SGr = htmlentities($key['secondaryYearGraduate']);
        $SSr = htmlentities($key['secondaryScholarship']);

        $VSc =  htmlentities($key['vocationalSchoolName']);
        $VDe =  htmlentities($key['vocationalDegree']);
        $VFr =  htmlentities($key['vocationalPeriodFrom']);
        $VTo =  htmlentities($key['vocationalPeriodTo']);
        $VUn = htmlentities($key['vocationalUnitsEarned']);
        $VGr = htmlentities($key['vocationalYearGraduate']);
        $VSr = htmlentities($key['vocationalScholarship']);

        $CSc =  htmlentities($key['collegeSchoolName']);
        $CDe =  htmlentities($key['collegeDegree']);
        $CFr =  htmlentities($key['collegePeriodFrom']);
        $CTo =  htmlentities($key['collegePeriodTo']);
        $CUn = htmlentities($key['collegeUnitsEarned']);
        $CGr = htmlentities($key['collegeYearGraduate']);
        $CSr = htmlentities($key['collegeScholarship']);

        $GSc =  htmlentities($key['gradSchoolName']);
        $GDe =  htmlentities($key['gradDegree']);
        $GFr =  htmlentities($key['gradPeriodFrom']);
        $GTo =  htmlentities($key['gradPeriodTo']);
        $GUn = htmlentities($key['gradUnitsEarned']);
        $GGr = htmlentities($key['gradYearGraduate']);
        $GSr = htmlentities($key['gradScholarship']);
      }
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
    <div class="card-header bg-dark text-light"><i class="fas fa-user-graduate"></i> Educational Background</div>
    <div class="card-body">
      <form action="../process/updateEducationalBackground.php" method="POST" data-toggle="validator" role="form" enctype="multipart/form-data">
        <!-- ELEMENTARY -->
        <div class="card-title"> <strong>ELEMENTARY</strong></div>
        <div class="row p-1">
          <div class="col-md-6">
            <label for="nameSchool">Name of School</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="nameSchool" name="elemSchoolName" value="<?= $ESc ?>">
          </div>
          <div class="col-md-6">
            <label for="course">Basic Education / Degree / Course</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="course" name="elemDegree" value="<?= $EDe ?>">
          </div>
        </div>

        <div class="card-title">Period of Attendance</div>
        <div class="row p-1">
          <div class="col-md-3">
            <label for="periodFrom">From</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="periodFrom" name="elemPeriodFrom" value="<?= $EFr ?>">
          </div>
          <div class="col-md-3">
            <label for="periodTo">To</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="periodTo" name="elemPeriodTo" value="<?= $ETo ?>">
          </div>
          <div class="col-md-3">
            <label for="highestLevel">Highest Level / Units Earned</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="highestLevel" name="elemUnitsEarned" value="<?= $EUn ?>">
          </div>
          <div class="col-md-3">
            <label for="yearGraduated">Year Graduated</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="yearGraduated" name="elemYearGraduate" value="<?= $EGr ?>">
          </div>
        </div>

        <div class="row p-1">
          <div class="col-md-12">
            <label for="scholarship">Scholarship / Academic Honors Received</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="scholarship" name="elemScholarship" value="<?= $ESr ?>">
          </div>
        </div>
        <!-- SECONDARY -->
        <hr>
        <div class="card-title"><strong>SECONDARY</strong></div>
        <div class="row p-1">
          <div class="col-md-6">
            <label for="nameSchool">Name of School</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="nameSchool" name="secondarySchoolName" value="<?= $SSc ?>">
          </div>
          <div class="col-md-6">
            <label for="course">Basic Education / Degree / Course</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="course" name="secondaryDegree" value="<?= $SDe ?>">
          </div>
        </div>

        <div class="card-title">Period of Attendance</div>
        <div class="row p-1">
          <div class="col-md-3">
            <label for="periodFrom">From</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="periodFrom" name="secondaryPeriodFrom" value="<?= $SFr ?>">
          </div>
          <div class="col-md-3">
            <label for="periodTo">To</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="periodTo" name="secondaryPeriodTo" value="<?= $STo ?>">
          </div>
          <div class="col-md-3">
            <label for="highestLevel">Highest Level / Units Earned</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="highestLevel" name="secondaryUnitsEarned" value="<?= $SUn ?>">
          </div>
          <div class="col-md-3">
            <label for="yearGraduated">Year Graduated</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="yearGraduated" name="secondaryYearGraduate" value="<?= $SGr ?>">
          </div>
        </div>

        <div class="row p-1">
          <div class="col-md-12">
            <label for="scholarship">Scholarship / Academic Honors Received</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="scholarship" name="secondaryScholarship" value="<?= $SSr ?>">
          </div>
        </div>
        </hr>
        <!-- VOCATIONAL/ TRADE COURSE -->
        <hr>
        <div class="card-title"><strong>VOCATIONAL/ TRADE COURSE</strong></div>
        <div class="row p-1">
          <div class="col-md-6">
            <label for="nameSchool">Name of School</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="nameSchool" name="vocationalSchoolName" value="<?= $VSc ?>">
          </div>
          <div class="col-md-6">
            <label for="course">Basic Education / Degree / Course</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="course" name="vocationalDegree" value="<?= $VDe ?>">
          </div>
        </div>

        <div class="card-title">Period of Attendance</div>
        <div class="row p-1">
          <div class="col-md-3">
            <label for="periodFrom">From</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="periodFrom" name="vocationalPeriodFrom" value="<?= $VFr ?>">
          </div>
          <div class="col-md-3">
            <label for="periodTo">To</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="periodTo" name="vocationalPeriodTo" value="<?= $VTo ?>">
          </div>
          <div class="col-md-3">
            <label for="highestLevel">Highest Level / Units Earned</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="highestLevel" name="vocationalUnitsEarned" value="<?= $VUn ?>">
          </div>
          <div class="col-md-3">
            <label for="yearGraduated">Year Graduated</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="yearGraduated" name="vocationalYearGraduate" value="<?= $VGr ?>">
          </div>
        </div>

        <div class="row p-1">
          <div class="col-md-12">
            <label for="scholarship">Scholarship / Academic Honors Received</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="scholarship" name="vocationalScholarship" value="<?= $VSr ?>">
          </div>
        </div>
        </hr>
        <!-- COLLEGE -->
        <hr>
        <div class="card-title"><strong>COLLEGE</strong></div>
        <div class="row p-1">
          <div class="col-md-6">
            <label for="nameSchool">Name of School</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="nameSchool" name="collegeSchoolName" value="<?= $CSc ?>">
          </div>
          <div class="col-md-6">
            <label for="course">Basic Education / Degree / Course</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="course" name="collegeDegree" value="<?= $CDe ?>">
          </div>
        </div>

        <div class="card-title">Period of Attendance</div>
        <div class="row p-1">
          <div class="col-md-3">
            <label for="periodFrom">From</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="periodFrom" name="collegePeriodFrom" value="<?= $CFr ?>">
          </div>
          <div class="col-md-3">
            <label for="periodTo">To</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="periodTo" name="collegePeriodTo" value="<?= $CTo ?>">
          </div>
          <div class="col-md-3">
            <label for="highestLevel">Highest Level / Units Earned</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="highestLevel" name="collegeUnitsEarned" value="<?= $CUn ?>">
          </div>
          <div class="col-md-3">
            <label for="yearGraduated">Year Graduated</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="yearGraduated" name="collegeYearGraduate" value="<?= $CGr ?>">
          </div>
        </div>

        <div class="row p-1">
          <div class="col-md-12">
            <label for="scholarship">Scholarship / Academic Honors Received</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="scholarship" name="collegeScholarship" value="<?= $CSr ?>">
          </div>
        </div>
        </hr>
        <!-- GRADUATE STUDIES -->
        <hr>
        <div class="card-title"><strong>GRADUATE STUDIES</strong></div>
        <div class="row p-1">
          <div class="col-md-6">
            <label for="nameSchool">Name of School</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="nameSchool" name="gradSchoolName" value="<?= $GSc ?>">
          </div>
          <div class="col-md-6">
            <label for="course">Basic Education / Degree / Course</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="course" name="gradDegree" value="<?= $GDe ?>">
          </div>
        </div>

        <div class="card-title">Period of Attendance</div>
        <div class="row p-1">
          <div class="col-md-3">
            <label for="periodFrom">From</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="periodFrom" name="gradPeriodFrom" value="<?= $GFr ?>">
          </div>
          <div class="col-md-3">
            <label for="periodTo">To</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="periodTo" name="gradPeriodTo" value="<?= $GTo ?>">
          </div>
          <div class="col-md-3">
            <label for="highestLevel">Highest Level / Units Earned</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="highestLevel" name="gradUnitsEarned" value="<?= $GUn ?>">
          </div>
          <div class="col-md-3">
            <label for="yearGraduated">Year Graduated</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="yearGraduated" name="gradYearGraduate" value="<?= $GGr ?>">
          </div>
        </div>

        <div class="row p-1">
          <div class="col-md-12">
            <label for="scholarship">Scholarship / Academic Honors Received</label>
            <input type="text" class="form-control form-control-sm text-uppercase" id="scholarship" name="gradScholarship" value="<?= $GSr ?>">
          </div>
        </div>

        </hr>
    </div> <!-- END CARD BODY-->

    <div class="card-footer">
      <div class="row p-1">
        <div class="col-md-6 g-2 d-flex text-left">
          <!-- <button type="submit" value="Next" class="form-control form-control-sm btn btn-md btn-success">Next</button> -->
          <a href="personal_data_sheet_2.php" class="form-control form-control-sm btn btn-md btn-secondary">Back</a>
        </div>
        <div class="col-md-6 g-2 d-flex text-right">
          <button type="submit" value="Next" class="form-control form-control-sm btn btn-md btn-success">Next</button>
        </div>
      </div>
      </form>
    </div>
  </div>

</div>
<!-- /.container-fluid -->

<?php include('common/footer.php'); ?>