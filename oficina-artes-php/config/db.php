<?php
// Ajuste DSN, usuário e senha conforme seu ambiente.
$DB_HOST = '127.0.0.1';
$DB_NAME = 'oficina_db';
$DB_USER = 'root';
$DB_PASS = '';

$dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";
try {
$pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
} catch (PDOException $e) {
die('Erro na conexão com o banco: ' . $e->getMessage());
}