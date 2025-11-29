<?php
// /public/admin/admin_add_product.php
require_once '../../classes/authService.php';
require_once '../../classes/product_repository.php';
session_start();

// 1. Verifică rolul Admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login_admin.php");
    exit;
}

$productRepo = new productRepository();
$categories = $productRepo->getAllCategories();
$error = '';
$pageTitle = 'Adaugă Produs Nou';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Preluare și validare date
    $data = [
            'category_id' => (int)$_POST['category_id'],
            'name' => trim($_POST['name']),
            'code' => trim($_POST['code'] ?? 'N/A'),
            'venue' => trim($_POST['venue']),
            'event_date' => trim($_POST['event_date']),
            'available_tickets' => (int)$_POST['available_tickets'],
            'price' => (float)$_POST['price'],
            'description' => trim($_POST['description']),
            'image' => trim($_POST['image'])
    ];

    // Execută INSERT
    $success = $productRepo->createProduct($data);

    if ($success) {
        header("Location: admin_home.php?status=created");
        exit;
    } else {
        $error = "Eroare la adăugarea produsului.";
    }
}

include 'admin_header.php';
?>
<main class="py-4 admin-add-main">
    <h2 class="header-add-admin mb-4"><?php echo $pageTitle; ?></h2>

<?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

    <form method="POST" action="admin_add_product.php">
        <div class="mb-3">
            <label for="name" class="form-label">Nume Produs/Eveniment</label>
            <input type="text" class="form-control" name="name" required>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Categorie</label>
            <select class="form-select" name="category_id" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id_categories']; ?>">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="venue" class="form-label">Locație (Venue)</label>
            <input type="text" class="form-control" name="venue" required>
        </div>

        <div class="mb-3">
            <label for="event_date" class="form-label">Dată și Oră Eveniment</label>
            <input type="datetime-local" class="form-control" name="event_date" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Preț Bilet (RON)</label>
            <input type="number" step="0.01" class="form-control" name="price" required>
        </div>

        <div class="mb-3">
            <label for="available_tickets" class="form-label">Stoc Disponibil</label>
            <input type="number" class="form-control" name="available_tickets" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descriere Eveniment</label>
            <textarea class="form-control" name="description" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Calea către Imagine</label>
            <input type="text" class="form-control" name="image" value="assets/images/placeholder.webp" required>
        </div>

        <input type="hidden" name="code" value="<?php echo uniqid(); ?>">

        <button type="submit" class="btn btn-success btn-lg mt-3">Adaugă Produs</button>
        <a href="admin_home.php" class="btn btn-secondary mt-3">Anulează</a>
    </form>
</main>
<?php include '../footer.php'; ?>