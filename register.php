<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Пароли не совпадают.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Подготовка запроса для регистрации
        $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $hashed_password);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $conn->insert_id; // Идентификатор нового пользователя
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Ошибка регистрации.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="register-container">
        <h2>Регистрация</h2>
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="email">Электронная почта:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Подтвердите пароль:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <?php if (isset($error)) { ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php } ?>

            <button type="submit">Зарегистрироваться</button>
            <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
        </form>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let theme = localStorage.getItem('theme') || 'light'; // по умолчанию светлая тема
            document.body.classList.add(theme + '-theme');

            const themeToggle = document.querySelector('.theme-toggle');
            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    if (theme === 'light') {
                        theme = 'dark';
                    } else {
                        theme = 'light';
                    }

                    document.body.classList.remove('light-theme', 'dark-theme');
                    document.body.classList.add(theme + '-theme');
                    localStorage.setItem('theme', theme);
                });
            }
        });
    </script>

</body>
</html>
