<?php
include('db.php');
session_start();
if(!$_SESSION['admin']) die('Чтобы посмотреть панель администратора, надо войти в его аккаунт.');
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$query = $con->query("UPDATE request SET status='{$_POST['status']}' WHERE id={$_POST['request_id']}");
	if(!$query) die('update error: ' . $con->error);}
$query = $con->query("SELECT request.*, users.login, users.fullname FROM request INNER JOIN users WHERE request.user_id = users.id");
if(!$query) die('query error: ' . $con->error);
if (isset($_GET['index'])){ 
            session_destroy();
            header('Location:index.php');
            exit;}?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Панель Администратора</title>
            <link rel = "stylesheet" href = "styles/style.css">
    </head>
    <body>
        <div class='header'>
            <div class = "nav">
                <a href = "index.php" class = "logo">Водить.РФ</a>
                <div class = "nav-buttons">
                    <a href = "admin.php" class = "btn-active">Панель администратора</a>
                    <a href = "?index=1" name = "index" class = "btn-exit">Выход</a>
                </div>
            </div> 
        </div>
        <div class='container'>
            <div class='admin-card'>
                <div class='admin-header'>
                    <h1>Панель администратора</h1>
                    <p>Управление заявками пользователей</p>
                </div>
                <?php
                $i = 0;
                while($request = $query->fetch_assoc()) {
                    $i++;
                    echo "
                    <div class = 'request-card'>
                        <h2>Заявка $i от {$request['login']}</h2>
                        <b>ФИО: </b>{$request['fullname']}<br>
                        <b>Дата: </b>{$request['date']}<br>
                        <b>Вид услуги: </b>{$request['curses']}<br>
                        <b>Тип оплаты: </b>{$request['payment']}<br><br>
                        <b>Комментарий пользователя: </b>{$request['review']}<br>
                        <form action='' method='POST'>
                            <input type='hidden' name='request_id' value='{$request['id']}'>
                            <select name='status'>
                                <option " . ($request['status'] == 'Новая' ? 'selected' : '') . " value='Новая'>Новая</option>
                                <option " . ($request['status'] == 'Идет обучение' ? 'selected' : '') . " value='Идет обучение'>Идет обучение</option>
                                <option " . ($request['status'] == 'Обучение завершено' ? 'selected' : '') . " value='Обучение завершено'>Обучение завершено</option>
                            </select>
                            <button type='submit' class = 'btn-sub'>Сохранить</button>
                        </form>
                    </div>";}?>
            </div>
        </div>
    </body>
</html>