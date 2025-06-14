<?php
require_once 'db.php';
session_start();

// Получаем user_id или guest_id (через сессию)
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Получаем или создаём заказ-корзину
$order_id = null;
if ($user_id) {
    $stmt = $conn->prepare("SELECT id FROM `order` WHERE user_id = ? AND status = 'cart'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($order_id);
    if (!$stmt->fetch()) {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO `order` (user_id, total_price, status, created_at) VALUES (?, 0, 'cart', NOW())");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $order_id = $stmt->insert_id;
    }
    $stmt->close();
} else {
    if (!isset($_SESSION['order_id'])) {
        $stmt = $conn->prepare("INSERT INTO `order` (user_id, total_price, status, created_at) VALUES (NULL, 0, 'cart', NOW())");
        $stmt->execute();
        $_SESSION['order_id'] = $stmt->insert_id;
        $order_id = $_SESSION['order_id'];
        $stmt->close();
    } else {
        $order_id = $_SESSION['order_id'];
        $stmt = $conn->prepare("SELECT id FROM `order` WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 0) {
            unset($_SESSION['order_id']);
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO `order` (user_id, total_price, status, created_at) VALUES (NULL, 0, 'cart', NOW())");
            $stmt->execute();
            $_SESSION['order_id'] = $stmt->insert_id;
            $order_id = $_SESSION['order_id'];
            $stmt->close();
        } else {
            $stmt->close();
        }
    }
}

// Добавление товара в корзину
if (isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = max(1, intval($_POST['quantity']));

    $stmt = $conn->prepare("SELECT id, quantity FROM order_item WHERE order_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $order_id, $product_id);
    $stmt->execute();
    $stmt->bind_result($order_item_id, $old_quantity);
    if ($stmt->fetch()) {
        $stmt->close();
        $new_quantity = $old_quantity + $quantity;
        $stmt = $conn->prepare("UPDATE order_item SET quantity = ? WHERE id = ?");
        $stmt->bind_param("ii", $new_quantity, $order_item_id);
        $stmt->execute();
    } else {
        $stmt->close();
        $stmt = $conn->prepare("SELECT IFNULL(discount_price, price) FROM product WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->bind_result($price_at_purchase);
        $stmt->fetch();
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO order_item (order_id, product_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price_at_purchase);
        $stmt->execute();
    }
    $stmt->close();
    header("Location: cart.php");
    exit;
}

// Удаление товара из корзины
if (isset($_POST['remove_item'])) {
    $order_item_id = intval($_POST['order_item_id']);
    $stmt = $conn->prepare("DELETE FROM order_item WHERE id = ?");
    $stmt->bind_param("i", $order_item_id);
    $stmt->execute();
    $stmt->close();
    header("Location: cart.php");
    exit;
}

// Получаем товары в корзине
$cart_items = [];
$total = 0;
$stmt = $conn->prepare("SELECT oi.id, p.title, p.image_url, oi.quantity, oi.price_at_purchase
                        FROM order_item oi
                        JOIN product p ON oi.product_id = p.id
                        WHERE oi.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$stmt->bind_result($item_id, $title, $image_url, $quantity, $price);
while ($stmt->fetch()) {
    $cart_items[] = [
        'id' => $item_id,
        'title' => $title,
        'image_url' => $image_url,
        'quantity' => $quantity,
        'price' => $price
    ];
    $total += $price * $quantity;
}
$stmt->close();

// Получаем количество товаров в корзине через функцию из БД
$item_count = 0;
if ($order_id) {
    $stmt = $conn->prepare("SELECT get_order_item_count(?)");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->bind_result($item_count);
    $stmt->fetch();
    $stmt->close();
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Корзина | Brand Nest</title>
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
    <h2 class="mb-4 text-center">Ваша корзина</h2>
    <?php if (count($cart_items) === 0): ?>
        <div class="alert alert-info text-center">Ваша корзина пуста.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>Товар</th>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Сумма</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td style="width:100px;">
                            <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="img-fluid" style="max-height:70px;">
                        </td>
                        <td><?= htmlspecialchars($item['title']) ?></td>
                        <td><?= number_format($item['price'], 2, '.', ' ') ?> ₽</td>
                        <td><?= $item['quantity'] ?></td>
                        <td><?= number_format($item['price'] * $item['quantity'], 2, '.', ' ') ?> ₽</td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="order_item_id" value="<?= $item['id'] ?>">
                                <button type="submit" name="remove_item" class="btn btn-danger btn-sm">Удалить</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Итого:</th>
                    <th>
        <span class="badge bg-secondary">
            Товаров: <?= $item_count ?>
        </span>
                    </th>
                    <th>
                        <span class="fw-bold"><?= number_format($total, 2, '.', ' ') ?> ₽</span>
                    </th>
                    <th></th>
                </tr>
                </tfoot>
            </table>
        </div>
        <div class="d-flex justify-content-end mt-4">
            <form method="post" action="checkout.php">
                <button type="submit" class="btn btn-success btn-lg">Оформить заказ</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>