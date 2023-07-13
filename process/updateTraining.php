<?php include '../functions/function.php';?>
<?php
if (isset($_SESSION['userID'])) {
		deleteTrainingInfo($mysqli);
	    insertTrainingInfo($mysqli);	    
 }
  header("Location: ../pds/personal_data_sheet_8.php");
  return;
?>