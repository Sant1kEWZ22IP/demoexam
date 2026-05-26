<?php
include('db.php');
session_start();

if(!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    die('Чтобы посмотреть панель администратора, надо войти в аккаунт администратора.');
}

// Обработка изменения статуса
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request_id']) && isset($_POST['status'])) {
    $request_id = intval($_POST['request_id']);
    $status = trim($_POST['status']);
    
    $allowed_statuses = ['Новая', 'Идет обучение', 'Обучение завершено'];
    if(in_array($status, $allowed_statuses)) {
        $stmt = $con->prepare("UPDATE request SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $request_id);
        $stmt->execute();
        $stmt->close();
    }
    // После обновления перенаправляем, чтобы избежать повторной отправки
    header('Location: admin.php' . (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : ''));
    exit;
}

// Получаем параметры фильтрации
$filter_login = isset($_GET['filter_login']) ? trim($_GET['filter_login']) : '';
$filter_status = isset($_GET['filter_status']) ? trim($_GET['filter_status']) : '';

// Формируем запрос с фильтрацией
$sql = "
    SELECT request.*, users.login, users.fullname 
    FROM request 
    INNER JOIN users ON request.user_id = users.id
    WHERE 1=1
";

$params = [];
$types = "";

if(!empty($filter_login)) {
    $sql .= " AND users.login LIKE ?";
    $params[] = "%" . $filter_login . "%";
    $types .= "s";
}

if(!empty($filter_status)) {
    $sql .= " AND request.status = ?";
    $params[] = $filter_status;
    $types .= "s";
}

$sql .= " ORDER BY request.id DESC";

$stmt = $con->prepare($sql);
if(!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Обработка выхода
if (isset($_GET['index'])) { 
    session_destroy();
    header('Location: index.php');
    exit;
}

$statuses = ['Новая', 'Идет обучение', 'Обучение завершено'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель Администратора</title>
    <link rel="icon" href="assets/boat.png">
    <link rel="stylesheet" href="styles/style.css">
    <script src="script/filter.js" defer></script>
</head>
<body>
    <div class='header'>
        <div class="nav">
            <a href="index.php" class="logo"><img src="assets/boat.png"></a>
            <div class="nav-buttons">
                <a href="admin.php" class="btn-active">Панель администратора</a>
                <a href="?index=1" class="btn-exit">Выход</a>
            </div>
        </div> 
    </div>
    <div class='container'>
        <div class='admin-card'>
            <div class='admin-header'>
                <h1>Панель администратора</h1>
                <p>Управление заявками пользователей</p>
            </div>
            
            <!-- Форма фильтрации -->
            <form method="GET" class="filter-form" id="filterForm">
                <div class="filter-group">
                    <label>Фильтр по логину:</label>
                    <input type="text" name="filter_login" placeholder="Введите логин" value="<?= htmlspecialchars($filter_login) ?>">
                </div>
                
                <div class="filter-group">
                    <label>Фильтр по статусу:</label>
                    <select name="filter_status">
                        <option value="">Все статусы</option>
                        <?php foreach($statuses as $status): ?>
                            <option value="<?= $status ?>" <?= $filter_status == $status ? 'selected' : '' ?>><?= $status ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <button type="submit">Применить фильтр</button>
                </div>
                
                <div class="filter-group">
                    <a href="admin.php" class="reset-btn">Сбросить фильтр</a>
                </div>
            </form>
            
            <!-- Информация о фильтрации -->
            <?php if(!empty($filter_login) || !empty($filter_status)): ?>
                <div class="filter-info">
                    <strong>Применён фильтр:</strong>
                    <?php if(!empty($filter_login)): ?> Логин содержит "<?= htmlspecialchars($filter_login) ?>"<?php endif; ?>
                    <?php if(!empty($filter_status)): ?> Статус = "<?= htmlspecialchars($filter_status) ?>"<?php endif; ?>
                    <br>
                    <strong>Найдено заявок:</strong> <?= $result->num_rows ?>
                </div>
            <?php endif; ?>
            
            <!-- Отображение заявок -->
            <?php
            if($result->num_rows === 0) {
                echo "<div class='request-card' style='text-align: center; color: #666;'>";
                echo "Нет заявок, соответствующих фильтру";
                echo "</div>";
            }
            
            $i = 0;
            while($request = $result->fetch_assoc()) {
                $i++;
                $login = htmlspecialchars($request['login']);
                $fullname = htmlspecialchars($request['fullname']);
                $date = htmlspecialchars($request['date']);
                $curses = htmlspecialchars($request['curses']);
                $payment = htmlspecialchars($request['payment']);
                $review = htmlspecialchars($request['review']);
                $status = htmlspecialchars($request['status']);
                $id = intval($request['id']);
                ?>
                <div class='request-card'>
                    <h2>Заявка №<?= $id ?> от <?= $login ?></h2>
                    <b>ФИО: </b><?= $fullname ?><br>
                    <b>Дата: </b><?= $date ?><br>
                    <b>Вид услуги: </b><?= $curses ?><br>
                    <b>Тип оплаты: </b><?= $payment ?><br><br>
                    <b>Комментарий пользователя: </b><?= $review ?: '—' ?><br><br>
                    
                    <form action='' method='POST' class="status-form">
                        <input type='hidden' name='request_id' value='<?= $id ?>'>
                        <select name='status'>
                            <?php foreach($statuses as $s): ?>
                                <option <?= $status == $s ? 'selected' : '' ?> value='<?= $s ?>'><?= $s ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type='submit' class='btn-sub'>Сохранить</button>
                    </form>
                </div>
            <?php } ?>
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