<?php
    ob_start();
    header('Content-Description: File Transfer');
    header("Content-type: application/octet-stream");
    header("Content-disposition: attachment; filename= Image.jpg");
    exit(base64_decode($_POST['data'])); //url length is limited, use post instead
?>