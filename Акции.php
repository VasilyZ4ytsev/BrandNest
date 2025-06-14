<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Акции | Brand Nest</title>
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

<!-- Main Container -->
<div class="main_container">
    <section class="container my-4">
        <h4 class="text-center mb-4">Акции</h4>

        <!-- Сортировка -->
        <div class="d-flex justify-content-between mb-4">
            <select id="sortSelect" class="form-select w-auto">
                <option value="asc">По возрастанию скидки</option>
                <option value="desc">По убыванию скидки</option>
                <option value="price_asc">По возрастанию цены</option>
                <option value="price_desc">По убыванию цены</option>
            </select>
        </div>

        <!-- Grid with products -->
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="productModalImage" src="" alt="Product Image" class="img-fluid mb-3">
                    <p id="productModalDescription"></p>
                    <div id="productModalPrice" class="mb-3"></div>
                    <label for="sizeSelect">Выберите размер:</label>
                    <select id="sizeSelect" class="form-select mb-3">
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                    </select>
                    <input type="hidden" name="product_id" id="modalProductId">
                    <input type="number" name="quantity" value="1" min="1" class="form-control mb-3" style="max-width:120px;">
                    <button type="submit" name="add_to_cart" class="btn btn-primary w-100">Добавить в корзину</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-light mt-5 py-4">
    <div class="container text-center">
        <p>&copy; 2024 Brand Nest. All rights reserved.</p>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>

<script>
    let products = []; // Будет хранить данные с сервера

    // Функция для загрузки товаров с сервера
    async function loadProducts() {
        try {
            const response = await fetch('get_sales_products.php');
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            products = await response.json();
            displayProducts(products);
        } catch (error) {
            console.error('Error loading products:', error);
            const productGrid = document.getElementById("productGrid");
            productGrid.innerHTML = '<div class="col-12 text-center"><p class="text-danger">Ошибка загрузки товаров. Пожалуйста, попробуйте позже.</p></div>';
        }
    }

    // Функция для отображения товаров
    function displayProducts(products) {
        const productGrid = document.getElementById("productGrid");
        productGrid.innerHTML = '';

        if (products.length === 0) {
            productGrid.innerHTML = '<div class="col-12 text-center"><p>Акционных товаров не найдено</p></div>';
            return;
        }

        products.forEach(product => {
            const productCard = `
          <div class="col-md-3 col-sm-6 col-12">
            <div class="card text-center" data-id="${product.id}" style="cursor: pointer;">
              <div class="position-relative">
                <img src="${product.image_url}" class="card-img-top" alt="${product.title}" onerror="this.src='Photo/placeholder.jpg'">
                <div class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1 m-2 rounded">
                  -${product.discount_percent}%
                </div>
              </div>
              <div class="card-body">
                <h5 class="card-title">${product.title}</h5>
                <p class="card-text">${product.description}</p>
                <div class="d-flex justify-content-center align-items-center gap-2">
                  <p class="card-text text-decoration-line-through text-muted mb-0">${product.price} ₽</p>
                  <p class="card-text text-danger fw-bold mb-0">${product.discount_price} ₽</p>
                </div>
              </div>
            </div>
          </div>
        `;
            productGrid.innerHTML += productCard;
        });

        // Добавить обработчик события на карточки
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('click', (e) => {
                const productId = card.getAttribute('data-id');
                showProductModal(productId);
            });
        });
    }

    // Функция для отображения модального окна с информацией о товаре
    function showProductModal(productId) {
        const product = products.find(p => p.id == productId);
        if (product) {
            document.getElementById('productModalLabel').textContent = product.title;
            document.getElementById('productModalImage').src = product.image_url;
            document.getElementById('productModalDescription').textContent = product.description;

            const priceElement = document.getElementById('productModalPrice');
            priceElement.innerHTML = `
          <div class="d-flex justify-content-center align-items-center gap-2">
            <span class="text-decoration-line-through text-muted">${product.price} ₽</span>
            <span class="text-danger fw-bold">${product.discount_price} ₽</span>
            <span class="badge bg-danger">-${product.discount_percent}%</span>
          </div>
        `;
            document.getElementById('modalProductId').value = product.id;

            const productModal = new bootstrap.Modal(document.getElementById('productModal'));
            productModal.show();
        }
    }

    // Инициализация страницы
    loadProducts();

    // Обработчик изменения сортировки
    document.getElementById("sortSelect").addEventListener("change", (e) => {
        sortProducts(e.target.value);
    });

    // Функция для сортировки
    function sortProducts(criteria) {
        const sortedProducts = [...products].sort((a, b) => {
            switch(criteria) {
                case 'asc':
                    return a.discount_percent - b.discount_percent;
                case 'desc':
                    return b.discount_percent - a.discount_percent;
                case 'price_asc':
                    return a.discount_price - b.discount_price;
                case 'price_desc':
                    return b.discount_price - a.discount_price;
                default:
                    return 0;
            }
        });
        displayProducts(sortedProducts);
    }

    // Обработчик поиска
    document.getElementById("searchForm").addEventListener("submit", (e) => {
        e.preventDefault();
        searchProducts(document.getElementById("searchInput").value);
    });

    // Функция для поиска
    function searchProducts(query) {
        const filteredProducts = products.filter(product =>
            product.title.toLowerCase().includes(query.toLowerCase()) ||
            product.description.toLowerCase().includes(query.toLowerCase())
        );
        displayProducts(filteredProducts);
    }
</script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>