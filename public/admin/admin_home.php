<?php
// /public/admin/admin_home.php (Panoul principal de listare)
require_once '../../classes/authService.php';
require_once '../../classes/product_repository.php';
session_start();

// 1. Verifică autentificarea și rolul Admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$productRepo = new productRepository();
$events = $productRepo->getFilteredProducts(); // Preia lista de produse
$pageTitle = 'Gestionare Evenimente';
include 'admin_header.php'; // Ajustează calea
?>

    <h3 class="header-admin mb-4">Panou Administrator: <?php echo $pageTitle; ?></h3>

    <p><a href="admin_add_product.php" class="btn btn-success add-admin"><i class="bi bi-plus-circle-fill"></i> Adaugă Eveniment Nou</a></p>

<?php if (isset($_GET['status'])): ?>
    <?php if ($_GET['status'] == 'deleted'): ?>
        <div class="alert alert-success">Produsul a fost șters cu succes!</div>
    <?php elseif ($_GET['status'] == 'created'): ?>
        <div class="alert alert-success">Produsul a fost adăugat cu succes!</div>
    <?php elseif ($_GET['status'] == 'updated'): ?>
        <div class="alert alert-success">Produsul a fost actualizat cu succes!</div>
    <?php elseif ($_GET['status'] == 'delete_error'): ?>
        <div class="alert alert-danger">Eroare la ștergerea produsului.</div>
    <?php endif; ?>
<?php endif; ?>

<main class=" main-home py-4">
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
                <td><?php echo date('Y-m-m H:i', strtotime($event['event_date'])); ?></td>
                <td><?php echo htmlspecialchars($event['venue']); ?></td>
                <td><?php echo number_format($event['price'], 2); ?> RON</td>
                <td><?php echo $event['available_tickets']; ?></td>
                <td>
                    <a href="admin_edit_product.php?id=<?php echo $event['id_products']; ?>" class="btn btn-sm btn-primary">Editează</a>
                    <a href="admin_delete_product.php?id=<?php echo $event['id_products']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Sunteți sigur că doriți să ștergeți?');">Șterge</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php include '../footer.php'; // Ajustează calea ?>