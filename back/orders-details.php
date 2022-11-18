<!DOCTYPE html>
<html>
	<head>
		<title>Hong Kong Cube Ship Online Shopping - Order Details</title>
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
		<link rel="stylesheet" type="text/css" href="./css/place-order.css">
		<script type="text/javascript" src="./js/place_order.js"></script>
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
			$tenantID = "";
			if(isset($_SESSION['tenantID'])){
				$tenantID = $_SESSION['tenantID'];
			}
			if(isset($_COOKIE['tenantID'])){
				$tenantID = $_COOKIE['tenantID'];
			}
			$pass = false;
			if(strlen($customerEmail) >0 || strlen($tenantID) >0){
				$pass = true;
			}
			if(!$pass){
				header('location: signin.php');
			}

			
		?>
		<div class="container">
			<section id="order-details">
				<div class="title-container">
					<h1 class="title" style="text-align: center">Hong Kong Cube Shop- Orders Details</h1>
					<div class="divider-container"><hr></div>
				</div>
				<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
					<div class="row">
						<div class="col-lg-12">
							<table class="table ">
								<thead>
									<tr>
										<th scope="col">Product Name</th>
										<th scope="col">Seller</th>
										<th scope="col">Store ID</th>
										<th scope="col">Shop ID</th>
										<th scope="col">Qty</th>
										<th scope="col">Single Price</th>
										<th scope="col">Pick up address</th>
										<th scope="col">Total Price</th>
									</tr>
								</thead>
								<tbody>
									<?php
										if(isset($_GET['orderID'])){
											$rs = mysqli_query($conn, "SELECT * FROM orderitem WHERE orderID={$_GET['orderID']}") or die(mysqli_error($conn));
											while($rc = mysqli_fetch_assoc($rs)){
												$ordersResult =mysqli_query($conn, "SELECT * FROM orders WHERE orderID={$rc['orderID']}") or die(mysqli_error($conn));
												$ordersData = mysqli_fetch_assoc($ordersResult);
												$consignmentstoreResult = mysqli_query($conn, "SELECT * FROM consignmentstore WHERE consignmentStoreID={$ordersData['consignmentStoreID']}") or die(mysqli_error($conn));
												$consignmentstoreData = mysqli_fetch_assoc($consignmentstoreResult);
												$shopResult = mysqli_query($conn, "SELECT * FROM shop WHERE shopID={$ordersData['shopID']}") or die(mysqli_error($conn));
												$shopData = mysqli_fetch_assoc($shopResult);
												$goodsResult = mysqli_query($conn, "SELECT * FROM goods WHERE goodsNumber={$rc['goodsNumber']}");
												$goodsResultData =mysqli_fetch_assoc($goodsResult);
									?>
									<tr>
										<td><?=$goodsResultData['goodsName']?></td>
										<td><?=$consignmentstoreData['ConsignmentStoreName']?></td>
										<td><?=$consignmentstoreData['consignmentStoreID']?></td>
										<td><?=$shopData['shopID']?></td>
										<td><?=$rc['quantity']?></td>
										<td>$<?=$goodsResultData['stockPrice']?></td>
										<td><?=$shopData['address']?></td>
										<td>$<?=$goodsResultData['stockPrice'] * $rc['quantity']?></td>
									</tr>
									<?php }}?>
								</tbody>
							</table>
						</div>
					</div>
				</form>
			</body>