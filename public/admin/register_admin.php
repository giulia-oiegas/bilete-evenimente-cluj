<?php
// /public/admin/register_admin.php

require_once __DIR__ . '/../../config/config.php';
require_once '../../classes/AuthService.php';

session_start();

$pageTitle = 'Înregistrare Administrator';
$error = '';
$success = '';

// Redirecționează dacă e deja logat
if (isset($_SESSION['user_role'])) {
    header("Location: admin_home.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $role = 'admin'; // <--- Rolul setat FIX

    // Validare de bază
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = 'Toate câmpurile sunt obligatorii.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Adresă de email invalidă.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Parola și confirmarea parolei nu se potrivesc.';
    } elseif (strlen($password) < 6) {
        $error = 'Parola trebuie să aibă minim 6 caractere.';
    } else {
        // Înregistrare
        try {
            $authService = new AuthService();
            // Presupunem că registerUser setează automat rolul pe 'user' sau că DB-ul folosește 'admin'
            // Dacă Auth Service suportă rol, modifică apelul aici:
            if ($authService->registerUser($username, $email, $password, $role)) {
                $success = 'Înregistrare ADMIN reușită! Vă puteți autentifica acum.';
                header("Refresh: 3; URL=login_admin.php");
            } else {
                $error = 'Eroare la înregistrare. Este posibil ca numele de utilizator sau emailul să fie deja folosit.';
            }
        } catch (Exception $e) {
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                $error = 'Numele de utilizator sau adresa de email este deja înregistrată.';
            } else {
                $error = 'A apărut o eroare necunoscută. Vă rugăm încercați mai târziu.';
            }
        }
    }
}
include 'admin_header.php'; // Ajustează calea
?>

    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <h2 class="mb-4 text-center">Creare Cont Admin</h2>
            <?php if ($error): ?><div class="alert alert-danger rounded-3"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success rounded-3"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

            <div class="card shadow-lg">
                <div class="card-body p-4">
                    <form method="POST" action="register_admin.php">
                        <div class="mb-3">
                            <label for="username" class="form-label">Nume utilizator</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Parolă</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">Confirmă Parola</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 btn-lg">Înregistrare Admin</button>
                        <p class="mt-3 text-center text-muted"><a href="login_admin.php" class="text-primary fw-bold">Autentifică-te ca Admin</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php include '../footer.php'; // Ajustează calea ?>