<?php include '../../functions/function.php';?>
<?php

//   S   A   V    E         S     T     A     T     U     S   //
if(isset($_POST['updateStatus'])){
	if($_POST['updateStatus'] = "Status"){
		$id =$_POST['docID'];
		$referenceNo =$_POST['referenceNo'];
		$res = UpdateStatusIncoming($mysqli, $id, $_POST['statusType']);
if($res == "saved"){
	header("Location: ../../records/incomingDocs.php?referenceNo=".$referenceNo);
}

	}
}

//     S     E     N     D     E      R    //
if(isset($_POST['dtsFirstName']) && isset($_POST['dtsLastName']) && isset($_POST['dtsEmail']) && isset($_POST['dtsDivAgency'])){
		$res = dtsCheckSender($mysqli,$_POST['dtsFirstName'],$_POST['dtsLastName'] ,$_POST['dtsEmail'] ,$_POST['dtsDivAgency']);
		// echo "hello1";
		if(count($res) < 1){
			echo json_encode("no");
			dtsSaveSender($mysqli,$_POST['dtsFirstName'],$_POST['dtsLastName'],$_POST['dtsEmail'],$_POST['dtsDivAgency']);
		}
		if(count($res) >= 1){
			echo json_encode("yes");
		}
	}
//      D    O    C    U    M     E    N     T        T    Y    P    E    //
	if(isset($_POST['docuType'])){
		$res = dtsCheckDocuType($mysqli,$_POST['docuType']);
		if(count($res) < 1){
			echo json_encode("no");
		}
		if(count($res) >= 1){
			echo json_encode("yes");
		}
	}
	if(isset($_POST['saveDocType'])){
		dtsSaveDocType($mysqli,$_POST['docuType']);
		echo json_encode("saved");
		header("Location: ../../records/addDocType.php");
	}
//    S    A    V    I    N    G     I    N    C    O    M    I     N     G        T   O      P    E    R    S   O   N      C   O   N   C   E   R   N   E   D  //	

if (isset($_POST['saveIncomingPC'])){
	if($_POST['saveIncomingPC'] == "savePersonConcerned"){
	$id =$_POST['docuID'];
		$res = dtsSaveIncomingOD($mysqli, $id,$_POST['dtsDocuActionNeeded'],$_POST['dtsDocuRemarks'],$_POST['dtsActionTaken'],$_POST['dtsDateDone'], $_POST['dtsDocuDivision'], $_POST['dtsDocuSection']);
			 
			header("Location: ../../records/history.php?code=I");
			
	}
	
	}

	//    S    A    V    I    N    G      O     U     T     G     O      I    N   G        S     E     N     D     E    R     //

if(isset($_POST['saveOutgoingSender'])){
	if($_POST['saveOutgoingSender'] == "saveOutgoing"){
		$id =$_POST['docuID'];
		$res = dtsSaveOutgoingSender($mysqli, $id, $_POST['Sender'], $_POST['dtsActionTaken']);
		header("Location: ../../records/history.php?code=O");
	}
}

if(isset($_POST['documentCategory'])){
	//construct the multi-dimensional array for constructStatement function.
	if($_POST['documentCategory'] == "dts_incoming"){
		$category = "Incoming";
		// for ($i = 1; $i < 10; $i++) {
		// }
		$res = dtsSaveIncoming($mysqli ,$category, $_POST['dtsReferenceNo'],$_POST['dtsDocumentSender'],$_POST['dtsDocumentType'],$_POST['dtsDateReceived'],$_POST['dtsParticulars'],$_FILES['dtsAttachmentsI']);
		if($res=="saved"){ 
			header("Location: ../../records/history.php?code=I");
		}
	}

//    S    A    V    I    N    G      O     U     T     G     O      I    N   G   //
	if($_POST['documentCategory'] == "dts_outgoing"){
		$category = "Outgoing";
		$res = dtsSaveOutgoing($mysqli ,$category, $_POST['dtsReferenceNo'],$_POST['dtsDocumentSender'],$_POST['dtsDocumentType'],$_POST['dtsParticulars'],$_POST['dtsActionTaken'],$_POST['dtsPersonConcerned'],$_FILES['dtsAttachmentsO']);

		if($res=="saved"){
			header("Location: ../../records/history.php?code=O");
		}
	}

	
//    S    A    V    I    N    G     S   P   E   C   I   A   L     O   R   D   E   R    //
	if($_POST['documentCategory'] == "dts_specialorder"){
		$category = "Special Order";
		$//concernedPeople = array();
			//for ($i = 1; $i < 10; $i++) {
				//if (!isset($_POST['dtsPersonConcerned'.$i])) continue;
				//$concernedPeople[] = array(
				//'createdDateTime' => date('Y-m-d H:m:s'),
				//'addressedTo' => $_POST['dtsPersonConcerned'.$i]
			 //);
		//}

		$res = dtsSaveSpecialOrder($mysqli ,$category, $_POST['dtsReferenceNo'],$_POST['dtsParticulars'],$_POST['dtsaddressedTo'],$_FILES['dtsAttachmentsSO'],$concernedPeople);

		header("Location: ../../records/history.php?code=SO");

	}

	if($_POST['documentCategory'] == "dts_memorandum"){
		$category = "Memorandum";
		$concernedPeople = array();
			for ($i = 1; $i < 10; $i++) {
				if (!isset($_POST['dtsPersonConcerned'.$i])) continue;

				$concernedPeople[] = array(
				'createdDateTime' => date('Y-m-d H:m:s'),
				'addressedTo' => $_POST['dtsPersonConcerned'.$i]
			);
		}

		$res = dtsSaveMemorandum($mysqli ,$category, $_POST['dtsReferenceNo'],$_POST['dtsParticulars'],$_POST['dtsaddressedTo'],$_FILES['dtsAttachmentsM'],$concernedPeople);

		header("Location: ../../records/history.php?code=M");
	}

}

	if(isset($_POST['dtscategoryTable'])){
		$monthYear = date("m-Y");
		$res = dtsCheckCategory($mysqli, $monthYear, $_POST['dtscategoryTable']);
		echo json_encode($res);
	}

?>