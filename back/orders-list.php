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
	</head>
	<body>
		<?php
			include("conn.php");
			session_start();
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
			if(isset($_GET['action'])){
				if($_GET['action']=='delete'){
					$rs = mysqli_query($conn, "SELECT * FROM orderitem WHERE orderID={$_GET['orderID']}");
					while($rc = mysqli_fetch_assoc($rs)){
						$ordersResult = mysqli_query($conn, "SELECT * FROM orders WHERE orderID={$_GET['orderID']}");
						$orderData = mysqli_fetch_assoc($ordersResult);
						if($orderData['status']!=3){
							$goodsNumber = $rc['goodsNumber'];
							$quantity = $rc['quantity'];
							mysqli_query($conn, "UPDATE goods SET remainingStock=remainingStock+$quantity WHERE goodsNumber=$goodsNumber") or die(mysqli_error($conn));
						}
	

					}
					mysqli_query($conn, "DELETE FROM orderitem WHERE orderID={$_GET['orderID']}")  or die(mysqli_error($conn));
					mysqli_query($conn, "DELETE FROM orders WHERE orderID={$_GET['orderID']}")  or die(mysqli_error($conn));
				}
			}

		?>
		<div class="navbar-section">
			<nav class="navbar navbar-expand-lg navbar-dark">
				<a class="navbar-brand" href="orders-list.php">Hong Kong Cube Shop</a>
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
							<a class="nav-link" href="logout.php">logout</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#"><?=$tenantID?></a>
						</li>
					</ul>
				</div>
			</nav>
		</div>
		</seciton>
		<section id="order-list">
			<div class="title">
				<h1>Accepted Order</h1>
				<div class="dropdown-divider"></div>
			</div>
			<div class="table-responsive-lg">
				<table class="table">
					<thead class="thead-dark">
						<tr>
							<th scope="col">Order ID</th>
							<th scope="col">Store Name</th>
							<th scope="col">Order Date</th>
							<th scope="col">Status</th>
							<th scope="col">Order total</th>
							<th scope="col">Total Benefit</th>
							<th scope="col"></th>
						</tr>
					</thead>
					<tbody>
						<?php
							$total = 0;
							$rs = mysqli_query($conn, "SELECT * FROM consignmentstore WHERE tenantID='$tenantID'")
											or die(mysqli_error($conn));
							$rc = mysqli_fetch_assoc($rs);
							$storeID = $rc['consignmentStoreID'];
							$sql = "SELECT * FROM orders WHERE consignmentStoreID=$storeID ORDER BY orderDateTime DESC";
							$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
								while($rc = mysqli_fetch_assoc($rs)){
									$result = mysqli_query($conn, "SELECT * FROM consignmentstore WHERE consignmentStoreID=$storeID") or die(mysqli_error($conn));
									$data = mysqli_fetch_assoc($result);
									$shop_store_result = mysqli_query($conn, "SELECT * FROM consignmentstore_shop WHERE consignmentStoreID=$storeID") or die(mysqli_error($conn));
									$shop_store_result_data = mysqli_fetch_assoc($shop_store_result);
									$shopAddress = mysqli_query($conn, "SELECT * FROM shop WHERE shopID={$shop_store_result_data['shopID']}") or die(mysqli_error($conn));
									$shopAddress_data = mysqli_fetch_assoc($shopAddress);
									$total += $rc['totalPrice'];
						?>
						<tr>
							<th scope="row"><a href="orders-details.php?orderID=<?=$rc['orderID']?>&shopID=<?=$shop_store_result_data['shopID']?>"><?=$rc['orderID']?></a></th>
							<td><?=$data['ConsignmentStoreName']?></td>
							<td><?=$rc['orderDateTime']?></td>
							<?php
								if($rc['status']==1){
									echo '<td>Delivery</td>';
								}else if($rc['status']==2){
									echo '<td>Awaiting</td>';
								}else if($rc['status']==3){
									echo '<td>Completed</td>';
								}
							?>
							<td>$<?=$rc['totalPrice']?></td>
							<td>+$<?=$rc['totalPrice']?></td>
							<td><button type="button" class="btn-lg btn-danger"onclick="location.href='orders-list.php?action=delete&orderID=<?=$rc['orderID']?>'">Delete Order</button></td>
						</tr><?php }?>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td class="text-danger">$<?=$total?></td>
						</tr>
						
					</tbody>
				</table>
				<p class="message">Orders are ordered by date in <span class="text-danger">descending </span>order</p>
			</div>
		</section>
		<section id="footer">
			<p>© Copyright 2020 Hong Kong Cube Shop</p>
		</section>
	</body>
</html>