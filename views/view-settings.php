<?php include("../common/header.php"); ?>
<?php include("../common/sidebar.php"); ?>

<?php $employee = getAllEmpInfor($mysqli, $_POST['employeeID']);?>

<!--start page wrapper -->
<div class="page-wrapper">
	<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Components</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Modals</li>
          </ol>
        </nav>
      </div>
      <div class="ms-auto">
        <div class="btn-group">
          <button type="button" class="btn btn-primary">Settings</button>
          <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	<span class="visually-hidden">Toggle Dropdown</span>
          </button>
          <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">	<a class="dropdown-item" href="javascript:;">Action</a>
            <a class="dropdown-item" href="javascript:;">Another action</a>
            <a class="dropdown-item" href="javascript:;">Something else here</a>
            <div class="dropdown-divider"></div>	<a class="dropdown-item" href="javascript:;">Separated link</a>
          </div>
        </div>
      </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
      <div class="col col-lg-9 mx-auto">
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
	</div>
</div>
<!--end page wrapper -->

<?php include("../common/footer.php"); ?>