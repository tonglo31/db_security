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
    <link rel="stylesheet" href="./css/registration.css"/>
    <script src="./js/jquery-3.6.0.min.js"></script>
    <title>Registration</title>
</head>
<body>
    <?php
    include "connectdb.php";
    if(isset($_COOKIE['userID'])) {
        $userID = $_COOKIE['userID'];
    } 
    if (isset($_GET['resendVerification']) == "True") {
        $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
        $hashVerifiedCode = md5($verification_code);
        $update_verify = "UPDATE verification SET verify_code = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_verify);
        mysqli_stmt_bind_param($stmt, "si", $hashVerifiedCode, $userID);
        mysqli_stmt_execute($stmt);
        resendVerification($verification_code, $userID); 
    }
    if(isset($_POST['verify'])) {
        $invalid_code = False;
        $code = $_POST['code'];
        $hashCode = md5($code);
        //Query by userId
        $query = "SELECT * FROM verification INNER JOIN user ON user.id = verification.id WHERE verification.id=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $userID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(!$result) {
            die('Query Failed');
        }
        while($row = mysqli_fetch_assoc($result)) {
            //get the salt
            $code = $row['verify_code'];
        }
        if ($hashCode == $code) {
            session_start();
            $_SESSION["login_verified"] = "True";
            updateAsVerified($userID);
            header("Location: index.php");
        } else {
            $invalid_code = True;
        }
    }
    ?>
        <div class="container form-width">
            <form id="loginForm" action="registrationVerify.php" method="POST" id="demo-form">
                <div class="form-group">
                    <h5 class="text-center" style="margin: 1.275rem">Email Verification</h5>
                    <?php 
                        if (isset($_POST['verify']) && $invalid_code) {
                            echo "<h6 class='text-danger'>Invalid code</h6>";
                        }
                    ?>
                    <label for="code">Verification Code</label> 
                    <input type="password" id="code" class="form-control" name="code" placeholder="code" style="margin: 0.75rem 0"/>
                    <button type="submit" name="verify" style="margin: 0.75rem 0"class="btn form-control btn-secondary">Verify</button>
                    <a href="registrationVerify.php?resendVerification=True" style="text-decoration: none">Resend verification email</a>
                </div>
            </form>
        </div>
</body>
</html>