<?php
// /public/index.php
require_once '../classes/product_repository.php';
require_once '../classes/authService.php';

session_start();

$productRepo = new productRepository();
$auth        = new authService();

// Preluare parametri de filtrare
$search_term = $_GET['q'] ?? null;
$category_id = (isset($_GET['category']) && is_numeric($_GET['category']))
        ? (int)$_GET['category']
        : null;
$sort        = $_GET['sort'] ?? null;

// Folosim metoda pentru filtrare
$events     = $productRepo->getFilteredProducts($category_id, $search_term, $sort);
// Preluarea categoriilor pentru meniul de filtrare
$categories = $productRepo->getAllCategories();

include 'header.php';
?>

<main class="py-4">
    <div class="container mt-4">
        <!-- HERO + search -->
        <section class="hero-section mb-4">
            <h1 class="hero-title">Caută evenimente în Cluj-Napoca</h1>
            <p class="hero-subtitle">
                Teatru, operă, concerte, meciuri și spectacole pentru toate gusturile.
            </p>
            <form method="get" class="hero-search d-flex justify-content-center mt-4">
                <input
                        type="text"
                        name="q"
                        class="form-control hero-search-input me-2"
                        placeholder="exemplu: teatru, opera, concert..."
                        value="<?= htmlspecialchars($search_term ?? '') ?>"
                >
                <?php if ($category_id !== null): ?>
                    <input type="hidden" name="category" value="<?= (int)$category_id ?>">
                <?php endif; ?>
                <button type="submit" class="hero-search-button"><strong>Caută</strong></button>
            </form>
        </section>

        <!-- TAB-URI categorii -->
        <ul class="nav nav-pills justify-content-center mb-4">
            <li class="all-events-li nav-item">
                <a class="all-events-btn btn nav-link <?= $category_id === null ? 'active' : '' ?>" href="index.php">
                    Toate evenimentele:
                </a>
            </li>

            <?php foreach ($categories as $cat): ?>
                <li class="nav-item">
                    <a
                            class="tab-names nav-link <?= ($category_id === (int)$cat['id_categories']) ? 'active' : '' ?>"
                            href="index.php?category=<?= (int)$cat['id_categories'] ?>"
                    >
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- ROW: stânga filtre, dreapta evenimente -->
        <div class="row">
            <!-- Coloană FILTRE -->
            <div class="col-lg-3 mb-4">
                <div class="card filters-card p-3">
                    <h5 class="mb-3">Filtre</h5>

                    <form method="get">
                        <!-- păstrăm căutarea curentă -->
                        <input type="hidden" name="q" value="<?= htmlspecialchars($search_term ?? '') ?>">
                        <!-- păstrăm categoria curentă, dacă e selectată -->
                        <?php if ($category_id !== null): ?>
                            <input type="hidden" name="category" value="<?= (int)$category_id ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="sort" class="form-label">Ordonează după:</label>
                            <select name="sort" id="sort" class="form-select" onchange="this.form.submit()">
                                <option value="" <?= $sort === null || $sort === 'date_asc' ? 'selected' : '' ?>>
                                    Data (crescător)
                                </option>
                                <option value="date_desc" <?= $sort === 'date_desc' ? 'selected' : '' ?>>
                                    Data (descrescător)
                                </option>
                                <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>
                                    Preț (crescător)
                                </option>
                                <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>
                                    Preț (descrescător)
                                </option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Coloană EVENIMENTE -->
            <section class="col-lg-9">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php if (!empty($events)): ?>
                        <?php foreach ($events as $event): ?>
                            <div class="col">
                                <article class="card event-card h-100 shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title event-card-title"><?= htmlspecialchars($event['name']); ?></h5>
                                        <p class="card-text event-meta mb-2">
                                            <span class="d-block">
                                                <strong>Locație</strong>: <?= htmlspecialchars($event['venue']); ?><br>
                                            </span>
                                            <span class="d-block">
                                                <strong>Data</strong>: <?= date('d M Y, H:i', strtotime($event['event_date'])); ?>
                                            </span>
                                        </p>
                                        <p class="event-price fs-5 fw-semibold mb-3">
                                            Preț: <?= number_format($event['price'], 2); ?> RON
                                        </p>
                                        <a
                                                href="event.php?id=<?= $event['id_products']; ?>"
                                                class="btn btn-primary w-100"
                                        >
                                            Vezi detalii
                                        </a>
                                    </div>
                                </article>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <p class="alert alert-info mb-0">Nu au fost găsite evenimente.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div> <!-- row -->
    </div> <!-- container -->
</main>

<?php include 'footer.php'; ?>
