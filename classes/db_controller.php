<?php
// /classes/db_controller.php
require_once __DIR__ . '/../config/config.php';

class db_controller {
    private PDO $conn;

    public function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("Eroare la conectare: " . $e->getMessage());
        }
    }

    // getDBResult
    public function select(string $query, array $params = []): array {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // updateDB
    public function execute(string $query, array $params = []): int {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    public function lastInsertId(): string {
        return $this->conn->lastInsertId();
    }
}