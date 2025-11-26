<?php
// /classes/cart_service.php
require_once 'db_controller.php';

class cartService {
    private db_controller $db;

    public function __construct() {
        $this->db = new db_controller();
    }

    /**
     * Adaugă un produs în coș sau actualizează cantitatea
     * id_user, id_product, quantity corespund tabelei TBL_CART.
     */
    public function addToCart(int $id_user, int $id_product, int $quantity): bool {
        $check_query = "SELECT id_cart, quantity FROM TBL_CART WHERE id_user = ? AND id_product = ?";
        $existing = $this->db->select($check_query, [$id_user, $id_product]);

        if (count($existing) > 0) {
            // Dacă produsul există, actualizează cantitatea
            $new_quantity = $existing[0]['quantity'] + $quantity;
            $update_query = "UPDATE TBL_CART SET quantity = ? WHERE id_cart = ?";
            return $this->db->execute($update_query, [$new_quantity, $existing[0]['id_cart']]) > 0;
        } else {
            // Dacă nu există, inserează o linie nouă
            $insert_query = "INSERT INTO TBL_CART (id_user, id_product, quantity) VALUES (?, ?, ?)";
            return $this->db->execute($insert_query, [$id_user, $id_product, $quantity]) > 0;
        }
    }

    /**
     * Preluarea conținutului coșului
     * SELECT JOIN PRODUCTS pentru a afișa detalii (nume, preț).
     */
    public function getCartItems(int $id_user): array {
        $query = "SELECT t.id_cart, t.quantity, p.name, p.available_tickets, p.price AS price_at_purchase, t.id_product
                  FROM TBL_CART t 
                  JOIN PRODUCTS p ON t.id_product = p.id_products 
                  WHERE t.id_user = ?";
        return $this->db->select($query, [$id_user]);
    }


    //Modifică cantitatea unui articol existent

    public function updateQuantity(int $id_cart, int $quantity): bool {
        if ($quantity <= 0) {
            // Dacă cantitatea este zero, ștergem articolul (conform Lab 7)
            return $this->removeItem($id_cart);
        }
        $update_query = "UPDATE TBL_CART SET quantity = ? WHERE id_cart = ?";
        return $this->db->execute($update_query, [$quantity, $id_cart]) > 0;
    }


    // Elimină un singur articol din coș

    public function removeItem(int $id_cart): bool {
        $delete_query = "DELETE FROM TBL_CART WHERE id_cart = ?";
        return $this->db->execute($delete_query, [$id_cart]) > 0;
    }


    //  Golește complet coșul (Utilizat la finalizarea comenzii)

    public function emptyCart(int $id_user): bool {
        $delete_query = "DELETE FROM TBL_CART WHERE id_user = ?";
        return $this->db->execute($delete_query, [$id_user]) > 0;
    }
}
