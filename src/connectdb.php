<?php
    ob_start();
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    include "vendor/autoload.php";
    $conn = mysqli_connect('db', 'proj_docker', 'password', 'lamp_docker');
    if($conn) {
        echo "<p style='display: none;'>Connected</p>";
    } else {
        die("Failed to conenct to databaes");
    }
    function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function generateRandomStringUpper($length) {
        $characters = '56789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function generateReCaptchaTokenInfo($resp) {
        $secretkey = "6Ld-Ml0fAAAAAHBSa1yC85pour-wdwTYC1--4dh4";
        $ip = $_SERVER['REMOTE_ADDR'];
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretkey&response=$resp&remoteip=$ip";
        $file = file_get_contents($url);
        $json = json_decode($file);
        return $json;
    }

    function sendForgetPasswordEmail($email, $password, $passwordHash) {
        $conn = mysqli_connect('db', 'proj_docker', 'password', 'lamp_docker');
        $my_query = "UPDATE user SET password = ? WHERE email = ?";
        $stmt = mysqli_prepare($conn, $my_query);
        mysqli_stmt_bind_param($stmt, "ss", $passwordHash, $email);
        mysqli_stmt_execute($stmt);
        $mail = new PHPMailer(true);
        $mail->Username = "projecttest482131@gmail.com";
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Password = 'qumgxwsvvbtrvpvm';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('noreply@haha.com', 'System Mailer');
        $mail->addAddress($email);
        $mail->isHTML(true);
        // $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
        $mail->Subject = "Forget Password";
        $mail->Body = '<p style="bold">Dear user' . ':</p> <pre>  Your new password is: <b style=font-size: 30px;">' . $password . '</b> Please reset your password immediately.</pre>';
        $mail->send();
    
    }
    function sendRegistrationVerifyEmail($email, $name, $verification_code) {
        $mail = new PHPMailer(true);
        $mail->Username = "projecttest482131@gmail.com";
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Password = 'qumgxwsvvbtrvpvm';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('noreply@haha.com', 'System Mailer');
        $mail->addAddress($email, $name);
        $mail->isHTML(true);
        // $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
        $mail->Subject = "Registration verification";
        $mail->Body = '<p style="bold">Dear '.$name . ':</p> <pre>  Your Verification code is: <b style=font-size: 30px;">' . $verification_code . '</b></pre>';
        $mail->send();
    
    }
    function resendVerification($verification_code, $id) {
        $conn = mysqli_connect('db', 'proj_docker', 'password', 'lamp_docker');
        $my_query = "SELECT * FROM user WHERE id=?";
        $stmt = mysqli_prepare($conn, $my_query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(!$result) {
            die('Query Failed');
        }
        while($row = mysqli_fetch_assoc($result)) {
            //get user id
            $userID = $row['id'];
            $email = $row['email'];
            $username = $row['username'];
        }
        $mail = new PHPMailer(true);
        $mail->Username = "projecttest482131@gmail.com";
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Password = 'qumgxwsvvbtrvpvm';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('noreply@haha.com', 'System Mailer');
        $mail->addAddress($email, $username);
        $mail->isHTML(true);
        // $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
        $mail->Subject = "Verification Email";
        $mail->Body = '<p style="bold">Dear ' . $username . ':</p> <pre> Your Latest Verification code is: <b style=font-size: 30px;">' . $verification_code . '</b></pre>';
        $mail->send();
    }

    function getCurrentPoint($id) {
        $conn = mysqli_connect('db', 'proj_docker', 'password', 'lamp_docker');
        $my_query = "SELECT * FROM user WHERE id=?";
        $stmt = mysqli_prepare($conn, $my_query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(!$result) {
            die('Query Failed');
        }
        while($row = mysqli_fetch_assoc($result)) {
            //get user id
            $money = $row['money'];
        }
        return $money;
    }

    function updateAsVerified($id) {
        $conn = mysqli_connect('db', 'proj_docker', 'password', 'lamp_docker');
        $update_verify = "UPDATE verification SET register_verified = 1 WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_verify);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
    
    }
    function getEmailAddress($id) {
        $conn = mysqli_connect('db', 'proj_docker', 'password', 'lamp_docker');
        $my_query = "SELECT * FROM user WHERE id=?";
        $stmt = mysqli_prepare($conn, $my_query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(!$result) {
            die('Query Failed');
        }
        while($row = mysqli_fetch_assoc($result)) {
            //get user id
            $email = $row['email'];
        }
        return $email;
    }


    function checkVerifyType($id) {
        $conn = mysqli_connect('db', 'proj_docker', 'password', 'lamp_docker');
        $my_query = "SELECT * FROM verification WHERE verification.id=?";
        $stmt = mysqli_prepare($conn, $my_query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $verify_type = "";
        if(!$result) {
            die('Query Failed');
        }
        while($row = mysqli_fetch_assoc($result)) {
            //get user id
            $verify_type = $row['enabled_verified'];
        }
        return $verify_type;
    }

    function addVerifyMethod($id, $method, $secret="48264") {
        $conn = mysqli_connect('db', 'proj_docker', 'password', 'lamp_docker');
        $update_verify = "UPDATE verification SET enabled_verified = ?, verify_code = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_verify);
        mysqli_stmt_bind_param($stmt, "isi", $method, $secret, $id);
        mysqli_stmt_execute($stmt);
    }

    function getVerifyCode($id) {
        $code = "";
        $conn = mysqli_connect('db', 'proj_docker', 'password', 'lamp_docker');
        $update_verify = "SELECT * FROM verification WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_verify);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(!$result) {
            die('Query Failed');
        }
        while($row = mysqli_fetch_assoc($result)) {
            //get user id
            $code = $row['verify_code'];
        }
        return $code;
    }

    function obfuscate_email($email)
    {
        $em   = explode("@",$email);
        $name = implode('@', array_slice($em, 0, count($em)-1));
        $len  = floor(strlen($name)/2);

        return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);   
    }

    function isVerifiedUser($id) {
        $conn = mysqli_connect('db', 'proj_docker', 'password', 'lamp_docker');
        $my_query = "SELECT * FROM verification INNER JOIN user ON user.id = verification.id WHERE verification.id=?";
        $stmt = mysqli_prepare($conn, $my_query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(!$result) {
            die('Query Failed');
        }
        while($row = mysqli_fetch_assoc($result)) {
            //get user id
            $isRegistered = $row['register_verified'];
            $username = $row['username'];
            $enabled_verified = $row['enabled_verified'];
        }
        $host  = $_SERVER['HTTP_HOST'];
        if ($isRegistered == 1) {
            $_SESSION['username'] = $username;
            $_SESSION['userID'] = $id;
            if ($enabled_verified == 0){
                header('Location: index.php');
            } else if($enabled_verified == 1){
                setcookie("verify_type", 1);
                header("Location: http://$host/verifyTwofactor.php");
            } else if($enabled_verified == 2){
                setcookie("verify_type", 2);
                header("Location: http://$host/verifyTwofactor.php");
            } 
        } else {
            setcookie("userID", $id);
            header("Location: registrationVerify.php");
        }
    }
?>
