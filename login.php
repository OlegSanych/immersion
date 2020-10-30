<?php
session_start();
require "functions.php";

$email = $_POST["email"];
$password = $_POST['password'];

$conn = new PDO("mysql:host=" . HOSTNAME . ";dbname=" . DATABASE . ";", USERNAME, PASSWORD);

$user = sign_in($conn, $email, $password);

if($user == "email error") {
    set_flash_message("danger", "Пользователь не найден!");
    redirect_to("/page_login.php");
}elseif($user == "password error") {
    set_flash_message("danger", "Ошибка при вводе пароля");
    redirect_to("/page_login.php");
}else {
    $_SESSION['id'] = $user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['password'] = $user['password'];
    $_SESSION['role'] = $user['role'];
    redirect_to("/users.php");
}



?>