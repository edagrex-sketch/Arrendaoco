<?php
try {
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=bd_arrendaoco', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5
    ]);
    echo "Connection successful!\n";
    $stmt = $pdo->query("SELECT database()");
    echo "Database: " . $stmt->fetchColumn() . "\n";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
