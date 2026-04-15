<?php
session_start();
$host = 'localhost';
$dbname = 'korochki_db';
$user = 'root';
$pass = '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
?>