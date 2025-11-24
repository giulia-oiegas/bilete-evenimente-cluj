<?php
    require_once __DIR__ . '/../stripe-php-master/init.php';
    require_once __DIR__ . '/../config/stripe_config_template.php';

class stripe_payment_service {

    private \Stripe\StripeClient $stripe;

    public function __construct() {
        \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
        $this->stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);
    }

    //Creeaza o sesiune de Stripe Checkout.
    public function createCheckoutSession(array $cartItems, string $successUrl, string $cancelUrl,int $id_order): string {

        $lineItems = [];

        // construire lista de produse in format Stripe
        foreach ($cartItems as $item) {
            $price_to_use = $item['price_at_purchase'];

            // Verificam daca pretul e pozitiv (pentru a evita erori Stripe)
            if ((int)($price_to_use * 100) <= 0) {
                throw new \Exception("Pretul produsului '{$item['name']}' nu este valid.");
            }

            $lineItems[] = [
                'price_data' => [
                    'currency' => STRIPE_CURRENCY,
                    'unit_amount' => (int)($price_to_use * 100), // Prețul trebuie trimis in centi
                    'product_data' => [
                        'name' => $item['name'],
                        'description' => 'Bilet Eveniment',
                    ],
                ],
                'quantity' => $item['quantity'],
            ];
        }

        try {
            // Crearea sesiunii de checkout
            $params = [
                'payment_method_types' => ['card'],
                'line_items' => $lineItems, // Array de articole construit mai sus
                'mode' => 'payment',

                // Metadate (chei de tip string)
                'metadata' => [
                    'order_id' => (string)$id_order,
                    'user_id' => (string)($_SESSION['user_id'] ?? 0),
                ],
                // URL-urile de succes si esec pentru redirectionare
                'success_url' => $successUrl . '?session_id={CHECKOUT_SESSION_ID}&order_id=' . $id_order,
                'cancel_url' => $cancelUrl . '?order_id=' . $id_order,
            ];

            $session = \Stripe\Checkout\Session::create($params);
            // Intoarce URL-ul catre pagina Stripe de plata
            return $session->url;

        } catch (Exception $e) {
            // In caz de eroare,  se arunca o exceptie
            error_log("Eroare Stripe Checkout: " . $e->getMessage());
            throw new Exception("A aparut o problema la initializarea platii: " . $e->getMessage());
        }
    }
}
?>
