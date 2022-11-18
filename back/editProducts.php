<?php 
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Hong Kong Cube Ship Online Shopping</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
		<link rel="icon" href="./favicon.ico" type="image/x-icon">
		<link rel="stylesheet" href="./css/styles.css">
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
		<!-- 		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V" crossorigin="anonymous"> -->
		<script type="text/javascript"> (function() { var css = document.createElement('link'); css.href = 'https://use.fontawesome.com/releases/v5.1.0/css/all.css'; css.rel = 'stylesheet'; css.type = 'text/css'; document.getElementsByTagName('head')[0].appendChild(css); })(); </script>
		<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="./css/signup.css">
	</head>
	<style>
		
		.edit-form{
			width: 45%;
			margin: 0 auto;
			color: #fff;
		}
		.button-group{
			margin: 5% auto;
			text-align: center;
		}
		.banner-btn{
			display: inline;
			margin: 0 30px;
		}
		@media only screen and (max-width: 1200px){
		.edit-form{
			width: 90%;
		}
	}
	@media only screen and (max-width: 500px){
		.banner-btn{
			text-align: center;
			margin: 5%;
		}
	}
	</style>
	<body>
		<?php
			include("conn.php");
			session_start();
			$success = false;
			$error = false;
			$errorMessage = "";
			$tenantID = "";
			if(isset($_SESSION['tenantID'])){
				$tenantID = $_SESSION['tenantID'];
			}
			if(isset($_COOKIE['tenantID'])){
				$tenantID = $_COOKIE['tenantID'];
			}
			if($tenantID==""){
				header('location: tenant-signin.php');
			}
			if(isset($_GET['goodsNumber'])){
				$_SESSION['goodsNumber'] = $_GET['goodsNumber'];
			}
			if(isset($_POST['qty'])){
				if(strlen($_POST['gdName'])<=0){
					$error = true;
					$errorMessage .= "Goods name cannot empty <br>";
				}
				if(!is_numeric($_POST['qty']) || strlen($_POST['qty'])<=0 || $_POST['qty'] <=0){
					$error = true;
					$errorMessage .= "Quantity shoud be digit or not less than 0<br>";
				}
				if(!is_numeric($_POST['price']) || strlen($_POST['price'])<=0 || $_POST['price'] <=0){
					$error = true;
					$errorMessage .= "Price should be digit or not less than 0.<br>";
				}
				if(!$error){
					$lastGoodsID = 0;
					mysqli_query($conn, "UPDATE goods SET goodsName='{$_POST['gdName']}', stockPrice={$_POST['price']}, remainingStock={$_POST['qty']} WHERE goodsNumber={$_SESSION['goodsNumber']}") or die(mysqli_error($conn));
					$success = true;
				}
			}
		?>
		<section id="banner">
			<div class="container-fluid banner-section">
				<div class="row">
					<div class="col banner-form-container">
						<?php
							$rs = mysqli_query($conn, "SELECT * FROM goods WHERE goodsNumber={$_SESSION['goodsNumber']}") or die(mysqli_error($conn));
							$rc = mysqli_fetch_assoc($rs);
							$goodsName = $rc['goodsName'];
						?>
						<h1>Edit Products: <span class="text-warning"><?=$goodsName?></span></h1>
						<form action="<?=$_SERVER['PHP_SELF']?>" method="POST" class="edit-form">
							<div class="form-group">
								<?php
									if($success){
										echo '<div id="successMessage" class="bg-success" style="padding:20px; 0; width:100%; text-align: center; color: white; font-size: 1.45rem;" >
													<p>Update Goods info success!</p>
											</div>';
										echo '<script>setTimeout(function() {
												document.getElementById("successMessage").style.display = "none";
										}, 3000)</script>';
										unset($_SESSION['goodsNumber']);
										echo '<script type="text/javascript">
								                    window.setTimeout(function() {
								                        location.href = "goods-management.php";
								                    }, 3000);
								        	</script>';
								}
								if($error){
								echo '<p class="text-danger">' . $errorMessage . '</p>';
								}
								?>
							</div>
							<div class="form-group">
								<label for="gdName">Goods Name</label>
								<input type="text" class="form-control" id="gdName" name="gdName">
							</div>
							<div class="form-group">
								<label for="qty">Stock Quantity</label>
								<input type="text" class="form-control" id="qty" name="qty">
							</div>
							<div class="form-group">
								<label for="price">Stock Price</label>
								<input type="text" class="form-control" id="price" name="price">
							</div>
							<div class="form-group">
								<label for="status">Stock Status</label>
								<select name="status" id="status">
									<option value="1">Available</option>
									<option value="2">Unavailable</option>
								</select>
							</div>
							<div class="button-group">
								<Button type="submit" class="btn btn-success banner-btn" >Edit Product</Button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>
		<section id="footer">
			<p>© Copyright 2020 Hong Kong Cube Shop</p>
		</section>
	</body>
</html>