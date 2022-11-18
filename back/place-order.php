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
		<link rel="stylesheet" type="text/css" href="./css/place-order.css">
		<script type="text/javascript" src="./js/place_order.js"></script>
	</head>
	<body>
		<?php
		include("conn.php");
			session_start();
			$success = false;
			$error = false;
			$errorMessage = '<td class="text-danger">You can only place order at the same store</td>';
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
			$cartName = 'cart' . $customerEmail;
			if(isset($_GET['action'])){
				if($_GET['action']=='editQty'){
					$goodsID = $_GET['id'];
					$newQty = $_GET['value'];
					foreach($_SESSION[$cartName] as $index => $arrayIndex){
						if($arrayIndex['goodsNumber']==$goodsID){
							$arrayIndex['quantity'] = $newQty;
							$newArray = $arrayIndex;
							$_SESSION[$cartName][$index] = $newArray;
						}
					}
				}
				if($_GET['action']=='delete'){
					foreach($_SESSION[$cartName] as $index => $arrayIndex){
						if($arrayIndex['goodsNumber']==$_GET['id']){
							unset($_SESSION[$cartName][$index]);
							$_SESSION['cartCount']--;
						}
					}
				}
				header('Location: place-order.php');
			}

			$canbuy = false;
			if(isset($_POST['placeOrder'])){
				if($_SESSION['cartCount'] >0){
					$canbuy = true;
					$shopId = 0;
					$storeId = 0;
					foreach($_SESSION[$cartName] as $index => $arrayIndex){
						$storeId = $arrayIndex['storeID'];
						if($arrayIndex['storeID']!=$arrayIndex['storeID']){
							$canbuy = false;
							$error = true;
						}
					}
					if($canbuy){
						$maxOrderId = 0;
						$rs = mysqli_query($conn, "SELECT * FROM orders");
							while($rc = mysqli_fetch_assoc($rs)){
								$maxOrderId = $rc['orderID'];
							}
						$maxOrderId ++;
						$date = date("Y-m-d H:i:s");
						$sql = "INSERT INTO orders VALUES($maxOrderId, '$customerEmail', $storeId, {$_POST['shopAddress']}, '$date', 1, {$_SESSION['totalPrice']})";
						mysqli_query($conn, $sql) or die(mysqli_error($conn));
						foreach($_SESSION[$cartName] as $index => $arrayIndex){
							$rs = mysqli_query($conn, "SELECT * FROM goods WHERE goodsNumber={$arrayIndex['goodsNumber']}");
							while($rc = mysqli_fetch_assoc($rs)){
								mysqli_query($conn, "INSERT INTO orderitem VALUES($maxOrderId, {$arrayIndex['goodsNumber']},  {$arrayIndex['quantity']},  {$arrayIndex['sellingPirce']})") or die(mysqli_error($conn));
								$remain = $rc['remainingStock'] - $arrayIndex['quantity'];
								if($remain <=0){
									mysqli_query($conn, "UPDATE goods SET remainingStock=$remain , status=2 WHERE goodsNumber={$arrayIndex['goodsNumber']} ") or die(mysqli_error($conn));
								}
								if($remain >0){
									mysqli_query($conn, "UPDATE goods SET remainingStock=$remain , status=1 WHERE goodsNumber={$arrayIndex['goodsNumber']}") or die(mysqli_error($conn));
								}
								
							}
							unset($_SESSION[$cartName][$index]);
							$_SESSION['cartCount'] --;
						}
						unset($_SESSION['totalPrice']);
						$success = true;
					}else{
						$error = true;
					}
				}
			}

		?>
		<section id="order-details">
			<div class="title-container">
				<h1 class="title" style="text-align: center">Hong Kong Cube Shop</h1>
				<div class="divider-container"><hr></div>
			</div>

			<?php
				if($success && !$error){
					echo '<div id="successMessage" class="bg-success" style="padding:20px; 0; width:100%; text-align: center; color: white; font-size: 1.45rem;" >
								<p>Place orders success!</p>
						</div>';
			echo '<script>setTimeout(function() {
					document.getElementById("successMessage").style.display = "none";
			}, 3000)</script>';
			}
			?>
			<?php 
				if(!$canbuy & $error){
					echo $errorMessage;
				}
			?>
			<div class="container">
				<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
					<div class="row">
						<div class="col-lg-8">
							<table class="table ">
								<thead>
									<tr>
										<th scope="col">Product Name</th>
										<th scope="col">Seller</th>
										<th scope="col">Store ID</th>
										<th scope="col">Shop ID</th>
										<th scope="col">Price</th>
										<th scope="col">Qty</th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach($_SESSION[$cartName] as $index => $arrayIndex){
										$rs = mysqli_query($conn, "SELECT * FROM goods WHERE goodsNumber=" . $arrayIndex['goodsNumber']) or die(mysqli_error($conn));
										while($rc = mysqli_fetch_assoc($rs)){
											$storeResult = mysqli_query($conn, "SELECT * FROM consignmentstore WHERE consignmentStoreID={$rc['consignmentStoreID']}") or die(mysqli_error($conn));
											$storeData = mysqli_fetch_assoc($storeResult);
											$storeShopResult = mysqli_query($conn, "SELECT * FROM consignmentstore_shop WHERE consignmentStoreID={$storeData['consignmentStoreID']}") or die(mysqli_error($conn));
											$storeShopData = mysqli_fetch_assoc($storeShopResult);
									?>
									<tr>
										<?php 
											if(count($_SESSION[$cartName])>0)
										?>
										<td scope="row"><?=$rc['goodsName']?></td>
										<td><?=$storeData['ConsignmentStoreName']?></td>
										<td><?=$storeShopData['consignmentStoreID']?></td>
										<td><?=$storeShopData['shopID']?></td>
										<td>$<span class="price" id="price"><?=$rc['stockPrice']?></span></td>
										<td>
											<select class="qty" name="qty" id="qty" onchange="changeText(this);">
												<?php
												$repeatValue = 0;
												for($i=1;$i<=$rc['remainingStock'];$i++){
													if($arrayIndex['quantity']==$i){
														$repeatValue = $i;
														echo '<option id="' . $rc['goodsNumber'] . '" value="' . $i . '" selected>' .$i . '</option>'; 
													}
												?>
													<?php 
														if($i==$repeatValue)
															continue;
													?>
													<option id="<?=$rc['goodsNumber']?>" value="<?=$i?>"><?=$i?></option>
												<?php }?>
											</select>
										</td>
										<td><a href="<?=$_SERVER['PHP_SELF'] . '?action=delete&id=' .$rc['goodsNumber'] ?>">Delete</a></td>
									</tr>
								<?php }}?>

									<?php
										if($_SESSION['cartCount'] <=0){
											echo "<td colspan='5' style='font-size: 1.45rem'>You have not any item in your shopping cart <a href='products.php'>Back to Products page</a></td>";
										}
									?>
								</tbody>
							</table>
						</div>
						<div class="col-lg-4">
							<div class="row order-form">
								
								<div class="col-lg-12 order-box">
									<div class="card">
										<div class="card-header">
											<h1 class="card-title">Total</h1>
										</div>
										<div class="card-body">
											<?php
												$total =0;
												foreach($_SESSION[$cartName] as $index => $arrayIndex){
													$total += ($arrayIndex['quantity'] * $arrayIndex['sellingPirce']);
												}
												$_SESSION['totalPrice'] = $total;
											?>
											<p>Total: $<span class="total"><?=$total?></span></p>
										</div>
									</div>
								</div>
								<div class="col-lg-12 order-box" style="min-width: 50%">
									<div class="card">
										<div class="card-header">
											<h1 class="card-title">Delivery Details</h1>
										</div>
										<div class="card-body">
											<?php
												$rs = mysqli_query($conn, "SELECT * FROM customer WHERE customerEmail='{$customerEmail}'") or die(mysqli_error($conn));
												while($rc = mysqli_fetch_assoc($rs)){
											?>
											<p>Name: <?=$rc['firstName'] . ' ' . $rc['lastName']?></p>
											<p>Phone Number: <?=$rc['phoneNumber']?></p><?php }?>
											<p>Delivery Address: <select class="shopAddress" name="shopAddress" style="width: 300px" >
												<?php
													$rs = mysqli_query($conn, "SELECT * FROM shop") or die(mysqli_error($conn));
													while($rc = mysqli_fetch_assoc($rs)){
												?>
												
												<option value="<?=$rc['shopID']?>"><?=$rc['address']?></option><?php }?>
											</select></p>
										</div>
									</div>
								</div>
								<div class="col-lg-12 order-box">
									<?php
										if($_SESSION['cartCount'] >0){
											$text = '<button type="submit" name="placeOrder" class="btn btn-lg btn-outline-dark"><i class="fas fa-money-check"></i> Place Order</button>';
											echo $text;
										}
									?>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</section>
	</body>
</html>