<?php
session_start();
require "functions.php";

$conn = new PDO("mysql:host=" . HOSTNAME . ";dbname=" . DATABASE . ";", USERNAME, PASSWORD);

$id = $_GET['id'];
$data = $_POST;

edit_user($conn, $data, $id);

set_flash_message("success", "Профиль успешно обновлен");
redirect_to("/page_edit.php?id=".$id);

?>