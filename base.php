<?php
session_start(); // Начало сессии
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
?>
<!-- base.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo $title ?? 'Flowers DB'; ?></title>
    <link rel="stylesheet" href="static/css/base.css">
    <link href="static/css/dashboard.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Остальные общие элементы head -->
</head>

<body>
    <div class="content">
        <?php include 'navbar.php'; ?>
        <?php echo $content; ?>
    </div>
    <script>
        // Переменная для хранения текущей активной вкладки
        let currentTabId = '';
        // Переменная для хранения текущей страницы
        let currentPage = 1;
        
        
        var lastPage = 1;
        var currentSortField = '';
        var currentSortOrder = 'ASC';
        
        const tableStructures = {
            materials: {
                primaryKey: 'material_id',
                fields: ['name', 'material_type', 'price']
            },
            inventory: {
                primaryKey: 'inventory_id',
                fields: ['material_id', 'stock_quantity'
            ]
            },
            construction_orders: {
                primaryKey: 'order_id',
                fields: ['customer_name', 'order_date', 'quantity', 'material_id']
            },
            
            // Другие таблицы...
        };

    // Русские названия столбцов
const russianColumnNames = {
    'name'  : 'Название материала',
    'material_type'  : 'Тип материала',
    'price'  : 'Цена',
    'material_id'  : 'Номер материала',
    'stock_quantity'  : 'Остаток на складе',
    'customer_name'  : 'Имя заказчика',
    'order_date'  : 'Дата заказа',
    'quantity'  : 'Количество',
    'material_id'  : 'Номер материала',
    'inventory_id'  : 'Номер записи',
    'order_id'  : 'Номер заказа'
    // Другие русские названия столбцов...
};
    </script>


    <script src="static/js/main.js"></script>
    <!-- Остальные общие элементы footer -->
</body>

</html>