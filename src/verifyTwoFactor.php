<?php 
    ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/registration.css" />
    <title>Login Page</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Two-factor authentication</title>
</head>
<body>
    
    <script type="text/javascript">
        $(document).ready(function(){
            $("#send_email_link").click(function(e){
                window.location.href = $(this).attr('href');
                $("#send_email_link").text("Resend the email");
            });
        });
    </script>
        <?php 
            include "connectdb.php";
            session_start();
            $id = $_SESSION['userID'];
            $code = getVerifyCode($id);
            $g = new \Google\Authenticator\GoogleAuthenticator();
            if(isset($_COOKIE['verify_type'])) {
                $type = $_COOKIE['verify_type'];
            }
            if (isset($_GET['resendVerification'])) {
                $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
                $hashVerifiedCode = md5($verification_code);
                $update_verify = "UPDATE verification SET verify_code = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $update_verify);
                mysqli_stmt_bind_param($stmt, "si", $hashVerifiedCode, $id);
                mysqli_stmt_execute($stmt);
                resendVerification($verification_code, $id);
            }
            if(isset($_POST['verify'])) {
                $passCode = $_POST['passcode'];
                if($type == 1){
                    if ($g->checkCode($code, $passCode)) {
                        unset($_COOKIE['verify_type']);
                        header("Location: index.php");
                    } else {
                        $invalid_code = True;
                    }
                } elseif($type == 2) {
                    $user_code = $_POST['passcode'];
                    if(md5($user_code) == $code) {
                        unset($_COOKIE['verify_type']);
                        header("Location: index.php");
                    } else {
                        $invalid_code = True;
                    }
                }
            }

        ?>
    <iframe name="tempFrame" style="display:none;">
    </iframe>
    <form action="verifyTwoFactor.php" id="send_email_ah" method="GET" target="tempFrame">
        <input type="hidden" name="resendVerification" value="True" />
    </form>
        <div class="container form-width">
            <form id="loginForm" action="verifyTwoFactor.php" method="POST" id="demo-form">
                <div class="form-group">
                    <label for="username">Verification Code</label>    
                    <input type="text" id="passcode" class="form-control" name="passcode" placeholder="Enter the verification code" />
                    <?php 
                        if($type == 2) {
                            ?>

                            <a id="send_email_link" href="javascript:document.getElementById('send_email_ah').submit();" style="text-decoration: none">Send verification email</a>
                            <?php
                        }
                        if(isset($_POST['verify']) && $invalid_code) {
                            echo "<h6 class='text-danger' sty   le='margin-top:0.7rem'>Invalid code</h6>";
                        }
                    ?>
                </div>
                <div class="container">
                    <div class="row justify-content-center" style="margin: 0.75rem 0;">
                        <button type="submit" name="verify" class="btn btn-secondary" >Verify</button>
                    </div>
                    <a href="lostDevice.php" style="text-decoration: none">I lost my device</a>
                </div>
            </form>
        </div>
</body>
</html>