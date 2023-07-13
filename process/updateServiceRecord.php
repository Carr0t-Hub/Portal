<?php include '../functions/function.php';?>
<?php
if (isset($_SESSION['userID'])) {
  $res = checkGovServicesInfo($mysqli);
  if ($res == true) {
    insertServiceRecordInfo($mysqli);
  } 
  // else {
  //   updateServiceRecordInfo($mysqli);
  // }




// deleteServiceRecordInfo($mysqli);
    header("Location: ../views/dashboard.php");
  return;
}


?>