<?php
session_start();
if(isset($_GET['index'])) { 
  session_destroy(); 
  header('Location: index.php'); 
  exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <link rel="icon" href="assets/boat.png">
  <meta charset="UTF-8">
  <title>Водить.РФ - Обучение управления речным транспортом</title>
  <link rel="stylesheet" href="styles/style.css">
  <link rel="stylesheet" href="styles/slider.css">
  <script src="script/script.js" defer></script>
</head>
<body>
<div class="header">
  <div class="nav">
    <a href="index.php" class="logo"><img src="assets/boat.png"></a>
    <?php if(!isset($_SESSION['user_id'])): ?>
      <div class="nav-buttons">
        <a href="login.php" class="btn-login">Войти</a>
        <a href="register.php" class="btn-register">Регистрация</a>
      </div>
    <?php elseif(isset($_SESSION['admin']) && $_SESSION['admin']): ?>
      <div class="nav-buttons">
        <a href="admin.php" class="btn-admin">Панель администратора</a>
        <a href="?index=1" class="btn-exit">Выход</a>
      </div>
    <?php else: ?>
      <div class="nav-buttons">
        <a href="history.php" class="btn-lk">Мои заявки</a>
        <a href="create.php" class="btn-create">Новая заявка</a>
      </div>
    <?php endif; ?>
  </div>
</div>

<div class="slideshow-container">
  <div class="mySlides fade">
    <img src="assets/67be0e9604c2fe619a95ca87.jpg" alt="Катеры" style="width:100%">
    <div class="text">Катеры</div>
  </div>
  <div class="mySlides fade">
    <img src="assets/1ybMfJJ9kSk.jpg" alt="Лайнеры" style="width:100%">
    <div class="text">Круизные лайнеры</div>
  </div>
  <div class="mySlides fade">
    <img src="assets/1.WL1xj7a49FRHJjZRTapnjA0u9lLPLnZcByv2VsEm_F7H.jpg" alt="Яхты" style="width:100%">
    <div class="text">Яхты</div>
  </div>
  <div class="mySlides fade">
    <img src="assets/yqzavLa5ZkXsFaRAwu-bzw4fYE9un26.jpg" alt="Лодки" style="width:100%">
    <div class="text">Лодки</div>
  </div>
  
  <a class="prev" onclick="plusSlides(-1)">❮</a>
  <a class="next" onclick="plusSlides(1)">❯</a>
</div>

<div class="dot-container">
  <span class="dot active" onclick="currentSlide(1)"></span> 
  <span class="dot" onclick="currentSlide(2)"></span> 
  <span class="dot" onclick="currentSlide(3)"></span> 
  <span class="dot" onclick="currentSlide(4)"></span>
</div>

<!-- Футер для главной страницы (большой) -->
<footer class="footer footer-main">
    <div class="footer-container">
        <div class="footer-main-content">
            <div class="footer-section">
                <h3>Водить.РФ</h3>
                <p>Обучение управления речным транспортом</p>
                <p class="footer-copyright">© <?= date('Y') ?> Все права защищены</p>
            </div>
            
            <div class="footer-section">
                <h3>Наше местонахождение</h3>
                <p><strong>Адрес головного офиса:</strong><br>
                г. Москва, ул. Большая Ордынка, д. 15</p>
                <p><strong>Телефон горячей линии:</strong><br>
                <a href="tel:+74951234567">+7 (495) 123-45-67</a></p>
            </div>
            
            <div class="footer-section">
                <h3>Обратная связь</h3>
                <p>Если возникли вопросы или пожелания, позвоните нам.<br>
                Ответим оперативно и подробно.</p>
                <img src = "social/soc.png" alt = "Соц. сети">
            </div>
        </div>
    </div>
</footer>

</body>
</html>