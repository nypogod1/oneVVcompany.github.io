<?php
define('DBSERVER', '800017ff8ed5'); // сервер с базой данных
define('DBUSERNAME', 'root'); // имя пользователя
define('DBPASSWORD', 'tiger'); // пароль
define('DBNAME', 'docker'); // название базы
 
/* соединяемся с базой */
$db = mysqli_connect(DBSERVER, DBUSERNAME, DBPASSWORD, DBNAME);
 
// проверяем соединение
if($db === false){
    die("Ошибка соединения с базой. " . mysqli_connect_error());
}
?>