<?php include '../functions/function.php';?>
<?php
if (isset($_SESSION['userID'])) {
deleteWorkInfo($mysqli);
insertWorkInfo($mysqli);
}

header("Location: ../pds/personal_data_sheet_6.php");
// header("Location: ../service_record.php");
return;
?>
