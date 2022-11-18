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

		<script>
			var phoneNum = document.getElementById('phoneNum');
			var firstName = document.getElementById('firstname');
			var lastName = document.getElementById('lastname');
			var email = document.getElementById('email');
			var password = document.getElementById('password');
			phoneNum.oninvalid = function(event) {
   				 event.target.setCustomValidity("Please input a correct phone number format with 8digits.");
			}
			email.oninvalid = function(event) {
   				 event.target.setCustomValidity("Please input a correct email format");
			}
			   firstName.oninvalid = function(event) {
   				 event.target.setCustomValidity("Your firstname must be within 255 characters and should not have any digit");
			}
			lastName.oninvalid = function(event) {
   				 event.target.setCustomValidity("Your lastname must be within 255 characters and should not have any digit");
			}
			password.oninvalid = function(event) {
   				 event.target.setCustomValidity("Your password must be at least 8 characters and within 50 characters");
			}
			

		</script>
	</head>
	<?php
		include('conn.php');

		if(isset($_POST['submit'])){
			$sql = "SELECT * FROM customer;";
			$rs = mysqli_query($conn, $sql) 
          		or die(mysqli_error($conn));
          	$pass = true;

          	$email = $_POST['email'];
          	if(mysqli_num_rows($rs) <= 0){
          		$sql = "INSERT INTO customer (customerEmail, firstName, lastName, password, phoneNumber) VALUES
          		 ('{$_POST['email']}', '{$_POST['firstname']}', '{$_POST['lastname']}', '{$_POST['password']}', '{$_POST['phoneNum']}');";
          		 mysqli_query($conn, $sql);
          	}else{
          		while($rc = mysqli_fetch_assoc($rs)) {
				     if($email == $rc['customerEmail']){
				     	$pass = false;
				     	break;
				     }
				}
				if($pass){
					$sql = "INSERT INTO customer (customerEmail, firstName, lastName, password, phoneNumber) VALUES
	          		 ('{$_POST['email']}', '{$_POST['firstname']}', '{$_POST['lastname']}', '{$_POST['password']}', '{$_POST['phoneNum']}');";
	          		mysqli_query($conn, $sql);
	          		session_start();
	          		$customerEmail = $_POST['email'];
	          		$cartName = 'cart' . $customerEmail;
	          		$_SESSION[$cartName] = array();
	          		$_SESSION['cartCount'] = 0;
	          		$_SESSION[email] = $_POST['email'];
	          		header('Location: products.php');
				}
			}
			$cartName = 'cart' .$email;
			if(isset($_SESSION[$cartName])){
				print_r($_SESSION[$cartName]);
			}
		}

		if(isset($_COOKIE['email']) || isset($_SESSION['email'])){
			header('Location: products.php');
		}

	?>
	


	<body >
		<section id="banner">
			<nav class="navbar navbar-expand-lg navbar-dark">
				<a class="navbar-brand" href="./index.php">Hong Kong Cube Shop</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#banner-navbar" aria-controls="banner-navbar" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="banner-navbar">
					<ul class="navbar-nav ml-auto banner-navbar">
						<li class="nav-item">
							<a class="nav-link" href="./products.php">Buy Product</a>
						</li>
						<li class="nav-item">
							<a class="nav-link sign-in" href="./signin.php">Sign in</a>
						</li>
						<li class="nav-item">
							<a class="nav-link sign-up" href="./signup.php">Sign up</a>
						</li>
					</ul>
				</div>
			</nav>
			<div class="container-fluid banner-section">
				<div class="row">
					<div class="col-lg-6 col-md-12 col-sm-12 banner-desc">
						<h1 class="title">Built for Online Shoppers</h1>
						<p class="banner-text">HKCS is a website for the online shoppers to view and order our bunch of goods at home.
						You can also become <br>a talent to sell your goods to the alongside 7 million Hong Kong people.</p>
					</div>
					<div class="col-lg-6 col-md-12 col-sm-12 banner-form-container">
						<form action="<?=$_SERVER['PHP_SELF']?>" method="POST" class="banner-form" id="registerForm">
							<div class="form-group"> 
								<label for="firstname">First Name</label>
								<input type="text" class="form-control" id="firstname" name="firstname" pattern="[a-zA-z]{2,255}" required>	
							</div>
							<div class="form-group">
								<label for="lastname">Last Name</label>
								<input type="text" class="form-control" id="lastname" name="lastname" pattern="[a-zA-z]{2,255}" required>
							</div>
							<div class="form-group">
								<label for="email">Email address</label>
								<input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email" required>
								<?php 
									if(isset($_POST['submit'])){
										if(!$pass){
											echo '<span class="text-danger">This email address has been token</span> <i class="fas fa-exclamation"></i>';
								?>
									
								
								<?php echo '<small id="emailHelp" class="form-text text-muted" style="display: none;" >We\'ll never share your email with anyone else.</small>' ;}}else{
									 echo '<small id="emailHelp" class="form-text text-muted">We\'ll never share your email with anyone else.</small>';
								} ?>

							</div>
							<div class="form-group">
								<label for="password">Password</label>
								<input type="password" class="form-control" id="password" name="password" pattern=".{8,50}" required>
								<small id="passwordHelp" class="form-text text-muted">Make sure it's not more than 50 characters.</small>
							</div>
							<div class="form-group">
								<label for="phoneNum">Phone Number</label>
								<input type="text" class="form-control" id="phoneNum" name="phoneNum" pattern="^[1-8]{1}[0-9]{7}$" onchange="checkIsValidPhone();" required >
							</div>
							<input type="submit" class="btn btn-success banner-btn" id="submit" name='submit' value="Sign up for HKCS">
						</form>
					</div>
				</div>
			</div>
		</section>
		<section id="features">
			<div class="row">
				<div class="col-lg-4 features-col">
					<i class="fas fa-laptop features-icon"></i>
					<h2 class="features-title">Convenient</h2>
					<p class="features-text">You don't need to go outside to see our goods.</p>
				</div>
				<div class="col-lg-4 features-col">
					<i class="fas fa-user-secret features-icon"></i>
					<h2 class="features-title">Safety</h2>
					<p class="features-text">Anyone will not know your order and individual information.  </p>
				</div>
				<div class="col-lg-4 features-col">
					<i class="fas fa-sign-out-alt features-icon"></i>
					<h2 class="features-title">Cancel our service anytime</h2>
					<p class="features-text">You can delete your account anytime and we will not keep the data in our database.</p>
				</div>
			</div>
		</section>
		<section id="delivery-stage">
			<div id="delivery-viewer" class="carousel slide" data-ride="carousel">
				<div class="carousel-inner">
					<div class="carousel-item active">
						<h2 class="delivery-header">Stage to get your products.</h2>
						<em>Stage 1: Order your products </em>
					</div>
					<div class="carousel-item">
						<h2  class="delivery-header" >Stage 2: Check your order details.</h2><br>
						<button type="button" class="btn btn-primary btn-lg">Check Order Details</button>
					</div>
					<div class="carousel-item">
						<h2  class="delivery-header">Stage 3: Waiting for delivery.</h2>
						<i class="fas fa-truck delivery-truck" ></i>
					</div>
					<a class="carousel-control-prev" href="#delivery-viewer" role="button" data-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="carousel-control-next" href="#delivery-viewer" role="button" data-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>
			</div>
		</section>
		<section id="viewGoods">
			<h2 class="goods-title">Buy goods</h2>
			<p class="goods-text" >View bunch of our goods and order the products now!</p>
			<button class="btn btn-lg viewgoods-btn btn-outline-dark" onclick="window.location.href='./products.php';" type="button">View Our Products</button>
		</section>
		<section id="footer">
			<p>© Copyright 2020 Hong Kong Cube Shop</p>
		</section>
<?php 
	mysqli_close($conn);
?>
	</body>
</html>