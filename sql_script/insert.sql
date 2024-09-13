-- Вставка данных в таблицу Materials
INSERT INTO materials (name, material_type, price)
VALUES
    ('Бетон', 'Твердый', 50.99),
    ('Кирпичи', 'Твердый', 0.75),
    ('Сталь', 'Металл', 120.49),
    ('Стекло', 'Прозрачный', 25.99);

-- Вставка данных в таблицу Inventory
INSERT INTO inventory (material_id, stock_quantity)
VALUES
    (1, 100),
    (2, 500),
    (3, 75),
    (4, 200);

-- Вставка данных в таблицу Construction_Orders
INSERT INTO construction_orders (material_id, customer_name, order_date, quantity)
VALUES
    (1, 'Иван Иванов', '2023-12-01', 3),
    (2, 'Мария Сидорова', '2023-12-05', 5),
    (3, 'Петр Петров', '2023-12-08', 2),
    (4, 'Анна Иванова', '2023-12-10', 4);
