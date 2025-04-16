<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'todo-app';

// Подключение к MySQL (без выбора базы)
$conn = new mysqli($host, $user, $password);

// Проверка соединения
if ($conn->connect_error) {
    die('Ошибка подключения: ' . $conn->connect_error);
}

// Создание базы данных, если не существует
$conn->query("CREATE DATABASE IF NOT EXISTS `$dbname`");

// Подключение к нужной базе
$conn->select_db($dbname);

// Создание таблиц, если не существуют
$conn->query("
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    )
");

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
