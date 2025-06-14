<?php
require_once 'db.php';

$sql = "SELECT id, title, description, price, image_url FROM product WHERE gender = 'men'";
$result = $conn->query($sql);

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

header('Content-Type: application/json');
echo json_encode($products);