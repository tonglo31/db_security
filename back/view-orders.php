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
		<link rel="stylesheet" type="text/css" href="./css/view-orders.css">
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
				if(empty($_SESSION[$cartName])){
					$cart_items_count =0;
				}else{
				}
			}
			if($customerEmail==""){
				header('location: signin.php');
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
							<a class="nav-link" href="./place-order.php"><i class="fas fa-shopping-cart"></i>  Cart <span class="text-warning"><?= $_SESSION['cartCount']?></span></a>
						</li>
					</ul>
				</div>
			</nav>
		</div>
		</seciton>
		<section id="order-status">
			<div class="table-responsive-lg">
				<table class="table">
					<thead class="thead-dark">
						<tr>
							<th scope="col">Order ID</th>
							<th scope="col">Store Name</th>
							<th scope="col">Ship to Address</th>
							<th scope="col">Order Date</th>
							<th scope="col">Total Price</th>
							<th scope="col">Status</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$sql = "SELECT * FROM orders WHERE customerEmail='$customerEmail'";
							$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
							while($rc = mysqli_fetch_assoc($rs)){
								$result = mysqli_query($conn, "SELECT * FROM consignmentstore WHERE consignmentStoreID={$rc['consignmentStoreID']}") or die(mysqli_error($conn));
								$data = mysqli_fetch_assoc($result);
								$shop_store_result = mysqli_query($conn, "SELECT * FROM consignmentstore_shop WHERE consignmentStoreID={$data['consignmentStoreID']}") or die(mysqli_error($conn));
								$shop_store_result_data = mysqli_fetch_assoc($shop_store_result);
								$shopAddress = mysqli_query($conn, "SELECT * FROM shop WHERE shopID={$shop_store_result_data['shopID']}") or die(mysqli_error($conn));
								$shopAddress_data = mysqli_fetch_assoc($shopAddress);
						?>
							<tr>
								<th scope="row"><a href="orders-details.php?orderID=<?=$rc['orderID']?>&shopID=<?=$shop_store_result_data['shopID']?>"><?=$rc['orderID']?></a></th>
								<td><?=$data['ConsignmentStoreName']?></td>
								<td><?=$shopAddress_data['address']?></td>
								<td><?=$rc['orderDateTime']?></td>
								<td>$<?=$rc['totalPrice']?></td>
								<?php 
									if($rc['status']==1){
										echo '<td>Delivery</td>';
									}else if($rc['status']==2){
										echo '<td>Awaiting</td>';
									}else if($rc['status']==3){
										echo '<td>Completed</td>';
									}
								?>
							</tr>
						<?php
							}
						?>
					</tbody>
				</table>
			</div>
			
		</section>
		<section id="footer">
			<p>© Copyright 2020 Hong Kong Cube Shop</p>
		</section>
	</body>
</html>