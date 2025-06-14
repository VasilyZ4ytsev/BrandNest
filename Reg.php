<?php
require_once 'db.php'; // Подключение к базе данных
session_start();

$message = '';
$message_type = 'info';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $birth_date = trim($_POST["birth_date"]);
    $created_at = date("Y-m-d H:i:s");

    if (empty($name) || empty($email) || empty($password) || empty($birth_date)) {
        $message = "Пожалуйста, заполните все поля!";
        $message_type = "warning";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO user (name, email, password, birth_date, created_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $hashed_password, $birth_date, $created_at);

        try {
            if ($stmt->execute()) {
                $user_id = $stmt->insert_id;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $name;
                header("Location: BrandNest.php");
                exit;
            } else {
                $message = "Ошибка при регистрации: " . $conn->error;
                $message_type = "danger";
            }
        } catch (mysqli_sql_exception $e) {
            $message = $e->getMessage();
            $message_type = "danger";
        }
        $stmt->close();
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Регистрация | Brand Nest</title>
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
        .register-card {
            max-width: 400px;
            width: 100%;
            margin: 40px auto;
            box-shadow: 0 0 24px rgba(0,0,0,0.08);
            border-radius: 16px;
        }
    </style>
</head>
<body>
<div class="register-card bg-white p-4">
    <h2 class="mb-4 text-center">Регистрация</h2>
    <?php if ($message): ?>
        <div class="alert alert-<?= $message_type ?> text-center"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="post" action="reg.php">
        <div class="mb-3">
            <label for="name" class="form-label">Имя</label>
            <input type="text" class="form-control" id="name" name="name" required value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="birth_date" class="form-label">Дата рождения</label>
            <input type="date" class="form-control" id="birth_date" name="birth_date" required value="<?= isset($_POST['birth_date']) ? htmlspecialchars($_POST['birth_date']) : '' ?>">
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
        </div>
    </form>
    <div class="text-center mt-3">
        Уже есть аккаунт? <a href="login.php">Войдите</a>
    </div>
</div>
</body>
</html>