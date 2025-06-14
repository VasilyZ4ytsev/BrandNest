<?php
require_once 'db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $content = trim($_POST["message"]);
    $created_at = date("Y-m-d H:i:s");

    if (empty($name) || empty($email)) {
        $message = "Пожалуйста, заполните все поля!";
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO review (name, email, content, created_at) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $content, $created_at);

            if ($stmt->execute()) {
                $message = "Спасибо за ваш отзыв!";
            }
        } catch (Exception $e) {
            $message =  $e->getMessage();
        }
        $stmt->close();
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Brand Nest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Css.css">
</head>
<body>
<!-- Header -->
<header class="bg-white shadow-sm sticky-top">
    <div class="container-fluid py-0">
        <div class="d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="BrandNest.php"><img src="Photo/logo.png" alt="Brand Nest" class="img-fluid" style="max-height: 90px;"></a>
            <!-- Navigation -->
            <nav class="d-none d-md-block">
                <ul class="nav">
                    <li class="nav-item"><a href="Men.php" class="nav-link text-dark">Мужское</a></li>
                    <li class="nav-item"><a href="Woman.php" class="nav-link text-dark">Женское</a></li>
                    <li class="nav-item"><a href="Acsesuar.php" class="nav-link text-dark">Аксессуары</a></li>
                    <li class="nav-item"><a href="Акции.php" class="nav-link text-dark">Акции</a></li>
                    <li class="nav-item"><a href="Blog.php" class="nav-link text-dark">Блог</a></li>
                </ul>
            </nav>
            <!-- Icons -->
            <div class="d-flex align-items-center gap-2">
                <form class="d-flex" role="search" id="searchForm">
                    <input class="form-control" type="search" placeholder="Поиск товаров" aria-label="Search" id="searchInput">
                    <button class="btn btn-outline-primary ms-2" type="submit">Найти</button>
                </form>
                <a href="Cart.php" class="btn btn-light" aria-label="Cart">
                    <i class="bi bi-cart"></i>
                </a>
                <a href="Reg.php" class="btn btn-light" aria-label="Profile">
                    <i class="bi bi-person"></i>
                </a>
            </div>
        </div>
    </div>
</header>
<div class="main_container">
    <div class="popular_brand">
        <h4>Популярные бренды</h4>
        <div class="ph_popular_brand">
            <a href="#"><img src="Photo/tommy-hilfiger-brandshop.svg" alt="Tommy Hilfiger"></a>
            <a href="#"><img src="Photo/calvin-clein-jeans-brandshop.svg" alt="Calvin Klein"></a>
            <a href="#"><img src="Photo/cp-company-brandshop.svg" alt="CP Comapny"></a>
            <a href="#"><img src="Photo/the-north-face-brandshop.svg" alt="The North Face"></a>
            <a href="#"><img src="Photo/nike-brandshop.svg" alt="Nike"></a>
        </div>
    </div>
    <div class="sale_banner">
        <a href="Акции.php"><img src="Photo/promo_sale_20241015.jpg" alt="Sale"></a>
    </div>
</div>
<div class="container">
    <div class="main-post">
        <img src="Photo/9a041f78fb137d3b9f1d217355e007df.jpg" alt="Основная статья">
        <div class="main-post-content">
            <h2>Новая коллекция</h2>
            <p>The North Face. Вдохновленный профессиональной экипировкой, сфокусированный на надежности и комфорте для современной городской жизни.</p>
            <a href="The North Face.php">Смотреть</a>
        </div>
    </div>
    <div class="grid">
        <div class="grid-item">
            <img src="Photo/c55146a7a7d6059ecc05658e0bc70d7e.jpg" alt="Jordan">
            <div class="grid-item-content">
                <h3>Jordan Air Jordan 1 Retro High OG</h3>
                <p>Sneakershead</p>
                <a href="Jordan.php">Подробнее</a>
            </div>
        </div>
        <div class="grid-item">
            <img src="Photo/69919df3a933d206981a9fffe9df0790.jpg" alt="Arcteryx">
            <div class="grid-item-content">
                <h3>Arcteryx</h3>
                <p>Новая коллекция</p>
                <a href="Arcteryx.php">Подробнее</a>
            </div>
        </div>
        <div class="grid-item">
            <img src="Photo/a14c8f980f7135338b2fd8a9b3299033.jpg" alt="Tommy Hilfiger">
            <div class="grid-item-content">
                <h3>Tommy Hilfiger</h3>
                <p>Блог</p>
                <a href="Tommy%20Hilfiger.php">Подробнее</a>
            </div>
        </div>
        <div class="grid-item">
            <img src="Photo/93023e310413ed2469c97a673db9cb7f.jpg" alt="Stone Island">
            <div class="grid-item-content">
                <h3>Stone Island: инновации в мире стиля и технологий</h3>
                <p>Блог</p>
                <a href="Stone%20Island.php">Подробнее</a>
            </div>
        </div>
    </div>
</div><br>
<div class="container mt-5">
    <h5 class="text-center mb-4">Оставьте отзыв и пожелания</h5>
    <?php if (!empty($message)): ?>
        <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form action="" method="POST" class="p-4 border rounded bg-light">
        <div class="mb-3">
            <label for="name" class="form-label">Ваше имя</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Введите ваше имя" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Ваш email</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Введите ваш email" required>
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Отзыв или пожелания</label>
            <textarea id="message" name="message" class="form-control" rows="4" placeholder="Напишите свой отзыв или пожелания"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Отправить</button>
    </form>
</div>
<div class="map-container mt-5">
    <h5 class="text-center">Наше местоположение</h5>
    <div style="position:relative;overflow:hidden;width:90%;height:400px;margin:0 auto;">
        <iframe src="https://yandex.ru/map-widget/v1/?ll=49.073443%2C55.832647&mode=whatshere&whatshere%5Bpoint%5D=49.073826%2C55.831624&whatshere%5Bzoom%5D=17&z=17.61" width="100%" height="400" frameborder="1" allowfullscreen="true" style="border:0;"></iframe>
    </div>
</div><br>
<div class="container d-flex align-items-center justify-content-center py-5">
    <div class="col-md-6">
        <div class="contact-info p-4 bg-light border rounded text-center">
            <h5>Свяжитесь с нами</h5>
            <p><strong>Телефон:</strong> +7 (800) 123-45-67</p>
            <p><strong>Email:</strong> support@brandnest.com</p>
            <p><strong>Время работы:</strong> Пн-Пт: 10:00 - 18:00</p>
        </div>
    </div>
</div>
<footer class="bg-light mt-5 py-4">
    <div class="container text-center">
        <p>&copy; 2024 Brand Nest. All rights reserved.</p>
    </div>
</footer>
</body>
</html>