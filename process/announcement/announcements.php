<?php include '../../functions/function.php';

$userID = $_POST['aUserID'];
$AnnouncementTitle = $_POST['AnnouncementTitle'];
$AnnouncementContent = $_POST['AnnouncementContent'];

$res = saveAnnouncement($mysqli, $AnnouncementTitle, $AnnouncementContent, $userID);
if($res == "success"){
	echo json_encode("submit");
  }
