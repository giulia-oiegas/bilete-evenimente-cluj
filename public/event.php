<?php

//afișează toate detaliile unui eveniment specific și formularul 'Adaugă în coș'


require_once __DIR__ . '/../config/config.php';

require_once '../classes/db_controller.php';
require_once '../classes/product_repository.php';
require_once '../classes/cart_service.php';
require_once '../classes/AuthService.php';

session_start();

$db = new db_controller();
$productRepo = new ProductRepository();
$eventData = null;
$pageTitle = 'Eveniment Negăsit';
$isDateValid = false;
$formattedDate = 'Dată Necunoscută';
$formattedTime = '--:--';

$auth = new AuthService();
$cartService = new CartService();


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
            } catch (\Exception $e) {
                error_log("Eroare la parsarea datei evenimentului {$id_product}: " . $e->getMessage());
                //setare o valoare implicită

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
        <a href="home.php" class="btn btn-danger mt-3">Înapoi la Evenimente</a>
    </div>

<?php else:

    if (!$isDateValid) {
        $formattedDate = 'Dată Necunoscută';
        $formattedTime = '--:--';
    }
    ?>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <!-- Imagine Mare (6.3) -->
                <img src="<?php echo htmlspecialchars($eventData['image'] ?? 'https://placehold.co/900x500/CCCCCC/333333?text=Imagine+Eveniment'); ?>"
                     class="card-img-top object-cover rounded-top-4"
                     alt="<?php echo htmlspecialchars($eventData['name']); ?>"
                     style="height: 400px;">

                <div class="card-body p-5">
                    <span class="badge bg-primary mb-3"><?php echo htmlspecialchars($eventData['category_name'] ?? 'Necunoscută'); ?></span>
                    <h1 class="display-4 fw-bolder mb-3"><?php echo htmlspecialchars($eventData['name']); ?></h1>

                    <div class="d-flex flex-wrap mb-4 text-muted">
                        <span class="me-4"><i class="bi bi-calendar-event me-2"></i> **Dată:** <?php echo $formattedDate; ?></span>
                        <span class="me-4"><i class="bi bi-clock me-2"></i> **Oră:** <?php echo $formattedTime; ?></span>
                        <span class="me-4"><i class="bi bi-geo-alt me-2"></i> **Locație:** <?php echo htmlspecialchars($eventData['venue']); ?></span>
                    </div>

                    <hr>


                    <h2 class="h4 mt-4 mb-3 text-success">Descriere</h2>
                    <p class="lead text-dark"><?php echo nl2br(htmlspecialchars($eventData['description'] ?? 'Nu este disponibilă o descriere detaliată.')); ?></p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- formular de adaugare cos-->
            <div class="card bg-light shadow-lg border-0 rounded-4 sticky-top" style="top: 20px;">
                <div class="card-body p-4">
                    <h3 class="h4 mb-4 text-center text-primary">Cumpără Bilete</h3>

                    <div class="text-center mb-4">
                        <span class="fs-1 fw-bolder text-danger"><?php echo number_format($eventData['price'], 2); ?> RON</span>
                        <p class="text-muted small">Preț per bilet</p>
                        <p class="text-success fw-bold"><i class="bi bi-ticket-perforated me-1"></i> <?php echo $eventData['available_tickets']; ?> Bilete disponibile</p>
                    </div>

                    <!--Formular adaugare cos-->
                    <form action="cart.php" method="POST" id="add-to-cart-form">
                        <input type="hidden" name="id_product" value="<?php echo $eventData['id_products']; ?>">
                        <input type="hidden" name="action" value="add">

                        <!-- Selectare tip bilet/zona-->
                        <div class="mb-3">
                            <label for="ticket_type" class="form-label fw-bold">Tip Bilet/Zona</label>
                            <select class="form-select rounded-2" id="ticket_type" name="ticket_type">
                                <option value="Standard" selected>Standard (Preț Unic)</option>
                                <!-- Aici ar veni optiuni din tabela ZONES, daca ar exista -->
                            </select>
                        </div>

                        <!--Cantitate-->
                        <div class="mb-4">
                            <label for="quantity" class="form-label fw-bold">Cantitate (max <?php echo $eventData['available_tickets']; ?>)</label>
                            <input type="number"
                                   class="form-control rounded-2"
                                   id="quantity"
                                   name="quantity"
                                   value="1"
                                   min="1"
                                   max="<?php echo $eventData['available_tickets']; ?>"
                                   required>
                        </div>

                        <?php if ($eventData['available_tickets'] > 0): ?>
                            <!-- Buton 'Adaugă în coș' (6.4) -->
                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-2 shadow-sm">
                                <i class="bi bi-cart-plus me-2"></i> Adaugă în Coș
                            </button>
                        <?php else: ?>
                            <button type="button" class="btn btn-secondary btn-lg w-100 rounded-2" disabled>
                                Bilete Epuizate
                            </button>
                        <?php endif; ?>

                    </form>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>

<?php
echo '</main>';
echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>';
echo '</body></html>';
?>