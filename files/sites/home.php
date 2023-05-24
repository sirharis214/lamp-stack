<html>
<head>
	<?php include('import-head.php'); ?>
</head>
<body>
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
	<a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="#">LampStack</a>
	<button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<a class="navbar-brand px-3 w-100" href="#">Center Heading</a>
	<div class="navbar-nav">
		<div class="nav-item text-nowrap">
			<a class="nav-link px-3" href="#">Sign out</a>
		</div> <!-- .nav-item text-nowrap -->
	</div> <!-- .navbar-nav -->
</header>
<div class="container-fluid">
	<div class="row">
		<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
			<div class="d-flex flex-column position-sticky pt-3 sidebar-sticky">
				<ul class="nav flex-column flex-grow-1">
					<li class="nav-item">
						<a href="#" class="nav-link text-primary" aria-current="page">
							<i class="me-2 fa-solid fa-house"></i>
							Home
						</a>
					</li>
					<li class="nav-item">
						<a href="#" class="nav-link text-primary">
							<i class="me-2 fa-solid fa-gauge"></i>
							Dashboard
						</a>
					</li>
					<li class="nav-item">
						<a href="#" class="nav-link text-primary">
							<i class="me-2 fa-solid fa-table-list"></i>
							Orders
						</a>
					</li>
					<li class="nav-item">
						<a href="#" class="nav-link text-primary">
							<i class="me-2 fa-solid fa-boxes-stacked"></i>
							Products
						</a>
					</li>
				</ul>
				<div class="dropdown">
                    <a href="#" class="d-flex align-items-center link-light text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
                      <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
                      <strong>mdo</strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser2">
                      <li><a class="dropdown-item" href="#">New project...</a></li>
                      <li><a class="dropdown-item" href="#">Settings</a></li>
                      <li><a class="dropdown-item" href="#">Profile</a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li><a class="dropdown-item" href="#">Sign out</a></li>
                    </ul>
                </div> <!-- .dropdown -->
			</div> <!-- .position-sticky pt-3 sidebar-sticky -->
		</nav>
		<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
				<h1 class="h2">Dashboard</h1>
			</div>
			<div class="my-4 w-100" style="display: block; background-color:yellowgreen; height: 384px; width: 911px;">
				<h3>First Section</h3>
			</div>
			<div class="my-4 w-100" style="display: block; background-color:lavender; height: 384px; width: 911px;">
				<h3>Second Section</h3>
			</div>
			<div class="my-4 w-100" style="display: block; background-color:teal; height: 384px; width: 911px;">
				<h3>Third Section</h3>
			</div>
		</main>
	</div> <!-- .row -->
</div> <!-- .container-fluid -->

<?php include('import-scripts.php'); ?>
</body>
</html>
