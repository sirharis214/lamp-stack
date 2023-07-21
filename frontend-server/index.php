<?php 
require('php/client.php');
session_start();
?>
<html>
<head>
    <?php include('files/sites/import-head.inc.php'); ?>
</head>
<body class="text-center">
<div class="container overflow-hidden">
	<div class="container" style="position: fixed;">
		<?php 
			if(!empty($_SESSION['messages']) ){
				foreach($_SESSION['messages'] as $msg){
					echo <<<EOL
						<div class='alert alert-primary alert-dismissible fade show' role='alert'>
							$msg
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
						EOL;
				}
				unset($_SESSION['messages']);
			}
		?>
		<!-- client side error messages -->
		<div class="alert alert-danger alert-dismissible fade show custom-alert mt-1" role="alert" id="error-message" style="display: none;">
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	</div>
	<img class="mb-4" src="files/logos/logo.svg" alt="" width="150" height="125">
	<div class="row form-signin w-100 mt-2 m-auto justify-content-md-center">
  		<div class="col" align="center">
  			<div class="card p-3" style="width: 35rem;">
  				<form action="php/client.php" method="POST">
    					<h1 class="h3 mb-3 fw-normal">Please sign in</h1>
    				<div class="input-group py-2">
						<div class="form-floating">
							<input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" autofocus="autofocus">
							<label for="email">Email address</label>
						</div>
						<span class="input-group-text" id="email-check" style="display:none;"><i class="fa-solid fa-check" style="color: #ffbe6f;"></i></span>
						<span class="input-group-text" id="email-x" style="display:none;"><i class="fa-solid fa-x" style="color: #c64600;"></i></span>
					</div>
					<div class="input-group py-2">
						<div class="form-floating">
							<input type="password" class="form-control" id="password" name="password" placeholder="Password">
							<label for="password">Password</label>
						</div>
						<span class="input-group-text" id="password-check" style="display:none;"><i class="fa-solid fa-check" style="color: #ffbe6f;"></i></span>
						<span class="input-group-text" id="password-x" style="display:none;"><i class="fa-solid fa-x" style="color: #c64600;"></i></span>
					</div>
					<div class="checkbox mb-3">
						<label>
							<input type="checkbox" value="remember-me"> Remember me
						</label>
					</div>
					<input type="hidden" name="action" value="login">
					<button class="w-100 btn btn-lg btn-primary" type="submit" name="login">Sign in</button>
					<p class="mt-3"><a href="files/sites/register.php" class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">Signup Here</a></p>

					<p class="mt-5 mb-3 text-body-secondary">&copy; 2023 - present</p>
				</form>
  			</div><!--.card-->
  		</div><!--.col-->
	</div><!--.row-->
</div><!--.container-->
<script src="files/js/validate_login.js"></script>
<?php include('files/sites/import-scripts.inc.php'); ?>
</body>
</html>
