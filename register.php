<?php
session_start();
require "functions.php";

$conn = new PDO("mysql:host=" . HOSTNAME . ";dbname=" . DATABASE . ";", USERNAME, PASSWORD);


    $email_of_forms = $_POST['email'];
    $password_of_forms = $_POST['password'];


    $user = get_user_by_email($conn, $email_of_forms);

    if (!empty($user)) {
        set_flash_message("danger", "LOL!Этот эл. адрес уже занят другим пользователем");
        redirect_to("/page_register.php");
    }

    add_new_user($conn, $email_of_forms, $password_of_forms);
    set_flash_message("success", "Регистрация успешна");
    redirect_to("/page_login.php");