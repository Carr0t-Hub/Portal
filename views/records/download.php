<?php


//download Attachment
  if(isset($_REQUEST['id'])){
	$key = $_REQUEST['id'];
	$query = $mysqli->prepare("SELECT * FROM 'attachments' WHERE 'id' = '$key'");
	$query->execute();
	$fetch = $query->fetch();

	header("Content-Disposition: attachment; filename=".$key['attachment']);
	header("Content-Type: application/octet-stream;");
	readfile("../uploads/records/".$key['attachment']);

  }

?>