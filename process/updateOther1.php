<?php include '../functions/function.php';?>
<?php
if (isset($_SESSION['userID'])) {
deleteSkillInfo($mysqli);
insertSkillInfo($mysqli);

deleteRecognitionInfo($mysqli);
insertRecognitionInfo($mysqli);

deleteOrganizationInfo($mysqli);
insertOrganizationInfo($mysqli);
}
header("Location: ../pds/personal_data_sheet_9.php");
return;
?>
