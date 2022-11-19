<?php 
    ob_start();
    include "connectdb.php";
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
    <script src="./js/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="./styles.css" />
    <title>Your Artworks</title>
</head>
<body>
    <?php 
        session_start();
        if(isset($_SESSION['userID'])) {

            $userID = $_SESSION['userID'];
        } else {
            header("Location: login.php");
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
        <?php
        $query = "SELECT * FROM artwork WHERE id=?";
        $stmt = mysqli_prepare($conn, $query);
        //bind the param
        mysqli_stmt_bind_param($stmt, "i", $userID);

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(!$result) {
            die('Query Failed');
        }
        if (mysqli_num_rows($result) == 0) { 
            ?>
            <div class="container">
                <div class="row text-center" style="margin-top: 4rem" >
                    <div class="col">
                        <h4>Ooops..... You didn't upload any artwork.</h6>
                    </div>
                    <div class="col">

                        <a href="uploadNft.php" class="btn btn-secondary">Upload artwork to NFT</a>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <section id="artworks">
                <div class="container">
                    <div class="title text-center" style="margin-top:3rem">
                        <h1>Artworks</h1>
                        <p style="font-size:1.15rem">These are your works, don't worry, they will always be there in NFT</p>
                    </div>
                    <div class="row">
                        <?php 
                        
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
            <h1 class="text-center" style="margin-top: 2rem">Exchanged Artwork</h1>
            <!-- <?php 

                    $query = "SELECT * FROM own_artwork INNER JOIN artwork ON own_artwork.CID = artwork.CID WHERE own_artwork.id=? and exchange = 1";
                    $stmt = mysqli_prepare($conn, $query);
                    //bind the param
                    mysqli_stmt_bind_param($stmt, "i", $userID);

                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if(!$result) {
                        die('Query Failed');
                    }
                    if (mysqli_num_rows($result) == 0) { 
                        echo "<h4> You do not have any exchanged artwork, exchange <a href='index.php'>here</a>";
                    } else {
                        ?>
                        <table class="table">
                            <thead>
                                <tr>
                                <th scope="col">CID</th>
                                <th scope="col">Artwork's title</th>
                                <th scope="col">Upload on</th>
                                <th scope="col">View artwork</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while($row = mysqli_fetch_assoc($result)) {
                                    //get user id
                                    $cid = $row['CID'];
                                    $title = $row['artwork_name'];
                                    $date = $row['time'];
                                    $user = $row['id'];
                                    ?>
                                    <tr>
                                        <td><?php echo $cid ?></td>
                                        <td><?php echo $title ?></td>
                                        <td><?php echo $date ?></td>
                                        <td><a href="artworkView.php?cid=<?php echo $cid ?>&id=<?php echo $user ?>">View</a>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <?php
                    }
            ?>
            <?php
        }
    ?> -->
</body>
</html>