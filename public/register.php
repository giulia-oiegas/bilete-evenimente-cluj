<?php
require_once __DIR__ . '/../config/config.php';
$pageTitle = 'Înregistrare Utilizator';
require_once 'header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validare de baza
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = 'Toate câmpurile sunt obligatorii.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Adresă de email invalidă.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Parola și confirmarea parolei nu se potrivesc.';
    } elseif (strlen($password) < 6) {
        $error = 'Parola trebuie să aibă minim 6 caractere.';
    } else {
        // inregistrare
        try {
            $db = new db_controller();
            $authService = new AuthService();

            if ($authService->registerUser($username, $email, $password)) {
                $success = 'Înregistrare reușită! Vă puteți autentifica acum.';
                // Redirectionare la pagina de login
                header("Refresh: 3; URL=login.php");
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
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <h2 class="mb-4 text-center">Creare Cont Nou</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger rounded-3" role="alert"><i class="bi bi-exclamation-triangle-fill"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success rounded-3" role="alert">
                <i class="bi bi-check-circle-fill"></i> <?php echo htmlspecialchars($success); ?>
                Redirecționare la Login...
            </div>
        <?php endif; ?>

        <div class="card shadow-lg rounded-3 border-0">
            <div class="card-body p-4">
                <form method="POST" action="register.php">
                    <div class="mb-3">
                        <label for="username" class="form-label">Nume utilizator</label>
                        <input type="text" class="form-control rounded-2" id="username" name="username" required
                               value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control rounded-2" id="email" name="email" required
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Parolă</label>
                        <input type="password" class="form-control rounded-2" id="password" name="password" required>
                    </div>
                    <div class="mb-4">
                        <label for="confirm_password" class="form-label">Confirmă Parola</label>
                        <input type="password" class="form-control rounded-2" id="confirm_password" name="confirm_password" required>
                    </div>

                    <button type="submit" class="btn btn-success w-100 rounded-2 btn-lg">Înregistrare</button>

                    <p class="mt-3 text-center text-muted">Ai deja cont? <a href="login.php" class="text-success fw-bold">Autentifică-te</a></p>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
echo '</main>';
echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>';
echo '</body></html>';
?>
