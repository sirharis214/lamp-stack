<!DOCTYPE html>
<html lang="en">
<?php session_start(); ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        /* Custom CSS to make the sidebar full height */
        html, body {
            height: 100%;
        }

        .sidebar {
            height: 100%;
        }
        /* Custom CSS to adjust the position of the alert */
        .custom-alert {
            position: fixed;
            top: 60px; /* Adjust the top value to avoid overlapping with the navbar */
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            width: calc(100% - 30px); /* Set the width to match the main content section minus padding */
            max-width: 800px; /* Set a maximum width for the alert */
        }
    </style>
</head>

<body class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar bg-dark text-light p-3 d-flex flex-column">
        <ul class="nav flex-column flex-grow-1">
            <li class="nav-item">
                <a class="nav-link active" href="#">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Readme</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">Account</a>
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
                <a class="navbar-brand" href="#">Logo</a>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="../../php/logout.php">Sign Out</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container mt-4">
            <!-- Example PHP Dismissible Alert -->
            <?php if (isset($_SESSION['messages'])): foreach($_SESSION['messages'] as $msg){?>
                <div class="alert alert-warning alert-dismissible fade show custom-alert" role="alert">
                    <?php echo $msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php } endif; #unset($_SESSION['messages']);?>
        </div>
        <div class="container mt-4">
            <!-- Centered Heading -->
            <div class="text-center">
                <h1>Home Page</h1>
            </div>

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

    <!-- Bootstrap 5 JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

