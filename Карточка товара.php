<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Карточка товара</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding: 20px;
      background-color: #f9f9f9;
    }
    .product-container {
      display: flex;
      gap: 20px;
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 20px;
      width: 800px;
    }
    .product-images {
      flex: 1;
    }
    .product-images img {
      width: 100%;
      border-radius: 8px;
      margin-bottom: 10px;
    }
    .product-details {
      flex: 1;
      display: flex;
      flex-direction: column;
    }
    .product-title {
      font-size: 24px;
      margin-bottom: 10px;
      font-weight: bold;
    }
    .product-price {
      font-size: 20px;
      color: #333;
      margin-bottom: 20px;
    }
    .size-options {
      margin-bottom: 20px;
    }
    .size-options label {
      display: inline-block;
      margin-right: 10px;
      cursor: pointer;
    }
    .add-to-cart {
      background-color: #007bff;
      color: white;
      border: none;
      padding: 10px 16px;
      cursor: pointer;
      border-radius: 4px;
      text-align: center;
      font-size: 16px;
    }
    .add-to-cart:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>

<div class="product-container">
  <div class="product-images">
    <img src="https://via.placeholder.com/400x400" alt="Product Image">
    <img src="https://via.placeholder.com/400x400" alt="Product Image">
  </div>
  <div class="product-details">
    <div class="product-title">Champion Legacy Jersey</div>
    <div class="product-price">6 690 ₽</div>
    <div class="size-options">
      <strong>Размеры:</strong><br>
      <label><input type="radio" name="size" value="S"> S</label>
      <label><input type="radio" name="size" value="M"> M</label>
      <label><input type="radio" name="size" value="L"> L</label>
      <label><input type="radio" name="size" value="XL"> XL</label>
    </div>
    <button class="add-to-cart" onclick="addToCart('Champion Legacy Jersey', 6690)">
      Добавить в корзину
    </button>
  </div>
</div>

<script>
  function addToCart(productName, productPrice) {
    alert(`Товар "${productName}" стоимостью ${productPrice} ₽ добавлен в корзину!`);
    console.log(`Добавлено в корзину: ${productName}, ${productPrice} ₽`);
  }
</script>

</body>
</html>
