<?php
// /public/admin/login_admin.php
require_once '../../classes/authService.php';
session_start();

$auth = new authService();
$error = '';
$pageTitle = 'Autentificare Administrator';

// Dacă utilizatorul este deja logat și este admin, redirecționează
if ($auth->isUserLoggedIn() && ($_SESSION['user_role'] ?? '') === 'admin') {
    header("Location: admin_home.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usernameOrEmail = trim($_POST['username_or_email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($usernameOrEmail) || empty($password)) {
        $error = "Toate câmpurile sunt obligatorii.";
    } elseif ($auth->loginUser($usernameOrEmail, $password)) {
        // Autentificare reușită, acum verifică ROLUL
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            header("Location: admin_home.php"); // Redirecționare la panoul admin
            exit;
        } else {
            // Logare reușită, dar nu este admin - deloghează-l
            $auth->logout();
            $error = "Acces refuzat. Nu aveți rol de administrator.";
        }
    } else {
        $error = "Date de autentificare invalide.";
    }
}
include 'admin_header.php';
?>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <h2 class="mb-4 text-center">Login Administrator</h2>

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
                            <button type="submit" class="btn btn-primary">Login Admin</button>
                        </div>
                        <p class="mt-3 text-center"><small>Nu ai cont de admin? <a href="../login.php">Mergi la Login Clienți</a></small></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php include '../footer.php'; ?>