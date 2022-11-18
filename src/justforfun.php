<?php 
    ob_start();
?>
<?php 
    include "connectdb.php";
    sendRegistrationVerifyEmail("21029122d@connect.polyu.hk", "leeman");
?>