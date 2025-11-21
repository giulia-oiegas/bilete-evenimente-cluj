<?php

require_once 'db_controller.php';

class productRepository
{
    private db_controller $db; // <-- Proprietate  pentru a apela $this->db->select()

    public function __construct()
    {
        $this->db = new db_controller();
    }

    public function getFilteredProducts(?int $category_id = null, ?string $search_term = null): array
    {
        $query = "SELECT p.*, c.name AS category_name
                FROM PRODUCTS p
                JOIN CATEGORIES c ON p.category_id = c.id_categories
                WHERE 1=1";
        $params = [];

        if ($category_id !== null) {
            $query .= " AND p.category_id = ?";
            $params[] = $category_id;
        }

        if ($search_term !== null && $search_term !== '') {
            $search_term = '%' . $search_term . '%';

            // Căutare după titlu sau locație (venue)
            $query .= " AND (p.name LIKE ? OR p.venue LIKE ?)";
            $params[] = $search_term;
            $params[] = $search_term;
        }

        $query .= " ORDER BY p.event_date ASC";
        return $this->db->select($query, $params);
    }
    public function getAllCategories(): array {
        $query = "SELECT id_categories, name FROM CATEGORIES ORDER BY name ASC";
        return $this->db->select($query);
    }

    public function getEventById(int $id_product): ?array
    {
        $query = "SELECT p.*, c.name AS category_name
                  FROM PRODUCTS p
                  JOIN CATEGORIES c ON p.category_id = c.id_categories
                  WHERE p.id_products = ?";

        $result = $this->db->select($query, [$id_product]);

        // returneaza primul (si singurul) rezultat, sau null
        return $result[0] ?? null;
    }
}