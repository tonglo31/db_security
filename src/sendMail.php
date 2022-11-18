    <?php
        ob_start();
        include 'connectdb.php';
        if(isset($_GET['send_email_ah'])) {
            $conn = mysqli_connect('localhost', 'root', '', 'security_project');
           resendVerification(48264, 2);
        }
    ?>