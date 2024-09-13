<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
include 'db_connect.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tab = $_GET['tab'] ?? 'tab-1';

    $table=getTable($tab);
    $primaryKey=getPK($tab);
    $id = $_POST['id'] ?? null;

    $username = $_SESSION['username'];
    $password = $_SESSION['password']; // Это должен быть хеш, а не открытый пароль

    try {
        $pdo = getDbConnection($username, $password);

        // Подготовка данных для SQL-запроса
        $parameters = [];
        $updateParts = [];
        foreach ($_POST as $key => $value) {
            if ($key != 'id' && $key != 'primaryKey' && $key != 'table') {
                
                $updateParts[] = "$key = :$key";
                $parameters[$key] = $value;
            }
        }
        $updateString = implode(', ', $updateParts);
        $sql = "UPDATE $table SET $updateString WHERE $primaryKey = :id";
        $stmt = $pdo->prepare($sql);
        error_log($sql);
        foreach ($parameters as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(':id', $id);
        // Логирование сформированного SQL-запроса
        error_log("Executing SQL: $sql with parameters: " . json_encode($parameters));
        $stmt->execute();
        error_log("Affected rows");
        error_log($stmt->rowCount());
        echo json_encode(['status' => 'success', 'message' => 'Запись успешно обновлена']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => "Ошибка обновления: " . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Неверный тип запроса']);
}
?>
