<?php
//pagina afisata de Stripe dupa o plata reusita.
// Actualizeaza statusul comenzii in DB

require_once __DIR__ . '/../config/config.php';
require_once '../classes/mail_service.php';
require_once '../classes/order_service.php';
require_once '../classes/authService.php';

session_start();

$auth = new authService();
$orderService = new orderService();
$mailService = new mailService();

$pageTitle = 'Plată reușită';
$orderId = isset($_GET['orderId']) ? (int)$_GET['orderId'] : 0;
$message = "";
$statusUpdateSuccess = false;

// Verifică dacă utilizatorul este logat
if (!$auth->isUserLoggedIn()) {
    $error = "Eroare: Sesiunea a expirat. Vă rugăm să vă autentificați.";
    header("Location: login.php");
    exit;
}

$userId = (int)$_SESSION['user_id'];

if ($orderId > 0) {
    try {
        // Actualizează starea comenzii la 'paid'
        $statusUpdateSuccess = $orderService->updateOrderStatus($orderId, 'paid');

        if ($statusUpdateSuccess) {
            $message = "Plata a fost confirmată. Îți mulțumim pentru comanda #$orderId!";

            $orderData = $orderService->getOrderDetails($orderId);

            if($orderData) {
                $userEmail = $orderData['user_email'];
                $mailService->sendOrderConfirmation($userEmail, $orderData);
            }
        } else {
            $message = "Plata a fost confirmată, dar nu am putut actualiza starea comenzii #$orderId în baza de date. Vă rugăm contactați suportul.";
        }

    } catch (Exception $e) {
        // În caz de eroare la DB sau Service
        $message = "A apărut o eroare la procesarea comenzii: " . htmlspecialchars($e->getMessage());
        $statusUpdateSuccess = false;
    }
} else {
    $message = "Eroare: ID-ul comenzii nu a fost găsit în URL. Vă rugăm verificați linkul de plată.";
}

require_once 'header.php';
?>

<div class="container mt-5 text-center">
    <?php if ($statusUpdateSuccess): ?>
        <div class="alert alert-success shadow-lg p-5" role="alert">
            <i class="bi bi-check-circle-fill display-3 text-green-600"></i>
            <h1 class="mt-3 text-4xl font-bold">Comandă Plătită cu Succes!</h1>
            <p class="lead text-lg mt-2"><?php echo $message; ?></p>

            <p class="mb-4 text-xl">Comanda ta ID: <strong>#<?php echo htmlspecialchars($orderId); ?></strong> a fost finalizată.</p>

            <a href="my_account.php" class="btn btn-primary btn-lg me-3 transition duration-300 hover:scale-105">Vezi Comenzile Mele</a>
            <a href="index.php" class="btn btn-secondary btn-lg transition duration-300 hover:scale-105">Continuă Cumpărăturile</a>
        </div>
    <?php else: ?>
        <div class="alert alert-warning shadow-lg p-5" role="alert">
            <i class="bi bi-exclamation-triangle-fill display-3 text-yellow-600"></i>
            <h1 class="mt-3 text-4xl font-bold">Atenție!</h1>
            <p class="lead text-lg mt-2"><?= htmlspecialchars($message) ?></p>
            <p class="mb-4 text-gray-700">Dacă ați fost taxat, dar starea nu s-a actualizat, vă rugăm contactați serviciul de suport.</p>
            <a href="index.php" class="btn btn-warning btn-lg transition duration-300 hover:scale-105">Înapoi la prima pagină</a>
        </div>
    <?php endif; ?>
</div>

<?php
require_once 'footer.php';
?>
