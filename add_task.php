<?php
session_start();
include 'db.php';

// Проверка на авторизацию
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Если форма отправлена
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];

    // Подготовка запроса для вставки задачи в базу данных
    $stmt = $conn->prepare("INSERT INTO tasks (title, description, due_date, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $title, $description, $due_date, $user_id);
    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Ошибка при добавлении задачи.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить задачу</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Добавить задачу</h2>
        <form action="add_task.php" method="POST">
            <div class="form-group">
                <label for="title">Название задачи:</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="description">Описание задачи:</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="due_date">Дата выполнения:</label>
                <input type="date" id="due_date" name="due_date" required>
            </div>

            <?php if (isset($error)) { ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php } ?>

            <button type="submit">Добавить задачу</button>
            <a href="dashboard.php" class="cancel-link">Отмена</a>
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

