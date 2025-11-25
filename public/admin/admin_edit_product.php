<?php
// /public/admin/admin_edit_product.php
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
$id_products = (int)($_GET['id'] ?? 0);
$error = '';
$pageTitle = 'Editează Produs';

// Verifică ID-ul și încarcă datele curente
if ($id_products <= 0) {
    header("Location: admin_home.php?status=id_missing");
    exit;
}
$event = $productRepo->getEventById($id_products);
if (!$event) {
    header("Location: admin_home.php?status=not_found");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Preluare și validare date
    $data = [
        'category_id' => (int)$_POST['category_id'],
        'name' => trim($_POST['name']),
        'code' => trim($_POST['code']),
        'venue' => trim($_POST['venue']),
        'event_date' => trim($_POST['event_date']),
        'available_tickets' => (int)$_POST['available_tickets'],
        'price' => (float)$_POST['price'],
        'description' => trim($_POST['description']),
        'image' => trim($_POST['image'])
    ];

    // 2. Execută UPDATE
    $success = $productRepo->updateProduct($id_products, $data);

    if ($success) {
        header("Location: admin_home.php?status=updated");
        exit;
    } else {
        $error = "Eroare la salvarea modificărilor produsului.";
    }
    // Reîmprospătează $event în caz de eroare
    $event = array_merge($event, $_POST);
}

include 'admin_header.php';
?>

    <h1 class="mb-4"><?php echo $pageTitle; ?></h1>

<?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

    <form method="POST" action="admin_edit_product.php?id=<?php echo $id_products; ?>">
        <div class="mb-3">
            <label for="name" class="form-label">Nume Produs/Eveniment</label>
            <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($event['name'] ?? ''); ?>" required>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Categorie</label>
            <select class="form-select" name="category_id" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id_categories']; ?>"
                        <?php echo ($event['category_id'] ?? '') == $cat['id_categories'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="venue" class="form-label">Locație (Venue)</label>
            <input type="text" class="form-control" name="venue" value="<?php echo htmlspecialchars($event['venue'] ?? ''); ?>" required>
        </div>

        <div class="mb-3">
            <label for="event_date" class="form-label">Dată și Oră Eveniment</label>
            <input type="datetime-local" class="form-control" name="event_date" value="<?php echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($event['event_date'] ?? 'now'))); ?>" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Preț Bilet (RON)</label>
            <input type="number" step="0.01" class="form-control" name="price" value="<?php echo htmlspecialchars($event['price'] ?? ''); ?>" required>
        </div>

        <div class="mb-3">
            <label for="available_tickets" class="form-label">Stoc Disponibil</label>
            <input type="number" class="form-control" name="available_tickets" value="<?php echo htmlspecialchars($event['available_tickets'] ?? ''); ?>" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descriere Eveniment</label>
            <textarea class="form-control" name="description" rows="3" required><?php echo htmlspecialchars($event['description'] ?? ''); ?></textarea>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Calea către Imagine</label>
            <input type="text" class="form-control" name="image" value="<?php echo htmlspecialchars($event['image'] ?? 'assets/images/placeholder.webp'); ?>" required>
        </div>

        <input type="hidden" name="code" value="<?php echo htmlspecialchars($event['code'] ?? ''); ?>">

        <button type="submit" class="btn btn-success btn-lg mt-3">Salvează Modificările</button>
        <a href="admin_home.php" class="btn btn-secondary mt-3">Anulează</a>
    </form>

<?php include '../footer.php'; ?>