<html>
	<?php include('import-head.php'); ?>
	<title>Home</title>
<body>
<main class="d-flex flex-nowrap">
	<div class="d-flex flex-row bd-highlight">
		<div class="ph-2">
			<!-- Expanded Nav -->
			<div class="offcanvas offcanvas-start bg-dark" tabindex="-1" id="expanded-nav" aria-labelledby="expanded-nav">
			<div class="offcanvas-body">
			  <div class="d-flex flex-column flex-shrink-0 text-white bg-dark">
			    <a href="#" class="d-flex align-items-center justify-content-between text-white text-decoration-none">
			      <span class="fs-4">Sidebar</span>
			      <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"></button>
			    </a>
			    <hr>
			    <ul class="nav nav-pills nav-flush flex-column mb-auto">
			      <li class="nav-item">
				<a href="#" class="nav-link active" aria-current="page">
				  <i class="me-2 fa-solid fa-house"></i>
				  Home
				</a>
			      </li>
			      <li>
				<a href="#" class="nav-link text-white">
				  <i class="me-2 fa-solid fa-gauge"></i>
				  Dashboard
				</a>
			      </li>
			      <li>
				<a href="#" class="nav-link text-white">
				  <i class="me-2 fa-solid fa-table-list"></i>
				  Orders
				</a>
			      </li>
			      <li>
				<a href="#" class="nav-link text-white">
				  <i class="me-2 fa-solid fa-boxes-stacked"></i>
				  Products
				</a>
			      </li>
			      <li>
				<a href="#" class="nav-link text-white">
				  <i class="me-2 fa-solid fa-users-line"></i>
				  Customers
				</a>
			      </li>
			    </ul>
			    <hr>
			    <div class="dropdown mt-auto">
			      <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
				<img src="../logos/favicon-32x32.png" alt="" width="32" height="32" class="rounded-circle me-2">
				<strong>Settings</strong>
			      </a>
			      <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
				<li><a class="dropdown-item" href="#">New project...</a></li>
				<li><a class="dropdown-item" href="#">Settings</a></li>
				<li><a class="dropdown-item" href="#">Profile</a></li>
				<li><hr class="dropdown-divider"></li>
				<li><a class="dropdown-item" href="#">Sign out</a></li>
			      </ul>
			    </div>
			    </div> <!-- Expanded Nav -->
			    </div> <!-- .offcanvas-body -->
			  </div> <!-- .offcanvas -->
			  
			  
                           <div class="d-flex flex-column flex-shrink-0 bg-dark" style="width: 4.5rem; height:100vh;">
                           
                           <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#expanded-nav" aria-controls="expanded-nav">
				  <i class="fa-solid fa-arrow-right" style="color: #ffffff;"></i>
			    </button>
			    
				    <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">
				      <li class="nav-item">
					<a href="#" class="nav-link py-3 border-bottom rounded-0 text-white" aria-current="page" title="Home" data-bs-toggle="tooltip" data-bs-placement="right">
					  <i class="me-2 fa-solid fa-house"></i>
					</a>
				      </li>
				      <li>
					<a href="#" class="nav-link py-3 border-bottom rounded-0" title="Dashboard" data-bs-toggle="tooltip" data-bs-placement="right">
					  <i class="me-2 fa-solid fa-gauge"></i>
					</a>
				      </li>
				      <li>
					<a href="#" class="nav-link py-3 border-bottom rounded-0" title="Orders" data-bs-toggle="tooltip" data-bs-placement="right">
					  <i class="me-2 fa-solid fa-table-list"></i>
					</a>
				      </li>
				      <li>
					<a href="#" class="nav-link py-3 border-bottom rounded-0" title="Products" data-bs-toggle="tooltip" data-bs-placement="right">
					  <i class="me-2 fa-solid fa-boxes-stacked"></i>
					</a>
				      </li>
				      <li>
					<a href="#" class="nav-link py-3 border-bottom rounded-0" title="Customers" data-bs-toggle="tooltip" data-bs-placement="right">
					  <i class="me-2 fa-solid fa-users-line"></i>
					</a>
				      </li>
				    </ul>
				    <div class="dropdown dropup">
				      <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
					<i class="fa-solid fa-gear" style="color: #ffffff;"></i>
				      </a>
				      <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser2">
					<li><a class="dropdown-item" href="#">New project...</a></li>
					<li><a class="dropdown-item" href="#">Settings</a></li>
					<li><a class="dropdown-item" href="#">Profile</a></li>
					<li><hr class="dropdown-divider"></li>
					<li><a class="dropdown-item" href="#">Sign out</a></li>
				      </ul>
				    </div>
  			    </div> <!-- .d-flex flex-column flex-shrink-0 bg-dark -->
			  
		</div> <!-- ph-2 -->
		<div class="ph-2 flex-grow-1">
			<div class="container background-fill">
			  <h1> heading goes here </h1>
			</div <!-- .container .background-fill -->
		</div> <!-- .ph-2 flex-grow-1 -->
	</div> <!-- .d-flex flex-row --> 
</main>
<?php include('import-scripts.php'); ?>
</body>
</html>
