<?php
session_start();
include 'db.php';

// Проверка на авторизацию
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель задач</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <button id="theme-toggle" class="theme-toggle">🌙 Темная тема</button>
    <div class="tasks-container">
        <h2>Ваши задачи</h2>
        <a href="add_task.php">Добавить задачу</a>
        <table>
            <thead>
                <tr>
                    <th>Задача</th>
                    <th>Описание</th>
                    <th>Дата</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($task = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($task['title']); ?></td>
                        <td><?php echo htmlspecialchars($task['description']); ?></td>
                        <td class="due-date"
                            data-status="<?php echo $task['status']; ?>"
                            data-date="<?php echo $task['due_date']; ?>">
                            <?php echo htmlspecialchars($task['due_date']); ?>
                        </td>
                        <td>
                            <?php echo $task['status'] == 1 ? 'Завершена' : 'Не завершена'; ?>
                        </td>
                        <td>
                            <?php if ($task['status'] == 0): ?>
                                <a href="complete_task.php?id=<?php echo $task['id']; ?>">Завершить</a> |
                            <?php endif; ?>
                            <a href="delete_task.php?id=<?php echo $task['id']; ?>">Удалить</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>

        </table>
        <!-- Кнопка выхода -->
        <form action="logout.php" method="POST">
            <button type="submit" class="logout-btn">Выйти</button>
        </form>
    </div>

    <script>
document.addEventListener("DOMContentLoaded", function () {
    const dueDates = document.querySelectorAll(".due-date");

    dueDates.forEach(cell => {
        const dueDateStr = cell.getAttribute("data-date");
        const status = parseInt(cell.getAttribute("data-status")); // 0 или 1
        const dueDate = new Date(dueDateStr);
        const today = new Date();
        today.setHours(0, 0, 0, 0); // обнуляем время

        if (status === 0 && dueDate < today) {
            // если задача НЕ завершена и дата просрочена
            cell.style.color = "red";
            cell.style.fontWeight = "normal";
        }
    });
});
</script>

</body>
</html>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById("theme-toggle");

    // Применение сохранённой темы
    if (localStorage.getItem("theme") === "dark") {
        document.body.classList.add("dark-theme");
        toggleBtn.textContent = "🌞 Светлая тема";
    }

    // Переключатель
    toggleBtn.addEventListener("click", function () {
        document.body.classList.toggle("dark-theme");

        if (document.body.classList.contains("dark-theme")) {
            localStorage.setItem("theme", "dark");
            toggleBtn.textContent = "🌞 Светлая тема";
        } else {
            localStorage.setItem("theme", "light");
            toggleBtn.textContent = "🌙 Темная тема";
        }
    });
});
</script>
