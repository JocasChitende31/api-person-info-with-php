<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;port=3306;dbname=api_php_test;charset=utf8mb4", "orbita_user", "Orbita123");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
} catch (PDOException $e) {
    die("Erro na conexão" . $e->getMessage());
}