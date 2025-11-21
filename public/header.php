<?php
//require_once '../classes/product_repository.php';
//require_once '../classes/authService.php';
//pornim sesiunea o singura data
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
//$productRepo = new productRepository();
//$auth = new authService();

// Preluare parametri de filtrare
//$search_term = $_GET['search'] ?? null;
//$category_id = (isset($_GET['cat_id']) && is_numeric($_GET['cat_id'])) ? (int)$_GET['cat_id'] : null;

// Folosim metoda pentru filtrare
//$events = $productRepo->getFilteredProducts($category_id, $search_term);

// Preluarea categoriilor pentru meniul de filtrare
//$categories = $productRepo->getAllCategories();

$isLoggedIn = !empty($_SESSION['user_id']);
$userRole = $_SESSION['user_role'] ?? 'guest';

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

                <ul class="navbar-nav ms-auto">
                    <?php if ($isLoggedIn): ?>
                        <?php if ($userRole === 'admin'): ?>
                            <li class="nav-item"><a class="nav-link text-warning" href="admin/events_list.php">ADMIN</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link" href="my_account.php">Contul meu</a></li>
                        <li class="nav-item"><a class="nav-link" href="cart.php">Coș</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login/Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>



