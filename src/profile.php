<?php 
    ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/107262dd19.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/profile.css"/>
    <script src="./js/jquery-3.6.0.min.js"></script>
    <title>Document</title>
    <style>
        a.disabled {
            opacity: 0.5;
            pointer-events: none;
            cursor: default;
        }
    </style>
</head>
    <iframe name="tempFrame" style="display:none;">
    </iframe>
    <?php 
        session_start();
        include "connectdb.php";
        $g = new \Google\Authenticator\GoogleAuthenticator();
        $id = $_SESSION['userID'];
        $userVerify = checkVerifyType($id);
        $code = getVerifyCode($id);
        $cannot_be_empty = False;
    

        if (!isset($_SESSION['username'])) {
            header("Location: login.php");
        } else {
            $username = $_SESSION['username'];
        }
        //upload
        if(isset($_POST['upload_success']) && !empty($_FILES['upload_picture']['tmp_name'])) {
            $data = file_get_contents($_FILES['upload_picture']['tmp_name']);
            $query = "UPDATE user SET profile_pic = ? WHERE username = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ss", $data, $username);
            //execute the statement
            $success = mysqli_stmt_execute($stmt);
        } else {
            $cannot_be_empty = True;
            ?>
            <script>
                function showDialog() {
                    document.getElementById("show_dialog").click();
                }

                function showDialog2FA(x) {
                    if(x == 1) {
                        document.getElementById("google_2fa_dialog").click();
                    } else if(x == 3) {
                        document.getElementById("disable_2fa_dialog").click();
                    } else if(x == 2) {
                        document.getElementById("show_email_dialog").click();
                    }
                }
            </script>
            <?php 
        }

    ?>
    <?php 
        if (isset($_GET['resendVerification'])) {
            $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
            $hashVerifiedCode = md5($verification_code);
            $update_verify = "UPDATE verification SET verify_code = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $update_verify);
            mysqli_stmt_bind_param($stmt, "si", $hashVerifiedCode, $id);
            mysqli_stmt_execute($stmt);
            resendVerification($verification_code, $id);
        }
        if (isset($_POST['upload_success'])) {
            if($cannot_be_empty) {
                echo "<body onload='showDialog()'>";
            } else {
                echo "<body>";
            }
        } else {
            //enable google two step
            if(isset($_POST['enable_two_step'])) {
                $passCode = $_POST['google-code'];
                $secret = $_POST['secret'];
                if ($g->checkCode($secret, $passCode)) {
                    addVerifyMethod($id, 1, $secret);
                    ?>
                    <script>
                        $(document).ready(function() {
                            $("#two-factor-success").delay(3000).hide("slow");
                            function reFresh() {
                                window.location.href=window.location.href;
                            }
                            window.setTimeout(reFresh, 5000);
                        });
                    </script>
                    <div class="alert alert-success" id="two-factor-success" role="alert">
                        <p>Your account's Two-step verification (Google Authenticator) was successful enabled.</p>
                    </div>
                    <?php
                } else {
                    echo "<body onload='showDialog2FA(1)'>";
                    $invalid_2FA_CODE = True;
                }
            } 

            if(isset($_POST['enable_email_2FA'])) {
                $user_code = $_POST['email_code'];
                if(md5($user_code) == $code) {
                    addVerifyMethod($id, 2);
                    ?>
                    <script>
                        $(document).ready(function() {
                            $("#two-factor-success").delay(3000).hide("slow");
                            function reFresh() {
                                window.location.href=window.location.href;
                            }
                            window.setTimeout(reFresh, 5000);
                        });
                    </script>
                    <div class="alert alert-success" id="two-factor-success" role="alert">
                        <p>Your account's Two-step verification (Google Authenticator) was successful enabled.</p>
                    </div>
                    <?php
                } else {
                    echo "<body onload='showDialog2FA(2)'>";
                    $invalid_2FA_CODE = True;
                }
            }
            if(isset($_POST['disable_two_step'])) {
                $passCode = $_POST['google-code'];
                $secret = $_POST['secret'];
                $verify_type = $_POST['2fa_type'];
                if($verify_type == 1){
                    //if code is equal
                    if ($g->checkCode($secret, $passCode)) {
                        addVerifyMethod($id, 0);
                        ?>
                        <script>
                            $(document).ready(function() {
                                $("#two-factor-disable").delay(3000).hide("slow");
                                function reFresh() {
                                    window.location.href=window.location.href;
                                }
                                window.setTimeout(reFresh, 5000);
                            });
                        </script>
                        <div class="alert alert-warning" id="two-factor-disable" role="alert">
                            <p>Your account's Two-step verification (Google Authenticator) was successful disabled.</p>
                        </div>
                        <?php
                    } else {

                        echo "<body onload='showDialog2FA(3)'>";
                        $invalid_2FA_CODE = True;
                    }
                } else if ($userVerify == 2){
                    $passCode = $_POST['google-code'];
                    $verify_type = $_POST['2fa_type'];
                    if(md5($passCode) == $code) {
                        addVerifyMethod($id, 0);
                        ?>
                        <script>
                            $(document).ready(function() {
                                $("#two-factor-disable").delay(3000).hide("slow");
                                function reFresh() {
                                    window.location.href=window.location.href;
                                }
                                window.setTimeout(reFresh, 5000);
                            });
                        </script>
                        <div class="alert alert-warning" id="two-factor-disable" role="alert">
                            <p>Your account's Two-step verification (Email) was successful disabled.</p>
                        </div>
                        <?php
                    } else {
                        echo "<body onload='showDialog2FA(3)'>";
                        $invalid_2FA_CODE = True;
                    }
                }
            }

            
        }
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#send_email_link").click(function(e){
                window.location.href = $(this).attr('href');
                $("#send_email_link").text("Resend the email");
            });
        });
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#profile-img').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
        <div class="container" style="max-width:100%; padding:0">
            <nav class="navbar navbar-expand-md navbar-dark bg-dark">
                <a href="index.php" class="navbar-brand" style="margin-left:4rem">Home</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#toggleMobileMenu" aria-controls="toggleMobileMenu"
                aria-expamnded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="toggleMobileMenu">
                <ul class="navbar-nav ms-auto text-center" style="padding:0 1.25rem;">
                    <li><a href="uploadNft.php" class="nav-link">Upload Nft Artwork</a></li>
                    <li><a href="uploadedArtWork.php" class="nav-link">My Artwork</a></li>
                    <li><a href="profile.php" class="nav-link">Profile</a></li>
                    <?php 
                        if (isset($_SESSION['userID'])) {
                            echo '<li><a href="logout.php" class="nav-link">Logout</a></li>';
                        } else {
                            echo '<li><a href="registration.php" class="nav-link">Register</a></li>';
                            echo '<li><a href="login.php" class="nav-link">Login</a></li>';
                        }
                    ?>
                </ul>
            </div>
            </nav>
        </div>
    <div class="container">
        <div class="row">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col">
                        <a href="#" class="profile-img">
                            <?php 
                                $query = "SELECT * FROM user WHERE username= ?";
                                $stmt = mysqli_prepare($conn, $query);
                                mysqli_stmt_bind_param($stmt, "s", $username);
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);
                                while($row = mysqli_fetch_assoc($result)) {
                                    //get the salt
                                    $profile = $row['profile_pic'];
                                }
                            ?>
                            <img src='data:image/jpeg;base64,<?php echo base64_encode($profile);?>' width="200px" style="opacity:.8;shadow-box: 4px; border:2px solid #B8B8B8"class="rounded-circle profile-img" alt="Profile.png">
                        </a>
                    </div>
                </div>
                <div class="row justify-content-center text-center" style="margin: 0.5rem 0;">
                    <div class="col">
                        <button type="button" class="btn btn-primary" id="show_dialog" data-bs-toggle="modal" data-bs-target="#upload-profile">
                           <i class="fa-solid fa-user"></i> Profile Image
                        </button>
                        <button type="button" id="aaab333" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#reset_password">
                           Reset Password
                        </button>
                        <div class="modal fade" id="upload-profile" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">profile picture</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                                    </div>
                                    <form action="profile.php" method="post" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <p>a picture helps other artists recognize you.</p>
                                            <img id="profile-img" src='data:image/jpeg;base64,<?php echo base64_encode($profile);?>' width="200px" style="opacity:.8;shadow-box: 4px; border:2px solid #b8b8b8"class="rounded-circle upload-pro" alt="profile.png">
                                            <div style="margin: 1.5rem 0;">
                                                <?php
                                                    if (isset($_post['upload_success'])) {
                                                        if($cannot_be_empty) {
                                                            echo "<h6 class='text-danger'>you must upload the picture before submit</h6>";
                                                        }
                                                    }
                                                ?>
                                                <input type="file" accept="image/png, image/jpeg" class="form-control" name="upload_picture" onchange="readURL(this);">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">close</button>
                                            <button type="submit" name="upload_success" class="btn btn-primary">save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container" style="max-width: 50%; margin-top: 2rem; margin-bottom: 2rem;">
        <div class="card row justify-content-center text-center">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-9 text-center align-self-center">
                            <h5><?php 
                                if($userVerify != 1 && ($userVerify == 0 || $userVerify == 2) ) {
                                    echo "<i class='fa-solid fa-xmark' style='margin-right:0.575rem; color:red';></i>";
                                } else {
                                    if ($userVerify == 1) {
                                        echo "<i class='fa-solid fa-check text-success' style='margin-right:0.575rem;';></i>";
                                    }
                                }
                            ?>Enable Two-step verification (Google Authenticator)</h5>
                        </div>
                        <div class="col-3">
                            <?php 
                                if ($userVerify == 1) {
                                    echo '<button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#disable_verify" id="disable_2fa_dialog">Disable</button>';                                    
                                } else if($userVerify == 0) {
                                    ?>
                                    <button type="button" class="btn btn-secondary btn-lg" data-bs-toggle="modal" id="google_2fa_dialog" data-bs-target="#two-step">Enable</button>
                                    <?php
                                } else {
                                    echo '<button type="button" class="btn btn-secondary btn-lg" data-bs-toggle="modal" data-bs-target="#two-step" disabled>Enable</button>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card row justify-content-center text-center">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-9 text-center align-self-center">
                            <h5><?php 
                                if($userVerify != 2 && ($userVerify == 0 || $userVerify == 1)) {
                                    echo "<i class='fa-solid fa-xmark' style='margin-right:0.575rem; color:red';></i>";
                                } else {
                                    if ($userVerify == 2) {
                                        echo "<i class='fa-solid fa-check text-success' style='margin-right:0.575rem;';></i>";
                                    }
                                }
                            ?>Enable Email verification</h5>
                        </div>
                        <div class="col-3">
                            <?php 
                                if ($userVerify == 2) {
                                    echo '<button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#disable_verify" id="disable_2fa_dialog">Disable</button>';                                    
                                } else if($userVerify == 0) {
                                    ?>
                                        <button type="button" class="btn btn-secondary btn-lg" data-bs-toggle="modal" id="show_email_dialog" data-bs-target="#email_two_step">Enable</button>
                                    <?php
                                } else {
                                    echo '<button type="button" class="btn btn-secondary btn-lg" data-bs-toggle="modal" data-bs-target="#two-step" disabled>Enable</button>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- google Two step modal -->
    <div class="modal fade" id="two-step" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Enable Two-Step Verification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                </div>
                <form action="profile.php" method="post">
                    <div class="modal-body">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-6" style='margin-top: 0.5rem; margin-bottom: 0.5rem;'>
                                    <?php 
                                        if(isset($_POST['enable_two_step'])) {
                                            echo '<img class="text-center" src="'.$g->getURL($username, 'localhost', $secret).'" />';
                                        } else {
                                            $secret = $g->generateSecret();
                                            echo '<img class="text-center" src="'.$g->getURL($username, 'localhost', $secret).'" />';
                                        }
                                        echo '<input type="hidden" name="secret" value="' .$secret . '"/>';
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <p>Please install <b>Google Authenticator</b> App in your phone. Then scan the above QR code for authentication. Once you authorized the device, you need to enter one-time password to complete the process.</p>
                                <?php 
                                    if(isset($_POST['enable_two_step']) && $invalid_2FA_CODE) {
                                        echo '<h6 class="text-danger">Incorrect one-time password</h6>';
                                    }
                                ?>
                                <input type="text" class="form-control" name="google-code" placeholder="Enter 6 digits password"/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Leave</button>
                        <button type="submit" name="enable_two_step" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end of google two step -->
<form action="profile.php" id="send_email_ah" method="GET" target="tempFrame">
    <input type="hidden" name="resendVerification" value="True" />
</form>
    <!-- email verify modal -->
    <div class="modal fade" id="email_two_step" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Enable Two-Step Verification (Email)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                </div>
                <form action="profile.php" method="post">
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <img src="./img/2fa.png"style="margin-bottom: 0.75rem" alt="">
                            </div>
                            <div class="row">
                                <p>Two-Factor Authentication (2FA - Email) is an option that provides an extra layer of security to your Private Email account in addition to your email and password. When Two-Factor Authentication is enabled, your account cannot be accessed by anyone unauthorized by you, even if they have stolen your password.</p>
                                <a id="send_email_link" href="javascript:document.getElementById('send_email_ah').submit();" style="text-decoration: none">Send verification email</a>
                                <input type="text" class="form-control" name="email_code" placeholder="Enter 6 digit verification code"/>
                                <?php 
                                    if(isset($_POST['enable_email_2FA']) && $invalid_2FA_CODE) {
                                        echo '<h6 class="text-danger" style="margin: 0.5rem 0">Incorrect verification code </h6>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Leave</button>
                        <button type="submit" name="enable_email_2FA" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- disable email verify -->

    <!-- disable verify -->
    <div class="modal fade" id="disable_verify" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <?php
                        if($userVerify == 1) {
                            echo '<h5 class="modal-title">Disable Two-Step Verification</h5>';
                        } else {
                            echo '<h5 class="modal-title">Disable Two-Step Verification (Email)</h5>';
                        }
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                </div>
                <form action="profile.php" method="post">
                    <div class="modal-body">
                        <div class="container">
                            <div class="row justify-content-center">
                                <img src="./img/dangerous.png" style="margin-bottom:0.5rem; max-width:50%;" class="rounded" alt="Dangerous">
                            </div>
                            <div class="row">
                                <?php 
                                    if($userVerify == 1) {
                                        echo '<p>Please enter the six-digit one-time password displayed on the Google Authenticator application. Please note that canceling multi-factor authentication may increase the risk of <span class="text-danger">account compromise.</span></p>';
                                    } else {
                                        echo '<p>Please note that canceling multi-factor authentication may increase the risk of <span class="text-danger">account compromise.</span></p>';
                                        ?>

                                        <a id="send_email_link" href="javascript:document.getElementById('send_email_ah').submit();" style="text-decoration: none">Send verification email</a>
                                        <?php
                                    }
                                ?>
                                <?php 
                                    if(isset($_POST['disable_two_step'])) {
                                        echo '<h6 class="text-danger">Incorrect one-time password</h6>';
                                    }
                                ?>
                                <input type="text" class="form-control" name="google-code" placeholder="Enter 6 digits password"/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-Secondary" data-bs-dismiss="modal">Leave</button>
                        <input type="hidden" name="secret" value="<?php echo $code ?>">
                        <input type="hidden" name="2fa_type" value="<?php echo $userVerify ?>">
                        <button type="submit" name="disable_two_step" class="btn btn-danger">Disable</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end of disable verify -->
    <?php 
        if(isset($_POST['reset_pw'])) {
            $showDialogAgain = False;
            $passwordNotMatch = False;
            $notStrongEnough = False;
            $password = $_POST['new_pw'];
            $currentPw = $_POST['current_pw'];
            $query = "SELECT * FROM user WHERE username= ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            while($row = mysqli_fetch_assoc($result)) {
                //get the salt
                $salt = $row['salt'];
                $hashPw = $row['password'];
            }
            $hashFormat = "$2y$10$";
            $hashF_and_salt = $hashFormat . $salt;
            $hashPassword = crypt($currentPw, $hashF_and_salt);
            if ($hashPassword != $hashPw) {
                $passwordNotMatch = True;
                $showDialogAgain = True;
            }
            $uppercase = preg_match('@[A-Z]@', $password);
            $lowercase = preg_match('@[a-z]@', $password);
            $number    = preg_match('@[0-9]@', $password);
            $specialChars = preg_match('@[^\w]@', $password);
            if ( (strlen($password) < 8) || !$uppercase || !$lowercase || !$number || !$specialChars) { 
                $notStrongEnough = True;
                $showDialogAgain = True;
            }
            if ($showDialogAgain) {
                ?>
                <script>
                    $(document).ready(function(){
                        $("#aaab333").click();
                    });
                </script>
                <?php
            } else {

                $my_query = "UPDATE user SET password = ? WHERE username = ?";
                $stmt = mysqli_prepare($conn, $my_query);
                $hashPassword = crypt($password, $hashF_and_salt);
                mysqli_stmt_bind_param($stmt, "ss", $hashPassword, $username);
                mysqli_stmt_execute($stmt);

                ?>
                <script>
                    $(document).ready(function(){
                        window.location = "index.php?resetPassword=1";
                    });
                </script>
                <?php
            }
        }
    ?>

    <div class="modal fade" id="reset_password" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reset Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                </div>
                <form action="profile.php" method="post" >
                    <div class="modal-body">
                        <div class="form-group">
                            <?php 
                                if(isset($_POST['reset_pw']) && $notStrongEnough) {
                                    echo "<h4 class='text-danger text-center'>Password is not strong enough</h4>";
                                }
                                if(isset($_POST['reset_pw']) && $passwordNotMatch) {
                                    echo "<h4 class='text-danger text-center'>The Password is not match with the current password</h4>";
                                }
                            ?>
                            <label for="current_pw">Current Password:</label>
                            <input type="password" class="form-control" id="current_pw" name="current_pw" placeholder="Current Password">
                        </div>
                        <div class="form-group">
                            <label for="new_pw">New Password:</label>
                            <input type="password" class="form-control" id="new_pw" name="new_pw" placeholder="New Password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="reset_pw" class="btn btn-primary">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>