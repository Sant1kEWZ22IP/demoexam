<?php
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('db.php');
    
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    
    if (empty($login)) {
        $error = 'Введите логин';
    } elseif (empty($password)) {
        $error = 'Введите пароль';
    } else {
        $stmt = $con->prepare("SELECT id, login, password FROM users WHERE login = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['admin'] = ($user['login'] == 'Admin26');
            header('Location: index.php');
            exit();
        } else {
            $error = 'Неверный логин или пароль';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход - Водить.РФ</title>
    <link rel="icon" href="assets/boat.png">
    <link rel='stylesheet' href='styles/style.css'>
    <script src='js/validate.js'></script>
</head>
<body class="body-form">
    <div class="login-container">
        <a href='index.php' class='index-link'>◄ На главную</a>
        <h1>Вход в систему</h1>
        <p>Войдите в свой аккаунт</p>
        
        <?php if ($error): ?>
            <div class="general-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST" id="loginForm">
            <div class="form-group">
                <label>Логин</label>
                <input type="text" name="login" value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
                <span class="error-text" id="error-login"></span>
            </div>
            
            <div class="form-group">
                <label>Пароль</label>
                <input type="password" name="password">
                <span class="error-text" id="error-password"></span>
            </div>
            
            <button type="submit" class="btn-sub">Войти</button>
        </form>
        
        <p>Нет аккаунта? <a href="register.php" class='register-link'>Зарегистрироваться</a></p>
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