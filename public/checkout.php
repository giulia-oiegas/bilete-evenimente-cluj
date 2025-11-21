
<?php
// /public/checkout.php
require_once '../classes/AuthService.php';
require_once '../classes/cart_service.php';
require_once '../classes/order_service.php';
session_start();

$auth = new authService();
$cartService = new cartService();
$orderService = new orderService();
$message = '';

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

// 2. Logica procesării comenzii (FĂRĂ PLATĂ)
if (isset($_POST['confirm_order'])) {
    // Apelează createOrderFromCart()
    $id_order = $orderService->createOrderFromCart($id_user);

    if ($id_order > 0) {
        // Redirecționare către o pagină de confirmare simplă
        header("Location: payment_success.php?order_id=" . $id_order . "&status=pending");
        exit;
    } else {
        $message = "<div class='alert alert-danger'>Comanda nu a putut fi plasată. Vă rugăm încercați din nou.</div>";
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

                <form method="POST">
                    <button type="submit" name="confirm_order" class="btn btn-warning w-100 mb-2">
                        Confirmă Comanda (FĂRĂ PLATĂ)
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; // Include JS, Footer ?>