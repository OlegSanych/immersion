<?php
session_start();
require "functions.php";

$conn = new PDO("mysql:host=" . HOSTNAME . ";dbname=" . DATABASE . ";", USERNAME, PASSWORD);

$edit_user_id = $_GET['id'];
$current_user_email = $_SESSION['email'];
$change_status = $_POST['online_status'];

set_status($conn, $change_status, $edit_user_id);

set_flash_message("success", "Профиль успешно обновлен");
redirect_to("/page_status.php?id=".$edit_user_id);

?>