<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
$pdo = new PDO("mysql:host=localhost;dbname=korochki_db;charset=utf8", "root", "");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit();
}

// Изменение статуса заявки
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $request_id = $_POST['request_id'];
    $new_status = $_POST['status'];
    
    $sql = "UPDATE requests SET status = '$new_status' WHERE id = $request_id";
    $pdo->exec($sql);
}

// Получение всех заявок
$sql = "SELECT r.*, u.username FROM requests r JOIN users u ON r.user_id = u.id ORDER BY r.created_at DESC";
$result = $pdo->query($sql);
$all_requests = $result->fetchAll();

// Получение всех пользователей
$users = $pdo->query("SELECT id, username, email, role FROM users")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Админ-панель</title>
</head>
<body>
    <h2>Панель администратора</h2>
    <a href="dashboard.php">В личный кабинет</a> | <a href="logout.php">Выйти</a>

    <h3>Все заявки</h3>
    <table> <border="1" cellpadding="8">
        <tr>
            <th>ID</th><th>Пользователь</th><th>Тема</th><th>Описание</th><th>Статус</th><th>Дата</th><th>Действие</th>
        </tr>
        <?php foreach ($all_requests as $req): ?>
        <tr>
            <td><?php echo $req['id']; ?></td>
            <td><?php echo $req['username']; ?></td>
            <td><?php echo $req['title']; ?></td>
            <td><?php echo $req['description']; ?></td>
            <td>
                <form method="post" style="margin:0">
                    <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
                    <select name="status">
                        <option value="new" <?php echo $req['status']=='new'?'selected':''; ?>>Новая</option>
                        <option value="in_progress" <?php echo $req['status']=='in_progress'?'selected':''; ?>>В работе</option>
                        <option value="completed" <?php echo $req['status']=='completed'?'selected':''; ?>>Завершена</option>
                    </select>
                    <button type="submit" name="update_status">Обновить</button>
                </form>
            </td>
            <td><?php echo $req['created_at']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h3>Все пользователи</h3>
    <table> <border="1" cellpadding="8">
        <tr><th>ID</th><th>Логин</th><th>Email</th><th>Роль</th></tr>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?php echo $u['id']; ?></td>
            <td><?php echo $u['username']; ?></td>
            <td><?php echo $u['email']; ?></td>
            <td><?php echo $u['role']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>