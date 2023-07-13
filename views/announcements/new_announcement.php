<?php include('../common/header.php'); ?>
<?php include('../common/sidebar.php'); ?>


<main id="main" class="main">
    <div class="pagetitle"><!-- Start Page Title -->
      <h1>  <i data-toggle="modal" data-target="#modal-register_recieve"><img src="../assets/img/dts.png" class="img-circle" width="50" height="50"></i><b> ADD ANNOUNCEMENT</b></h1>
</div>
	<div class="card">
		<form action="../process/announcement/announcement.php" class="Announcement" id="saveAnnouncement" method="POST">
			<!-- <form class="Newsletter" id="NewsletterForm"  method="POST"> -->

			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<label for="TPosition">Announcement Title</label>
						<input type="text" class="form-control text-uppercase" id="announcementTitle" name="announcementTitle" value="" required>
					</div>
				</div><br>
				<div class="row">
					<div class="col-md-12">
						<label for="TPosition">Announcement Content</label>
						<textarea type="text" class="form-control text-uppercase" id="announcementContent" name="announcementContent" value="" required></textarea>
					</div>
				</div>
			</div>
			<div class="card-footer">
				<div class="row p-1">
					<div class="col-md-12 g-2">

						<button type="button" onclick="saveAnnouncement(<?php echo $_SESSION['userID']; ?>);" id="saveAnnouncementbutton" name="saveAnnouncementbutton" class="form-control btn btn-md btn-success">SUBMIT</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<script src="js/ajax//libs/jquery/3.1.1/jquery-3.1.1.min.js"></script>
<script type="text/javascript">
	function saveAnnouncement(userID) {
		var announcementContent = document.getElementById('announcementContent').value;
		var announcementTitle = document.getElementById('announcementTitle').value;

		if (announcementContent == "" && announcementTitle == "") {
			alert("Empty Fields");
		} else {

			$.ajax({
				url: 'process/announcement/announcements.php',
				type: 'post',
				data: {
					aUserID: userID,
					AnnouncementTitle: announcementTitle,
					AnnouncementContent: announcementContent
				},
				dataType: "json",

				success: function(response) {
					
					if (response == "submit") {
						// $(".modal-header #PheaderMsg").val("Success!");
						// $(".modal-body #Pmessage").val("Information Saved.");
						// $("#positive").modal("show");
						let addAnother = confirm("Announcement Saved!, Do you want to add another announcement?");
						if(addAnother){
							location.reload(true);
						}
						else{
							window.location="dashboard.php";
						}
					} // END IF EMPTY
				} // END SUCCESS
			});
		}
	}
</script>


<?php include('../common/footer.php'); ?>