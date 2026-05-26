<?php
session_start();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('db.php');
    
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    $fullname = trim($_POST['fullname']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    
    if (strlen($fullname) < 2) {
        $errors['fullname'] = 'ФИО должно содержать хотя бы 2 символа';
    }
    
    if (!preg_match('/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/', $phone)) {
        $errors['phone'] = 'Телефон должен быть в формате +7(XXX)XXX-XX-XX';
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Введите корректный email (например, name@domain.ru)';
    }
    
    if (!preg_match('/^[a-zA-Z0-9]{6,}$/', $login)) {
        $errors['login'] = 'Логин: только латиница и цифры, минимум 6 символов';
    }
    
    if (strlen($password) < 8) {
        $errors['password'] = 'Пароль должен содержать минимум 8 символов';
    }
    
    if (empty($errors)) {
        $check = $con->prepare("SELECT id FROM users WHERE login = ? OR email = ?");
        $check->bind_param("ss", $login, $email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $errors['general'] = 'Такой логин или email уже зарегистрирован';
        }
        $check->close();
    }
    
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $con->prepare("INSERT INTO users (login, password, fullname, phone, email) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $login, $hashed_password, $fullname, $phone, $email);
        
        if ($stmt->execute()) {
            header('Location: login.php');
            exit();
        } else {
            $errors['general'] = 'Ошибка регистрации: ' . $con->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang='ru'>
<head>
    <meta charset='UTF-8'>
    <title>Регистрация - Водить.РФ</title>
    <link rel='stylesheet' href='styles/style.css'>
    <link rel="icon" href="assets/boat.png">
    <script src='script/phone.js'></script>
    <script src='script/validate.js'></script>
</head>
<body class="body-form">
    <div class='register-container'>
        <a href='index.php' class='index-link'>◄ На главную</a>
        <h1>Регистрация</h1>
        <p>Создайте аккаунт для составления заявки</p>
        
        <?php if (!empty($errors['general'])): ?>
            <div class="general-error"><?= htmlspecialchars($errors['general']) ?></div>
        <?php endif; ?>
        
        <form method='POST' id="registerForm">
            <div class="form-group">
                <label>ФИО*</label>
                <input type='text' name='fullname' value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>" class="<?= isset($errors['fullname']) ? 'error-border' : '' ?>">
                <span class="error-text" id="error-fullname"><?= htmlspecialchars($errors['fullname'] ?? '') ?></span>
            </div>
            
            <div class="form-group">
                <label>Телефон*</label>
                <input type='tel' name='phone' placeholder='+7(___)___-__-__' maxlength='16' value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" class="<?= isset($errors['phone']) ? 'error-border' : '' ?>">
                <span class="error-text" id="error-phone"><?= htmlspecialchars($errors['phone'] ?? '') ?></span>
            </div>
            
            <div class="form-group">
                <label>Email*</label>
                <input type='email' name='email' value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" class="<?= isset($errors['email']) ? 'error-border' : '' ?>">
                <span class="error-text" id="error-email"><?= htmlspecialchars($errors['email'] ?? '') ?></span>
            </div>
            
            <div class="form-group">
                <label>Логин* (латиница, от 6 символов)</label>
                <input type='text' name='login' value="<?= htmlspecialchars($_POST['login'] ?? '') ?>" class="<?= isset($errors['login']) ? 'error-border' : '' ?>">
                <span class="error-text" id="error-login"><?= htmlspecialchars($errors['login'] ?? '') ?></span>
            </div>
            
            <div class="form-group">
                <label>Пароль* (от 8 символов)</label>
                <input type='password' name='password' class="<?= isset($errors['password']) ? 'error-border' : '' ?>">
                <span class="error-text" id="error-password"><?= htmlspecialchars($errors['password'] ?? '') ?></span>
            </div>
            
            <button type='submit' class='btn-sub'>Зарегистрироваться</button>
        </form>
        
        <p>Уже есть аккаунт? <a href='login.php' class='login-link'>Войти</a></p>
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