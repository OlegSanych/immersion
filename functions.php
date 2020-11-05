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
    $query = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $params = [
        ':email' => $email,
    ];
    $query->execute($params);
    $users = $query->fetch(PDO::FETCH_ASSOC);
    return $users;
};

//Получить пользователя по email
function get_user_by_id($conn, $id)
{
    $query = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $params = [
        ':id' => $id,
    ];
    $query->execute($params);
    $user = $query->fetch(PDO::FETCH_ASSOC);
    return $user;
};

//Аваторизация пользователя
function sign_in($conn, $email, $password) {

    $user = get_user_by_email($conn, $email);

    if(empty($user)) {
        return "email error";
    }elseif(!password_verify($password, $user['password'])) {
        return "password error";
    }else {
        return $user;
    }
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

    return $conn->lastInsertId();
};

//Редактирование данных пользователя
function edit_user($conn, $data, $id) {
    $fields = '';

    foreach($data as $key => $value) {
        if($key == "full_name" || $key == "company" || $key == "phone_number" || $key == "address" || $key == "role"){
            $fields .= $key . "=:" . $key . ",";
        }else {
            unset($data[$key]);
        }
    }

    $data += ['id'=>$id];
    $fields = rtrim($fields, ',');

    $query = $conn->prepare("UPDATE users SET $fields WHERE id=:id");
    $query->execute($data);
    $data = $query->fetch(PDO::FETCH_ASSOC);
    return $data;

};

//Получить список всех пользователей
function list_users ($conn)
{
    $query = $conn->prepare("SELECT * FROM users ORDER BY id ASC");
    $query->execute();

    return $query->fetchAll();
};

//Обновить данные авторизации пользователя
function update_credentials($conn, $id, $email = null, $password = null) {
    if ($email == null) {
        $query = $conn->prepare("UPDATE users SET password=:password WHERE id=:id");
        $params = [
            ':id' => $id,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
        ];
        $query->execute($params);
    } elseif ($password == null) {
        $query = $conn->prepare("UPDATE users SET email=:email WHERE id=:id");
        $params = [
            ':id' => $id,
            ':email' => $email,
        ];
        $query->execute($params);
    } else {
        $query = $conn->prepare("UPDATE users SET password=:password, email=:email WHERE id=:id");
        $params = [
            ':id' => $id,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
        ];
        $query->execute($params);
    }
};

//Изменить статус
function set_status($conn, $status, $id) {

    $query = $conn->prepare("UPDATE users SET online_status=:online_status WHERE id=:id");
    $params = [
        ':id' => $id,
        ':online_status' => $status,
    ];
    $query->execute($params);
}

//Подготовить сообщение
function set_flash_message($key, $message)
{
    $_SESSION[$key] = $message;
    $_SESSION['status_message'] = $key;
};

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
};

//Проверка на авторизацию
function is_not_logged_in () {

    if(isset($_SESSION['email']) && !empty($_SESSION['email'])) {
        return false;
    }
    return true;
};

//Авторизованный пользователь - админ?
function check_admin () {
    if($_SESSION['role'] == 'Администратор') {
        return true;
    }
    return false;
};

//Авторизованный пользователь - автор?
function check_author ($sign_in_user_id, $edit_user_id) {
    if ($sign_in_user_id == $edit_user_id) {
        return true;
    }
    return false;
};

//Тестирование
function vd($value) {
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
    die();
};