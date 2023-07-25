<!DOCTYPE html>
<html lang="en">
<?php 
session_start(); 
include('import-head.inc.php');
include('session-check.inc.php');
?>
<body class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar bg-dark text-light p-3 d-flex flex-column">
        <ul class="nav flex-column flex-grow-1">
            <li class="nav-item">
                <a class="nav-link active" href="#">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Menu</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">Options</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="../../php/logout.php">Sign Out</a>
                    <a class="dropdown-item" href="#">Contact Us</a>
                </div>
            </li>
        </ul>
        
        <div class="mt-auto">
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">Account</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Sign Out</a>
                    <a class="dropdown-item" href="#">Contact Us</a>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Main content -->
    <div class="content flex-grow-1">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="./home.php">LampStack</a>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="../../php/logout.php">Sign Out</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container" style="position: fixed;">
            <!-- Dismissible Alert -->
            <?php if (isset($_SESSION['messages'])): foreach($_SESSION['messages'] as $msg){?>
                <div class="alert alert-warning alert-dismissible fade show custom-alert mt-1" role="alert">
                    <?php echo $msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php } endif; unset($_SESSION['messages']);?>
        </div>
        <!-- client side error messages -->
		<div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-message" style="display: none;">
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
        <div class="container mt-4">
            <!-- Centered Heading -->
            <div class="text-center">
                <h1>New Home Page</h1>
            </div>
			<?php
			if (isset($_SESSION['role']) AND $_SESSION['role']=='admin'):
				require('../../php/data.inc.php');				
				$userData = new Data();
				$data = $userData->get_all_users_data();
			?>
			
			<div class="table-responsive" style="max-height: 300px;">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created On</th>
                            <th>Updated On</th>
                            <th>Action</th>
                            <!-- Add more columns as needed -->
                        </tr>
                    </thead>
                    <tbody>
                    	<?php include('admin-user-table.inc.php');?>
                    </tbody>
                </table>
            </div>
			<?php endif;?>
			
            <!-- Scrollable Fixed Height and Width Table -->
            <div class="table-responsive" style="max-height: 300px;">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Column 1</th>
                            <th>Column 2</th>
                            <!-- Add more columns as needed -->
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Table data goes here -->
                        <tr>
                            <td>1</td>
                            <td>Data 1</td>
                            <td>Data 2</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Data 1</td>
                            <td>Data 2</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Data 1</td>
                            <td>Data 2</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Data 1</td>
                            <td>Data 2</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Data 1</td>
                            <td>Data 2</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Data 1</td>
                            <td>Data 2</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Data 1</td>
                            <td>Data 2</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Data 1</td>
                            <td>Data 2</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Data 1</td>
                            <td>Data 2</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Data 1</td>
                            <td>Data 2</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Data 1</td>
                            <td>Data 2</td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>

            <!-- Custom Content Section -->
            <div class="mt-4">
                <!-- Your custom content goes here -->
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis ut volutpat orci. Nulla facilisi.
                    Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Etiam
                    eget
                    est nunc. Integer feugiat lorem eget dolor interdum, vel efficitur sapien feugiat. Nulla facilisi.
                    Sed
                    nec convallis nisi. Donec volutpat mi orci, non dictum neque euismod vel. Sed maximus, orci sit
                    amet
                    tincidunt euismod, dui elit egestas turpis, at convallis erat lacus vel orci.</p>
            </div>
        </div>
    </div> <!-- .content flex-grow-1 -->
    
	<script src="../js/validate_update_user.js"></script>
	<?php include('import-scripts.inc.php'); ?>
</body>

</html>

