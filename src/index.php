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
    <title>Document</title>
</head>
<body>
    <?php 
        session_start();
        include "connectdb.php";
        if (isset($_SESSION['login_verified']) == "True") {
            ?>
            <div class="alert alert-success" role="alert">
                <p>Your account's email verification was successful and you will be redirected to the home page.</p>
            </div>
            <?php
            unset($_SESSION['login_verified']);
            unset($_COOKIE['userID']);
            header("refresh:2;url=index.php");
        }
        if(isset($_GET['resetPassword'])) {
            
            ?>
            <div class="alert alert-success" role="alert">
                <p>Your password was reset successfully.</p>
            </div>
            <?php

                header("refresh:2;url=logout.php");
        }
        ?>

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
        <section id="artworks">
            <div class="container">
                <div class="title text-center" style="margin-top:3rem">
                    <h1>Artworks NFT platform</h1>
                    <p style="font-size:1.15rem;">The following are the artworks painted by all the artists on this platform, welcome to join the creation, all works will be saved to NFT and will not disappear</p>
                </div>
                <div class="row">
                    <?php 
                    $query = "SELECT * FROM artwork";
                    $stmt = mysqli_prepare($conn, $query);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if(!$result) {
                        die('Query Failed');
                    }
                    while($row = mysqli_fetch_assoc($result)) {
                        //get user id
                        $cid = $row['CID'];
                        $artwork_name = $row['artwork_name'];
                        $create_time = $row['time'];
                        $url = "https://$cid.ipfs.nftstorage.link";
                        $data = file_get_contents($url);
                        $id = $row['id'];
                        ?>
                        <div class="col-md-4 wow" style="margin-top: 3rem">
                            <div class="card text-center">
                                <img src="data:image/jpeg;base64,<?php echo $data;?>" class="card-img-top">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $artwork_name?></h5>
                                    <p class="card-text">Created time: <?php echo $create_time?></p>
                                    <a href="artworkView.php?cid=<?php echo $cid?>&id=<?php echo $id?>">View Artwok</a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </section>
</body>
</html>