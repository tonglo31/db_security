<?php 
    ob_start();
?>
<?php
    session_start();
    unset($_SESSION['username']);
    unset($_SESSION['salt']);
    unset($_SESSION['userID']);
    header("Location: index.php");
?>