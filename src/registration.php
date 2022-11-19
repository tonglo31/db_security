<?php 
    ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/registration.css" />
    <title>Registration Page</title>
</head>
<body>
    <?php
        include "connectdb.php";
        $emptySecAns = False;
        $finished_captcha = False;
        $failedCaptcha = False;
        $userExist = False;
        $canCreate = True;
        $invalidForm = False;
        $default_profile = file_get_contents(__DIR__ . '/img/profile.png');
        if(isset($_POST['submit'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];
            $secAns = $_POST['secAns'];
            $sq = $_POST['SQ']; 
            //regular expression for checking character
            $uppercase = preg_match('@[A-Z]@', $password);
            $lowercase = preg_match('@[a-z]@', $password);
            $number    = preg_match('@[0-9]@', $password);
            $specialChars = preg_match('@[^\w]@', $password);
            //form format validate
            if (strlen($secAns) <= 0 ) {
                $emptySecAns = True;
                $canCreate = False;
            }
            if ( (strlen($password) < 8) || (strlen($email) <= 0) || !$uppercase || !$lowercase || !$number || !$specialChars) {
                $invalidForm = True;
            } else {
                //run 10 times for the hash
                if ($canCreate) {
                    $query = "SELECT * FROM user WHERE username=?";
                    $stmt = mysqli_prepare($conn, $query);
                    mysqli_stmt_bind_param($stmt, "s", $username);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if(!$result) {
                        die('Query Failed');
                    }
                    $respon = $_POST['g-recaptcha-response'];
                    if($respon) {
                        $finished_captcha = True;
                        $json = generateReCaptchaTokenInfo($respon);
                        if($json->success == true) {
                            if (mysqli_num_rows($result) == 0) {
                                $hashFormat = "$2y$10$";
                                $salt = generateRandomString(22);
                                $money = 0;
                                $hashF_and_salt = $hashFormat . $salt;
                                $encrypted_password = crypt($password, $hashF_and_salt);
                                $secAnsHash = md5(strtolower($secAns));
                                $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
                                $hashVerifiedCode = md5($verification_code);
                                //prepared statement 
                                $query = "INSERT INTO user (username, password, money, salt, security_code, sec_ans, profile_pic, email) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
                                $verify_query = "INSERT INTO verification VALUES(?, ?, ?, ?)";
                                $stmt = mysqli_prepare($conn, $query);
                                //bind the param
                                mysqli_stmt_bind_param($stmt, "ssdsssss", $username, $encrypted_password, $money, $salt, $sq, $secAnsHash, $default_profile, $email);
                                //execute the statement
                                mysqli_stmt_execute($stmt);

                                //get the user id

                                $query = "SELECT * FROM user WHERE username=?";
                                $stmt = mysqli_prepare($conn, $query);
                                mysqli_stmt_bind_param($stmt, "s", $username);
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);
                                if(!$result) {
                                    die('Query Failed');
                                }
                                while($row = mysqli_fetch_assoc($result)) {
                                    //get user id
                                    $userID = $row['id'];
                                }
                                //insert to Verification table
                                $stmt = mysqli_prepare($conn, $verify_query);
                                $default_option = 0;
                                mysqli_stmt_bind_param($stmt, "iiis", $userID, $default_option, $default_option, $hashVerifiedCode);
                                mysqli_stmt_execute($stmt);

                                sendRegistrationVerifyEmail($email, $username, $verification_code);
                                setcookie("userID", $userID);
                                header("Location: registrationVerify.php");
                                // $result = mysqli_query($conn, $query);
                            } else {
                                $userExist = True;
                            }
                        } else {
                            $failedCaptcha = True;
                        }
                    } else {
                        $finished_captcha = False;
                    }
                }
            }
        }

        ?>
        <div class="container form-width">
            <form action="registration.php" method="POST">
                <div class="container">
                    <div class="row ">
                        <h4 id="register-title">Registration Page</h1>
                    </div>
                </div>
                <div class="form-group">
                    <?php 
                        
                        if (isset($_POST['submit']) && $userExist) {
                            echo "<p class='text-danger'>Username is used by someone.</p>";
                        }
                    ?>
                    <label for="username">Username</label>
                    <input type="text" id="username" class="form-control" name="username" placeholder="Enter username" />
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" />
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Email" />
                </div>
                <div class="form-group">
                    <?php 
                        if (isset($_POST['submit']) && $emptySecAns) {
                            echo "<p class='text-danger'>security ans is empty</p>";
                        }
                    ?>
                    <label for="SQ">Security Question：</label>
                    <select class="form-control" name="SQ" id="SQ">
                        <?php 
                            $query = "SELECT * FROM security_question";
                            $result = mysqli_query($conn, $query);
                            while($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='$row[security_code]'>$row[question]</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="secAns" placeholder="Put your Answer here" />
                </div>
                <?php 
                if(isset($_POST['submit'])) {
                    if(!$finished_captcha) {
                        echo "<div class='container text-danger'>Do captcha first</div>";
                    }
                    if($failedCaptcha) {
                        echo "<div class='container text-danger'>Do captcha again</div>";
                    }
                }
                ?>
                <div class="form-group captcha">
                    <div class="g-recaptcha " data-sitekey="6Ld-Ml0fAAAAAOcsUFbrou1SMFJFAMp9Jg3wGIwO"></div>
                </div>
                <br/>
                <?php
                if(isset($_POST['submit']) && $invalidForm) {
                    ?>
                    <div class="container text-danger">
                        <p>Invalid registration info</p>
                        <ul>
                            <li>Password must be at least 8 characters in length.</li>
                            <li>Password must include at least one upper case letter.</li>
                            <li>Password must include at least one number.</li>
                            <li>Password must include at least one special character</li>
                        </ul>
                    </div>
                    <?php
                }
                ?>
                <div class="container">
                    <div class="row">
                        <div class="col text-center">
                            <button type="submit" name="submit" class="btn form-control  btn-secondary">Create Account</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
</body>
</html>