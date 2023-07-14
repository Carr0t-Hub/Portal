<?php

include '../../functions/function.php';
if (isset($_POST['updatePasswordBtn'])) {
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    $resetHash = $_POST['updatePasswordBtn'];
    $username = getUserNameByResetHash($mysqli, $resetHash);

    if (strcmp($newPassword, $confirmPassword) == 0) {
        if (strlen($newPassword) >= 10) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            if (updateUserPasswordByUsername($mysqli, $username, $hashedPassword)) {
                setResetHashToUsed($mysqli, $resetHash);
                echo "<script>alert('Password updated successfully' );</script>";
                echo "<script>window.location.href='../../index.php'</script>";
            } else {
                echo "<script>alert('Uknown error occured.' );</script>";
                echo "<script>window.location.href='../../views/account/ResetPassword.php?rpw=" . $resetHash . "'</script>";
            }
        } else {
            echo "<script>alert('Password length must be 10 characters or more.' );</script>";
            echo "<script>window.location.href='../../views/account/ResetPassword.php?rpw=" . $resetHash . "'</script>";
        }
    } else {
        echo "<script>alert('Passwords dont match' );</script>";
        echo "<script>window.location.href='../../views/account/ResetPassword.php?rpw=" . $resetHash . "'</script>";
    }
} else {
    echo "<script>window.location.href='../../views/index.php'</script>";
}
