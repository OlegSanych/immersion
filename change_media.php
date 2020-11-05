<?php
session_start();
require "functions.php";

$conn = new PDO("mysql:host=" . HOSTNAME . ";dbname=" . DATABASE . ";", USERNAME, PASSWORD);

$edit_user_id = $_GET['id'];
$user = get_user_by_id($conn, $edit_user_id);

if (empty($_FILES['image']['name'])) {
    redirect_to("/page_media.php?id=".$edit_user_id);
} else {
    if (!empty($user['img_avatar'])) {
        delete_avatar($conn, $edit_user_id);
    }
    upload_avatar($_FILES['image'], $conn, $edit_user_id);
}

set_flash_message("success", "Профиль успешно обновлен");
redirect_to("/page_profile.php?id=".$edit_user_id);

?>