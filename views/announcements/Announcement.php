<?php include '../../functions/function.php';?>

<?php
if(isset($_POST['announcement_title']) && isset($_POST['description'])){
	
    // $tempPath = $_FILES['letter_attachment']['tmp_name'];
    // echo json_encode($tempPath);
    saveAnnouncement($mysqli, $_POST['announcement_title'],$_POST['description']);
    

    return;
}


?>