<?php

    require_once __DIR__ . '/../config/stripe_config_template.php';

class stripe_payment_service {

    private \Stripe\StripeClient $stripe;

    public function __construct() {
        // Se instaleaza clientul Stripe cu cheia SECRETA definita in fisierul de configurare.
        \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
        //StripeClient este corect
        $this->stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);
    }

    //Creeaza o sesiune de Stripe Checkout.
    public function createCheckoutSession(array $cartItems, string $successUrl, string $cancelUrl): string {

        $lineItems = [];

        // construire lista de produse in format Stripe
        foreach ($cartItems as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => STRIPE_CURRENCY,
                    'unit_amount' => (int)($item['price'] * 100), // Prețul trebuie trimis in centi
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
            $session = $this->stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                // URL-urile de succes si esec pentru redirectionare
                'success_url' => $successUrl . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $cancelUrl,
                'metadata' => [
                    // stocare id-ul comenzii sau al utilizatorului
                    'user_id' => $_SESSION['user_id'] ?? 0,
                ]
            ]);

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
