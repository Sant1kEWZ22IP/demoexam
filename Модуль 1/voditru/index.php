<?php session_start();
if(isset($_GET['index'])) { 
      session_destroy(); 
      header('Location:index.php'); 
      exit;}?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Водить.РФ - Обучение управлению речным транспортом</title>
  <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<!-- Шапка сайта -->
  <a href="index.php">Водить.РФ</a></br>
  <!-- Кнопки для неавторизованных -->
  <?php if(!isset($_SESSION['user_id'])): ?>
    <a href="login.php">Войти</a></br>
    <a href="register.php">Регистрация</a></br>
  <!-- Кнопки для администратора -->
  <?php elseif($_SESSION['admin']): ?>
    <!-- Обработка кнопки выхода -->
    <a href="admin.php">Панель администратора</a></br>
    <a href="?index=1">Выход</a></br>
  <!-- Кнопки для обычных пользователей -->
  <?php else: ?>
    <a href="history.php">Мои заявки</a></br>
    <a href="create.php">Новая заявка</a></br>
  <?php endif; ?>
</body>
</html>