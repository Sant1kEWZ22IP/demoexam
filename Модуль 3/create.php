<?php
session_start();

// Проверка авторизации
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('db.php');
    
    // Получаем и очищаем данные
    $review = trim($_POST['review'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $curses = trim($_POST['curses'] ?? '');
    $payment = trim($_POST['payment'] ?? '');
    $user_id = intval($_SESSION['user_id']);
    
    // Белый список для курсов и оплаты
    $allowed_curses = ['Управление катером', 'Управление круизным лайнером', 'Управление яхтой', 'Управление лодкой'];
    $allowed_payments = ['Наличными', 'Терминалом (по карте)', 'СБП (по QR-коду)'];
    
    if(!in_array($curses, $allowed_curses)) {
        die('Некорректное значение курса');
    }
    if(!in_array($payment, $allowed_payments)) {
        die('Некорректное значение оплаты');
    }
    
    // Подготовленный запрос
    $stmt = $con->prepare("INSERT INTO request (review, date, curses, payment, user_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $review, $date, $curses, $payment, $user_id);
    
    if($stmt->execute()) {
        header('Location: history.php');
        exit;
    } else {
        die('Ошибка: ' . htmlspecialchars($stmt->error));
    }
    $stmt->close();
}
?>
<html>
<head>
    <title>Создание заявки</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="icon" href="assets/boat.png">
</head>
<body>
<div class="header"> 
    <div class="nav">
        <a href="index.php" class="logo"><img src="assets/boat.png"></a>
        <div class="nav-buttons">
            <a href="history.php" class="btn-lk">Мои заявки</a>
            <a href="create.php" class="btn-active">Новая заявка</a>
        </div>
    </div>
</div>
<div class="container">
    <div class="booking-card">
        <div class="booking-header"><h1>Создание заявки</h1></div>
        <form method="POST" class="form-group">
            <label for='curses'>Выберите транспорт для обучения</label>
            <select required name="curses">
                <option value="Управление катером">Управление катером</option>
                <option value="Управление круизным лайнером">Управление круизным лайнером</option>
                <option value="Управление яхтой">Управление яхтой</option>
                <option value="Управление лодкой">Управление лодкой</option>
            </select>
            
            <label>Дата начала</label>
            <input type="datetime-local" name="date">
            
            <label>Способ оплаты</label>
            <select required name="payment">
                <option value="Наличными">Наличные</option>
                <option value="Терминалом (по карте)">Картой</option>
                <option value="СБП (по QR-коду)">СБП (по QR-коду)</option>
            </select>
            
            <label>Комментарий</label>
            <textarea name="review" rows="3" placeholder="Ваш комментарий (необязательно)"></textarea>
            
            <button class="btn-sub">Отправить</button>
        </form>
    </div>
</div>
<!-- Маленький футер -->
<footer class="footer footer-small">
    <div class="footer-container">
        <div class="footer-small-content">
            <p class="footer-copyright">© <?= date('Y') ?> Водить.РФ Все права защищены.</p>
        </div>
    </div>
</footer>
</body>
</html>