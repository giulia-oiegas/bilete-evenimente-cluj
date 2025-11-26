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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= $public_path ?>assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="<?= $public_path ?>assets/favicon/favicon-cluj-events-v2.ico">

    <style>
        @media (max-width: 991px) {
            .navbar-collapse {
                background-color: #212529;
                padding: 1rem;
                margin-top: 0.5rem;
                border-radius: 0.25rem;
            }

            .navbar-nav .nav-link {
                color: #ffffff !important;
                padding: 0.5rem 1rem;
            }

            .navbar-nav .nav-link:hover {
                background-color: #343a40;
                border-radius: 0.25rem;
            }

            .navbar-nav .nav-link.text-warning {
                color: #ffc107 !important;
            }
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <span class="navbar-brand d-flex align-items-center">
            <img src="<?= $public_path ?>assets/images/logoClujEvents.png" alt="Cluj Events" class="logo-navbar me-2">
        </span>

        <button class="navbar-toggler " type="button"
                data-bs-toggle="collapse"
                data-bs-target="#adminNavbar"
                aria-controls="adminNavbar"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
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