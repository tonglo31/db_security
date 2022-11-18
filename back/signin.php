<!DOCTYPE html>
<html>
	<head>
		<title>Hong Kong Cube Shop - Customer Signin</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
		<link rel="icon" href="./favicon.ico" type="image/x-icon">
		<!-- 		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V" crossorigin="anonymous"> -->
		<script type="text/javascript"> (function() { var css = document.createElement('link'); css.href = 'https://use.fontawesome.com/releases/v5.1.0/css/all.css'; css.rel = 'stylesheet'; css.type = 'text/css'; document.getElementsByTagName('head')[0].appendChild(css); })(); </script>
		<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
		<style>
			body{
				background: #f7f7ee;
				padding: 6% 15%;
			}
			h2{
				text-align: center;
				margin-bottom: 2rem;
			}

			.signin-form{
				width: 30%;
				margin: 0 auto;
				background: #fff;
				padding: 3%;
				border-radius: 6px;
				border: 1px solid #d8dee2;
			}
		  p{
		  	margin-top: 3rem;
		  	text-align: center;
		  }
		  input[type="submit"]{
		  	margin: 5% 3%;
		  }

		  .footer .tenant-signup{
				display: block;
				text-align: center;
			}
		  @media only screen and (max-width: 1650px){
		  	.signin-form{
		  		width: 45%;
		  	}
		  }
		  @media only screen and (max-width: 900px){
		  	.signin-form{
		  		width: 65%;
		  	}
		  }
		</style>
	</head>
	<body>
		<?php
			session_start();
			if(isset($_COOKIE['email'])){
				header('Location: products.php');
			}
			if(isset($_SESSION['email'])){
				header('Location: products.php');
			}
			if(isset($_POST['password'])){
				include('conn.php');
				$error = false;
				$result = mysqli_query($conn, "SELECT * FROM customer WHERE customerEmail='{$_POST['email']}'")
					or die(mysqli_error($conn));
				$row = mysqli_fetch_assoc($result);
				if(mysqli_num_rows($result)<=0){
					$error = true;
				}else{
					if($row['password']==$_POST['password']){
						if(isset($_POST['rememberMe'])){
							setcookie("email", $_POST['email'], time() + 7889231);
						}else{
							$_SESSION[email] = $_POST['email'];
						}
						$customerEmail = $_POST['email'];
	          			$cartName = 'cart' . $customerEmail;
	          			$_SESSION['cartCount'] = 0;
	          			$_SESSION[$cartName] = array();
						header('location: products.php');
					}else{
						$error = true;
					}
				}
			}

			if(isset($_POST['password'])){
				if($error){
					echo '<p style="text-align:center;" class="text-danger">Your password or email is not match</p>';
				}
			}
		?>
		<section id="signin">
			<h2>Sign in to HKCS</h1>
			<form action="<?=$_SERVER['PHP_SELF']?>" method="POST" class="signin-form">
			<div class="form-group">
				<label for="email">Email address</label>
				<input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email" required="required">
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<input type="password" class="form-control" id="password" name="password" required="required">
			</div>
			<input type="checkbox" id="checkbox" name="rememberMe"> Remember me
			<input type="submit" class="btn btn-success banner-btn btn-block" value="Sign in">
		</form>
		<div class="footer">
			<p>Do not have an account? <a href="./signup.php">Create an account</a></p>
			<div class="dropdown-divider"></div>
			<span class="tenant-signup">Or you are a tenant? Click <a href="./tenant-signin.php">here </a> to login</span>
		</div>
	</section>
</body>
</html>