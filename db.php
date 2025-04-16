<?php
$host = 'localhost';
$username = 'root';
$password = 'root';
$database = 'todo_app';
$conn = new mysqli($host, $username, $password, $database);

// Проверка на ошибки соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
