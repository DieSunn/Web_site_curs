<?php
// db_connect.php
$host = 'localhost';
$dbname = 'test_curs';
$port = '5432';

$tableMappings = [
    'materials' => [
        'columns' => ['name', 'material_type', 'price'],
        'primaryKey' => 'material_id'
    ],
    'inventory' => [
        'columns' => ['material_id', 'stock_quantity'],
        'primaryKey' => 'inventory_id'
    ],
    'construction_orders' => [
        'columns' => ['material_id', 'customer_name', 'order_date', 'quantity'],
        'primaryKey' => 'order_id'
    ],
    // Другие таблицы и столбцы...
];

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// функция получения объекта БД
function getDbConnection($username, $password)
{
    global $host, $dbname, $port, $options;
    return new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$username;password=$password", null, null, $options);
}

function getTableMappings()
{
    global $tableMappings; // Добавляем это для доступа к глобальной переменной

    return $tableMappings;
}

// функция получения названия таблицы в зависимости от выбранной вкладки
function getTable($tab)
{
    switch ($tab) {
        case 'tab-1':
            $table = 'materials';
            break;
        case 'tab-2':
            $table = 'inventory';
            break;
        case 'tab-3':
            $table = 'construction_orders';
            break;
    }
    return $table;
}

// функция получения названия таблицы в зависимости от выбранной вкладки
function getPk($tab)
{
    global $tableMappings; // Добавляем это для доступа к глобальной переменной
    switch ($tab) {
        case 'tab-1':
            $primaryKey = $tableMappings['materials']['primaryKey'];
            break;
        case 'tab-2':
            $primaryKey = $tableMappings['inventory']['primaryKey'];
            break;
        case 'tab-3':
            $primaryKey = $tableMappings['construction_orders']['primaryKey'];
            break;
    }
    return $primaryKey;
}


// функция получения запроса для выборки всех данных
function getSQL($tab)
{
    switch ($tab) {
        case 'tab-1':
            $sql = "SELECT * FROM materials";
            break;
        case 'tab-2':
            $sql = "SELECT * FROM inventory";
            break;
        case 'tab-3':
            $sql = "SELECT * FROM construction_orders";
            break;
    }
    return $sql;
}
