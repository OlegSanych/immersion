<?php
//Подключение к БД
define("HOSTNAME", "localhost");
define("USERNAME", "root");
define("PASSWORD", "root");
define("DATABASE", "my_immersion");

try {
    $conn = new PDO("mysql:host=" . HOSTNAME . ";dbname=" . DATABASE . ";", USERNAME, PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Подключение не удалось: ' . $e->getMessage();
};
/////////////////////////////////
//Получить пользователя по email
function get_user_by_email($conn, $email)
{
    $query = $conn->prepare("SELECT email FROM users WHERE email = :email");
    $params = [
        ':email' => $email,
    ];
    $query->execute($params);
    $users = $query->fetch(PDO::FETCH_ASSOC);
    return $users;
};

//Добавить пользователя в БД
function add_new_user ($conn, $email, $password)
{
    $query = $conn->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
    $params = [
        ':email' => $email,
        ':password' => password_hash($password, PASSWORD_DEFAULT)
    ];
    $query->execute($params);
};

//Подготовить сообщение
function set_flash_message($key, $message)
{
    $_SESSION[$key] = $message;
    $_SESSION['status_message'] = $key;
}
//Показать сообщение
function display_flash_message($key) {
    if (!empty($_SESSION[$key])) {
        echo "<div class=\"alert alert-{$key} text-dark\" role=\"alert\">{$_SESSION[$key]}</div>";
        unset($_SESSION[$key]);
        unset($_SESSION['status_message']);
    }
};
//Перенаправить на страницу
function redirect_to($path)
{
    header('Location: ' . $path);
    exit();
}