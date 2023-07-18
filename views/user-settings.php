<?php include("../common/header.php"); ?>
<?php include("../common/sidebar.php"); ?>

<?php $employee = getAllEmpList($mysqli); ?>

<!--start page wrapper -->
<div class="page-wrapper">
	<div class="page-content">
  <div class="card">
    <div class="h5 card-header bg-primary text-dark">
      <i class="fas fa-user"></i> Admin | User Settings
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm table-striped table-bordered" id="dataTable2" >
          <thead class="table-dark text-light">
            <tr>
              <th>EMPLOYEE ID</th>
              <th>NAME</th>
              <th>SECTION</th>
              <th>ACTION</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($employee as $empInfo) : ?>
            <tr>
              <td class="text-left align-middle"><?= strtoupper($empInfo['employeeID']); ?></td>
              <td class="text-left align-middle"><?=  strtoupper($empInfo['firstName']). " " . strtoupper($empInfo['lastName']); ?></td>
              <td class="text-left align-middle"><?= strtoupper($empInfo['section']); ?></td>
              <td>
                <center>
                  <?php
                    $empID = $empInfo['employeeID'];
                    $cos = "COS";
                    $permanent = "P";
                    if (strpos($empID, $cos)) {
                      echo ('<form action="view-settings.php" method="POST"><button type="submit" method="POST" name="employeeID" value="' . $empInfo['employeeID'] . '" class="btn btn-primary"><i class="bx bx-edit"></i> Edit</button></form>');
                    } else {
                      echo ('<form action="view-settings.php" method="POST"><button type="submit" method="POST" name="employeeID" value="' . $empInfo['employeeID'] . '" class="btn btn-primary"><i class="bx bx-edit"></i> Edit</button></form>');
                    }
                  ?>
                </center>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
	</div>
</div>

<!--end page wrapper -->

<?php include("../common/footer.php"); ?>