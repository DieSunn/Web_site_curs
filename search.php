<?php
// search.php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
session_start();
include 'db_connect.php'; // Подключение к базе данных
include 'base.php';

// Проверка на наличие параметра поиска
if (!isset($_GET['query'])) {
    die('Параметры поиска не указаны.');
}

$tab = $_GET['tab'] ?? 'tab-1';
$searchQuery = $_GET['query'];
$tableName = getTable($tab);
$tableMappings = getTableMappings();
error_log("search query:$searchQuery");

try {
    $pdo = getDbConnection($_SESSION['username'], $_SESSION['password']);
    $sql = "SELECT * FROM $tableName WHERE ";
    $fields = $tableMappings[$tableName]['columns'];

    $conditions = [];
    foreach ($fields as $field) {
        $conditions[] = "CAST($field AS TEXT) LIKE ?";
    }
    $sql .= implode(' OR ', $conditions);

    error_log("Executed SQL: $sql");

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_fill(0, count($fields), "%$searchQuery%"));

    $firstRow = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$firstRow) {
        echo "<h1>Нет результатов для: \"" . htmlspecialchars($searchQuery) . "\"</h1>";
    } else {
        // Вывод результатов поиска
        echo "<h1 style='align-items: center;display: flex;align-content: stretch;justify-content: center;'>Результаты поиска: \"" . htmlspecialchars($searchQuery) . "\"</h1>";
        echo "<table class='table-glass'>";
        echo "<thead><tr>";
        foreach ($fields as $field) {
            echo "<th>" . htmlspecialchars($field) . "</th>";
        }
        echo "</tr></thead><tbody>";

        // Вывод первой строки
        echo "<tr>";
        foreach ($fields as $field) {
            echo "<td>" . htmlspecialchars($firstRow[$field]) . "</td>";
        }
        echo "</tr>";

        // Вывод остальных строк
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            foreach ($fields as $field) {
                echo "<td>" . htmlspecialchars($row[$field]) . "</td>";
            }
            echo "</tr>";
        }
        echo "</tbody></table>";
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    die("Ошибка поиска: " . $e->getMessage());
}
