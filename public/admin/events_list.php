<?php
// /public/admin/events_list.php
require_once '../../classes/authService.php';
require_once '../../classes/product_repository.php';
session_start();

$auth = new authService();

// 1. Verifică rolul Admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$productRepo = new productRepository();
$events = $productRepo->getFilteredProducts();
$pageTitle = 'Administrare Evenimente';
include '../header.php'; // Includem header-ul cu navbar-ul

// Logica de ștergere
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    if ($productRepo->deleteProduct((int)$_GET['id'])) {
        header("Location: events_list.php?status=deleted");
        exit;
    }
}
?>

    <h1 class="mb-4">Panou Administrator: Gestionare Evenimente</h1>
    <p><a href="event_edit.php" class="btn btn-success"><i class="bi bi-plus-circle-fill"></i> Adaugă Eveniment Nou</a></p>

<?php if (isset($_GET['status']) && $_GET['status'] == 'deleted'): ?>
    <div class="alert alert-success">Evenimentul a fost șters cu succes!</div>
<?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nume Eveniment</th>
            <th>Data</th>
            <th>Locație</th>
            <th>Preț</th>
            <th>Stoc</th>
            <th>Acțiuni</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($events as $event): ?>
            <tr>
                <td><?php echo $event['id_products']; ?></td>
                <td><?php echo htmlspecialchars($event['name']); ?></td>
                <td><?php echo date('Y-m-d H:i', strtotime($event['event_date'])); ?></td>
                <td><?php echo htmlspecialchars($event['venue']); ?></td>
                <td><?php echo number_format($event['price'], 2); ?> RON</td>
                <td><?php echo $event['available_tickets']; ?></td>
                <td>
                    <a href="event_edit.php?id=<?php echo $event['id_products']; ?>" class="btn btn-sm btn-primary">Editează</a>
                    <a href="events_list.php?action=delete&id=<?php echo $event['id_products']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Sunteți sigur că doriți să ștergeți?');">Șterge</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php include '../footer.php'; ?>