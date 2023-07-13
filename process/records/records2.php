<?php include '../functions/function.php';?>
<?php
	if(isset($_POST['dtsFName']) && isset($_POST['dtsLName']) && isset($_POST['dtsEmail'])){
		$res = dtsCheckSender($mysqli,$_POST['dtsEmail']);
		//  echo "hello1";
		if(count($res) < 1){
			echo json_encode("no");
		}
		if(count($res) >= 1){
			echo json_encode("yes");
		}
	}

?>