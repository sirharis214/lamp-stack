<?php
require('php/client.php')

?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LampStack.io</title>
    <link rel="icon" type="image/x-icon" href="files/logos/favicon.ico">	
     <link rel="stylesheet" href="files/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body class="text-center">

<div class="container overflow-hidden">
	<img class="mb-4" src="files/logos/logo.svg" alt="" width="150" height="125">
	<div class="row form-signin w-100 mt-2 m-auto justify-content-md-center">
  		<div class="col" align="center">
  			<div class="card p-3" style="width: 35rem;">
  				<form>
    					<h1 class="h3 mb-3 fw-normal">Please sign in</h1>

					<div class="form-floating py-2">
						<input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
						<label for="floatingInput">Email address</label>
					</div>
					<div class="form-floating py-2">
						<input type="password" class="form-control" id="floatingPassword" placeholder="Password">
						<label for="floatingPassword">Password</label>
					</div>

					<div class="checkbox mb-3">
						<label>
							<input type="checkbox" value="remember-me"> Remember me
						</label>
					</div>
					<button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
					<p class="mt-5 mb-3 text-body-secondary">&copy; 2023 - </p>
				</form>
  			</div><!--.card-->
  		</div><!--.col-->
	</div><!--.row-->
</div><!--.container-->

<script src="https://kit.fontawesome.com/ba44f6fe56.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<?php 

echo '<pre>'; var_dump($data); echo '</pre>';
?>
</body>
</html>



