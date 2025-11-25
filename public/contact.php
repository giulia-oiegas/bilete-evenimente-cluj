<?php
require_once '../classes/mail_service.php';
include 'header.php';

$mailService = new mailService();

$successMessage = '';
$errorMessage = '';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if(empty($name) || empty($email) || empty($message)){
        $errorMessage = "Te rugăm să completezi toate câmpurile.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Te rugăm să introduci o adresă de email validă.";
    } else {
        if($mailService->sendContactMessage($name, $email, $message)){
            $successMessage = "Mesajul tău a fost trimis cu succes. Îți mulțumim!";
        } else {
            $errorMessage = "A apărut o eroare la trimiterea mesajului. Încearcă din nou.";
        }
    }
}
?>

<main class="py-4">
    <div class="container mt-4">
        <h1>Contact</h1>

        <?php if($successMessage): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>

        <?php if($errorMessage): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <form method="post" class="mt-3">
            <div class="mb-3">
                <label class="form-label">Nume</label>
                <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($name ?? '')?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($email ?? '')?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Mesaj</label>
                <textarea name="message" rows="5" class="form-control" required><?= htmlspecialchars($message ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Trimite mesaj</button>
        </form>
    </div>
</main>

<?php
include 'footer.php';
?>