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
	</head>
	<body>
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

			$cartName = 'cart' . $customerEmail;
			if(isset($_SESSION[$cartName])){
					
			}
		?>
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
																<a class="dropdown-item logout" class="" href="logout.php">Logout</a>
										</div>';
								}
							?>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="./place-order.php"><i class="fas fa-shopping-cart"></i>  Cart <span class="text-warning"><?=$_SESSION['cartCount']?></span></a>
						</li>
					</ul>
				</div>
			</nav>
		</div>
		<?php
			$currentGoodsName = "";
			$goodsNumber = 0;
			$rs = mysqli_query($conn, "SELECT * FROM goods WHERE goodsNumber={$_GET['goodsId']}")
								or die(mysqli_error($conn));
			$stockPrice = 0;
					
							while($rc = mysqli_fetch_assoc($rs)) {
								$result = mysqli_query($conn, "SELECT * FROM consignmentstore WHERE consignmentStoreID={$rc['consignmentStoreID']}");
								$data = mysqli_fetch_assoc($result);
								$shopResult = mysqli_query($conn, "SELECT * FROM consignmentstore_shop WHERE consignmentStoreID={$data['consignmentStoreID']}");
								$shopData = mysqli_fetch_assoc($shopResult);
								$currentGoodsName = $rc['goodsName'];
								$goodsNumber = $rc['goodsNumber'];
		?>
		
		<section id="products-details">
			<div class="container">
				<div class="row">
					<div class="col-lg-8 col-md-6 col-sm-6">
						<div class="products-info">
							<h1 class="info-title"><?=$rc['goodsName']?></h1>
						</div>
						<div class="dropdown-divider"></div>
						<h3 class="product-price">Price: $<?=$rc['stockPrice']?></h3>
						<h3 class="product-price">Sold By: <?=$data['ConsignmentStoreName']?></h3>
					</div>
					<div class="col-lg-4 col-md-6 col-sm-6">
						<div class="card">
							<div class="card-body">
								<form action="products.php" method="GET" class="payment-form" >
									<h2 class="spacing">Shop ID: <?=$shopData['shopID']?></h2>
									<h2 class="spacing">Store ID: <?=$data['consignmentStoreID']?></h2>
									<h4 class="spacing">Price: <span class="price">$<?=$rc['stockPrice']?></span></h4>
									<h4 class="spacing">Status: <span class="text-success"><?php if($rc['status']==1) echo "Available"; else echo "Unavailable"; ?></span> </h4>
									<h4 class="spacing">Remain Stock: <?=$rc['remainingStock']?>  LEFT</h4>
									<h4 class="spacing">Qty:
									<select class="qty" name="qty" id="qty">
										<?php
											for($i=1;$i<=$rc['remainingStock'];$i++){
												echo '<option value="' . $i . '">' . $i .'</option>';
											}
											$stockPrice = $rc['stockPrice'];
										}
										?>
									</select></h4>
									<input type="hidden" name="storeID" value="<?=$data['consignmentStoreID']?>"></hidden>
									<input type="hidden" name="shopID" value="<?=$shopData['shopID']?>"></hidden>
									<input type="hidden" name="goodsNumber" value="<?=$goodsNumber?>"></hidden>
									<input type="hidden" name="goodsPrice" value="<?=$stockPrice?>"></hidden>
									<button class="btn btn-lg btn-outline-warning buy-button" type="submit" name="addToCart"><i class="fas fa-shopping-cart"></i> Add To Cart</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section id="similar-products">
			<div class="container">
				<h2 class="text-info">Some products related to this item</h2>
				<div class="dropdown-divider"></div>
				<div class="row">
					<?php
						$similarCount = 0;
						$rs = mysqli_query($conn, "SELECT * FROM goods")
							or die(mysqli_error($conn));
						$goodsNameList = array();
						while($rc = mysqli_fetch_assoc($rs)){
							$goodsNameList[] = $rc['goodsName'];
						}
						foreach($goodsNameList as $gName){
							$lowerGName = strtolower($gName);
							if(strpos($lowerGName, strtolower($currentGoodsName))!== false){
								$rs = mysqli_query($conn, "SELECT * FROM goods WHERE goodsName='{$gName}'")
									or die(mysqli_error($conn));
								$similarCount++;
							}
							while($rc = mysqli_fetch_assoc($rs)){
								$result = mysqli_query($conn, "SELECT * FROM consignmentstore WHERE consignmentStoreID={$rc['consignmentStoreID']}");
								$data = mysqli_fetch_assoc($result);
					?>
					<?php if($similarCount >=3){ ?>
					<div class="col-lg-4">
						<div class="card products-col">
							<h1><?=$rc['goodsName']?></h1>
							<p>Price: $<?=$rc['stockPrice']?></p>
							<p>Seller: <?=$data['ConsignmentStoreName']?></p>
							<form action="<?=$_SERVER['PHP_SELF']?>" method="GET">
								<input type="hidden" name="goodsId" value="<?=$rc['goodsNumber']?>">
								<button class="btn btn-outline-dark btn-lg" type="submit">View Product</button>
							</form>
						</div>
					</div>
					<?php }else if($similarCount==2){

					?>

					<div class="col-lg-6">
						<div class="card products-col">
							<h1><?=$rc['goodsName']?></h1>
							<p>Price: $<?=$rc['stockPrice']?></p>
							<p>Seller: <?=$data['ConsignmentStoreName']?></p>
							<form action="<?=$_SERVER['PHP_SELF']?>" method="GET">
								<input type="hidden" name="goodsId" value="<?=$rc['goodsNumber']?>">
								<button class="btn btn-outline-dark btn-lg" type="submit">View Product</button>
							</form>
						</div>
					</div>


				<?php } else if ($similarCount==1){


				?>
				<div class="col-lg-12">
					<div class="card products-col">
						<h1><?=$rc['goodsName']?></h1>
						<p>Price: $<?=$rc['stockPrice']?></p>
						<p>Seller: <?=$data['ConsignmentStoreName']?></p>
						<form action="<?=$_SERVER['PHP_SELF']?>" method="GET">
							<input type="hidden" name="goodsId" value="<?=$rc['goodsNumber']?>">
							<button class="btn btn-outline-dark btn-lg" type="submit">View Product</button>
						</form>

					</div>
				</div>



				<?php }else if($similarCount==0){

				?>

				<div class="col-lg-12">
					<div class="card products-col">
						<p>similar products not found</p>
					</div>
				</div>
				<?php
				}}}?>
				</div>
			</div>
			
		</section>
		<section id="footer">
			<p>© Copyright 2020 Hong Kong Cube Shop</p>
		</section>
	</body>
</html>