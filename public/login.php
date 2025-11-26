

<?php
// /public/login.php
require_once '../classes/authService.php';
session_start();


$auth = new authService();
$error = '';
$success = '';

// Verifică dacă utilizatorul este deja logat; dacă da, redirecționează la index
if ($auth->isUserLoggedIn()) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usernameOrEmail = trim($_POST['username_or_email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($usernameOrEmail) || empty($password)) {
        $error = "Toate câmpurile sunt obligatorii.";
    } elseif ($auth->loginUser($usernameOrEmail, $password)) {
        // Autentificare reușită
        header("Location: index.php"); // Redirecționare la home/index.php
        exit;
    } else {
        $error = "Date de autentificare invalide."; // Eroare
    }
}
include 'header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <h2 class="mb-4 text-center">Autentificare</h2>

            <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

            <div class="card p-4 shadow-sm">
                <form method="POST">
                    <div class="mb-3">
                        <label for="loginUser" class="form-label">Username sau Email</label>
                        <input type="text" class="form-control" id="loginUser" name="username_or_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="loginPassword" class="form-label">Parolă</label>
                        <input type="password" class="form-control" id="loginPassword" name="password" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                    <p class="mt-3 text-center"><small>Nu ai cont? <a href="register.php">Înregistrează-te</a></small></p>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php include 'footer.php';?>