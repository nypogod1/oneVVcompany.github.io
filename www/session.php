<?php
// начинаем новую сессию
session_start();

// если логин и пароль верны — показываем стартовую внутреннюю страницу
if (isset($_SESSION["userid"]) && $_SESSION["userid"] === true) {
    header("location: welcome.php");
    exit;
}
?>