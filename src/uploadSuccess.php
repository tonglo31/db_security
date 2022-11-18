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
    <link rel="stylesheet" href="./css/profile.css"/>
    <script src="./js/jquery-3.6.0.min.js"></script>
        <?php
        include 'connectdb.php';
        if(isset($_SESSION['userID'])) {
            $userID = $_SESSION['userID'];
        }
        if(isset($_POST['uploadNft'])) {
            $databaseExist = False;
            $nft_json = $_POST['uploadNft'];
            $description = $_POST['description'];
            $uploadName = $_POST['artwork_name'];
            $userID = $_POST['userID'];
            $obj = json_decode($nft_json);
            $cid = $obj->value->cid;
            $date = $obj->value->created;
            $url = "https://$cid.ipfs.nftstorage.link";
            $data = file_get_contents($url);
            $query = "SELECT * FROM artwork WHERE cid = ?";
            $stmt = mysqli_prepare($conn, $query);
            //bind the param
            mysqli_stmt_bind_param($stmt, "s", $cid);

            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if(!$result) {
                die('Query Failed');
            }
            if (mysqli_num_rows($result) == 0) {
                $query = "INSERT INTO artwork (CID, id, artwork_name, description, time) VALUES(?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $query);
                //bind the param
                mysqli_stmt_bind_param($stmt, "sisss", $cid, $userID, $uploadName, $description, $date);
                //execute the statement
                mysqli_stmt_execute($stmt);
            } else{
                $databaseExist = True;
            }
        }?>
    <title>Upload Success - <?php echo $uploadName?></title>
</head>
<body>
        <div class="container" style="max-width:100%; padding:0">
            <nav class="navbar navbar-expand-md navbar-dark bg-dark">
                <a href="index.php" class="navbar-brand" style="margin-left:4rem">Home</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#toggleMobileMenu" aria-controls="toggleMobileMenu"
                aria-expamnded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="toggleMobileMenu">
                <ul class="navbar-nav ms-auto text-center" style="padding:0 1.25rem;">
                    <li><a href="uploadNft.php" class="nav-link">Upload Nft Artwork</a></li>
                    <li><a href="uploadedArtWork.php" class="nav-link">My Artwork</a></li>
                    <li><a href="profile.php" class="nav-link">Profile</a></li>
                    <?php 
                        if (isset($_SESSION['userID'])) {
                            echo '<li><a href="logout.php" class="nav-link">Logout</a></li>';
                        } else {
                            echo '<li><a href="registration.php" class="nav-link">Register</a></li>';
                            echo '<li><a href="login.php" class="nav-link">Login</a></li>';
                        }
                    ?>
                </ul>
            </div>
            </nav>
        </div>
    <?php 
        ?>
        <?php 
        if(isset($_POST['uploadNft']) && $databaseExist) {

            echo '<div class="alert alert-danger" id="two-factor-success" role="alert">
                <p>Your artwork already exist, please change the file name and artwork name !!!!</p>
            </div>';
        } else {
            
            //update point
            $query = "UPDATE user SET money = money + 1 WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            //bind the param
            mysqli_stmt_bind_param($stmt, "i", $userID);
            mysqli_stmt_execute($stmt);
            //set owner

            $hashFormat = "$2y$10$";
            $salt = generateRandomString(22);
            $hashF_and_salt = $hashFormat . $salt;
            $hashCID = crypt($cid, $hashF_and_salt);
            $query = "INSERT INTO own_artwork (CID, id, salt, cid_hash, exchange) VALUES(?, ?, ? , ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            //bind the param
            $isExchange = 0;
            mysqli_stmt_bind_param($stmt, "sissi", $cid, $userID, $salt, $hashCID, $isExchange);
            mysqli_stmt_execute($stmt);
            echo '<div class="alert alert-success" id="two-factor-success" role="alert">
                <p>Your artwork has been upload to the NFT successfully!!!!</p>
            </div>';
        }
        ?>
        <div class="container">
            <div class="row">
                <div class="col">

                    <img id="profile-img" src='data:image/jpeg;base64,<?php echo $data;?>' width="500px" style="opacity:.8;shadow-box: 4px; border:2px solid #b8b8b8"class="rounded upload-pro" alt="profile.png">
                </div>
               <div class="col">
                   <h2>Artwork: <?php echo $uploadName?></h3>
                   <p>Description: <?php echo $description?></p>
                   <div class="form-group">
                       <p>The following is your password to download artwork, don't worry this password only you can use, other users are not able to use, so don't worry about leaking out.</p>
                       <h5>Unlock Passowrd:</h5> <input type="text" class="form-control" value="<?php echo $hashCID ?>" disabled>
                   </div>
               </div> 
            </div>
        </div>
        <?php
    ?>
</body>
</html>