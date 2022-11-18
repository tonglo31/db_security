<!DOCTYPE html>
<html>
	<head>
		<title>Hong Kong Cube Ship Online Shopping</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
		<link rel="stylesheet" href="./css/styles.css">
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
		<link rel="icon" href="./favicon.ico" type="image/x-icon">
		<!-- 		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V" crossorigin="anonymous"> -->
		<script type="text/javascript"> (function() { var css = document.createElement('link'); css.href = 'https://use.fontawesome.com/releases/v5.1.0/css/all.css'; css.rel = 'stylesheet'; css.type = 'text/css'; document.getElementsByTagName('head')[0].appendChild(css); })(); </script>
		<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="./css/profile.css">
	</head>
	<?php
		include("conn.php");
		session_start();
		$cart_items_count = 0;
		$customerEmail = "";
		if(isset($_SESSION['email'])){
			$customerEmail = $_SESSION['email'];
		}
		if(isset($_COOKIE['email'])){
			$customerEmail = $_COOKIE['email'];
		}

		if($customerEmail==""){
			header('location: signin.php');
		}
		if(isset($_POST['oldpassword'])){
			$error = false;
			$canUpdate = false;
			$errorMessage = "The following  information cannot update: <br>";
			$result = mysqli_query($conn, "SELECT * FROM customer WHERE customerEmail='{$customerEmail}'")
				or die(mysqli_error($conn));
			$row = mysqli_fetch_assoc($result);
			$firstnameLength = 0;
			$lastnameLength = 0;
			$phoneNumLength = 0;
			$newPasswordLength = 0;
			if($_POST['oldpassword']==$row['password']){
				$canUpdate = true;
				$firstnameLength = strlen($_POST['firstname']);
				$lastnameLength = strlen($_POST['lastname']);
				$phoneNumLength = strlen($_POST['phoneNum']);
				$newPasswordLength = strlen($_POST['newpassword']);
			}else{
				$canUpdate = false;
				$error = true;
				$errorMessage .= "Your old password is not match or not entered";
			}
			
			if($canUpdate){
				if($firstnameLength>0){
					if($firstnameLength>255 || $firstnameLength<2){
						$error = true;
						$errorMessage .= "Your firstname should not more than 255 characters and less than 2 characters<br>";
					}else{
						if($firstnameLength>=2){
							$sql = "UPDATE customer SET firstName='{$_POST['firstname']}' WHERE customerEmail='{$customerEmail}'";
							mysqli_query($conn, $sql) or die(mysqli_error($conn));
						}
					}
				}
				if($lastnameLength>0){
					if($lastnameLength>255 || $lastnameLength <2){
						$error = true;
						$errorMessage .= "Your lastname should not more than 255 characters and less than 2 characters<br>";
					}else{
						if($lastnameLength>=2){
							$sql = "UPDATE customer set lastName='{$_POST['lastname']}' WHERE customerEmail='{$customerEmail}'";
							mysqli_query($conn, $sql) or die(mysqli_error($conn));
						}
					}
				}
				if($phoneNumLength>0){
					if(!is_numeric($_POST['phoneNum'])){
						$error = true;
						$errorMessage .= "Your phone number should be 8 digit<br>";
					}else{
						if($phoneNumLength==8 && is_numeric($_POST['phoneNum'])){
							$sql = "UPDATE customer set phoneNumber='{$_POST['phoneNum']}' WHERE customerEmail='{$customerEmail}'";
							mysqli_query($conn, $sql) or die(mysqli_error($conn));
						}
					}
				}
				if($newPasswordLength>0){
					if($newPasswordLength<8 || ($_POST['newpassword'] == $row['password'])){
						$error = true;
						$errorMessage .= "Your new Password should more than 7 charcters and not the same as before.<br>";
					}else{
						if($newPasswordLength>=8){
							$sql = "UPDATE customer set password='{$_POST['newpassword']}' WHERE customerEmail='{$customerEmail}'";
							mysqli_query($conn, $sql) or die(mysqli_error($conn));
						}
					}
				}
			}
		}

		$cartName = 'cart' . $customerEmail;
		if(isset($_SESSION[$cartName])){

		}
	?>
	<body>
		<div class="navbar-section">
			<nav class="navbar navbar-expand-lg navbar-dark">
				<a class="navbar-brand" href="./products.php">Hong Kong Cube Shop</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#banner-navbar" aria-controls="banner-navbar" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="banner-navbar">
					<ul class="navbar-nav ml-auto banner-navbar">
						<li class="nav-item">
							<a class="nav-link" href="./products.php">Buy Products</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="./view-orders.php">Your Orders</a>
						</li>
						<li class="nav-item dropdown">
							<?php
								if($customerEmail==""){
									echo '<a class="nav-link" href="./signin.php">Sign in</a>';
								}else{
									$result = mysqli_query($conn, "SELECT * FROM customer WHERE customerEmail='{$customerEmail}'")
										or die(mysqli_error($conn));
									$row = mysqli_fetch_assoc($result);
									echo '<a class="nav-link dropdown-toggle" href="./profile.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<i class="fas fa-user"></i> ' . $row['firstName'] . " " .$row['lastName'] ."</a>";
									echo '<div class="dropdown-menu" aria-labelledby="navbarDropdown">
														<a class="dropdown-item" href="./profile.php">Profile</a>
														<a class="dropdown-item logout" class="" href="./logout.php">Logout</a>
										</div>';
								}
							?>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="
								<?php
									if(isset($_COOKIE['email'])){
										echo '#';
									}else{
										echo "place-order.php/";
									}
								?>"><i class="fas fa-shopping-cart"></i>  Cart <span class="text-warning"><?=$_SESSION['cartCount']?></span></a>
							</li>
						</ul>
					</div>
				</nav>
			</div>
			<?php
				if(isset($_POST['oldpassword']) && !$error){
					echo '<div id="successMessage" class="bg-success" style="padding:20px; 0; width:100%; text-align: center; color: white; font-size: 1.45rem;" >
								<p>Update Profile info success!</p>
						</div>';
			echo '<script>setTimeout(function() {
					document.getElementById("successMessage").style.display = "none";
			}, 3000)</script>';
			}
			?>
			<seciton id="profile">
			<h1 class="profile-title">Profile</h1>
			<div class="dropdown-divider"></div>
			<div class="profile-edit">
				<form action="<?=$_SERVER['PHP_SELF']?>" method="POST" id="profile-form" class="profile-form">
					<?php
						if(isset($_POST['oldpassword']) && $error){
							echo '<div class="form-group">
										<p class="text-danger">' . $errorMessage . '</p>
								</div>';
						}
					?>
					<div class="form-group">
						<label for="firstname">First Name</label>
						<input type="text" class="form-control" id="firstname" name="firstname" pattern="[a-zA-z]{2,255}">
					</div>
					<div class="form-group">
						<label for="lastname">Last Name</label>
						<input type="text" class="form-control" id="lastname" name="lastname" pattern="[a-zA-z]{2,255}">
					</div>
					<div class="form-group">
						<label for="newpassword">Old Password</label>
						<input type="oldpassword" class="form-control" id="oldpassword" name="oldpassword"  pattern=".{8,50}" required="required">
					</div>
					<div class="form-group">
						<label for="newpassword">New Password</label>
						<input type="password" class="form-control" id="newpassword" name="newpassword"  pattern=".{8,50}">
						<small id="passwordHelp" class="form-text text-muted">Make sure new password is not more than 50 characters.</small>
					</div>
					<div class="form-group">
						<label for="phoneNum">New Phone Number</label>
						<input type="text" class="form-control" id="phoneNum" name="phoneNum" >
					</div>
					<button type="button" class="btn btn-success profile-btn" data-toggle="modal" data-target="#updateFormModal">Update profile</button>
					<div class="modal fade" id="updateFormModal" tabindex="-1" role="dialog" aria-labelledby="updateFormLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="updateFormLabel">Are you sure?</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<p>Confirm to Change your profile information</p>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									<button type="button" class="btn btn-primary" onclick="document.getElementById('profile-form').submit();">Save changes</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="dropdown-divider"></div>
			<div class="delete-account">
				<form action="delete_account.php" METHOD="GET" id="deleteAccount">
					<p style="color: red;">Delete account</p>
					<div class="dropdown-divider"></div>
					<p>Once you delete your account, your account will lost <span style="color: red;">permanently.</span></p>
					 <input type="hidden" id="email" name="email" value="<?=$customerEmail?>">
					<button type="button" class="btn btn-danger btn-md" data-toggle="modal" data-target="#deleteAccountModal">Delete Account</button>
					<div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog" aria-labelledby="deleteAccountLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="deleteAccountLabel">Are you sure to delete your account?</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<p>Once you delete your account, your account will lost <span class="text-danger">permanently</span>.</p>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									<button type="submit" class="btn btn-danger" onclick="document.getElementById('profile-form').submit();">Save changes</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			</seciton>

			<?php 
				mysqli_close($conn);
			?>
		</body>
	</html>