<?php
// login.php
session_start();
include 'db_connect.php';

// Получение данных из POST
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

try {
    // Создание объекта PDO и попытка подключения к базе данных
    $db = getDbConnection($username, $password);

    // Если подключение успешно, сохраняем имя пользователя и пароль в сессии и перенаправляем на дашборд
    $_SESSION['username'] = $username;
    $_SESSION['password'] = $password;
    header('Location: dashboard.php');
    exit;
} catch (PDOException $e) {
    // Обработка ошибки подключения
    $error_message = "Ошибка подключения: " . $e->getMessage();
    header("Location: index.php?error_message=" . urlencode($error_message));
    exit(); // Перенаправление, чтобы остановить выполнение остального кода

}
?>
