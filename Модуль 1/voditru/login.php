<?php
// Обработка входа пользователя
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('db.php');
    $query = $con->query("SELECT * FROM users WHERE login='{$_POST['login']}' AND password='{$_POST['password']}'") or die('Ошибка: ' . $con->error);
    $user = $query->fetch_assoc();
    if(!$user) die('Неверный логин или пароль');
    session_start();
    $_SESSION['user_id'] = $user['id']; // Сохраняем ID пользователя
    $_SESSION['admin'] = $user['login'] == 'Admin26'; // Проверяем, является ли админом
    header('Location: index.php');}?> <!-- Переходим на главную -->
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход - Водить.РФ</title> <!-- Загаловок видный на вкладке браузера -->
    <link rel='stylesheet' href='styles/style.css'>
</head>
<body>
    <a href='index.php' class='index-link'>◄ На главную</a></br>
    <h1>Вход в систему</h1></br>
    <h2>Войдите в свой аккаунт</h2></br>
    <!-- Форма входа -->
    <form method="POST">
        <label>Логин: </label></br>
        <input type="text" name="login" required></br></br>
        <label>Пароль: </label></br>
        <input type="password" name="password" required></br></br>
        <button type="submit">Войти</button></br>
    </form>
    <p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p> <!-- Переход на register.php -->
</body>
</html>