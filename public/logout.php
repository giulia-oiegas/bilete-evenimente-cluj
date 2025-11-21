<?php
// /public/logout.php
require_once '../classes/authService.php';
session_start();
include 'header.php';
// 1. Apelarea metodei statice de logout
authService::logout();

// 2. Redirecționarea spre pagina principală sau pagina de login
header("Location: index.php");
include 'footer.php';
exit;
?>