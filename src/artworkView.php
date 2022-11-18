<?php 

    ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/107262dd19.js" crossorigin="anonymous"></script>
        <script src="./js/jquery-3.6.0.min.js"></script>
        <?php 
        include "connectdb.php";
        session_start();
        if(isset($_SESSION['userID'])) {

            $userID = $_SESSION['userID'];
        } else {
            header("Location: login.php");
        }
        if(isset($_POST['checkHashCID'])) {
            $correct = False;
            $download_cid = $_POST['hash_cid'];
            $url = $_POST['url'];
            $cid = $_POST['cid'];
            $query = "SELECT * FROM own_artwork WHERE id = ? and CID = ?";
            $stmt = mysqli_prepare($conn, $query);
            //bind the param
            mysqli_stmt_bind_param($stmt, "is", $userID, $cid);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            while($row = mysqli_fetch_assoc($result)) {
                $cid_hash = $row['cid_hash'];
            }
            echo $cid_hash;
            if($download_cid == $cid_hash) {
                $correct = True;
            }
            if($correct == True) {
                ?>
                <script>
                    $(document).ready(function(){
                        location.href = '<?php echo $url . "&dw=1"?>';
                    });
                </script>
                <?php
            } else {
                ?>
                <script>
                    $(document).ready(function(){
                        location.href = '<?php echo $url . "&failed=1"?>';
                    });
                </script>
                <?php
            }
        }
            if(isset($_GET['cid'])) {
                $cid = $_GET['cid'];
                $id = $_GET['id'];
                $query = "SELECT * FROM artwork INNER JOIN user ON user.id = artwork.id WHERE CID=? and artwork.id=?";
                $stmt = mysqli_prepare($conn, $query);
                //bind the param
                mysqli_stmt_bind_param($stmt, "si", $cid, $id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_assoc($result)) {
                    //get user id
                    $cid = $row['CID'];
                    $artwork_name = $row['artwork_name'];
                    $description = $row['description'];
                    $url = "https://$cid.ipfs.nftstorage.link";
                    $data = file_get_contents($url);
                    $artist_name = $row['username'];
                    $date = $row['time'];
                }
            }
        ?>
    <title></title>
</head>
<body>
    <script>
        $(document).ready(function(){
            $("#DownloadBtn2").click(function(){
                $("#checkHashCID").submit();
            });
            $("#copy").click(function(){
                  var copyText = document.getElementById('unlock_password_input');
                  copyText.select();
                  document.execCommand('copy')
            });
        });
        </script>
<?php 
    if(isset($_POST['getRight']) && $hasRight) {
        ?>
        <div class="alert alert-success" id="two-factor-success" role="alert">
            <p>You You now have permission to download the artist's work.!!!!</p>
        </div>
        <?php
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
    <div class="container" style="margin-top: 3rem">
        <div class="row">
            <div class="col">
                <img id="profile-img" src='data:image/jpeg;base64,<?php echo $data;?>' width="500px" style="opacity:.8;shadow-box: 4px; border:2px solid #b8b8b8"class="rounded upload-pro" alt="profile.png">
            </div>
            <div class="col">
                <h2>Artwork: <?php echo $artwork_name?></h3>
                <p>Created By: <?php echo $artist_name ?></p>
                <p>Upload Time and Date: <?php echo $date?></p>
                <p>Description: <?php echo $description?></p>
                <?php 
                    $query = "SELECT * FROM own_artwork WHERE id=? and CID =?";
                    $stmt = mysqli_prepare($conn, $query);
                    //bind the param
                    mysqli_stmt_bind_param($stmt, "is", $userID, $cid);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if (mysqli_num_rows($result) == 0) {
                        $isOwner = False;
                    } else {
                        $isOwner = True;
                    }
                    if($isOwner) {
                        $query = "SELECT * FROM own_artwork INNER JOIN artwork ON own_artwork.id = artwork.id WHERE own_artwork.id=? AND own_artwork.CID = ?";
                        $stmt = mysqli_prepare($conn, $query);
                        //bind the param
                        mysqli_stmt_bind_param($stmt, "is", $userID, $cid);
                        mysqli_stmt_execute($stmt);
                        while($row = mysqli_fetch_assoc($result)) {
                            //get user id
                            $unlockPw = $row['cid_hash'];
                        }
                    }
                ?>
                <?php
                    if($isOwner) {
                        ?>

                        <div class="form-group">
                            <h5>Unlock Password:   <a href="#" id="copy" style="margin-left: 2rem; text-decoration: none;">Copy to clipboard</a></h5> 
                            <input type="text" id="unlock_password_input" class="form-control" value="<?php echo $unlockPw ?>" >
                        </div>
                        <?php
                    }
?>
                <?php 
                    if (!$isOwner) {
                        echo '<button type="button" style="margin-top:3rem" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#viewDialog">Exchange Artwork</button>';
                    }  else {
                        echo '<button type="button" id="downloadDialog" style="margin-top:3rem" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#viewDialog" >Download</button>';
                    }
                ?>
            </div> 
        </div>
    </div>
    <div class="modal fade" id="viewDialog" tabindex="-1" aria-labelledby="viewDialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <?php 
                    if(!$isOwner) {
                        echo '<h5 class="modal-title" id="exampleModalLabel">Exchange Artwork with Point</h5>';
                    } else {
                        echo "<h5>You file is ready to download.</h5>";
                    }
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php 
                    if(!$isOwner) {
                        ?>
                        <p>You can exchange other artists' work with Points, which can only be obtained by uploading Points. You can gain point by uploading artwork <a href="uploadNft.php" style="text-decoration: none">here. </a> You currently have <bold><?php echo getCurrentPoint($userID)?> </bold>points</p>
                        <?php
                    } else {
                        if(isset($_GET['failed']) &&!isset($_GET['dw'])) {
                            ?>
                            <script>
                                $(document).ready(function(){
                                    $("#downloadDialog").click();
                                });
                            </script>
                            <?php
                            echo "<h5 class='text-danger text-center'>Incorrect Password</h5>";
                        }
                        echo "<p>You have access to this artwork, so you can download the file with the unlock password.</p>";
                        ?>
                        <form action="artworkView.php" id="checkHashCID" method="POST" target="OK">
                            <h4>Unlock Password</h4>
                            <?php 
                                $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                            ?>
                            <input type="text" class="form-control" style="margin: 0.85rem 0; width:400px;" id="unlockPassword" name="hash_cid">
                            <input type="hidden" name="cid" value="<?php echo $cid?>">
                            <input type="hidden" name="id" value="<?php echo $userID?>">
                            <input type="hidden" name="url" value="<?php echo $actual_link?>">
                            <div class="form-group captcha">
                            <div class="g-recaptcha " data-sitekey="6Ld-Ml0fAAAAAOcsUFbrou1SMFJFAMp9Jg3wGIwO"></div>
                            </div>
                            <input type="hidden" name="checkHashCID">
                        </form>
                        <?php
                    }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <?php
                if($isOwner) {
                ?>
                    <button type="button" id="DownloadBtn2" class="btn btn-success">Download</button>
                <?php
                }
                ?>
                <?php 
                    if(!$isOwner) {
                        ?>
                        <form action="setOwner.php" method="POST"/>
                            <input type="hidden" name="cid" value="<?php echo $cid?>">
                            <input type="hidden" name="getRight">
                            <input type="hidden" name="id" value="<?php echo $id?>">
                            <button type="submit" class="btn btn-success" <?php if(getCurrentPoint($userID) <= 0){ echo "disabled ";}?>>Exchange</button>
                        </form>
                        <?php
                    } else {
                        ?>
                        <iframe style="display: none"name="Test" frameborder="0"></iframe>
                        <form action="download.php" id="downloadNow" method="POST" target="Test"/>
                            <input type="hidden" name="data" value="<?php echo $data?>">
                        </form>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>
    </div>
</body>
</html>
    <?php 
        if(isset($_GET['dw'])) {
            ?>
            <script>
                $("#downloadNow").submit();
            </script>
            <?php
        }
    ?>