<?php
session_start();
require "functions.php";

$conn = new PDO("mysql:host=" . HOSTNAME . ";dbname=" . DATABASE . ";", USERNAME, PASSWORD);

$email = $_POST['email'];
$password = $_POST['password'];
$data = $_POST;

$user = get_user_by_email($conn, $email);

if (!empty($user)) {
    set_flash_message("danger", "Этот эл. адрес уже занят другим пользователем");
    redirect_to("/page_users.php");
}

$id = add_new_user($conn, $email, $password);

edit_user($conn, $data, $id);

set_status($conn, $data['online_status'], $id);

set_flash_message("success", "Профиль успешно создан");
redirect_to("/page_users.php");

?>