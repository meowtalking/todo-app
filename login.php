<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Подготовка запроса для авторизации
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            
            // Запоминаем пользователя, если установлена галочка
            if (isset($_POST['remember_me'])) {
                setcookie('email', $email, time() + (86400 * 30), "/"); // cookie на 30 дней
            }
            
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Неверный пароль.";
        }
    } else {
        $error = "Пользователь не найден.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2>Войти</h2>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email">Электронная почта:</label>
                <input type="email" id="email" name="email" value="<?php echo isset($_COOKIE['email']) ? $_COOKIE['email'] : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <input type="checkbox" id="remember_me" name="remember_me" <?php echo isset($_COOKIE['email']) ? 'checked' : ''; ?>>
                <label for="remember_me">Запомнить меня</label>
            </div>


            <?php if (isset($error)) { ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php } ?>

            <button type="submit">Войти</button>
            <p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
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
