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
        //verificam daca exista produsul si cat stoc are
        $available = $this->getAvailableTickets($id_product);
        if($available === null) {
            $_SESSION['cart_error'] = "Evenimentul nu mai există.";
            return false;
        }

        //cat are deja utilizatorul in cos din acest produs
        $sql = "SELECT COALESCE(SUM(quantity), 0) AS qty
                FROM tbl_cart
                WHERE id_user = ? AND id_product = ?";
        $rows = $this->db->select($sql, [$id_user, $id_product]);
        $alreadyInCart = (int)($rows[0]['qty'] ?? 0);

        $maxCantAdded = $available - $alreadyInCart;

        if($maxCantAdded <= 0) {
            $_SESSION['cart_error'] = 'Nu mai sunt bilete disponibile pentru acest eveniment.';
            return false;
        }

        //daca exista deja o linie in cos, o actualizam, altfel inseram
        $checkSql = "SELECT id_cart, quantity
                     FROM tbl_cart
                     WHERE id_user = ? AND id_product = ?
                     LIMIT 1";
        $existing = $this->db->select($checkSql, [$id_user, $id_product]);


        //$check_query = "SELECT id_cart, quantity FROM TBL_CART WHERE id_user = ? AND id_product = ?";
        //$existing = $this->db->select($check_query, [$id_user, $id_product]);

        if (!empty($existing)) {
            // Dacă produsul există, actualizează cantitatea
            $new_quantity = (int)$existing[0]['quantity'] + $quantity;
            $update_query = "UPDATE TBL_CART SET quantity = ? WHERE id_cart = ?";
            return $this->db->execute($update_query, [$new_quantity, (int)$existing[0]['id_cart']]) > 0;
        } //else {
            // Dacă nu există, inserează o linie nouă
            //$insert_query = "INSERT INTO TBL_CART (id_user, id_product, quantity) VALUES (?, ?, ?)";
            //return $this->db->execute($insert_query, [$id_user, $id_product, $quantity]) > 0;
        //}

        $insertSql = "INSERT INTO tbl_cart (id_user, id_product, quantity) VALUES (?, ?, ?)";
        return $this->db->execute($insertSql, [$id_user, $id_product, $quantity]);
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
        //aflam produsul si stocul lui
        $sql = "SELECT c.id_product, p.available_tickets 
                FROM tbl_cart AS c
                JOIN PRODUCTS AS p ON c.id_product = p.id_products
                WHERE c.id_cart = ?";
        $rows = $this->db->select($sql, [$id_cart]);

        if(empty($rows)) {
            return false;
        }

        $available = (int)$rows[0]['available_tickets'];

        //daca userul incearca mai mult decat stocul, limitam
        if($quantity <= 0) {
            $deleteSql = "DELETE FROM tbl_cart WHERE id_cart = ?";
            return $this->db->execute($deleteSql, [$id_cart]);
        }

        $updateSql = "UPDATE tbl_cart 
                      SET quantity = ? 
                      WHERE id_cart = ?";

        return $this->db->execute($updateSql, [$quantity, $id_cart]);
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

    private function getAvailableTickets(int $productId): ?int
    {
        $sql = "SELECT available_tickets FROM products WHERE id_products = ?";
        $rows = $this->db->select($sql, [$productId]);

        if(empty($rows)) return null;

        return (int)$rows[0]['available_tickets'];
    }
}
