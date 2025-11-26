<?php
// /public/admin/admin_header.php

//if(session_status() === PHP_SESSION_NONE){
 //   session_start();
//}

$isLoggedIn = !empty($_SESSION['user_id']);
$userRole = $_SESSION['user_role'] ?? 'guest';


$public_path = '../../';

?>

<!doctype html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cluj Events - ADMIN</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= $public_path ?>assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="<?= $public_path ?>assets/favicon/favicon-cluj-events-v2.ico">
</head>

<body class="d-flex flex-column min-vh-100">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
            <span class="navbar-brand d-flex align-items-center">
                <img src="<?= $public_path ?>assets/images/logoClujEvents.png" alt="Cluj Events" class="logo-navbar me-2">
            </span>


            <ul class="navbar-nav ms-auto">
                <?php if ($isLoggedIn): ?>
                    <li class="nav-item">
                        <a class="nav-link active text-warning" href="admin_home.php">ADMIN</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="logout_admin.php">Logout</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>