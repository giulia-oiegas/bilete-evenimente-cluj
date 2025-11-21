
<?php
// /classes/order_service.php

require_once 'db_controller.php';
require_once 'cart_service.php';

class orderService {
    private db_controller $db;
    private cartService $cartService;

    public function __construct() {
        $this->db = new db_controller();
        $this->cartService = new cartService();
    }

    /**
     * Creează o comandă și articolele aferente din coș
     * Mută datele din TBL_CART în ORDERS/ORDER_ITEMS într-o tranzacție.
     */
    public function createOrderFromCart(int $id_user): ?int {
        error_log("Tentativa de creare comanda pentru user: " . $id_user);

        // 1. Citește rândurile din TBL_CART
        $cart_items = $this->cartService->getCartItems($id_user);

        if (empty($cart_items)) {
            return null; // Coșul este gol
        }

        $total_amount = 0;
        foreach ($cart_items as $item) {
            $total_amount += $item['price_at_purchase'] * $item['quantity']; // Calculează total_amount
        }

        // Inițiază tranzacția pentru a asigura integritatea datelor
        $conn = $this->db->getConnection();
        $conn->beginTransaction();

        try {
            // 2. Inserează o linie în ORDERS
            $order_query = "INSERT INTO ORDERS (id_user, total_amount, order_status, created_at)
                            VALUES (?, ?, 'pending', NOW())"; // order_status inițial: 'pending'
            $this->db->execute($order_query, [$id_user, $total_amount]);
            $id_order = (int)$this->db->lastInsertId();

            // 3. Inserează în ORDER_ITEMS și scade stocul
            foreach ($cart_items as $item) {
                // Inserare în ORDER_ITEMS
                $item_query = "INSERT INTO ORDER_ITEMS (id_order, product_id, quantity, price_at_purchase)
                               VALUES (?, ?, ?, ?)";
                $this->db->execute($item_query, [
                    $id_order,
                    $item['id_product'],
                    $item['quantity'],
                    $item['price_at_purchase'] // price curent al biletului
                ]);

                // Scăderea stocului din PRODUCTS
                $stock_query = "UPDATE PRODUCTS SET available_tickets = available_tickets - ? 
                                WHERE id_products = ?";
                $this->db->execute($stock_query, [$item['quantity'], $item['id_product']]);
            }

            // 4. Goliște coșul (șterge rândurile din TBL_CART)
            $this->cartService->emptyCart($id_user);

            $conn->commit(); // Finalizează tranzacția
            return $id_order;

        } catch (Exception $e) {
            $conn->rollBack(); // Anulează totul în caz de eroare (ex: stoc negativ, eroare SQL)
            error_log("Order creation failed: " . $e->getMessage());
            return 0;
        }
    }
}