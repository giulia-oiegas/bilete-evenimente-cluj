<?php
// /public/admin/admin_delete_product.php
require_once '../../classes/authService.php';
require_once '../../classes/product_repository.php';
session_start();

// 1. Verifică rolul Admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login_admin.php");
    exit;
}

if (isset($_GET['id'])) {
    $productRepo = new productRepository();
    $id_products = (int)$_GET['id'];

    if ($productRepo->deleteProduct($id_products)) {
        // Redirecționează cu mesaj de succes
        header("Location: admin_home.php?status=deleted");
        exit;
    } else {
        // Redirecționează cu mesaj de eroare
        header("Location: admin_home.php?status=delete_error");
        exit;
    }
}

// Dacă nu este specificat un ID, redirecționează înapoi
header("Location: admin_home.php");
exit;
?>