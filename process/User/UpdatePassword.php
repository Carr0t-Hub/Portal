<?php

include '../../functions/function.php';

if (!isset($_SESSION['userID'])) {
    echo "<script>window.location.href='../../index.php'</script>";
}

if (isset($_POST['updatePasswordBtn'])) {
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    // print_r($newPassword);
    // print_r($confirmPassword);
    if (strcmp($newPassword, $confirmPassword) == 0) {
      if (strlen($newPassword) >= 10) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        // print_r($hashedPassword);
        if (updateUserPasswordByUserID($mysqli, $_SESSION['userID'], $hashedPassword)) {
          echo "<script>alert('Password updated successfully' );</script>";
          echo "<script>window.location.href='../../views/index.php'</script>";
        } else {
          echo "<script>alert('Uknown error occured.' );</script>";
          echo "<script>window.location.href='../../views/index.php'</script>";
        }
      } else {
        echo "<script>alert('Password length must be 10 characters or more.' );</script>";
        echo "<script>window.location.href='../../UpdatePassword.php'</script>";
      }
    } else {
      echo "<script>alert('Passwords dont match' );</script>";
      echo "<script>window.location.href='../../UpdatePassword.php'</script>";
    }
} else {
  // echo "<script>alert('Reset Button not clicked' );</script>";
  echo "<script>window.location.href='../../views/index.php'</script>";
}
