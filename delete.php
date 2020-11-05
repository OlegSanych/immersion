<?php
session_start();
require "functions.php";
$edit_user_id = $_GET['id'];

$conn = new PDO("mysql:host=" . HOSTNAME . ";dbname=" . DATABASE . ";", USERNAME, PASSWORD);

if (is_not_logged_in()) {
    redirect_to("/page_login.php");
}

if (!check_admin() and !check_author($_SESSION['id'], $edit_user_id)) {
    set_flash_message("danger", "Можно редактировать только свой профиль");
    redirect_to("/users.php");
}

$user = get_user_by_id($conn, $edit_user_id);

delete($conn, $edit_user_id);

if ($_SESSION['id'] == $edit_user_id) {
    session_unset();
    session_destroy();
    redirect_to('/page_register.php');
} else {
    set_flash_message("success", "Пользователь " . $user['full_name'] . " удален");
    redirect_to("/page_users.php");
}

?>