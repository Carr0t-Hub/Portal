<?php include('../common/header.php'); ?>
<?php
if (getPasswordUpdate($mysqli, $_SESSION['username'])) {
    //open modal for setting new password
    echo "<script> window.onload = function(){updatePasswordModal();}; </script>";
}
?>
<?php include('../common/footer.php'); ?>