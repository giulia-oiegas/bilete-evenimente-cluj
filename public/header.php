<?php
//pornim sesiunea o singura data
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
?>

<!doctype html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cluj Events</title>
    <!-- bootstrap cdn -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Cluj Events</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Evenimente</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Calendar</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">Despre noi</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                </ul>

                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item me-2">
                        <a class="btn btn-outline-light" href="cart.php">Cos</a>
                    </li>

                    <?php if(!empty($_SESSION['id'])) : ?>
                        <li class="nav-item dropdown">
                            <a class="btn btn-primary dopdown-toggle"
                               href="#"
                               id="accountDropdown"
                               role="button"
                               data-bs-toggle="dropdown"
                               aria-expanded="false"
                            ><?= htmlspecialchars($_SESSION['username'] ?? 'Contul meu') ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                                <li><a class="dropdown-item" href="my_account.php">Contul meu</a></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!--user nelogat -->
                        <li class="nav-item me-2">
                            <a class="btn btn-outline-light" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-success" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>



