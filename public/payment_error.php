<?php
//pagina afisata in cazul in care plata Stripe esueaza sau este anulata de utilizator.

require_once __DIR__ . '/../config/config.php';
require_once 'header.php';

$pageTitle = 'Plată Eșuată';
$orderId = $_GET['order_id'] ?? null;

?>

<div class="container mt-5 text-center">
    <div class="alert alert-danger shadow-lg p-5" role="alert">
        <i class="bi bi-x-octagon-fill display-3"></i>
        <h1 class="mt-3">Plată Eșuată sau Anulată</h1>
        <p class="lead">Tranzacția nu a fost finalizată. Nu a fost percepută nicio taxă.</p>

        <?php if ($orderId): ?>
            <p class="mb-4">Comanda ID: <strong>#<?php echo htmlspecialchars($orderId); ?></strong> a rămas în starea "pending".</p>
        <?php endif; ?>

        <a href="cart.php" class="btn btn-danger btn-lg me-3">Înapoi la Coș</a>
        <a href="index.php" class="btn btn-secondary btn-lg">Continuă Cumpărăturile</a>
    </div>
</div>

<?php
require_once 'footer.php';
?>

