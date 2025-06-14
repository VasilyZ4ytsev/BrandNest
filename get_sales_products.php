<?php
require_once 'db.php';

// Получаем только активные акционные товары
$sql = "SELECT id, title, description, price, discount_price, image_url 
        FROM product 
        WHERE is_sale = 1 
        AND sale_start_date <= NOW() 
        AND sale_end_date >= NOW()";

$result = $conn->query($sql);

$products = [];
while ($row = $result->fetch_assoc()) {
    // Добавляем процент скидки
    $original_price = floatval($row['price']);
    $discount_price = floatval($row['discount_price']);
    $discount_percent = round((($original_price - $discount_price) / $original_price) * 100);

    $row['discount_percent'] = $discount_percent;
    $products[] = $row;
}

header('Content-Type: application/json');
echo json_encode($products);