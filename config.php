<?php
session_start();
$host = 'localhost';
$dbname = 'korochki_db';
$user = 'root';
$pass = ''; // Пустой пароль для OpenServer

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Ошибка БД: " . $e->getMessage());
}
?>