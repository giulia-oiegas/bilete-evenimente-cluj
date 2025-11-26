<?php
// /public/admin/logout_admin.php
require_once '../../classes/authService.php';

session_start();

// Apelarea metodei statice de delogare
authService::logout();

// Redirecționarea spre pagina de login a adminului
header("Location: login_admin.php");

exit;
?>