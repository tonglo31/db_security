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
</head>
<body>
    <?php 
        include "connectdb.php";
        session_start();
        if (isset($_SESSION['username'])){
            header("Location: index.php");
        }
    ?>
    <?php 
        $validated = False;
        $invalidAcInfo = False;
        $failedCaptcha = False;
        $invalidPassword = False;
        $invalidUser = False;
        //when the form is posted
        if(isset($_POST['login'])) {
            $validated = true;
            $username = $_POST['username'];
            $password = $_POST['password'];
            $respon = $_POST['g-recaptcha-response'];
            //get reCaptcha token
            if($respon) {
                $finished_captcha = True;
                //get captcha info
                $json = generateReCaptchaTokenInfo($respon);
                //if captcha is success
                if($json->success == true) {
                    if($username && $password) {
                        //query data info from database
                        $query = "SELECT * FROM user WHERE username= ?";
                        $stmt = mysqli_prepare($conn, $query);
                        mysqli_stmt_bind_param($stmt, "s", $username);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $salt = "";
                        $hashPassword = "";
                        //if the user is exist
                        if (mysqli_num_rows($result) != 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                                //get the salt
                                $salt = $row['salt'];
                                $hashPassword = $row['password'];
                                $username = $row['username'];
                                $id = $row['id'];
                            }
                            $hashFormat = "$2y$10$";
                            $hashF_and_salt = $hashFormat . $salt;
                            //validate the password
                            if (crypt($password, $hashF_and_salt) == $hashPassword) {
                                isVerifiedUser($id);
                            } else {
                                $invalidPassword = True;
                            }
                        } else {
                            $invalidUser = True;
                        }
                    } else {
                        $invalidAcInfo = True;
                    }
                } else {
                    $failedCaptcha = True;
                }
            } else {
                $finished_captcha = False;
            }
        } 
        ?>
        <div class="container form-width">
            <form id="loginForm" action="login.php" method="POST" id="demo-form">
                <div class="form-group">
                    <?php 
                    //check whether the form data is empty
                        if(isset($_POST['login']) && $invalidAcInfo) {
                            echo "<div class='text-danger'><h5>Account info cannot be empty</h5></div>";
                        } else if(isset($_POST['login']) && $invalidUser) {
                            echo "<div class='text-danger'><h5>User is not exist</h5></div>";
                        } else if(isset($_POST['login']) && $invalidPassword) {
                            echo "<div class='text-danger'><h5>Incorrect password, please try again.</h5></div>";
                        }
                    ?>
                    <label for="username">Username</label>    
                    <input type="text" id="username" class="form-control" name="username" placeholder="Enter username" />
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" class="form-control" name="password" placeholder="password" />
                </div>
                <div class="form-group">
                    <?php 
                    
                    if(isset($_POST['login'])) {
                        if(!$finished_captcha) {
                            echo "<div class='container text-danger'>Do captcha first</div>";
                        }
                        if($failedCaptcha) {
                            echo "<div class='container text-danger'>Do captcha again</div>";
                        }
                    }
                    ?>
                    <div class="g-recaptcha" data-sitekey="6Ld-Ml0fAAAAAOcsUFbrou1SMFJFAMp9Jg3wGIwO"></div>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col text-center">
                            <button type="submit" name="login" class="btn form-control  btn-secondary">Login</button>
                        </div>
                    </div>
                    <div class="row" style="margin-top:0.75rem">
                        <a href="registration.php" style="text-decoration: none">I do not have an account</a>
                    </div>
                    <div class="row" style="margin-top:0.75rem">
                        <a href="ForgetPassword.php" style="text-decoration: none" class="text-danger">Forget password</a>
                    </div>
                </div>
            </form>
        </div>
</body>
</html>