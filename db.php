<?php
$host = 'localhost';
$database = 'shop_db';
$user = 'root';
$password = 'w123z486w';
$port = 3307; // если порт другой, укажите свой

$conn = new mysqli($host, $user, $password, $database, $port);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$conn->set_charset("utf8");