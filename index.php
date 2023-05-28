<?php require('php/client.php') ?>
<html>
<head>
    <?php include('files/sites/import-head.php'); ?>
</head>
<body class="text-center">
<div class="container overflow-hidden">
	<img class="mb-4" src="files/logos/logo.svg" alt="" width="150" height="125">
	<div class="row form-signin w-100 mt-2 m-auto justify-content-md-center">
  		<div class="col" align="center">
  			<div class="card p-3" style="width: 35rem;">
  				<form action="php/client.php" method="POST">
    					<h1 class="h3 mb-3 fw-normal">Please sign in</h1>
					<div class="form-floating py-2">
						<input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
						<label for="email">Email address</label>
					</div>
					<div class="form-floating py-2">
						<input type="password" class="form-control" id="password" name="password" placeholder="Password">
						<label for="password">Password</label>
					</div>
					<div class="checkbox mb-3">
						<label>
							<input type="checkbox" value="remember-me"> Remember me
						</label>
					</div>
					<button class="w-100 btn btn-lg btn-primary" type="submit" name="login">Sign in</button>
					<p class="mt-5 mb-3 text-body-secondary">&copy; 2023 - present</p>
				</form>
  			</div><!--.card-->
  		</div><!--.col-->
	</div><!--.row-->
</div><!--.container-->

<?php include('files/sites/import-scripts.php'); ?>
<?php 
session_start();
if(!empty($_SESSION['messages']) ){
	$messages = $_SESSION['messages'];
	foreach($messages as $msg){
		echo "<pre>".$msg."</pre";
	}
	unset($_SESSION['messages']);
}
?>
</body>
</html>
