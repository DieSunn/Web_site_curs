<?php
// fetch_data.php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
include 'db_connect.php'; // Подключение к базе данных

$username = $_SESSION['username'];
$password = $_SESSION['password'];


$columnMappings = [
    'name' => 'Название материала',
    'material_type' => 'Тип материала',
    'price' => 'Цена',
    'material_id' => 'Номер материала',
    'stock_quantity' => 'Остаток на складе',
    'customer_name' => 'Имя заказчика',
    'order_date' => 'Дата заказа',
    'quantity' => 'Количество',
    'material_id' => 'Номер материала',
    'inventory_id' => 'Номер записи'
];

try {
    $pdo = getDbConnection($username, $password);
    $tab = $_GET['tab'] ?? 'tab-1';
    $currentTableName = getTable($tab);
    // Получение параметров сортировки из запроса
    $sortField = getPk($tab);
    $sortOrder = $_GET['sortOrder'] ?? 'ASC';
    // Определение текущей страницы и количества записей на страницу
    $page = $_GET['page'] ?? 1;
    $perPage = 10; // Можно настроить по желанию
    $offset = ($page - 1) * $perPage;
    error_log("Текущая таблица: $currentTableName"); // Логирование имени таблицы

    // Получение общего количества записей
    $countSql = "SELECT COUNT(*) FROM $currentTableName";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute();
    $totalRows = $countStmt->fetchColumn();
    $totalPages = ceil($totalRows / $perPage);
    error_log("Всего страниц: $totalPages"); // Логирование общего количества страниц

    $sql = getSQL($tab) . " ORDER BY $sortField $sortOrder LIMIT $perPage OFFSET $offset";
    error_log("SQL запрос: $sql"); // Логирование SQL запроса

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Проверяем, есть ли данные
    $firstRow = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($firstRow) {
        $columns = array_keys($firstRow);
        echo "<table class='table-glass'>";
        echo "<thead><tr>";
        foreach ($columns as $column) {
            // Проверяем, есть ли новое имя столбца в массиве columnMappings
            $displayName = isset($columnMappings[$column]) ? $columnMappings[$column] : $column;
            echo "<th>" . htmlspecialchars($displayName) . "</th>";
        }
        echo "<th id='button-header'><button class='button-glass' id='addButton' onclick='addRecord(\"$currentTableName\")'>Добавить</button></th>";
        echo "<th id='button-header'><button class='button-glass' id='addButton' onclick=\"sortTable('$sortField')\">Сортировка</button></th>";
        echo "</tr></thead><tbody>";

        // Вывод первой строки
        do {
            echo "<tr>";
            foreach ($columns as $column) {
                echo "<td>" . htmlspecialchars($firstRow[$column]) . "</td>";
            }
            echo "<td><button class='button-glass' onclick='editRecord(" . json_encode($firstRow) . ", \"$currentTableName\")'>Изменить</button></td>";
            echo "<td><button class='button-glass' onclick='deleteRecord(" . json_encode($firstRow) . ", \"$currentTableName\")'>Удалить</button></td>";
            echo "</tr>";
        } while ($firstRow = $stmt->fetch(PDO::FETCH_ASSOC));

        echo "</tbody></table>";

        // Вывод элементов управления пагинацией
        echo "<div class='pagination'>";
        for ($i = 1; $i <= $totalPages; $i++) {
            echo "<button onclick='loadPage(\"$tab\", $i)' class='" . ($page == $i ? 'active' : '') . "'>$i</button>";
        }
        echo "</div>";
    } else {
        echo "Нет данных для отображения. <th id='button-header'><button class='button-glass' id='addButton' onclick='addRecord(\"$currentTableName\")'>Добавить</button></th>";
        error_log("Нет данных для отображения на странице $page"); // Логирование отсутствия данных

    }
} catch (PDOException $e) {
    error_log("Ошибка подключения: " . $e->getMessage()); // Логирование ошибки подключения

    die("Ошибка подключения: " . $e->getMessage());
}
