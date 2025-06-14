<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Arcteryx | Brand Nest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Css.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
<div class="container mt-5">
    <h1 class="text-center mb-3">Arcteryx</h1>
    <p class="text-center mb-4">Новая коллекция для экстремальных условий и городской жизни.</p>
    <div class="row align-items-center">
        <div class="col-md-6 text-center">
            <img src="Photo/69919df3a933d206981a9fffe9df0790.jpg" alt="Arcteryx" class="img-fluid rounded shadow">
        </div>
        <div class="col-md-6">
            <h3>Особенности коллекции</h3>
            <ul class="list-unstyled">
                <li class="mb-2"><i class="bi bi-check-circle text-primary"></i> Передовые технологии защиты от непогоды</li>
                <li class="mb-2"><i class="bi bi-check-circle text-primary"></i> Легкость и прочность материалов</li>
                <li class="mb-2"><i class="bi bi-check-circle text-primary"></i> Современный минималистичный дизайн</li>
                <li class="mb-2"><i class="bi bi-check-circle text-primary"></i> Для активного отдыха и города</li>
            </ul>
            <p>Arcteryx — выбор профессионалов и любителей приключений. Откройте новые горизонты с надежной экипировкой!</p>
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