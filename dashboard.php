<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
$pdo = new PDO("mysql:host=localhost;dbname=korochki_db;charset=utf8", "root", "");

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

// Создание заявки
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_request'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    $sql = "INSERT INTO requests (user_id, title, description) VALUES ($user_id, '$title', '$description')";
    $pdo->exec($sql);
    $message = "Заявка создана!";
}

// Получение заявок пользователя
$sql = "SELECT * FROM requests WHERE user_id = $user_id ORDER BY created_at DESC";
$result = $pdo->query($sql);
$requests = $result->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет</title>
</head>
<body>
    <h2>Добро пожаловать, <?php echo $_SESSION['username']; ?></h2>
    <a href="logout.php">Выйти</a>
    <?php if ($_SESSION['role'] == 'admin'): ?>
        | <a href="admin.php">Панель админа</a>
    <?php endif; ?>

    <h3>Создать новую заявку</h3>
    <form method="post">
        <input type="text" name="title" placeholder="Тема заявки" required><br><br>
        <textarea name="description" placeholder="Описание" rows="4" cols="50" required></textarea><br><br>
        <button type="submit" name="create_request">Отправить</button>
    </form>
    <p style="color:green"><?php echo $message; ?></p>

    <h3>Мои заявки</h3>
    <table> <border="1" cellpadding="8">
        <tr>
            <th>ID</th><th>Тема</th><th>Описание</th><th>Статус</th><th>Дата</th>
        </tr>
        <?php foreach ($requests as $req): ?>
        <tr>
            <td><?php echo $req['id']; ?></td>
            <td><?php echo $req['title']; ?></td>
            <td><?php echo $req['description']; ?></td>
            <td><?php echo $req['status']; ?></td>
            <td><?php echo $req['created_at']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>