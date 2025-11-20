<?php
// /public/test_db_connection.php
// Ieșim din /public și intrăm în /classes
require_once '../classes/db_controller.php';

// Script temporar pentru testarea conexiunii PDO
try {
    $db = new DBController();

    // Tentativa de citire a datelor din tabela CATEGORIES
    // Aceasta folosește metoda select() din DBController.php
    $categories = $db->select("SELECT id_categories, name FROM CATEGORIES LIMIT 3");

    echo "<h1>✅ Test Conexiune Bază de Date (ticket_shop) Reușit!</h1>";
    echo "<p>PDO s-a conectat și a citit date din tabela CATEGORIES.</p>";
    echo "<p>Categorii găsite: " . count($categories) . "</p>";

    echo "<pre>";
    print_r($categories);
    echo "</pre>";

} catch (Exception $e) {
    echo "<h1>❌ EROARE LA CONEXIUNE SAU INTEROGARE!</h1>";
    echo "<p>Vă rugăm verificați:</p>";
    echo "<ul>";
    echo "<li>1. Dacă WAMP (Apache/MySQL) este pornit.</li>";
    echo "<li>2. Dacă datele din **config.php** (DB_NAME='ticket_shop', DB_USER, DB_PASS) sunt corecte.</li>";
    echo "<li>3. Dacă tabela **CATEGORIES** există în baza de date **ticket_shop**.</li>";
    echo "<li>4. Mesajul detaliat al erorii: <strong>" . htmlspecialchars($e->getMessage()) . "</strong></li>";
    echo "</ul>";
}
?>