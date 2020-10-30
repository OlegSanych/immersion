<?php
session_start();
require "functions.php";

session_unset();
session_destroy();
redirect_to('/page_login.php');

?>