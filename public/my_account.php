<?php
require_once '../config/config.php';
require_once '../classes/authService.php';
require_once '../classes/order_service.php';

session_start();

$auth = new authService();
if(!$auth->isUserLoggedIn()) {
    header('Location: login.php');
    exit();
}

$userId = (int)$_SESSION['userId'];
$orderService = new orderService();

$orders = $orderService->getOrdersByUser($userId);

include 'header.php';
?>

<main class="py-4">
    <div class="container mt-4">
        <h1>Contul meu</h1>

        <h3 class="mt-4 mb-3">Comenzile mele</h3>

        <?php if(!empty($orders)): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Data</th>
                        <th>Total (RON)</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach($orders as $order): ?>
                    <tr>
                        <td><?= (int)$order['id_order'] ?></td>
                        <td><?= htmlspecialchars($order['created_at'])?></td>
                        <td><?= number_format($order['total_amount'], 2)?></td>
                        <td><?= htmlspecialchars($order['order_status']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nu ai încă nicio comandă.</p>
        <?php endif; ?>
    </div>
</main>
<?php
include 'footer.php';
?>

