<?php
session_start(); // Начинаем сессию для доступа к данным пользователя
if($_SERVER['REQUEST_METHOD'] == 'POST') { //обработка отправки формы
    include('db.php');
    $con->query("INSERT INTO request (date, curses, payment, user_id) VALUES ('{$_POST['date']}', '{$_POST['curses']}', '{$_POST['payment']}', '{$_SESSION['user_id']}')") or die('Ошибка: ' . $con->error);
    header('Location: history.php'); //перемещение на страничку истории
    exit;}?>
<html>
<head>
    <title>Создание заявки</title>
    <link rel = "stylesheet" href = "styles/style.css"></head>
<body>
    <!-- Шапка сайта -->
    <a href = "index.php">Водить.РФ</a></br>
    <a href = "history.php">Мои заявки</a></br>
    <a href = "create.php">Новая заявка</a></br>
    <!-- Основной контент -->
    <h1>Создание заявки</h1></br>
    <h2>Создание заявки на обучение. Укажите курс, удобное время начала и способ оплаты</h2></br>
         <!-- Форма создания заявки -->
    <form method="POST" class = "form-group">
        <label for='curses'>Название курса</label></br>
        <select required name="curses">
            <option value="Управление катером">Управление катером</option>
            <option value="Управление круизным лайнером">Управление круизным лайнером</option>
            <option value="Управление яхтой">Управление яхтой</option>
            <option value="Управление лодкой">Управление лодкой</option>
        </select></br></br>
        <label>Дата начала</label></br><input type="datetime-local" name="date"></br></br>
        <label>Способ оплаты</label></br>
        <select required name="payment">
            <option value="Наличными">Наличными</option>
            <option value="Терминалом (по карте)">Картой</option>
            <option value="СБП (по QR-коду)">СБП</option>
        </select></br></br>
        <button>Отправить</button>
    </form>
</body>
</html>