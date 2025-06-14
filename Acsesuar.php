<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Аксессуары | Brand Nest</title>
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
    <section class="container my-4">
        <h4 class="text-center">Аксессуары</h4>
        <div class="d-flex justify-content-between mb-4">
            <select id="sortSelect" class="form-select w-auto">
                <option value="asc">По возрастанию цены</option>
                <option value="desc">По убыванию цены</option>
            </select>
        </div>
        <div class="row g-4" id="productGrid">
            <!-- Карточки товара будут генерироваться с помощью JavaScript -->
        </div>
    </section>
</div>

<!-- Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addToCartForm" method="post" action="cart.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Название товара</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <img id="modalProductImage" src="" class="img-fluid mb-3" alt="">
                    <p id="modalProductDescription"></p>
                    <p class="fw-bold" id="modalProductPrice"></p>
                    <input type="hidden" name="product_id" id="modalProductId">
                    <div class="mb-3">
                        <label for="modalProductQuantity" class="form-label">Количество:</label>
                        <input type="number" name="quantity" id="modalProductQuantity" class="form-control" value="1" min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_to_cart" class="btn btn-primary">Добавить в корзину</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let products = [];

    async function loadProducts() {
        const response = await fetch('get_accessories_products.php');
        products = await response.json();
        displayProducts(products);
    }

    function displayProducts(products) {
        const grid = document.getElementById('productGrid');
        grid.innerHTML = '';
        products.forEach(product => {
            grid.innerHTML += `
            <div class="col-md-3 col-sm-6 col-12">
                <div class="card text-center h-100" style="cursor:pointer;" onclick="showProductModal(${product.id})">
                    <img src="${product.image_url}" class="card-img-top" alt="${product.title}" style="height:250px;object-fit:cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">${product.title}</h5>
                        <p class="card-text">${product.description}</p>
                        <p class="card-text fw-bold mt-auto">${product.price} ₽</p>
                    </div>
                </div>
            </div>
        `;
        });
    }

    function showProductModal(productId) {
        const product = products.find(p => p.id == productId);
        if (!product) return;
        document.getElementById('productModalLabel').textContent = product.title;
        document.getElementById('modalProductImage').src = product.image_url;
        document.getElementById('modalProductImage').alt = product.title;
        document.getElementById('modalProductDescription').textContent = product.description;
        document.getElementById('modalProductPrice').textContent = product.price + ' ₽';
        document.getElementById('modalProductId').value = product.id;
        document.getElementById('modalProductQuantity').value = 1;
        var modal = new bootstrap.Modal(document.getElementById('productModal'));
        modal.show();
    }

    document.getElementById('sortSelect').addEventListener('change', function() {
        let sorted = [...products];
        if (this.value === 'asc') {
            sorted.sort((a, b) => a.price - b.price);
        } else {
            sorted.sort((a, b) => b.price - a.price);
        }
        displayProducts(sorted);
    });

    loadProducts();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>