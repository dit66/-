<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
$pdo = new PDO("mysql:host=localhost;dbname=korochki_db;charset=utf8", "root", "");

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

$error = '';

// Регистрация
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    if ($pdo->exec($sql)) {
        $error = "Регистрация успешна! Теперь войдите.";
    } else {
        $error = "Ошибка регистрации.";
    }
}

// Вход
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $pdo->query($sql);
    $user = $result->fetch();
    
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Неверный логин или пароль.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Вход / Регистрация</title>
</head>
<body>
    <h2>Вход</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Логин" required><br><br>
        <input type="password" name="password" placeholder="Пароль" required><br><br>
        <button type="submit" name="login">Войти</button>
    </form>

    <h2>Регистрация</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Логин" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Пароль" required><br><br>
        <button type="submit" name="register">Зарегистрироваться</button>
    </form>
    <p style="color:red"><?php echo $error; ?></p>
</body>
</html>