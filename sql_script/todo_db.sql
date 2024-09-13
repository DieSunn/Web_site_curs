-- Создание таблицы Пользователей
CREATE TABLE Users (
    user_id SERIAL PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Создание таблицы Задач
CREATE TABLE Tasks (
    task_id SERIAL PRIMARY KEY,
    user_id INT REFERENCES Users(user_id),
    task_name VARCHAR(255) NOT NULL,
    description TEXT,
    due_date DATE,
    status VARCHAR(50) DEFAULT 'в процессе'
);
