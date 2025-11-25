<?php

define('STRIPE_PUBLISHABLE_KEY', 'INLOCUITI_CU_CHEIA_PUBLICA_STRIPE_AICI');
define('STRIPE_SECRET_KEY', 'INLOCUITI_CU_CHEIA_SECRETA_STRIPE_AICI');
define('STRIPE_CURRENCY', 'ron');


if (STRIPE_PUBLISHABLE_KEY === 'INLOCUITI_CU_CHEIA_PUBLICA_STRIPE_AICI') {
    die('EROARE: Configurare Stripe incompleta. Va rugam inlocuiti placeholder-urile din fisierul de configurare.');
}

?>