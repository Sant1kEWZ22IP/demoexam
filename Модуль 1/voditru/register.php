<?php
// Обработка регистрации пользователя
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('db.php');
    $con->query("INSERT INTO users (login, password, fullname, phone, email) VALUES ('{$_POST['login']}', '{$_POST['password']}', '{$_POST['fullname']}', '{$_POST['phone']}', '{$_POST['email']}')") or die('Ошибка: ' . $con->error); 
    header('Location: login.php');}?> <!-- Перенаправление на окно входа -->
<!DOCTYPE html>
<html lang='ru'>
<head>
    <meta charset='UTF-8'>
    <title>Регистрация - Водить.РФ</title>
    <link rel='stylesheet' href='styles/style.css'>
</head>
<body>
    <a href='index.php'>◄ На главную</a> <!-- Переход на главную страницу -->
    <h1>Регистрация</h1></br>
    <h2>Создайте аккаунт для составления заявки</h2></br>
    <!-- Форма регистрации -->
    <form method='POST'>
        <label>ФИО*</label></br>
        <input type='text' name='fullname' required></br></br>
        <label>Телефон*</label></br>
        <input type='tel' name='phone' placeholder='+7(___)___-__-__' maxlength='16' required></br></br>
        <label>Email*</label></br>
        <input type='email' name='email' required></br></br>
        <label>Логин* (латиница, от 6 символов)</label></br>
        <input type='text' name='login' pattern='[a-zA-Z0-9\s]{6,}' required></br></br>
        <label>Пароль* (от 8 символов)</label></br>
        <input type='password' name='password' minlength='6' required></br></br>
        <button type='submit'>Зарегистрироваться</button></br>
    </form>
    <p>Уже есть аккаунт? <a href='login.php'>Войти</a></p> <!-- Переход на login.php -->
</body>
</html>