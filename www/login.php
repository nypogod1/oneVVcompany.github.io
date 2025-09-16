<?php

require_once "config.php";
require_once "session.php";


$error = '';
// если нажата кнопка входа
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // если не указана почта
    if (empty($email)) {
        $error .= '<p class="error">Введите адрес электронной почты.</p>';
    }

    // если не указан пароль
    if (empty($password)) {
        $error .= '<p class="error">Введите пароль.</p>';
    }

    // если ошибок нет
    if (empty($error)) {
        // берём данные пользователя
        if($query = $db->prepare("SELECT * FROM users WHERE email = ?")) {
            $query->bind_param('s', $email);
            $query->execute();
            $row = $query->get_result()->fetch_assoc();
            // смотрим, есть ли такой пользователь в базе
            if ($row) {
                // если пароль правильный
                if (password_verify($password, $row['password'])) {
                    // стартуем новую сессию
                    $_SESSION["userid"] = $row['id'];
                    $_SESSION["user"] = $row;
                    // перенаправляем пользователя на внутреннюю страницу
                    header("location: index.html");
                    exit;
                // если пароль не подходит
                } else {
                    $error .= '<p class="error">Введён неверный пароль.</p>';
                }
            // если пользователя нет в базе
            } else {
                $error .= '<p class="error">Нет пользователя с таким адресом электронной почты.</p>';
            }
        }
    }
    // закрываем соединение с базой данных
    mysqli_close($db);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .left-aligned-header {
            text-align: left;
            margin-left: 0;
            padding-left: 0;
        }
        .login-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 25px;
            border-radius: 10px;
            color: rgb(255, 255, 255);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, #312c3a 0%, #cdb1b1 100%);
        }
        body {
            background: linear-gradient(270deg, #312c3a 0%, #cdb1b1 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .btn-primary {
            background-color: #000000;
            border: none;
            padding: 10px 20px;
            font-weight: 500;
            width: 100%;
        }
        .form-group {
            margin-bottom: 1.2rem;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="login-container">
                    <!-- Заголовок смещен влево -->
                    <h2 class="left-aligned-header mb-4">Вход</h2>
                    <p class="text-muted">Введите свою почту и пароль.</p>
                    
                    <form action="" method="post">
                        <div class="form-group">
                            <label class="form-label">Электронная почта</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>    
                        
                        <div class="form-group">
                            <label class="form-label">Пароль</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        
                        <div class="form-group mt-4">
                            <input type="submit" name="submit" class="btn btn-primary" value="Войти">
                        </div>
                        
                        <div class="text-center mt-3">
                            <p>Нет аккаунта? <a href="register.php">Создайте его за минуту</a>.</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>