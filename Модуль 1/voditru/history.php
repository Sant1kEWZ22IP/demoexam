<?php
session_start();
if(isset($_GET['index'])) {// Обработка выхода 
session_destroy(); // Уничтожаем сессию
header('Location:index.php'); // Перенаправляем на главную
exit;}
// Проверяем, авторизован ли пользователь
if(!isset($_SESSION['user_id'])) die('Чтобы посмотреть историю заявок, надо войти в аккаунт.');
include('db.php'); // Подключаемся к базе данных
// Обрабатываем отправку отзыва
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Обновляем отзыв в базе данных
    $con->query("UPDATE request SET review='{$_POST['review']}' WHERE id='{$_POST['request_id']}' AND user_id='{$_SESSION['user_id']}'");
}
// Получаем все заявки текущего пользователя
$query = $con->query("SELECT * FROM request WHERE user_id='{$_SESSION['user_id']}'");
if(!$query) die('query error: ' . $con->error); // Если ошибка запроса - показываем её
?>
<!DOCTYPE html>
<html>
<head>
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <!-- Шапка сайта -->
    <a href="index.php" class="logo">Водить.РФ</a></br>    
    <!-- Меню навигации -->
    <a href="history.php" class="btn-active">Мои заявки</a>
    <a href="create.php" class="btn-create">Новая заявка</a>
    <a href="?index=1" class="btn-exit">Выйти</a></br></br>
    <!-- Основной контент -->
    <h1>История заявок</h1></br>
    <h2>История Ваших заявок</h2>
    <?php $i = 0; // Счетчик заявок
    // Показываем все заявки пользователя
    while($request = $query->fetch_assoc()) {
        $i++; // Увеличиваем счетчик
        echo    "<h3>Заявка $i</h3></br>
                <!-- Основная информация о заявке -->
                <b>Дата начала: </b>{$request['date']} </br>
                <b>Вид услуги: </b>{$request['curses']} </br>
                <b>Тип оплаты: </b>{$request['payment']} </br> 
                <b>Статус: </b>{$request['status']} <br>";
        // Если отзыв уже есть - показываем его
        if(!empty($request['review'])) {
            echo "<b>Ваш отзыв: </b>{$request['review']}";}
        // Если обучение завершено - показываем форму для отзыва
        if($request['status'] === 'Обучение завершено') {
            echo    "<form method='POST'>
                    <b>Оставить отзыв</b>
                    <!-- Скрытое поле с ID заявки для обработки формы -->
                    <input type='hidden' name='request_id' value='{$request['id']}'>
                    <!-- Поле для ввода отзыва -->
                    <input name='review' placeholder='Отзыв об услуге' value='{$request['review']}'></br>
                    <button class='btn-sub'>Оставить отзыв</button>
                    </form>";}
    }
    // Если заявок нет - показываем сообщение
    if($i === 0) {echo "<p>У вас пока нет заявок</p>";}?>
</body>
</html>