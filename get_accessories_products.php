<?php
require_once 'db.php';

$category_id = null;
$res = $conn->query("SELECT id FROM category WHERE name = 'Accessories' LIMIT 1");
if ($row = $res->fetch_assoc()) {
    $category_id = (int)$row['id'];
}

$products = [];
if ($category_id) {
    $sql = "SELECT id, title, description, price, image_url FROM product WHERE category_id = $category_id";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($products);