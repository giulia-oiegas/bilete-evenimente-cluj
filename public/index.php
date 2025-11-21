<?php
// /public/index.php
require_once '../classes/product_repository.php';
require_once '../classes/authService.php';
session_start();

$productRepo = new productRepository();
$auth = new authService();

// Preluare parametri de filtrare
$search_term = $_GET['q'] ?? null;
$category_id = (isset($_GET['category']) && is_numeric($_GET['category'])) ? (int)$_GET['category'] : null;

// Folosim metoda pentru filtrare
$events = $productRepo->getFilteredProducts($category_id, $search_term);

// Preluarea categoriilor pentru meniul de filtrare
$categories = $productRepo->getAllCategories();

//$isLoggedIn = $auth->isUserLoggedIn();
//$userRole = $_SESSION['user_role'] ?? 'guest';

include 'header.php';
?>
<main class="py-4">
   <!-- <div class="container mt-4"> -->
    <div class="bg-light p-5 rounded mb-4">
        <h1 class="display-6 text-center mb-3">Caută evenimente în Cluj-Napoca</h1>
        <form method="get" class="d-flex justify-content-center">
            <input
                    type="text"
                    name="q"
                    class="form-control w-50 me-2"
                    placeholder="exemplu: teatru, opera, concert..."
                    value="<?= htmlspecialchars($search_term ?? '')?>"
            >
            <?php if($category_id !== null): ?>
                <input type="hidden" name="category" value="<?= (int)$category_id ?>">
            <?php endif; ?>
            <button type="submit" class="btn btn-primary">Caută</button>
        </form>
    </div>

    <ul class="nav nav-pills justify-content-center mb-4">
        <li class="nav-item">
            <a class="nav-link <?= $category_id === null ? 'active' : '' ?>" href="index.php">
                Toate evenimentele:
            </a>
        </li>

        <?php foreach ($categories as $cat): ?>
            <li class="nav-item">
                <a class="nav-link <?= ($category_id === (int)$cat['id_categories']) ? 'active' : '' ?>"
                   href="index.php?category=<?= (int)$cat['id_categories']?>">
                    <?= htmlspecialchars($cat['name']) ?>
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
</main>

<?php
include 'footer.php';
?>
