<?php include('../functions/function.php');
$username = $_POST['username'];
$password = $_POST['password'];


//check if the user needs to update password.
if (getPasswordUpdate($mysqli, $username)) {
  //if true login the user using the oldway. para makapag login parin using old method and old password (without hash)

  $log = userlogin($mysqli, $username, $password, 0);

  if (!$log == false) {
    //if login is successful redirect user to update password page.
    $_SESSION = $log;
    echo "<script>window.location.href='../UpdatePassword.php'</script>";
  } else {
    echo "<script>alert('Invalid Username and Password or Account is Disabled');</script>";
    echo "<script>window.location.href='../index.php'</script>";
  }
} else {
  $retrievedPassword = getUserPasswordByUsername($mysqli, $_POST['username']);
  // print_r($retrievedPassword);
  if (password_verify($password, $retrievedPassword)) {
    $_SESSION = getUserCredentials($mysqli, $username);
    // echo "<script>alert('password correct!');</script>";
    echo "<script>window.location.href='../views/dashboard.php'</script>";
  } else {
    echo "<script>alert('password incorrect!');</script>";
    echo "<script>window.location.href='../index.php'</script>";
  }
}
