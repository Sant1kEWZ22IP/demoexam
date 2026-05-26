<?php
session_start();

if(!isset($_SESSION['user_id'])) {
    die('Чтобы посмотреть историю заявок, надо войти в аккаунт.');
}

include('db.php');

// Обработка отправки отзыва
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request_id']) && isset($_POST['review'])) {
    $request_id = intval($_POST['request_id']);
    $review = trim($_POST['review']);
    $user_id = intval($_SESSION['user_id']);
    
    $stmt = $con->prepare("UPDATE request SET review = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sii", $review, $request_id, $user_id);
    $stmt->execute();
    $stmt->close();
}

// Получаем заявки пользователя
$user_id = intval($_SESSION['user_id']);
$stmt = $con->prepare("SELECT * FROM request WHERE user_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Обработка выхода ДО вывода HTML
if(isset($_GET['index'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="icon" href="assets/boat.png">
</head>
<body>
    <div class="header">
        <div class="nav">
            <a href="index.php" class="logo"><img src="assets/boat.png"></a>  
            <div class="nav-buttons">
                <a href="history.php" class="btn-active">Мои заявки</a>
                <a href="create.php" class="btn-create">Новая заявка</a>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="booking-card">
            <a href="?index=1" class="btn-exit">Выйти</a>
            <div class="booking-header"><h1>История заявок</h1></div>
            <?php 
            $i = 0;
            while($request = $result->fetch_assoc()) {
                $i++;
                // Экранирование вывода
                $date = htmlspecialchars($request['date']);
                $curses = htmlspecialchars($request['curses']);
                $payment = htmlspecialchars($request['payment']);
                $status = htmlspecialchars($request['status']);
                $review = htmlspecialchars($request['review']);
                $id = intval($request['id']);
                ?>
                <div class='request-card'>
                    <h2 style='text-align:center'>Заявка <?= $i ?></h2>
                    <b>Дата начала: </b><?= $date ?><br>
                    <b>Вид услуги: </b><?= $curses ?><br>
                    <b>Тип оплаты: </b><?= $payment ?><br>
                    <b>Статус: </b><?= $status ?><br>
                    
                    <?php if(!empty($review)): ?>
                        <b>Ваш отзыв: </b><?= $review ?><br>
                    <?php endif; ?>
                    
                    <?php if($status === 'Обучение завершено'): ?>
                        <form method='POST'>
                            <b>Оставить отзыв</b>
                            <input type='hidden' name='request_id' value='<?= $id ?>'>
                            <input name='review' placeholder='Отзыв об услуге' value='<?= $review ?>'>
                            <button class='btn-sub'>Сохранить отзыв</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php 
            }
            if($i === 0) {
                echo "<div class='request-card'><p>У вас пока нет заявок</p></div>";
            }
            ?>
        </div>
    </div>
    <!-- Маленький футер -->
    <footer class="footer footer-small">
    <div class="footer-container">
        <div class="footer-small-content">
            <p class="footer-copyright">© <?= date('Y') ?> Водить.РФ. Все права защищены.</p>
        </div>
    </div>
</footer>
</body>
</html> 