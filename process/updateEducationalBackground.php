<?php include '../functions/function.php';?>
<?php
if (isset($_SESSION['userID'])) {
	$res = getEducationalInfo($mysqli);

	if ($res == false) {
		insertEducationalInfo($mysqli);
	} else {
		updateEducationalInfo($mysqli);
	}
}
header("Location: ../pds/personal_data_sheet_4.php");
return;
?>
