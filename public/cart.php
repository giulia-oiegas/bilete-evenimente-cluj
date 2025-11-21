<?php
// /public/cart.php
require_once '../classes/cart_service.php';
require_once '../classes/authService.php';
session_start();

$auth = new AuthService();
$cartService = new CartService();
$message = '';
$total_amount = 0;

// Verificare Logare
if (!$auth->isUserLoggedIn()) {
    // Dacă nu este logat, redirecționează la login
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

// 1. Logica de procesare a formularelor (Actualizare/Ștergere/Golire)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST['action'] ?? '';

    // PROCESARE ADĂUGARE DIN EVENT.PHP
    if ($action === 'add') {
        $id_product = (int)($_POST['id_product'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 1);

        // Verificarea stocului (max) trebuie făcută în event.php, dar logica de bază e aici.
        if ($id_product > 0 && $quantity > 0) {
            if ($cartService->addToCart($user_id, $id_product, $quantity)) {
                // Succes: Redirecționează imediat (POST-Redirect-GET)
                header("Location: cart.php?status=added");
                exit;
            } else {
                //   mesaj de eroare
                $message = "<div class='alert alert-danger'>Eroare la adăugare.</div>";
            }
        }
    }

    if (isset($_POST['empty_cart'])) {
        $cartService->emptyCart($user_id);
        $message = "<div class='alert alert-info'>Coșul a fost golit.</div>";
    } elseif (isset($_POST['update_quantity'])) {
        $cart_id = (int)($_POST['cart_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 0);

        if ($cartService->updateQuantity($cart_id, $quantity)) { // updateQuantity
            $message = "<div class='alert alert-success'>Coșul a fost actualizat.</div>";
        }
    }
} elseif (isset($_GET['remove_item'])) {
    // Șterge un singur articol (removeItem)
    if ($cartService->removeItem((int)$_GET['remove_item'])) {
        $message = "<div class='alert alert-success'>Articolul a fost scos.</div>";
    }
}

if (isset($_GET['status']) && $_GET['status'] === 'added') {
    $message = "<div class='alert alert-success'>Bilete adăugate în coș!</div>";
}

// 2. Preluarea și Calculul Totalului
$cart_items = $cartService->getCartItems($user_id);

foreach ($cart_items as $item) {
    $total_amount += $item['price'] * $item['quantity']; // totalul pe linie + totalul general
}
include 'header.php';
?>

<div class="container mt-4">
    <h1>Coș de Cumpărături</h1>
    <?php echo $message; ?>

    <?php if (!empty($cart_items)): ?>
        <div class="row">
            <div class="col-lg-8">
                <?php foreach ($cart_items as $item): ?>
                    <div class="card mb-3 p-3">
                        <h5><?php echo htmlspecialchars($item['name']); ?></h5>
                        <p>Preț unitar: <?php echo number_format($item['price'], 2); ?> RON</p>
                        <p>Subtotal: <?php echo number_format($item['price'] * $item['quantity'], 2); ?> RON</p>

                        <form method="POST" class="d-flex align-items-center">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="0" max="<?php echo $item['available_tickets']; ?>" class="form-control" style="width: 80px;" required>
                            <input type="hidden" name="cart_id" value="<?php echo $item['id_cart']; ?>">
                            <button type="submit" name="update_quantity" class="btn btn-sm btn-success mx-2">Actualizează</button>
                            <a href="cart.php?remove_item=<?php echo $item['id_cart']; ?>" class="btn btn-sm btn-danger">Scoate</a>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="col-lg-4">
                <div class="card p-4 shadow-sm">
                    <h4>Total General: <strong><?php echo number_format($total_amount, 2); ?> RON</strong></h4>

                    <a href="checkout.php" class="btn btn-primary btn-lg mt-3">Continuă la Plată</a>
                    <form method="POST" class="mt-3">
                        <button type="submit" name="empty_cart" class="btn btn-sm btn-outline-danger w-100">Golește Coșul</button>
                    </form>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">Coșul este gol.</div>
    <?php endif; ?>
</div>

<?php
include 'footer.php';
?>