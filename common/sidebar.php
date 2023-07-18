<!--wrapper-->
<div class="wrapper">
  <!--sidebar wrapper -->
  <div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
      <div>
        <img src="../assets/images/logo.png" class="logo-icon" alt="logo icon">
      </div>
      <div>
        <h4 class="logo-text">DA-BAR</h4>
      </div>
      <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i></div>
    </div>

    <!--navigation-->
    <ul class="metismenu" id="menu">
      <li>
        <a href="../views/index.php">
          <div class="parent-icon"><i class='bx bx-home-alt'></i>
          </div>
          <div class="menu-title">Dashboard</div>
        </a>
      </li>
      <li class="menu-label">Applications</li>
      <li>
        <a href="#">
          <div class="parent-icon"><i class='bx bx-user-pin'></i>
          </div>
          <div class="menu-title">Personal Data Sheet</div>
        </a>
      </li>
      <li>
        <a href="#">
          <div class="parent-icon"><i class='bx bx-plus-medical'></i>
          </div>
          <div class="menu-title">Vaccination</div>
        </a>
      </li>
      <li>
        <a href="#">
          <div class="parent-icon"><i class='bx bx-user-check'></i>
          </div>
          <div class="menu-title">Work Schedule</div>
        </a>
      </li>
      <!-- <li>
        <a href="javascript:;" class="has-arrow">
          <div class="parent-icon"><i class="bx bx-category"></i>
          </div>
          <div class="menu-title">Application</div>
        </a>
        <ul>
          <li> <a href="../views/pds/personal_data_sheet.php"><i class='bx bx-radio-circle'></i>Personal Data Sheet</a>
          </li>
          <li> <a href="#"><i class='bx bx-radio-circle'></i>Vaccination</a>
          </li>
          <li> <a href="#"><i class='bx bx-radio-circle'></i>Work Schedule</a>
          </li>
        </ul>
      </li> -->
      <li class="menu-label">Human Resource</li>
      <li>
        <a href="#">
          <div class="parent-icon"><i class='bx bx-note'></i>
          </div>
          <div class="menu-title">Examination</div>
        </a>
      </li>
      <li>
        <a href="#">
          <div class="parent-icon"><i class='bx bx-pen'></i>
          </div>
          <div class="menu-title">Evaluation</div>
        </a>
      </li>
      <li>
        <a href="#">
          <div class="parent-icon"><i class='bx bx-user-voice'></i>
          </div>
          <div class="menu-title">Announcements</div>
        </a>
      </li>
      <!-- S T A R T  D O C U M E N T  T R A C K I N G -->
      <?php if (($_SESSION['role'] == 2) || ($_SESSION['role'] == 5) || ($_SESSION['role'] == 6) || ($_SESSION['username'] == "jgayod") || ($_SESSION['username'] == "jceugerio") || ($_SESSION['username'] == "amdelmundo") || ($_SESSION['username'] == "mmemita") || ($_SESSION['username'] == "msvaldeabella")) : ?>
      <li class="menu-label">Document Tracking</li>
      <li>
        <a href="../records/history.php?code=I">
          <div class="parent-icon"><i class='bx bx-book-reader'></i>
          </div>
          <div class="menu-title">Record Management</div>
        </a>
      </li>
      <li>
        <a href="#">
          <div class="parent-icon"><i class='bx bx-cabinet'></i>
          </div>
          <div class="menu-title">Document Archiving</div>
        </a>
      </li>
      <?php endif; ?>
      <li class="menu-label">Communications</li>
      <li>
        <a href="#">
          <div class="parent-icon"><i class='bx bx-news'></i>
          </div>
          <div class="menu-title">Newsletter</div>
        </a>
      </li>
      <li>
        <a href="#">
          <div class="parent-icon"><i class='bx bx-camera-movie'></i>
          </div>
          <div class="menu-title">Seminars</div>
        </a>
      </li>
      <li class="menu-label">Others</li>
      <li>
        <a href="#">
          <div class="parent-icon"><i class='bx bx-book-content'></i>
          </div>
          <div class="menu-title">Library Management</div>
        </a>
      </li>
      <li>
        <a href="#">
          <div class="parent-icon"><i class='bx bx-bed'></i>
          </div>
          <div class="menu-title">Dorm Reservation</div>
        </a>
      </li>
      <li>
        <a href="#">
          <div class="parent-icon"><i class='bx bx-paper-plane'></i>
          </div>
          <div class="menu-title">Travel Management</div>
        </a>
      </li>
    </ul>
    <!--end navigation-->
  </div>
  <!--end sidebar wrapper -->

  <!--start header -->
    <header>
      <div class="topbar d-flex align-items-center">
        <nav class="navbar navbar-expand gap-3">
          <div class="mobile-toggle-menu"><i class='bx bx-menu'></i></div>
            <div class="position-relative search-bar d-lg-block d-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
              <input class="form-control px-5" disabled type="search" placeholder="Search">
              <span class="position-absolute top-50 search-show ms-3 translate-middle-y start-0 top-50 fs-5"><i class='bx bx-search'></i></span>
            </div>

            <div class="top-menu ms-auto">
              <ul class="navbar-nav align-items-center gap-1">
                <li class="nav-item mobile-search-icon d-flex d-lg-none" data-bs-toggle="modal" data-bs-target="#SearchModal"><a class="nav-link" href="avascript:;"><i class='bx bx-search'></i></a></li>
                <li class="nav-item dropdown dropdown-laungauge d-none d-sm-flex"><a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-bs-toggle="dropdown"><img src="assets/images/county/02.png" width="22" alt=""></a>
                  <!-- <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img src="assets/images/county/01.png" width="20" alt=""><span class="ms-2">English</span></a>
                    </li>
                  </ul> -->
                </li>
                <li class="nav-item dark-mode d-none d-sm-flex">
                  <a class="nav-link dark-mode-icon" href="javascript:;"><i class='bx bx-moon'></i>
                  </a>
                </li>
                <li class="nav-item dropdown dropdown-app">
                  <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" href="javascript:;"><i class='bx bx-grid-alt'></i></a>
                  <div class="dropdown-menu dropdown-menu-end p-0">
                    <div class="app-container p-2 my-2">
                      <div class="row gx-0 gy-2 row-cols-3 justify-content-center p-2">
                        <div class="col">
                          <a href="https://bar.gov.ph">
                          <div class="app-box text-center">
                            <div class="app-icon">
                              <img src="../assets/images/app/safari.png" width="30" alt="">
                            </div>
                            <div class="app-name">
                              <p class="mb-0 mt-1">Website</p>
                            </div>
                            </div>
                          </a>
                        </div>
                        <div class="col">
                          <a href="https://mail.google.com/">
                          <div class="app-box text-center">
                            <div class="app-icon">
                              <img src="../assets/images/app/google.png" width="30" alt="">
                            </div>
                            <div class="app-name">
                              <p class="mb-0 mt-1">Email</p>
                            </div>
                            </div>
                          </a>
                        </div>
                        <div class="col">
                          <a href="https://www.facebook.com/DABAROfficial">
                          <div class="app-box text-center">
                            <div class="app-icon">
                              <img src="../assets/images/app/facebook.png" width="30" alt="">
                            </div>
                            <div class="app-name">
                              <p class="mb-0 mt-1">Facebook</p>
                            </div>
                            </div>
                          </a>
                        </div>
                        <div class="col">
                          <a href="https://www.youtube.com/DABAROfficial">
                          <div class="app-box text-center">
                            <div class="app-icon">
                              <img src="../assets/images/app/youtube.png" width="30" alt="">
                            </div>
                            <div class="app-name">
                              <p class="mb-0 mt-1">Youtube</p>
                            </div>
                            </div>
                          </a>
                        </div>
                        <div class="col">
                          <a href="https://www.instagram.com/DABAROfficial">
                          <div class="app-box text-center">
                            <div class="app-icon">
                              <img src="../assets/images/app/instagram.png" width="30" alt="">
                            </div>
                            <div class="app-name">
                              <p class="mb-0 mt-1">Instagram</p>
                            </div>
                            </div>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>
                <li class="nav-item dropdown dropdown-large">
                  <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" data-bs-toggle="dropdown"><span class="alert-count">#</span>
                    <i class='bx bx-bell'></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-end">
                    <a href="javascript:;">
                      <div class="msg-header">
                        <p class="msg-header-title">Notifications</p>
                        <p class="msg-header-badge"># New</p>
                      </div>
                    </a>
                    <div class="header-notifications-list">
                      <!-- <a class="dropdown-item" href="javascript:;">
                        <div class="d-flex align-items-center">
                          <div class="user-online">
                            <img src="assets/images/avatars/avatar-1.png" class="msg-avatar" alt="user avatar">
                          </div>
                          <div class="flex-grow-1">
                            <h6 class="msg-name">Daisy Anderson<span class="msg-time float-end">5 sec
                          ago</span></h6>
                            <p class="msg-info">The standard chunk of lorem</p>
                          </div>
                        </div>
                      </a> -->
                    </div>
                    <a href="javascript:;">
                      <div class="text-center msg-footer">
                        <button class="btn btn-primary w-100">View All Notifications</button>
                      </div>
                    </a>
                  </div>
                </li>
                <li class="nav-item dropdown dropdown-large">
                  <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class='bx bx-help-circle'></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-end">
                    <a href="javascript:;">
                      <div class="msg-header">
                        <p class="msg-header-title">Helpdesk</p>
                        <!-- <p class="msg-header-badge"># Items</p> -->
                      </div>
                    </a>
                    <div class="header-message-list">
                      <a class="dropdown-item" href="javascript:;">
                        <div class="d-flex align-items-center gap-3">
                          <textarea class="form-control textarea-autosize" name="" id="" cols="30" rows="5"></textarea>
                        </div>
                      </a>
                    </div>
                    <a href="javascript:;">
                      <div class="text-center msg-footer">
                        <button class="btn btn-outline-primary w-100">Submit</button>
                      </div>
                    </a>
                  </div>
                </li>
              </ul>
            </div>

            <div class="user-box dropdown px-3">
              <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="../assets/images/avatars/avatar-1.png" class="user-img" alt="user avatar">
                <div class="user-info">
                  <p class="user-name mb-0">
                    <?php $fullname = getFullname($mysqli, $_SESSION['username']); ?>
                    <?php foreach ($fullname as $data) :?>
                      <?php echo strtoupper($data['firstName'] . " " . $data['lastName']); ?>
                    <?php endforeach ?>
                  </p>
                  <!-- <p class="designattion mb-0">Division</p> -->
                </div>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item d-flex align-items-center" href="../views/user-profile.php"><i class="bx bx-user fs-5"></i><span>Profile</span></a></li>
                <li>
                  <?php if ($_SESSION['role'] == 5) :?>
                    <a class="dropdown-item d-flex align-items-center" href="../views/user-settings.php"><i class="bx bx-cog fs-5"></i><span>Admin Settings</span></a>
                  <?php endif; ?>
                </li>
                <li><div class="dropdown-divider mb-0"></div></li>
                <li><a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="bx bx-log-out-circle"></i><span> Sign Out</span></a></li>
              </ul>
            </div>
          </div>
        </nav>
      </div>
    </header>
  <!--end header -->