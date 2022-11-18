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
    <title>Document</title>
</head>
<body>
    <script>
        
        function readurl(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#preview-img').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
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
    if(isset($_POST['submit']) && !empty($_FILES['picture']['tmp_name'])) {
        $data = file_get_contents($_FILES['picture']['tmp_name']);
        $data = base64_encode($data);
        $description = $_POST['description'];
        $artwork = $_POST['artwork_name'];
        ?>
        <form action="uploadSuccess.php" id="sendTest" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="uploadNft" id="hiddenVal" value="True">
            <input type="hidden" name="description" value="<?php echo $description?> "name="description" id="uploadDesc">
            <input type="hidden" name="artwork_name" value="<?php echo $artwork ?>"id="uploadName">
            <input type="hidden" name="userID" value="<?php echo $userID ?>">
        </form>
        <script>
        $(document).ready(function() {
                jQuery.ajax({
                    type: 'POST',
                    url: 'https://api.nft.storage/upload',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJkaWQ6ZXRocjoweDRGOGQyMTg5YzM3NTA0M0JDQWEyZTM0M2Y5MzhlQzJkREQ4QzY5NzkiLCJpc3MiOiJuZnQtc3RvcmFnZSIsImlhdCI6MTY0OTc2MzU4MzIwNywibmFtZSI6InNlY3VyaXR5UHJvamVjdCJ9.XjojpkiP64ECg0u1pkZXUq9GrQuodg-RxYFvBemkkks")
                    },
                    mimeType: 'multipart/form-data', // this too
                    contentType: "image/png",
                    cache: false,
                    processData: false,
                    data: "<?php echo $data; ?>",
                    file: "<?php echo$data; ?>",
                    success: function(data) {
                        console.log(data);
                        $("#hiddenVal").val(data);
                        $("#sendTest").submit();
                    },
                    error: function(data) {
                        console.log(data);
                } 
                });
        });
        </script>
        <?php
    }

?>
<div class="container" style="margin-top: 3rem; background-color: #fff; box-shadow: 0 5px 15px rgb(0 0 0 / 40%); padding: 5rem">
    <div class="row text-center" style="margin-bottom: 3rem">
        <h3>Upload Artwork</h3>
    </div>
    <div class="row justify-content-center">
        <div class="col-6 align-self-center text-center">
            <h4>Preview Image</h5>
            <img id="preview-img" width="400px"class="rounded mx-auto d-block" src="./img/preview.png" alt="preview.img">
        </div>
        <div class="col-6 align-self-center">
            <form action="uploadNft.php" method="POST" enctype="multipart/form-data">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col">
                            <div class="form-group">
                                <label for="artwork_name">Name of Artwork</label>
                                <input type="text" class="form-control" id="artwork_name" name="artwork_name" />
                            </div>
                            <div class="form-group">
                                <label for="description">Artwork Description</label>
                                <textarea class="form-control" id="description" rows="5" name="description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="picture">File</label>
                                <input type="file" onchange="readurl(this);" name="picture" class="form-control" id="picture" accept="image/png, image/jpeg" />
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" class="btn btn-secondary mb-2" style="margin-top:2rem" >Upload Artwork</button>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="uploadNft" value="True">
            </form>
        </div>
    </div>
</div>
</body>
</html>