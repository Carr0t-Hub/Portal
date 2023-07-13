<?php include '../functions/function.php';?>
<?php
if (isset($_SESSION['userID'])) {
	$res = getOtherInfo($mysqli);
	$res2 = getReferenceInfo($mysqli);
	$res3 = getIssuedIDInfo($mysqli);

	if ($res == false) {
		// insertOtherInfo($userID,$num34_a,$num34_b,$num34_b_details,$num35_a,$num35_a_details,$num35_b,$num35_b_dateFiled,$num35_b_status,$num36,$num36_details,$num37,$num37_details,$num38_a,$num38_a_details,$num38_b,$num38_b_details,$num39,$num39_details,$num40_a,$num40_a_details,$num40_b,$num40_b_details,$num40_c,$num40_c_details,$mysqli);
		insertOtherInfo($mysqli);
	} else {
		// updateOtherInfo($userID,$num34_a,$num34_b,$num34_b_details,$num35_a,$num35_a_details,$num35_b,$num35_b_dateFiled,$num35_b_status,$num36,$num36_details,$num37,$num37_details,$num38_a,$num38_a_details,$num38_b,$num38_b_details,$num39,$num39_details,$num40_a,$num40_a_details,$num40_b,$num40_b_details,$num40_c,$num40_c_details,$mysqli);
		updateOtherInfo($mysqli);
	}


	//this will be accepted now but should be changed in the future.
	//the proper behavior here is to have an option to updat Each
	//row of data (current code delete's all, then replaces with new set of list.)
	deleteReferenceInfo($mysqli);
	insertReferenceInfo($mysqli);

	// if ($res2 == false) {
	// 	insertIssuedIDInfo(some data here);
	// } else {
	// 	updateIssuedIDInfo(some data here);
	// }

	if ($res3 == false) {
		insertIssuedIDInfo($mysqli);
	} else {
		updateIssuedIDInfo($mysqli);
	}
}
echo '<script>
alert("Information Saved!");
window.location="../views/dashboard.php";
</script>';
// return;
?>
