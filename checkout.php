<?php
require_once 'db.php';
session_start();

// Получаем user_id или guest_id (через сессию)
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$order_id = null;

// Находим текущий заказ-корзину
if ($user_id) {
    $stmt = $conn->prepare("SELECT id FROM `order` WHERE user_id = ? AND status = 'cart'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($order_id);
    $stmt->fetch();
    $stmt->close();
} else {
    if (isset($_SESSION['order_id'])) {
        $order_id = $_SESSION['order_id'];
    }
}

// Если корзина пуста — редирект обратно
if (!$order_id) {
    header("Location: cart.php");
    exit;
}

// Получаем товары в заказе
$cart_items = [];
$total = 0;
$stmt = $conn->prepare("SELECT oi.id, p.title, oi.quantity, oi.price_at_purchase
                        FROM order_item oi
                        JOIN product p ON oi.product_id = p.id
                        WHERE oi.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$stmt->bind_result($item_id, $title, $quantity, $price);
while ($stmt->fetch()) {
    $cart_items[] = [
        'id' => $item_id,
        'title' => $title,
        'quantity' => $quantity,
        'price' => $price
    ];
    $total += $price * $quantity;
}
$stmt->close();

if (count($cart_items) === 0) {
    header("Location: cart.php");
    exit;
}

// Обработка оформления заказа
$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = trim($_POST['customer_name'] ?? '');
    $customer_phone = trim($_POST['customer_phone'] ?? '');
    $customer_address = trim($_POST['customer_address'] ?? '');

    if ($customer_name && $customer_phone && $customer_address) {
        // Сохраняем данные в заказ
        $stmt = $conn->prepare("UPDATE `order` SET status = 'ordered', total_price = ?, customer_name = ?, customer_phone = ?, customer_address = ?, created_at = NOW() WHERE id = ?");
        $stmt->bind_param("dsssi", $total, $customer_name, $customer_phone, $customer_address, $order_id);
        $stmt->execute();
        $stmt->close();

        // Очищаем корзину (создаём новую)
        if ($user_id) {
            // Новый заказ-корзина для пользователя
            $stmt = $conn->prepare("INSERT INTO `order` (user_id, total_price, status, created_at) VALUES (?, 0, 'cart', NOW())");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $_SESSION['order_id'] = $stmt->insert_id;
            $stmt->close();
        } else {
            // Новый заказ-корзина для гостя
            $stmt = $conn->prepare("INSERT INTO `order` (user_id, total_price, status, created_at) VALUES (NULL, 0, 'cart', NOW())");
            $stmt->execute();
            $_SESSION['order_id'] = $stmt->insert_id;
            $stmt->close();
        }

        $success = true;
    } else {
        $error = "Пожалуйста, заполните все поля!";
    }
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Оформление заказа | Brand Nest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Css.css">
</head>
<body>
<header class="bg-white shadow-sm sticky-top">
    <div class="container-fluid py-0">
        <div class="d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="BrandNest.php"><img src="Photo/logo.png" alt="Brand Nest" class="img-fluid" style="max-height: 90px;"></a>
            <nav class="d-none d-md-block">
                <ul class="nav">
                    <li class="nav-item"><a href="Men.php" class="nav-link text-dark">Мужское</a></li>
                    <li class="nav-item"><a href="Woman.php" class="nav-link text-dark">Женское</a></li>
                    <li class="nav-item"><a href="Acsesuar.php" class="nav-link text-dark">Аксессуары</a></li>
                    <li class="nav-item"><a href="Акции.php" class="nav-link text-dark">Акции</a></li>
                    <li class="nav-item"><a href="Blog.php" class="nav-link text-dark">Блог</a></li>
                </ul>
            </nav>
            <div class="d-flex align-items-center gap-2">
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

<div class="container my-5">
    <h2 class="mb-4 text-center">Оформление заказа</h2>
    <?php if ($success): ?>
        <div class="alert alert-success text-center">
            Спасибо за заказ! Мы свяжемся с вами для подтверждения.<br>
            <a href="BrandNest.php" class="btn btn-primary mt-3">На главную</a>
        </div>
    <?php else: ?>
        <?php if ($error): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" class="mx-auto" style="max-width: 500px;">
            <div class="mb-3">
                <label for="customer_name" class="form-label">Ваше имя</label>
                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
            </div>
            <div class="mb-3">
                <label for="customer_phone" class="form-label">Телефон</label>
                <input type="text" class="form-control" id="customer_phone" name="customer_phone" required>
            </div>
            <div class="mb-3">
                <label for="customer_address" class="form-label">Адрес доставки</label>
                <textarea class="form-control" id="customer_address" name="customer_address" rows="2" required></textarea>
            </div>
            <h5 class="mt-4">Ваш заказ:</h5>
            <ul class="list-group mb-3">
                <?php foreach ($cart_items as $item): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= htmlspecialchars($item['title']) ?> × <?= $item['quantity'] ?>
                        <span><?= number_format($item['price'] * $item['quantity'], 2, '.', ' ') ?> ₽</span>
                    </li>
                <?php endforeach; ?>
                <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                    Итого:
                    <span><?= number_format($total, 2, '.', ' ') ?> ₽</span>
                </li>
            </ul>
            <div class="d-grid">
                <button type="submit" class="btn btn-success btn-lg">Подтвердить заказ</button>
            </div>
        </form>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>