<?php 
    ob_start();
?>
<?php 
    session_start();
    include 'connectdb.php';
    $userID = $_SESSION['userID'];
    if(isset($_POST['getRight'])) {
        $hasRight = True;
        $cid = $_POST['cid'];
        $id = $_POST['id'];
        $hashFormat = "$2y$10$";
        $salt = generateRandomString(22);
        $hashF_and_salt = $hashFormat . $salt;
        $hashCID = crypt($cid, $hashF_and_salt);
        $isExchange = 1;
        $query = "INSERT INTO own_artwork (CID, id, salt, cid_hash, exchange) VALUES(?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        //bind the param
        mysqli_stmt_bind_param($stmt, "sissi", $cid, $userID, $salt, $hashCID, $isExchange);
        mysqli_stmt_execute($stmt);
        //reduce point
        $query = "UPDATE user SET money = money - 1 WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        //bind the param
        mysqli_stmt_bind_param($stmt, "i", $userID);
        mysqli_stmt_execute($stmt);

        header("Location: artworkView.php?cid=$cid&id=$id");
    }
?>