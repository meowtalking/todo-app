<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $task_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("UPDATE tasks SET status = 1 WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $user_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php?success=1");
        exit;
    } else {
        echo "Ошибка при обновлении задачи.";
    }
} else {
    echo "ID задачи не указан.";
}
?>
