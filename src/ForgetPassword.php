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
    <title>Forget Password</title>
</head>
<body>
        <div class="container form-width">
            <h4 class="text-center">Forget Password</h4>
            <form id="ForgetPassword" action="ForgetPassword.php" method="POST" >
                <?php 
                include "connectdb.php";
                    if(isset($_POST['Reset'])) {
                        $email = $_POST['email'];
                        if(strlen($email) == 0) {
                            echo "<div class='container text-danger'>Email cannot be empty</div>";
                        }
                        $query = "SELECT * FROM user WHERE email= ?";
                        $stmt = mysqli_prepare($conn, $query);
                        mysqli_stmt_bind_param($stmt, "s", $email);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $salt = "";
                        $hashPassword = "";
                        if (mysqli_num_rows($result) == 0) { 
                            echo "<div class='container text-danger'>Email is not exist.</div>";
                        } else {
                            while($row = mysqli_fetch_assoc($result)) {
                                //get the salt
                                $salt = $row['salt'];
                            }
                            $newPassword = generateRandomString(8) . ".";
                            $hashFormat = "$2y$10$";
                            $hashF_and_salt = $hashFormat . $salt;
                            $hashPassword = crypt($newPassword, $hashF_and_salt);
                            sendForgetPasswordEmail($email, $newPassword, $hashPassword);
                            ?>
                            <script>
                                $(document).ready(function(){
                                    $("#showModal").click();
                                    var delay = 1000; 
                                    setTimeout(function(){ window.location = "login.php"; }, delay);
                                });
                            </script>
                            <?php
                        }

                    }
                ?>
                <div class="form-group">
                    <label for="email">Enter email:</label>    
                    <input type="email" id="email" class="form-control" name="email" placeholder="Enter email here" />
                </div>
                <div class="container">
                    <div class="row justify-content-center" style="margin: 0.75rem 0;">
                        <button type="submit" name="Reset" class="btn btn-secondary" >Reset Password</button>
                    </div>
                </div>
            </form>
        </div>

<button type="button" id="showModal" class="btn btn-primary" style="display: none;"data-toggle="modal" data-target="#exampleModal">
</button>

        <div class="modal" tabindex="-1" role="dialog" id="exampleModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Please check your email</h5>
      </div>
      <div class="modal-body">
        <p>The new password is sent to your email. Please login and change your password immediately.</p>
      </div>
    </div>
  </div>
</div>
</body>
</html>