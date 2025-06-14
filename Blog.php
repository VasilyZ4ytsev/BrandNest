<?php
require_once 'db.php';

// Получаем все посты блога с данными бренда
$sql = "SELECT blog_post.id, blog_post.image_url, blog_post.content, blog_post.created_at, brand.name AS brand_name
        FROM blog_post
        LEFT JOIN brand ON blog_post.brand_id = brand.id
        ORDER BY blog_post.created_at DESC";
$result = $conn->query($sql);

$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Блог | Brand Nest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
    <h2 class="mb-4 text-center">Блог</h2>
    <?php if (empty($posts)): ?>
        <div class="alert alert-info text-center">Постов пока нет.</div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($posts as $post): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <?php if ($post['image_url']): ?>
                            <img src="<?= htmlspecialchars($post['image_url']) ?>" class="card-img-top" alt="Блог">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($post['brand_name'] ?? 'Без бренда') ?></h5>
                            <p class="card-text"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                            <div class="mt-auto text-muted small"><?= date('d.m.Y', strtotime($post['created_at'])) ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<footer class="bg-light mt-5 py-4">
    <div class="container text-center">
        <p>&copy; 2024 Brand Nest. All rights reserved.</p>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>