<?php
include "db.php";
session_start();

$email = $_POST['email'];
$password = $_POST['password'];
$remember = isset($_POST['remember']);

// Запрос пользователя
$stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];

        // Установка cookie
        if ($remember) {
            setcookie("remember_email", $email, time() + (86400 * 30)); // 30 дней
        } else {
            setcookie("remember_email", "", time() - 3600); // удалить
        }

        header("Location: dashboard.php");
        exit;
    }
}

echo "Неверный email или пароль.";
