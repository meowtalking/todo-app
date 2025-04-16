<?php
session_start();
session_unset();
session_destroy();

setcookie('remember_email', '', time() - 3600, "/"); // Удаляем cookie

header("Location: login.php");
exit;
?>
