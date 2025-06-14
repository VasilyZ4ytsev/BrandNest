<?php
require_once 'db.php'; // Подключение к базе данных

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email) || empty($password)) {
        $message = "Пожалуйста, заполните все поля!";
    } else {
        $stmt = $conn->prepare("SELECT id, name, password FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $name, $hashed_password);
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                // Авторизация успешна, можно сохранить данные в сессии
                session_start();
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $name;
                header("Location: BrandNest.php");
                exit;
            } else {
                $message = "Неверный пароль!";
            }
        } else {
            $message = "Пользователь с таким email не найден!";
        }
        $stmt->close();
    }
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Вход | Brand Nest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Css.css">
    <style>
        body {
            min-height: 100vh;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            max-width: 400px;
            width: 100%;
            margin: 40px auto;
            box-shadow: 0 0 24px rgba(0,0,0,0.08);
            border-radius: 16px;
        }
    </style>
</head>
<body>
<div class="login-card bg-white p-4">
    <h2 class="mb-4 text-center">Вход</h2>
    <?php if ($message): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="post" action="login.php">
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Войти</button>
        </div>
    </form>
    <div class="text-center mt-3">
        <a href="Reg.php">Нет аккаунта? Зарегистрируйтесь</a>
    </div>
</div>
</body>
</html>