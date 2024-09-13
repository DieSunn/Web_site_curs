<?php
$error_message = $_GET['error_message'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Вход в систему</title>
 
    <link href="static/css/index.css" rel="stylesheet">

</head>
<body>
    <div class="background">
        <div class="pic"></div>
    </div>
    <form action="login.php" method="post">
        <h3>Вход в базу данных</h3>
        <label for="username">Имя пользователя:</label>
        <input type="text" name="username" id="username" required>
        <label for="password">Пароль:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Войти</button>
    </form>

    
    <script>
        // JavaScript код для вывода сообщения об ошибке в alert
        <?php if ($error_message) : ?>
        alert("<?php echo htmlspecialchars($error_message); ?>");
        <?php endif; ?>
    </script>

</body>
</html>
