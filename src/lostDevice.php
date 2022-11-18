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
    <title>I lost my device</title>
</head>
<body>
    
        <?php 
            include "connectdb.php";
            session_start();
            $id = $_SESSION['userID'];
            $my_query = "SELECT * FROM user WHERE id=?";
            $stmt = mysqli_prepare($conn, $my_query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if(!$result) {
                die('Query Failed');
            }
            while($row = mysqli_fetch_assoc($result)) {
                $securityCode = $row['security_code'];
                $securityHash = $row['sec_ans'];
            }
            $my_query = "SELECT * FROM security_question WHERE security_code=?";
            $stmt = mysqli_prepare($conn, $my_query);
            mysqli_stmt_bind_param($stmt, "s", $securityCode);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if(!$result) {
                die('Query Failed');
            }
            while($row = mysqli_fetch_assoc($result)) 
            {
                $securityQuestion = $row['question'];
            }

        ?>
    <iframe name="tempFrame" style="display:none;">
    </iframe>
    <form action="verifyTwoFactor.php" id="send_email_ah" method="GET" target="tempFrame">
        <input type="hidden" name="resendVerification" value="True" />
    </form>
        <div class="container form-width">
            <h5 style="margin: 0.5rem 0;" class="text-center">Remove Two-factor authentication</h4>
            <form id="loginForm" action="lostDevice.php" method="POST" id="demo-form">
                <?php 
                    if(isset($_POST['verify'])) {
                        $answer = $_POST['answer'];
                        if(md5(strtolower($answer)) != $securityHash) {
                            echo "<h6 class='text-danger text-center'>Invalid Answer</h6>";
                        } else {
                            $my_query = "UPDATE verification SET enabled_verified=0 WHERE id=?";
                            $stmt = mysqli_prepare($conn, $my_query);
                            mysqli_stmt_bind_param($stmt, "i", $id);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            echo "<h6 class='text-success'>Two-factor authentication removed successfully</h6>";
                            header("refresh:2;url=logout.php" );
                        }
                    }
                ?>
                <div class="form-group">
                    <label for="username"><?php echo $securityQuestion?></label>    
                    <input type="text" id="passcode" class="form-control" name="answer" placeholder="Enter the Answer" />
                </div>
                <div class="container">
                    <div class="row justify-content-center" style="margin: 0.75rem 0;">
                        <button type="submit" name="verify" class="btn btn-secondary" >Remove verification</button>
                    </div>
                    <a href="login.php" style="text-decoration: none">Back to login page</a>
                </div>
            </form>
        </div>
</body>
</html>