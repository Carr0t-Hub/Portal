<?php include '../functions/function.php';?>
<?php
if (isset($_SESSION['userID'])) {	
		deleteCSEInfo($mysqli);
	    insertCSEInfo($mysqli);
 }
  header("Location: ../pds/personal_data_sheet_5.php");
  return;
?>