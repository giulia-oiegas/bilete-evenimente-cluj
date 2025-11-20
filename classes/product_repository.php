
public function getFilteredProducts(?int $category_id = null, ?string $search_term = null): array {
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