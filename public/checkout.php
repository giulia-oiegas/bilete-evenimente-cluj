
<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/stripe_config_template.php';
require_once __DIR__ . '/../stripe-php-master/init.php';
require_once '../classes/AuthService.php';
require_once '../classes/cart_service.php';
require_once '../classes/order_service.php';
require_once '../classes/stripe_payment_service.php';

session_start();

$auth = new authService();
$cartService = new cartService();
$orderService = new orderService();
$stripeService = new stripe_payment_service();
$message = '';
$id_order=0;

if (!$auth->isUserLoggedIn()) {
    header("Location: login.php?redirect=checkout");
    exit;
}

$id_user = (int)$_SESSION['user_id'];
$cart_items = $cartService->getCartItems($id_user);
$total_amount = 0;

if (empty($cart_items)) {
    header("Location: cart.php"); // Redirecționează dacă coșul e gol
    exit;
}

// 1. Calculează Totalul Comenzii
foreach ($cart_items as $item) {
    $total_amount += $item['price_at_purchase'] * $item['quantity'];
}

// 2. Logica procesării comenzii Stripe
if (isset($_POST['pay_with_card'])) {

    // 2. Creează Comanda in DB cu status 'pending'
    $id_order = $orderService->createOrderFromCart($id_user);

    if ($id_order > 0) {
        try {
            // 3. Definește URL-urile de redirecționare după plată
            $base_url = "http://localhost/bilete-evenimente-cluj/public";
            $success_url = $base_url."/payment_success.php";
            $cancel_url = $base_url."/payment_error.php";
           ///4. Creează sesiunea Stripe și obține URL-ul de plată
            $stripe_url = $stripeService->createCheckoutSession(

                    $cart_items,
                    $success_url,
                    $cancel_url,
                    $id_order
            );
            // 4. Redirecționează utilizatorul catre pagina Stripe
            header("Location: " . $stripe_url);
            exit;

        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Eroare specifica Stripe
            $message = "<div class='alert alert-danger'>Eroare Stripe: " . htmlspecialchars($e->getMessage()) . "</div>";
            $orderService->updateOrderStatus($id_order, 'cancelled');

        } catch (Exception $e) {
            // Alte erori (ex: rețea, configurare)
            $message = "<div class='alert alert-danger'>Eroare necunoscută: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Comanda nu a putut fi plasată. Vă rugăm încercați din nou. (Verificați stocul)</div>";
    }
}
?>
<?php include 'header.php'; // Include Navbar, Bootstrap CSS ?>

<div class="container my-5">
    <h1 class="mb-4">Finalizare Comandă (Checkout)</h1>
    <?php echo $message; ?>

    <div class="row">
        <div class="col-lg-8">
            <h2 class="h4 mb-3">1. Rezumatul Coșului</h2>
            <?php foreach ($cart_items as $item): ?>
                <div class="card mb-2 p-3">
                    <?php echo htmlspecialchars($item['name']); ?> x <?php echo $item['quantity']; ?>
                    <span class="float-end fw-bold"><?php echo number_format($item['price_at_purchase'] * $item['quantity'], 2); ?> RON</span>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-lg p-4">
                <h2 class="h4">2. Total Comandă</h2>
                <hr>
                <p class="fs-4 fw-bold text-danger">Total: <?php echo number_format($total_amount, 2); ?> RON</p>

                <!-- Buton plată cu Stripe -->
                <form method="POST">
                    <button type="submit" name="pay_with_card" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-credit-card"></i> Plătește cu Cardul (Stripe)
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; // Include JS, Footer ?>