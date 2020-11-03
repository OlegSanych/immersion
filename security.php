
<?php
session_start();
require "functions.php";

$conn = new PDO("mysql:host=" . HOSTNAME . ";dbname=" . DATABASE . ";", USERNAME, PASSWORD);

$edit_user_id = $_GET['id'];
$current_user_email = $_SESSION['email'];
$change_email = $_POST['email'];
$change_password = $_POST['password'];

$change_user = get_user_by_email($conn, $change_email);

if($current_user_email == $change_email and empty($change_password)) {
    redirect_to("/page_security.php?id=".$edit_user_id);
}elseif(!empty($change_user['email']) and empty($change_password)) {
    set_flash_message("danger", "Данный email адрес уже занят");
    redirect_to("/page_security.php?id=".$edit_user_id);
}

if(empty($change_password) and empty($change_user['email'])) {              //не меняли пароль, но изменили емаил
    update_credentials($conn, $edit_user_id, $change_email);
    if(check_author($_SESSION['id'], $edit_user_id)){$_SESSION['email'] = $change_email;}
}elseif(!empty($change_user['email']) and !empty($change_password)) {       //не меняли емаил, но изменили пароль
    update_credentials($conn, $edit_user_id, null, $change_password);
}else {                                                                     //изменили емаил и ввели пароль
    update_credentials($conn, $edit_user_id, $change_email, $change_password);
    if(check_author($_SESSION['id'], $edit_user_id)){$_SESSION['email'] = $change_email;}
}

set_flash_message("success", "Профиль успешно обновлен");
redirect_to("/page_security.php?id=".$edit_user_id);

?>