<?php include("../common/header.php"); ?>
<?php include("../common/sidebar.php"); ?>

<?php $employee = getAllEmpInfor($mysqli, $_POST['employeeID']);?>

<!--start page wrapper -->
<div class="page-wrapper">
	<div class="page-content">
    <div class="card">
      <div class="h5 card-header bg-primary text-dark">
        <i class="fas fa-user"></i> Admin | User Settings
      </div>
      <?php foreach ($employee as $key) : ?>
        <div class="card-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-floating">
                <input type="text" class="form-control" id="firstName" name="firstName" value="<?= $key['firstName'];?>" disabled>
                <label for="firstName">First Name</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-floating">
                <input type="text" class="form-control" id="middleName" name="middleName" value="<?= $key['middleName'];?>" disabled>
                <label for="middleName">Middle Name</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-floating">
                <input type="text" class="form-control" id="lastName" name="lastName" value="<?= $key['lastName'];?>" disabled>
                <label for="lastName">Last Name</label>
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-md-2">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault1" checked>
                <label class="form-check-label" for="flexSwitchCheckDefault1">Active</label>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
	</div>
</div>

<!--end page wrapper -->

<?php include("../common/footer.php"); ?>