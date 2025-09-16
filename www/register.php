<?php

// подключаем служебные файлы, которые создали раньше
require_once "config.php";
require_once "session.php";
// сообщение об ошибке, на старте — пустое
$error ='';


// если на странице нажали кнопку регистрации
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    $error ='Ошибка';
    // берём данные из формы
    $fullname = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST["confirm_password"]);
    $password_hash = password_hash($password, PASSWORD_BCRYPT);


    if($query = $db->prepare("SELECT * FROM users WHERE email = ?")) {
        $error = '';
    // указываем, что почта — это строка
    $query->bind_param('s', $email);
    $query->execute();
    // сначала проверяем, есть ли такой аккаунт в базе
    $query->store_result();
        if ($query->num_rows > 0) {
            $error .= '<p class="error">Пользователь с такой почтой уже зарегистрирован!</p>';
        } else {
            // проверяем требование к паролю
            if (strlen($password ) < 6) {
                $error .= '<p class="error">Пароль не должен быть короче 6 символов.</p>';
            }

            // проверяем, ввели ли пароль второй раз
            if (empty($confirm_password)) {
                $error .= '<p class="error">Пожалуйста, подтвердите пароль.</p>';
            } else {
                // если пароли не совпадают
                if (empty($error) && ($password != $confirm_password)) {
                    $error .= '<p class="error">Введённые пароли не совпадают.</p>';
                }
            }
            // если ошибок нет
            if (empty($error) ) {
                // добавляем запись в базу данных
                $insertQuery = $db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?);");
                $insertQuery->bind_param("sss", $fullname, $email, $password_hash);
                $result = $insertQuery->execute();
                // если всё прошло успешно
                if ($result) {
                    $error .= '<p class="success">Вы успешно зарегистрировались!</p>';
                // если случилась ошибка
                } else {
                    $error .= '<p class="error">Ошибка регистрации, что-то пошло не так.</p>';
                }
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
    <title>Регистрация</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .left-aligned-header {
            text-align: left;
            margin-left: 0;
            padding-left: 0;
        }
        .registration-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 25px;
            border-radius: 10px;
            color: rgb(255, 255, 255);
            box-shadow: 0 0 20px rgba(23, 4, 4, 0.1);
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
                <div class="registration-container">
                    <!-- Заголовок смещен влево -->
                    <h2 class="left-aligned-header mb-4">Регистрация</h2>
                    <p class="text-muted">Заполните поля, чтобы создать новый аккаунт.</p>
                    
                    <form action="" method="post">
                        <div class="form-group">
                            <label class="form-label">Имя</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>    
                        
                        <div class="form-group">
                            <label class="form-label">Электронная почта</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>    
                        
                        <div class="form-group">
                            <label class="form-label">Пароль</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Повторите пароль</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        
                        <div class="form-group mt-4">
                            <input type="submit" name="submit" class="btn btn-primary" value="Зарегистрироваться">
                        </div>
                        
                        <div class="text-center mt-3">
                            <p>Уже зарегистрированы? <a href="login.php">Войдите в систему</a>.</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>