<?php include '../functions/function.php';?>
<?php
if (isset($_SESSION['userID'])) {
	$res = get_user_info($mysqli);

	if ($res == false) {
		insertPersonalInfo($mysqli);
	}
	else{	
		updatePersonalInfo($mysqli);
	}
}
header("Location: ../pds/personal_data_sheet_2.php");
return;
?>
