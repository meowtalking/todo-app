<?php
$host = 'localhost';
$user = 'root';
$password = 'root';
$dbname = 'todo-app';

// Шаг 1: Подключение к MySQL без выбора базы данных
$conn = new mysqli($host, $user, $password);

// Проверка соединения
if ($conn->connect_error) {
    die('Ошибка подключения: ' . $conn->connect_error);
}

// Шаг 2: Создание базы данных, если она не существует
$conn->query("CREATE DATABASE IF NOT EXISTS `$dbname`");

// Шаг 3: Переключение на эту базу
$conn->select_db($dbname);

// Шаг 4: Создание таблицы пользователей
$conn->query("
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    )
");

// Шаг 5: Создание таблицы задач
$conn->query("
    CREATE TABLE IF NOT EXISTS tasks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        due_date DATE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )
");

$conn->close();
?>
