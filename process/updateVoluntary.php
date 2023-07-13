<?php include '../functions/function.php';?>
<?php
if (isset($_SESSION['userID'])) {
deleteVoluntaryWorkInfo($mysqli);
insertVoluntaryWorkInfo($mysqli);
}

  header("Location: ../pds/personal_data_sheet_7.php");
  return;
?>
