<html>
	<head>
		<title>You account has been logout</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
		<link rel="icon" href="./favicon.ico" type="image/x-icon">

		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
		<!-- 		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V" crossorigin="anonymous"> -->
		<script type="text/javascript"> (function() { var css = document.createElement('link'); css.href = 'https://use.fontawesome.com/releases/v5.1.0/css/all.css'; css.rel = 'stylesheet'; css.type = 'text/css'; document.getElementsByTagName('head')[0].appendChild(css); })(); </script>
		<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">


		<style>
			.delete-info{
				text-align: center;
				max-width: 50%;
				margin: 250px auto;
			}

			@media screen and (max-width: 900px){
				.delete-info{
					max-width: 80%;
				}
			}
		</style>
	</head>
	<body>

		<?php 
			session_start();
			include('conn.php');
			if(isset($_SESSION['email'])){
				$customerEmail = $_SESSION['email'];
			}
			if(isset($_COOKIE['email'])){
				$customerEmail = $_COOKIE['email'];
				setcookie("email", "", time() - 7889231);
			}
			if(isset($_COOKIE['tenantID'])){
				setcookie("tenantID", "", time() - 7889231);
			}

			if($customerEmail==""){
				header('location: index.php');
			}

			session_destroy();

			echo '<div class="card delete-info">
					<div class="card-header">
						<h1 class="card-title">You account has been logouted</h1>
					</div>
					<div class="card-body">
						<p>You will redirect to main page in few seconds</p>
						 <script type="text/javascript">
		                    window.setTimeout(function() {
		                        location.href = "index.php";
		                    }, 5000);
		        		</script>
		        		 <p>Click here if you are not redirected automatically in 5 seconds<br />
		            		<a href="index.php">Click here</a>.
		        		</p>
					</div>
				</div>';

			mysqli_close($conn);

		?>

		
	</body>
</html>