<?php
include '../functions/function.php';

//connect to database with user that allows Select and Insert
//check if username is valid.
//if valid,  check if a current request exists (not expired)
//generate hash
//save hash to database along with createddatetime
//send the reset password url with hash to user's email something like bar/resetpassword?a=[hashcode here]


//when someone accessed the link
//connet to database with limited access
//check if the hash in url is valid and not expired (not more than or equal to 24 hours old)
//if valid, show UI that accepts new pasword and confirm password.
//if password requirements are met. show success message and Redirect to login.

if (isset($_POST['forgotPassword'])) {
    $resetUserName = strtolower($_POST['resetUserName']);
    if (validateForgotPassword($mysqli, $resetUserName)) {
        if (!hasExistingForgotPasswordLink($mysqli, $resetUserName)) {
            //generate hash
            $resetLinkHash = password_hash($resetUserName . '/BARPortal256', PASSWORD_DEFAULT);

            //get requestor IP Address
            $requestorIPAddress = Utilities::getIPAddress();

            //save hash to database.
            if (createResetPasswordLink($mysqli, $resetUserName, $resetLinkHash, $requestorIPAddress)) {
                //send email
                //Phpmailer doesn't have a feature to verify if the email was succesfully sent. so there's no point in checking the return of emailforgotpasswordlink function.
                emailForgotPasswordLink($resetUserName . "@bar.gov.ph ", $resetLinkHash);
                echo "<script>alert('Reset Password Link sent to your BAR Email' );</script>";
                echo "<script>window.location.href='../index.php'</script>";
            } else {
                //message: failed to create reset password link
                echo "<script>alert('Failed to create reset password link' );</script>";
                echo "<script>window.location.href='../index.php'</script>";
            }
        } else {
            echo "<script>alert('you already have existing reset password request');</script>";
            echo "<script>window.location.href='../index.php'</script>";
        }
    } else {
        echo "<script>alert('username doesn't exist');</script>";
        echo "<script>window.location.href='../index.php'</script>";
        //request not valid
    }
} else {
    echo "<script>window.location.href='../index.php'</script>";
}
