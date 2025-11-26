
<?php

// /config/config.php
// DB_NAME este setat la 'ticket_shop'
define('DB_HOST', 'localhost');
define('DB_NAME', 'ticket_shop');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configurare Stripe
//define('STRIPE_SECRET_KEY', 'sk_test_VOTRA_CHEIE_SECRETA');

// Configurare Email
define('CONTACT_EMAIL', 'contact@biletecluj.ro');

$classDir = __DIR__ . '/../classes/';
require_once $classDir . 'db_controller.php';
require_once $classDir . 'user.php';
require_once $classDir . 'authService.php';
require_once $classDir . 'product_repository.php';
require_once $classDir . 'cart_service.php';