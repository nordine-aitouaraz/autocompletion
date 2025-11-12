<?php
// db.php - configuration simple PDO pour MySQL
$DB_HOST = '127.0.0.1';
$DB_NAME = 'autocompletion';
$DB_USER = 'root';
$DB_PASS = '';

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo "Erreur de connexion à la base de données: " . htmlspecialchars($e->getMessage());
    exit;
}
