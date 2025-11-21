
<?php
// /public/index.php
require_once '../classes/product_repository.php';
require_once '../classes/authService.php';
session_start();

$productRepo = new productRepository();
$auth = new authService();

// Preluare parametri de filtrare
$search_term = $_GET['search'] ?? null;
$category_id = (isset($_GET['cat_id']) && is_numeric($_GET['cat_id'])) ? (int)$_GET['cat_id'] : null;

// Folosim metoda pentru filtrare
$events = $productRepo->getFilteredProducts($category_id, $search_term);

// Preluarea categoriilor pentru meniul de filtrare
$categories = $productRepo->getAllCategories();

$isLoggedIn = $auth->isUserLoggedIn();
$userRole = $_SESSION['user_role'] ?? 'guest';
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bilete Evenimente Cluj - Acasa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">LOGO</a>
        <div class="collapse navbar-collapse">
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

<div class="container mt-4">
    <h1 class="mb-4">Cauta spectacole in Cluj-Napoca</h1>

    <form class="row g-3 mb-4" method="GET" action="index.php">
        <div class="col-md-8">
            <label for="search_input" class="visually-hidden">Câmp de căutare</label>
            <input type="text" class="form-control" name="search" id="search_input" placeholder="Cauta artist / eveniment / locatie" value="<?php echo htmlspecialchars($search_term ?? ''); ?>">
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary w-100">Cauta</button>
        </div>
    </form>

    <ul class="nav nav-tabs mb-4">
        <li class="nav-item"><a class="nav-link <?php echo $category_id === null ? 'active' : ''; ?>" href="index.php">Toate</a></li>
        <?php foreach ($categories as $cat): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo $category_id === $cat['id_categories'] ? 'active' : ''; ?>"
                   href="index.php?cat_id=<?php echo $cat['id_categories']; ?>&search=<?php echo htmlspecialchars($search_term ?? ''); ?>">
                    <?php echo htmlspecialchars($cat['name']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="row">
        <div class="col-lg-3">
            <div class="card p-3 mb-4">
                <h5>Filtre</h5>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php if (!empty($events)): ?>
                    <?php foreach ($events as $event): ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm">
                                <img src="<?php echo htmlspecialchars($event['image'] ?? '/assets/placeholder.jpg'); ?>" class="card-img-top" alt="Imagine eveniment">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($event['name']); ?></h5>
                                    <p class="card-text mb-1">
                                        *Locație:* <?php echo htmlspecialchars($event['venue']); ?><br>
                                        *Data:* <?php echo date('d M Y, H:i', strtotime($event['event_date'])); ?>
                                    </p>
                                    <p class="fs-4 fw-bold text-success">
                                        Preț: <?php echo number_format($event['price'], 2); ?> RON
                                    </p>
                                    <a href="event.php?id=<?php echo $event['id_products']; ?>" class="btn btn-primary w-100">Vezi detalii</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12"><p class="alert alert-info">Nu au fost găsite evenimente.</p></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<footer class="bg-light text-center text-lg-start mt-5"><div class="text-center p-3">...</div></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script></html>
