<?php

//afișează toate detaliile unui eveniment specific și formularul 'Adaugă în coș'


require_once __DIR__ . '/../config/config.php';

session_start();

$db = new db_controller();
$productRepo = new ProductRepository();
$eventData = null;
$pageTitle = 'Eveniment Negăsit';
$isDateValid = false;
$formattedDate = 'Dată Necunoscută';
$formattedTime = '--:--';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_product = (int)$_GET['id'];
    $eventData = $productRepo->getEventById($id_product);

    if ($eventData) {
        $pageTitle = htmlspecialchars($eventData['name']);
        if (!empty($eventData['event_date'])) {
            try {
                // Încercăm să creăm obiectul DateTime. Dacă șirul e greșit, prindem excepția.
                $dateTime = new DateTime($eventData['event_date']);
                $formattedDate = $dateTime->format('l, d F Y');
                $formattedTime = $dateTime->format('H:i');
                $isDateValid = true; // Data este validă
            } catch (Exception $e) {
                error_log("Eroare la parsarea datei evenimentului {$id_product}: " . $e->getMessage());


            }
        }
    }
}


require_once 'header.php';

//daca evenimentul nu exista, se va afisa eroare
if (!$eventData): ?>

    <div class="alert alert-danger text-center shadow-sm" role="alert">
        <h4 class="alert-heading">Eveniment Negăsit</h4>
        <p>Ne pare rău, evenimentul cu ID-ul specificat nu există sau nu mai este disponibil.</p>
        <a href="index.php" class="btn btn-danger mt-3">Înapoi la Evenimente</a>
    </div>

<?php else: ?>

    <main class="py-4">
        <div class="container mt-4 event-page">
            <div class="row g-4">
                <!-- col stanga: imagine + detalii -->
                <div class="col-lg-8">
                    <article class="card event-main-card">
                        <div class="event-image-wrapper">
                            <img
                                    src="<?php echo htmlspecialchars($eventData['image'] ?? 'https://placehold.co/900x500/CCCCCC/333333?text=Imagine+Eveniment'); ?>"
                                    alt="<?php echo htmlspecialchars($eventData['name']); ?>"
                                    class="event-image"
                            >
                        </div>

                        <div class="card-body p-4 p-lg-5">
                        <span class="badge event-badge mb-3">
                            <?php echo htmlspecialchars($eventData['category_name'] ?? 'Necunoscută'); ?>
                        </span>

                            <h1 class="display-5 fw-bold mb-3">
                                <?php echo htmlspecialchars($eventData['name']); ?>
                            </h1>

                            <div class="d-flex flex-wrap gap-3 mb-4 text-muted event-meta-top">
                                <span>
                                    <i class="bi bi-calendar-event me-2"></i>
                                    <strong>Dată:</strong> <?php echo $formattedDate; ?>
                                </span>
                                <span>
                                    <i class="bi bi-clock me-2"></i>
                                    <strong>Oră:</strong> <?php echo $formattedTime; ?>
                                </span>
                                <span>
                                    <i class="bi bi-geo-alt me-2"></i>
                                    <strong>Locație:</strong> <?php echo htmlspecialchars($eventData['venue']); ?>
                                </span>
                            </div>

                            <hr>

                            <h2 class="h5 mt-3 mb-2 event-description-title">Descriere</h2>
                            <p class="lead mb-0">
                                <?php echo nl2br(htmlspecialchars($eventData['description'] ?? 'Nu este disponibilă o descriere detaliată.')); ?>
                            </p>
                        </div>
                    </article>
                </div>

                <!-- col dreapta: card cumpara bilete -->
                <div class="col-lg-4">
                    <aside class="card event-sidebar-card sticky-top">
                        <div class="card-body">
                            <h3 class="h5 mb-4 text-center">Cumpără Bilete</h3>

                            <div class="text-center mb-4">
                                <div class="event-price-highlight mb-1">
                                    <?php echo number_format($eventData['price'], 2); ?> RON
                                </div>
                                <p class="text-muted small mb-1">Preț per bilet</p>
                                <p class="available-text mb-0">
                                    <?php echo (int)$eventData['available_tickets']; ?> bilete disponibile
                                </p>
                            </div>

                            <!-- formular adaugare în coș -->
                            <form action="cart.php" method="POST" id="add-to-cart-form">
                                <input type="hidden" name="id_product" value="<?php echo $eventData['id_products']; ?>">
                                <input type="hidden" name="action" value="add">

                                <div class="mb-3">
                                    <label for="ticket_type" class="form-label fw-bold">Tip Bilet / Zonă</label>
                                    <select class="form-select rounded-2" id="ticket_type" name="ticket_type">
                                        <option value="Standard" selected>Standard (Preț unic)</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label for="quantity" class="form-label fw-bold">
                                        Cantitate (max <?php echo (int)$eventData['available_tickets']; ?>)
                                    </label>
                                    <input
                                            type="number"
                                            class="form-control rounded-2"
                                            id="quantity"
                                            name="quantity"
                                            value="1"
                                            min="1"
                                            max="<?php echo (int)$eventData['available_tickets']; ?>"
                                            required
                                    >
                                </div>

                                <?php if ($eventData['available_tickets'] > 0): ?>
                                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-2 shadow-sm">
                                        <i class="bi bi-cart-plus me-2"></i> Adaugă în Coș
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-secondary btn-lg w-100 rounded-2" disabled>
                                        Bilete epuizate
                                    </button>
                                <?php endif; ?>
                            </form>
                        </div>
                    </aside>
                </div>
            </div><!-- /.row -->

        </div><!-- /.container -->
    </main>

<?php endif; ?>


<?php
include 'footer.php';
?>