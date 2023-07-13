<?php include '../functions/function.php';?>
<?php
if (isset($_SESSION['userID'])) {
	$res = getSpouseInfo($mysqli);
	$res2 = getParentsInfo($mysqli);
	// $res3 = getChildInfo($mysqli);

	if ($res == false) {
		insertSpouseInfo($mysqli);
	} else {
		updateSpouseInfo($mysqli);
	}

	if ($res2 == false) {
		insertParentsInfo($mysqli);
	} else {
		updateParentsInfo($mysqli);
	}

// if ($res3 == false) {
// 	//add loop here to add all children in the database
// 	insertChildInfo($mysqli);
// } else {
// 	//add loop here to update all children in the database.
// 	updateChildInfo();
// }

//this will be accepted now but should be changed in the future.
//the proper behavior here is to have an option to updat Each
//row of data (current code delete's all, then replaces with new set of list.)
deleteChildInfo($mysqli);
insertChildInfo($mysqli);
}
header("Location: ../pds/personal_data_sheet_3.php");
return;
?>
