<?php include('common/header.php'); ?>

<?php include('common/sidebar.php'); ?>
<?php
if (isset($_SESSION['userID'])) {
    $VoluntaryWork = getVoluntaryWorkInfo($mysqli);
} else {
    echo "<script> alert('Invalid'); window.location.href='/'</script> ";
}
?>

<!-- Begin Page Content -->
<<main id="main" class="main">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Personal Data Sheet</h1>
    <div class="card">
        <div class="card-header bg-dark text-light">Voluntary Work or Involvement in Civic / Non-Government</div>
        <form action="../process/updateVoluntary.php" method="POST" data-toggle="validator" role="form" enctype="multipart/form-data">
            <?php
            $countVolWork = 1;
            if ($VoluntaryWork == false) {
                echo ('<div class="card-body" id="moreFields">');
                echo ('<div class="row p-1" id="removeHere">');
                echo ('<div class="col-md-6">');
                echo ('<label for="orgNameAddress">Name & Address of Organization</label>');
                echo ('<input type="text" class="form-control form-control-sm text-uppercase" name="orgNameAddress' . $countVolWork . '" id="orgNameAddress" cols="20" rows="2"></input>
                </div>');
                echo ('<div class="col-md-3">');
                echo ('<label for="startDate">Inclusive Dates From</label>');
                echo ('<input type="date" class="form-control form-control-sm" id="startDate" name="startDate' . $countVolWork . '">
                </div>');
                echo ('<div class="col-md-3">');
                echo ('<label for="endDate">Inclusive Dates To</label>');
                echo ('<input type="date" class="form-control form-control-sm" id="endDate" name="endDate' . $countVolWork . '">
                </div>');
                // echo('</div>');

                // echo('<div class="row">');
                echo ('<div class="col-md-1">');
                echo ('<label for="numberOfHours"># Hours</label>');
                echo ('<input class="form-control form-control-sm" id="numberOfHours" name="numberOfHours' . $countVolWork . '">
                </div>');
                echo ('<div class="col-md-10">');
                echo ('<label for="natureOfWork">Position / Nature of Work</label>');
                echo ('<input type="text" class="form-control form-control-sm text-uppercase" id="natureOfWork" name="natureOfWork' . $countVolWork . '">
                </div>');
                echo ('<div class="col-md-1">');
                echo ('<label for="addMore">ADD</label>');
                echo ('<input type="button" class="btn  btn-sm btn-warning form-control form-control-sm" name="addMore" id="addMore" value="+">
                </div>');
                echo ('</div>');
            } //END IF

            else {
                echo ('<div class="card-body" id="moreFields">');
                foreach ($VoluntaryWork as $key) {
                    $countVolWork++;
                    echo ('<div class="row p-1" id="removeHere">');
                    echo ('<div class="col-md-6">');
                    echo ('<label for="orgNameAddress">Name & Address of Organization</label>');
                    echo ('<input type="text" class="form-control form-control-sm text-uppercase" name="orgNameAddress' . $countVolWork . '"  id="orgNameAddress" cols="20" rows="2" value="' . $key['orgNameAddress'] . '">
                  </input> </div>');
                    echo ('<div class="col-md-3">');
                    echo ('<label for="startDate">Inclusive Dates From</label>');
                    echo ('<input type="date" class="form-control form-control-sm" id="startDate" name="startDate' . $countVolWork . '" value="' . $key['startDate'] . '">');
                    echo ('</div>');
                    echo ('<div class="col-md-3">');
                    echo ('<label for="endDate">Inclusive Dates To</label>');
                    echo ('<input type="date" class="form-control form-control-sm" id="endDate" name="endDate' . $countVolWork . '" value="' . $key['endDate'] . '">');
                    echo ('</div>');
                    // echo('</div>');

                    // echo('<div class="row">');
                    echo ('<div class="col-md-1">');
                    echo ('<label for="numberOfHours"># Hours</label>');
                    echo ('<input class="form-control form-control-sm text-uppercase" id="numberOfHours" name="numberOfHours' . $countVolWork . '" value="' . $key['numberOfHours'] . '">
                </div>');
                    echo ('<div class="col-md-10">');
                    echo ('<label for="natureOfWork">Position / Nature of Work</label>');
                    echo ('<input type="text" class="form-control form-control-sm text-uppercase" id="natureOfWork" name="natureOfWork' . $countVolWork . '" value="' . $key['natureOfWork'] . '">');
                    echo ('</div>');


                    if ($key['rank'] == 1) {
                        echo ('<div class="col-md-1">');
                        echo ('<label for="addMore">ADD</label>');
                        echo ('<input type="button" class="btn btn-sm btn-warning form-control form-control-sm" name="addMore" id="addMore" value="+">
                  </div>');
                    } else {
                        echo ('<div class="col-md-1">');
                        echo ('<label for="removeME">REMOVE</label>');
                        echo ('<input type="button" class="btn btn-sm btn-danger form-control form-control-sm" name="removeMe" id="removeMe" value="-">
                    </div>');
                    }
                    echo ('</div>'); // END ROW
                } //END FOR EACH
            } // END ELSE
            echo ('</div>'); // END MORE FIELDS
            ?>
            <div class="card-footer">
                <div class="row p-1">
                    <div class="col-md-6 g-2 d-flex text-left">
                        <a href="personal_data_sheet_5.php" class="form-control form-control-sm btn btn-md btn-secondary">Back</a>
                    </div>
                    <div class="col-md-6 g-2 d-flex text-right">
                        <button type="submit" value="Next" class="form-control form-control-sm btn btn-md btn-success">Next</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div><!-- /.container-fluid -->
<script src="js/ajax//libs/jquery/3.1.1/jquery-3.1.1.min.js"></script>
<script type="text/javascript">
    countVolWork = <?= $countVolWork ?>;
    $(document).ready(function() {
        // var max = 5;
        var x = 1;


        $("#addMore").click(function() {
            if (countVolWork > 50) {
                alert("Maximum number of entries reached");
                // alert(countChild);
                return;
            }
            x++;
            countVolWork++;
            $('#moreFields').append('<div class="row p-1" id="removeHere"><div class="col-md-6"><label for="orgNameAddress">Name & Address of Organization</label><input type="text" class="form-control form-control-sm text-uppercase" name="orgNameAddress' + countVolWork + '" id="nameAddOrg" cols="20" rows="2"></input></div> <div class="col-md-3"><label for="startDate">Inclusive Dates From</label><input type="date" class="form-control form-control-sm" id="inclusiveVolFrom" name="startDate' + countVolWork + '" value=""></div><div class="col-md-3"><label for="endDate">Inclusive Dates To</label><input type="date" class="form-control form-control-sm" id="inclusiveVolTo" name="endDate' + countVolWork + '" value=""></div><div class="col-md-1"><label for="numberOfHours"># Hours</label><input class="form-control form-control-sm" id="noOfHrs" name="numberOfHours' + countVolWork + '" value=""></div>  <div class="col-md-10"><label for="natureOfWork">Position / Nature of Work</label><input type="text" class="form-control form-control-sm text-uppercase" id="natureWork" name="natureOfWork' + countVolWork + '" value=""></div>  <div class="col-md-1"><label for="removeMe">Remove</label></label><input type="button" class="btn btn-sm btn-danger form-control form-control-sm" name="removeMe" id="removeMe" value="-"> </div></div>');
        });

        $('#moreFields').on('click', '#removeMe', function() {
            $(this).closest('#removeHere').remove();
            x--;
        });

    });
</script>
<?php include('common/footer.php'); ?>