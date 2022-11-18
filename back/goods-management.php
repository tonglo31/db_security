<?php session_start(); ?>
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
		<link rel="stylesheet" type="text/css" href="./css/details.css">
		<link rel="stylesheet" type="text/css" href="./css/orders-list.css">
		<script type="text/javascript" src="./js/edit_product.js"></script>
		<link rel="stylesheet" type="text/css" href="./css/good_managements.css">
		<script type="text/javascript">
			$('.nav-tabs > li:first-child > a')[0].click();
		</script>
		<style>
			@media only screen and (max-width: 1200px){
		.add-form{
			width: 70%;
		}
		</style>
	</head>
	<body>
		<?php 
			include("conn.php");
			$tenantID = "";
			$success = false;
			$error = false;
			$errorMessage = "";
			if(isset($_SESSION['tenantID'])){
			$tenantID = $_SESSION['tenantID'];
			}
			if(isset($_COOKIE['tenantID'])){
				$tenantID = $_COOKIE['tenantID'];
			}
			if($tenantID==""){
				header('location: tenant-signin.php');
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
					$rs = mysqli_query($conn, "SELECT * FROM consignmentstore WHERE tenantID='{$_SESSION['tenantID']}'") or die(mysqli_error($conn));
					$rc = mysqli_fetch_assoc($rs);
					$storeID = $rc['consignmentStoreID'];
					$lastGoodsID = 0;
					$rs = mysqli_query($conn, "SELECT * FROM goods");
					while($rc = mysqli_fetch_assoc($rs)){
						$lastGoodsID = $rc['goodsNumber'];
					}
					$lastGoodsID++;
					mysqli_query($conn, "INSERT INTO goods VALUES( $lastGoodsID, $storeID, '{$_POST['gdName']}', {$_POST['price']}, {$_POST['qty']}, {$_POST['status']})") or die(mysqli_error($conn));
					$success = true;
				}
			}

		?>
		<div class="navbar-section">
			<nav class="navbar navbar-expand-lg navbar-dark">
				<a class="navbar-brand" href="#">Hong Kong Cube Shop</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#banner-navbar" aria-controls="banner-navbar" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="banner-navbar">
					<ul class="navbar-nav ml-auto banner-navbar">
						<li class="nav-item">
							<a class="nav-link" href="./orders-list.php">Orders Report</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="./goods-management.php">Goods Management</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="logout.php">Logout</a>
						</li>
					</ul>
				</div>
			</nav>
		</div>
		</seciton>
		<section id="goods-manage">
			<div class="tab-products">
				<div class="title">
					<h1>Good Management</h1>
				</div>
				<ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="add-tab" data-toggle="tab" href="#add" role="tab" aria-controls="add" aria-selected="true">Add Products</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="edit-tab" data-toggle="tab" href="#edit" role="tab" aria-controls="edit" aria-selected="false">Edit Products</a>
					</li>
				</ul>
				<div class="tab-content" id="myTabContent">
					<div class="tab-pane fade show active" id="add" role="tabpanel" aria-labelledby="add-tab">
						<form action="<?=$_SERVER['PHP_SELF']?>" method="POST" class="add-form">
							<?php 

								if($success){

									echo '<div id="successMessage" class="bg-success" style="padding:20px; 0; width:100%; text-align: center; color: white; font-size: 1.45rem;" >
											<p>Insert Goods info success!</p>
										</div>';
									echo '<script>setTimeout(function() {
											document.getElementById("successMessage").style.display = "none";
									}, 3000)</script>';
									}
									
								
								if($error){
									echo '<p class="text-danger">' . $errorMessage . '</p>';
								}

							?>
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
							<input type="submit" class="btn btn-success banner-btn" value="Add Product">
						</form>
					</div>
					<div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
						<div class="row">

							<?php 
								$rs = mysqli_query($conn, "SELECT * FROM consignmentstore WHERE tenantID='{$_SESSION['tenantID']}'") or die(mysqli_error($conn));
								$rc = mysqli_fetch_assoc($rs);
								$storeID = $rc['consignmentStoreID'];
								$rs = mysqli_query($conn, "SELECT * FROM goods WHERE consignmentStoreID=$storeID") or die(mysqli_error($conn));
								while($rc = mysqli_fetch_assoc($rs)){
							?>
							<div class="col-lg-4">
								<div class="card edit-goods">
									<div class="card-body">
										<h2>Goods Name: <?=$rc['goodsName']?></h2>
										<p>Goods Number: <?=$rc['goodsNumber']?></p>
										<p>Stock Quantity: <?=$rc['remainingStock']?></p>
										<p>Stock Price: $<?=$rc['stockPrice']?></p>
										<Button type="button" class="btn btn-success btn-lg" onclick="window.location.href='./editProducts.php?goodsNumber=<?=$rc['goodsNumber']?>';" style="width: 50%;">Edit</Button>
									</div>
								</div>
							</div>
						<?php }?>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</section>
	<section id="footer">
		<p>© Copyright 2020 Hong Kong Cube Shop</p>
	</section>
</body>
</html>